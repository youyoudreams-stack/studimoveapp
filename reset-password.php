<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
$token = $_GET['token'] ?? '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Nouveau mot de passe — StudiMove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--violet:#6f45e9;--bg:#f7f6fb;--text:#111827;--muted:#6b7280;--line:#e5e7eb}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--text)}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
    .card{width:100%;max-width:430px;background:#fff;border:1px solid var(--line);border-radius:28px;padding:28px;box-shadow:0 18px 50px rgba(17,24,39,.08)}
    .brand{font-size:28px;font-weight:900;color:var(--violet);margin-bottom:6px}
    h1{margin:0 0 8px;font-size:24px}.muted{color:var(--muted);font-size:14px;margin-bottom:24px}
    label{display:block;font-size:13px;font-weight:800;margin:14px 0 7px}
    input{width:100%;border:1px solid var(--line);border-radius:16px;padding:14px 15px;font-size:15px;outline:none}
    button{width:100%;border:0;border-radius:16px;background:var(--violet);color:#fff;padding:14px 16px;font-weight:900;font-size:15px;margin-top:18px;cursor:pointer}
    .msg{display:none;margin-top:14px;border-radius:14px;padding:12px;font-size:13px}
    .ok{background:#dcfce7;color:#166534}.err{background:#fee2e2;color:#b91c1c}
    a{display:block;margin-top:18px;text-align:center;color:var(--violet);font-weight:800;text-decoration:none;font-size:13px}
  </style>
</head>
<body>
<div class="wrap">
  <main class="card">
    <div class="brand">StudiMove</div>
    <h1>Nouveau mot de passe</h1>
    <p class="muted">Choisis un nouveau mot de passe sécurisé.</p>

    <form id="resetForm">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars((string)$token, ENT_QUOTES, 'UTF-8'); ?>">
      <label>Nouveau mot de passe</label>
      <input name="password" type="password" minlength="8" required>
      <button type="submit">Mettre à jour</button>
      <div id="msg" class="msg"></div>
    </form>

    <a href="login.php">Retour connexion</a>
  </main>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const msg = document.getElementById('msg');
  msg.style.display = 'none';

  const fd = new FormData(e.currentTarget);

  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({
      action: 'reset_password',
      token: fd.get('token'),
      password: fd.get('password')
    })
  });

  const data = await res.json();

  if (!data.ok) {
    msg.className = 'msg err';
    msg.textContent = data.error === 'RESET_TOKEN_INVALID_OR_EXPIRED'
      ? 'Le lien est invalide ou expiré.'
      : 'Impossible de changer le mot de passe.';
    msg.style.display = 'block';
    return;
  }

  msg.className = 'msg ok';
  msg.textContent = 'Mot de passe modifié. Tu peux maintenant te connecter.';
  msg.style.display = 'block';
  setTimeout(() => window.location.href = 'login.php', 1200);
});
</script>
</body>
</html>
