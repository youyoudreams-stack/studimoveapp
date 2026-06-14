<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion — StudiMove</title>
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
    input:focus{border-color:var(--violet);box-shadow:0 0 0 4px rgba(111,69,233,.12)}
    button{width:100%;border:0;border-radius:16px;background:var(--violet);color:#fff;padding:14px 16px;font-weight:900;font-size:15px;margin-top:18px;cursor:pointer}
    .links{display:flex;justify-content:space-between;gap:10px;margin-top:18px;font-size:13px}
    a{color:var(--violet);font-weight:800;text-decoration:none}.error{display:none;margin-top:14px;color:#b91c1c;background:#fee2e2;border-radius:14px;padding:12px;font-size:13px}
  </style>
</head>
<body>
<div class="wrap">
  <main class="card">
    <div class="brand">StudiMove</div>
    <h1>Connexion</h1>
    <p class="muted">Connecte-toi pour accéder à ton app.</p>

    <form id="loginForm">
      <label>Email</label>
      <input name="email" type="email" autocomplete="email" required>

      <label>Mot de passe</label>
      <input name="password" type="password" autocomplete="current-password" required>

      <button type="submit">Se connecter</button>
      <div id="error" class="error"></div>
    </form>

    <div class="links">
      <a href="register.php">Créer un compte</a>
      <a href="forgot-password.php">Mot de passe oublié ?</a>
    </div>
  </main>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const error = document.getElementById('error');
  error.style.display = 'none';

  const fd = new FormData(e.currentTarget);
  const payload = {
    action: 'login',
    email: fd.get('email'),
    password: fd.get('password'),
    marque: 'studimove'
  };

  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify(payload)
  });

  const data = await res.json();
  if (!data.ok) {
    error.textContent = data.error === 'INVALID_CREDENTIALS'
      ? 'Email ou mot de passe incorrect.'
      : 'Connexion impossible. Réessaie.';
    error.style.display = 'block';
    return;
  }

  window.location.href = 'index.php';
});
</script>
</body>
</html>
