<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';

// ── Vérification du token secret ───────────────────────────────────────────
$token = $_GET['token'] ?? $_POST['token'] ?? '';
if (!hash_equals(ADMIN_SECRET_TOKEN, $token) || ADMIN_SECRET_TOKEN === 'CHANGE_MOI_PAR_UN_TOKEN_SECRET') {
    http_response_code(403);
    exit('<h1>403 — Accès refusé</h1><p>Token manquant ou non configuré.</p>');
}

// ── Helpers Supabase ───────────────────────────────────────────────────────
function supa(string $method, string $path, ?array $body = null, array $qs = []): array {
    $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($path, '/');
    if ($qs) $url .= '?' . http_build_query($qs);
    $headers = [
        'apikey: '         . SUPABASE_SERVICE_ROLE_KEY,
        'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation',
    ];
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    $resp   = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = json_decode((string)$resp, true);
    return ['ok' => $status >= 200 && $status < 300, 'data' => $data, 'status' => $status];
}

// ── Traitement du formulaire ───────────────────────────────────────────────
$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mb_strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $marque   = trim($_POST['marque'] ?? 'studimove');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email invalide.';
        $msgType = 'err';
    } elseif (strlen($password) < 8) {
        $message = 'Mot de passe trop court (8 caractères min).';
        $msgType = 'err';
    } else {
        // Chercher l'utilisateur
        $res = supa('GET', 'supertrip_users', null, [
            'select'           => 'id,email,first_name,last_name,marque',
            'email_normalized' => 'eq.' . $email,
            'marque'           => 'eq.' . $marque,
            'limit'            => 1,
        ]);

        if (!$res['ok'] || empty($res['data'][0])) {
            $message = "Aucun compte trouvé pour {$email} (marque : {$marque}).";
            $msgType = 'err';
        } else {
            $user = $res['data'][0];
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $up = supa('PATCH', 'supertrip_users', ['password_hash' => $hash], ['id' => 'eq.' . $user['id']]);

            if ($up['ok']) {
                // Invalider toutes les sessions actives
                supa('DELETE', 'supertrip_sessions', null, ['user_id' => 'eq.' . $user['id']]);
                $nom = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $email;
                $message = "Mot de passe mis à jour pour {$nom} ({$email}). Toutes ses sessions ont été invalidées.";
                $msgType = 'ok';
            } else {
                $message = 'Erreur lors de la mise à jour. Détail : ' . json_encode($up['data']);
                $msgType = 'err';
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Admin — Reset mot de passe · StudiMove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--blue:#0B6CFF;--bg:#f4f8ff;--line:#e5e7eb;--text:#111827;--muted:#6b7280}
    *{box-sizing:border-box} body{margin:0;font-family:system-ui,Arial,sans-serif;background:var(--bg);color:var(--text);padding:40px 20px}
    .card{max-width:480px;margin:0 auto;background:#fff;border:1px solid var(--line);border-radius:20px;padding:32px;box-shadow:0 8px 30px rgba(0,0,0,.07)}
    .brand{font-size:22px;font-weight:900;margin-bottom:4px}
    .brand span:first-child{color:var(--blue)}.brand span:last-child{color:#FFD000}
    h1{margin:0 0 6px;font-size:18px;font-weight:800}
    .sub{color:var(--muted);font-size:13px;margin-bottom:24px}
    label{display:block;font-size:13px;font-weight:700;margin:14px 0 6px}
    input,select{width:100%;border:1px solid var(--line);border-radius:12px;padding:12px 14px;font-size:15px;outline:none}
    input:focus,select:focus{border-color:var(--blue)}
    button{width:100%;margin-top:20px;border:0;border-radius:12px;background:var(--blue);color:#fff;padding:13px;font-size:15px;font-weight:800;cursor:pointer}
    .msg{margin-top:16px;border-radius:12px;padding:12px 14px;font-size:13px;font-weight:600}
    .ok{background:#dcfce7;color:#166534}.err{background:#fee2e2;color:#b91c1c}
    .warn{background:#fff7ed;border:1px solid #fdba74;border-radius:12px;padding:12px 14px;font-size:13px;color:#92400e;margin-bottom:20px}
  </style>
</head>
<body>
<div class="card">
  <div class="brand"><span>Studi</span><span>move</span></div>
  <h1>Réinitialiser un mot de passe</h1>
  <p class="sub">Réservé à l'administrateur · Accès protégé par token</p>

  <div class="warn">⚠️ Cette page est réservée à un usage interne. Ne partage jamais l'URL complète (avec le token).</div>

  <?php if ($message): ?>
    <div class="msg <?= $msgType ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

    <label>Email de l'utilisateur</label>
    <input name="email" type="email" required placeholder="user@email.fr" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <label>Nouveau mot de passe</label>
    <input name="password" type="text" required placeholder="Minimum 8 caractères">

    <label>Marque</label>
    <select name="marque">
      <option value="studimove" <?= ($_POST['marque'] ?? 'studimove') === 'studimove' ? 'selected' : '' ?>>studimove</option>
      <option value="supertrip" <?= ($_POST['marque'] ?? '') === 'supertrip' ? 'selected' : '' ?>>supertrip</option>
    </select>

    <button type="submit">Mettre à jour le mot de passe</button>
  </form>
</div>
</body>
</html>
