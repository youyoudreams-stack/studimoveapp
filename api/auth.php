<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth_config.php';
require_once __DIR__ . '/../mail_helper.php';

header('Content-Type: application/json; charset=utf-8');

function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function input_data(): array {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw ?: '', true);
    if (is_array($json)) return $json;
    return $_POST ?: [];
}

function normalize_email(string $email): string {
    return mb_strtolower(trim($email));
}

function normalize_username(string $username): string {
    $username = mb_strtolower(trim($username));
    $username = preg_replace('/[^a-z0-9._-]/', '', $username);
    $username = preg_replace('/[._-]{2,}/', '.', $username);
    return trim($username, '._-');
}

function random_token(int $bytes = 32): string {
    return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
}

function token_hash(string $token): string {
    return hash('sha256', $token);
}

function supabase_request(string $method, string $path, ?array $payload = null, array $query = []): array {
    $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($path, '/');
    if (!empty($query)) {
        $url .= '?' . http_build_query($query);
    }

    $headers = [
        'apikey: ' . SUPABASE_SERVICE_ROLE_KEY,
        'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY,
        'Content-Type: application/json',
        'Accept: application/json',
        'Prefer: return=representation'
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
    ]);

    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    $body = curl_exec($ch);
    $err = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false) {
        return ['ok' => false, 'status' => 0, 'error' => $err ?: 'CURL_ERROR', 'data' => null];
    }

    $data = json_decode($body, true);
    if ($data === null && $body !== '' && json_last_error() !== JSON_ERROR_NONE) {
        $data = $body;
    }

    return [
        'ok' => $status >= 200 && $status < 300,
        'status' => $status,
        'data' => $data,
        'error' => $status >= 200 && $status < 300 ? null : $data
    ];
}

function get_user_by_email(string $emailNormalized, string $marque): ?array {
    $res = supabase_request('GET', 'supertrip_users', null, [
        'select' => '*',
        'email_normalized' => 'eq.' . $emailNormalized,
        'marque' => 'eq.' . $marque,
        'limit' => 1
    ]);
    if (!$res['ok'] || empty($res['data'][0])) return null;
    return $res['data'][0];
}

function get_user_by_id(string $id): ?array {
    $res = supabase_request('GET', 'supertrip_users', null, [
        'select' => '*',
        'id' => 'eq.' . $id,
        'limit' => 1
    ]);
    if (!$res['ok'] || empty($res['data'][0])) return null;
    return $res['data'][0];
}

function public_user(array $user): array {
    return [
        'id' => $user['id'] ?? null,
        'marque' => $user['marque'] ?? null,
        'source' => $user['source'] ?? null,
        'first_name' => $user['first_name'] ?? null,
        'last_name' => $user['last_name'] ?? null,
        'email' => $user['email'] ?? null,
        'phone' => $user['phone'] ?? null,
        'username' => $user['username'] ?? null,
        'is_active' => $user['is_active'] ?? null,
        'profile_completed' => $user['profile_completed'] ?? null,
        'last_login_at' => $user['last_login_at'] ?? null,
    ];
}

function set_auth_cookie(string $token, string $expiresAt): void {
    $expires = strtotime($expiresAt) ?: (time() + AUTH_SESSION_DAYS * 86400);
    setcookie(AUTH_COOKIE_NAME, $token, [
        'expires' => $expires,
        'path' => '/studimove_app_test',
        'secure' => COOKIE_SECURE,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function clear_auth_cookie(): void {
    setcookie(AUTH_COOKIE_NAME, '', [
        'expires' => time() - 3600,
        'path' => '/studimove_app_test',
        'secure' => COOKIE_SECURE,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function create_session(string $userId): array {
    $token = random_token(48);
    $hash = token_hash($token);
    $expiresAt = gmdate('c', time() + AUTH_SESSION_DAYS * 86400);

    $payload = [
        'user_id' => $userId,
        'session_token_hash' => $hash,
        'expires_at' => $expiresAt,
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
    ];

    $res = supabase_request('POST', 'supertrip_sessions', $payload);
    if (!$res['ok']) {
        json_response(['ok' => false, 'error' => 'SESSION_CREATE_FAILED', 'detail' => $res['error']], 500);
    }

    set_auth_cookie($token, $expiresAt);
    return ['token' => $token, 'expires_at' => $expiresAt];
}

function current_session(): ?array {
    $token = $_COOKIE[AUTH_COOKIE_NAME] ?? '';
    if (!$token) return null;

    $hash = token_hash($token);
    $res = supabase_request('GET', 'supertrip_sessions', null, [
        'select' => '*',
        'session_token_hash' => 'eq.' . $hash,
        'expires_at' => 'gt.' . gmdate('c'),
        'limit' => 1
    ]);

    if (!$res['ok'] || empty($res['data'][0])) return null;
    return $res['data'][0];
}

function current_user(): ?array {
    $session = current_session();
    if (!$session) return null;
    $user = get_user_by_id($session['user_id']);
    if (!$user || empty($user['is_active'])) return null;

    supabase_request('PATCH', 'supertrip_sessions', [
        'last_seen_at' => gmdate('c')
    ], [
        'id' => 'eq.' . $session['id']
    ]);

    return $user;
}

function require_method_post(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_response(['ok' => false, 'error' => 'METHOD_NOT_ALLOWED'], 405);
    }
}

function send_reset_email(string $to, string $token): bool {
    $link    = rtrim(APP_BASE_URL, '/') . '/reset-password.php?token=' . urlencode($token);
    $subject = 'Réinitialisation de votre mot de passe StudiMove';

    $message  = "Bonjour,\n\n";
    $message .= "Vous avez demandé à réinitialiser votre mot de passe StudiMove.\n\n";
    $message .= "Cliquez sur ce lien pour choisir un nouveau mot de passe :\n";
    $message .= $link . "\n\n";
    $message .= "Ce lien expire dans " . PASSWORD_RESET_MINUTES . " minutes.\n\n";
    $message .= "Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.\n\n";
    $message .= "L'équipe StudiMove";

    return smtp_send($to, $subject, $message);
}

$input = input_data();
$action = $input['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'register': {
            require_method_post();

            $firstName = trim((string)($input['first_name'] ?? ''));
            $lastName = trim((string)($input['last_name'] ?? ''));
            $email = trim((string)($input['email'] ?? ''));
            $password = (string)($input['password'] ?? '');
            $username = trim((string)($input['username'] ?? ''));
            $marque = trim((string)($input['marque'] ?? DEFAULT_MARQUE)) ?: DEFAULT_MARQUE;

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                json_response(['ok' => false, 'error' => 'EMAIL_INVALID'], 422);
            }
            if (strlen($password) < 8) {
                json_response(['ok' => false, 'error' => 'PASSWORD_TOO_SHORT'], 422);
            }
            if ($username === '') {
                $local = explode('@', $email)[0] ?? 'user';
                $username = $local . rand(100, 999);
            }

            $emailNorm = normalize_email($email);
            $usernameNorm = normalize_username($username);

            if (strlen($usernameNorm) < 3) {
                json_response(['ok' => false, 'error' => 'USERNAME_INVALID'], 422);
            }

            $existing = get_user_by_email($emailNorm, $marque);
            if ($existing) {
                json_response(['ok' => false, 'error' => 'EMAIL_ALREADY_EXISTS'], 409);
            }

            $payload = [
                'marque' => $marque,
                'source' => DEFAULT_SOURCE,
                'first_name' => $firstName ?: null,
                'last_name' => $lastName ?: null,
                'email' => $email,
                'email_normalized' => $emailNorm,
                'username' => $username,
                'username_normalized' => $usernameNorm,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'is_active' => true,
                'profile_completed' => false,
            ];

            $res = supabase_request('POST', 'supertrip_users', $payload);
            if (!$res['ok']) {
                $detail = $res['error'];
                json_response(['ok' => false, 'error' => 'REGISTER_FAILED', 'detail' => $detail], 400);
            }

            $user = $res['data'][0] ?? null;
            if (!$user) {
                json_response(['ok' => false, 'error' => 'REGISTER_NO_USER_RETURNED'], 500);
            }

            supabase_request('POST', 'supertrip_user_profiles', [
                'user_id' => $user['id']
            ]);

            create_session($user['id']);

            json_response(['ok' => true, 'user' => public_user($user)]);
        }

        case 'login': {
            require_method_post();

            $email = trim((string)($input['email'] ?? ''));
            $password = (string)($input['password'] ?? '');
            $marque = trim((string)($input['marque'] ?? DEFAULT_MARQUE)) ?: DEFAULT_MARQUE;

            if ($email === '' || $password === '') {
                json_response(['ok' => false, 'error' => 'MISSING_CREDENTIALS'], 422);
            }

            $user = get_user_by_email(normalize_email($email), $marque);
            if (!$user || empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
                json_response(['ok' => false, 'error' => 'INVALID_CREDENTIALS'], 401);
            }

            if (empty($user['is_active'])) {
                json_response(['ok' => false, 'error' => 'ACCOUNT_DISABLED'], 403);
            }

            supabase_request('PATCH', 'supertrip_users', [
                'last_login_at' => gmdate('c')
            ], [
                'id' => 'eq.' . $user['id']
            ]);

            create_session($user['id']);
            json_response(['ok' => true, 'user' => public_user($user)]);
        }

        case 'me': {
            $user = current_user();
            if (!$user) {
                json_response(['ok' => false, 'authenticated' => false], 401);
            }
            json_response(['ok' => true, 'authenticated' => true, 'user' => public_user($user)]);
        }

        case 'logout': {
            $session = current_session();
            if ($session) {
                supabase_request('DELETE', 'supertrip_sessions', null, [
                    'id' => 'eq.' . $session['id']
                ]);
            }
            clear_auth_cookie();
            json_response(['ok' => true]);
        }

        case 'forgot_password': {
            require_method_post();

            $email = trim((string)($input['email'] ?? ''));
            $marque = trim((string)($input['marque'] ?? DEFAULT_MARQUE)) ?: DEFAULT_MARQUE;

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                json_response(['ok' => true, 'message' => 'Si un compte existe, un email sera envoyé.']);
            }

            $user = get_user_by_email(normalize_email($email), $marque);

            /**
             * Réponse volontairement identique même si le compte n'existe pas.
             * Cela évite de révéler les emails inscrits.
             */
            if (!$user) {
                json_response(['ok' => true, 'message' => 'Si un compte existe, un email sera envoyé.']);
            }

            $token = random_token(48);
            $hash = token_hash($token);
            $expiresAt = gmdate('c', time() + PASSWORD_RESET_MINUTES * 60);

            $res = supabase_request('POST', 'supertrip_password_resets', [
                'user_id' => $user['id'],
                'token_hash' => $hash,
                'expires_at' => $expiresAt
            ]);

            if (!$res['ok']) {
                json_response(['ok' => false, 'error' => 'RESET_TOKEN_CREATE_FAILED', 'detail' => $res['error']], 500);
            }

            send_reset_email($user['email'], $token);

            json_response(['ok' => true, 'message' => 'Si un compte existe, un email sera envoyé.']);
        }

        case 'reset_password': {
            require_method_post();

            $token = trim((string)($input['token'] ?? ''));
            $password = (string)($input['password'] ?? '');

            if ($token === '' || strlen($password) < 8) {
                json_response(['ok' => false, 'error' => 'INVALID_RESET_REQUEST'], 422);
            }

            $hash = token_hash($token);

            $res = supabase_request('GET', 'supertrip_password_resets', null, [
                'select' => '*',
                'token_hash' => 'eq.' . $hash,
                'used_at' => 'is.null',
                'expires_at' => 'gt.' . gmdate('c'),
                'limit' => 1
            ]);

            if (!$res['ok'] || empty($res['data'][0])) {
                json_response(['ok' => false, 'error' => 'RESET_TOKEN_INVALID_OR_EXPIRED'], 400);
            }

            $reset = $res['data'][0];

            $up = supabase_request('PATCH', 'supertrip_users', [
                'password_hash' => password_hash($password, PASSWORD_DEFAULT)
            ], [
                'id' => 'eq.' . $reset['user_id']
            ]);

            if (!$up['ok']) {
                json_response(['ok' => false, 'error' => 'PASSWORD_UPDATE_FAILED', 'detail' => $up['error']], 500);
            }

            supabase_request('PATCH', 'supertrip_password_resets', [
                'used_at' => gmdate('c')
            ], [
                'id' => 'eq.' . $reset['id']
            ]);

            supabase_request('DELETE', 'supertrip_sessions', null, [
                'user_id' => 'eq.' . $reset['user_id']
            ]);

            clear_auth_cookie();
            json_response(['ok' => true]);
        }

        default:
            json_response(['ok' => false, 'error' => 'INVALID_ACTION'], 400);
    }
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'SERVER_ERROR', 'message' => $e->getMessage()], 500);
}
