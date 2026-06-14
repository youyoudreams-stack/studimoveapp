<?php
declare(strict_types=1);
/**
 * Mailer SMTP pur PHP — sans dépendance externe.
 * Fonctionne avec IONOS (smtp.ionos.fr:587 STARTTLS).
 */

function smtp_send(string $to, string $subject, string $body): bool
{
    $host    = defined('SMTP_HOST')       ? SMTP_HOST       : 'smtp.ionos.fr';
    $port    = defined('SMTP_PORT')       ? (int)SMTP_PORT  : 587;
    $enc     = defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'tls';
    $user    = defined('SMTP_USER')       ? SMTP_USER       : '';
    $pass    = defined('SMTP_PASSWORD')   ? SMTP_PASSWORD   : '';
    $from    = defined('MAIL_FROM')       ? MAIL_FROM       : $user;
    $name    = defined('MAIL_FROM_NAME')  ? MAIL_FROM_NAME  : 'StudiMove';

    if ($user === '' || $pass === '' || $pass === 'VOTRE_MOT_DE_PASSE_IONOS') {
        error_log('[smtp_send] SMTP_PASSWORD non configuré dans auth_config.php');
        return false;
    }

    $ctx = stream_context_create([
        'ssl' => [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
            'allow_self_signed' => false,
        ]
    ]);

    if ($enc === 'ssl') {
        $sock = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
    } else {
        $sock = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 15);
    }

    if (!$sock) {
        error_log("[smtp_send] Connexion échouée : {$errstr} ({$errno})");
        return false;
    }

    stream_set_timeout($sock, 15);

    $read  = function () use ($sock): string { return (string)fgets($sock, 1024); };
    $write = function (string $cmd) use ($sock): void { fwrite($sock, $cmd . "\r\n"); };
    $code  = function (string $line): int { return (int)substr($line, 0, 3); };

    $ehlo = $_SERVER['HTTP_HOST'] ?? 'studimove.fr';

    $line = $read();
    if ($code($line) !== 220) { fclose($sock); return false; }

    $write("EHLO {$ehlo}");
    do { $line = $read(); } while ($line !== '' && $line[3] !== ' ');

    if ($enc === 'tls') {
        $write('STARTTLS');
        $line = $read();
        if ($code($line) !== 220) { fclose($sock); return false; }
        if (!stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT)) {
            stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }
        $write("EHLO {$ehlo}");
        do { $line = $read(); } while ($line !== '' && $line[3] !== ' ');
    }

    $write('AUTH LOGIN');
    $read();
    $write(base64_encode($user));
    $read();
    $write(base64_encode($pass));
    $line = $read();
    if ($code($line) !== 235) {
        error_log("[smtp_send] Authentification SMTP échouée : {$line}");
        fclose($sock);
        return false;
    }

    $write("MAIL FROM:<{$from}>");
    $read();
    $write("RCPT TO:<{$to}>");
    $line = $read();
    if ($code($line) !== 250) {
        error_log("[smtp_send] RCPT TO refusé : {$line}");
        fclose($sock);
        return false;
    }

    $write('DATA');
    $read();

    $date           = date('r');
    $msgId          = uniqid('smv.', true) . '@' . $ehlo;
    $subjectEncoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $bodyEscaped    = str_replace("\n.", "\n..", $body);

    $headers  = "Date: {$date}\r\n";
    $headers .= "From: {$name} <{$from}>\r\n";
    $headers .= "To: {$to}\r\n";
    $headers .= "Subject: {$subjectEncoded}\r\n";
    $headers .= "Message-ID: <{$msgId}>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";

    fwrite($sock, $headers . "\r\n" . $bodyEscaped . "\r\n.\r\n");
    $line = $read();

    $write('QUIT');
    fclose($sock);

    $ok = $code($line) === 250;
    if (!$ok) error_log("[smtp_send] Message rejeté : {$line}");
    return $ok;
}
