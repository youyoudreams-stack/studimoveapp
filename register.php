<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Créer un compte — StudiMove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--violet:#6f45e9;--bg:#f7f6fb;--text:#111827;--muted:#6b7280;--line:#e5e7eb}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--text)}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
    .card{width:100%;max-width:470px;background:#fff;border:1px solid var(--line);border-radius:28px;padding:28px;box-shadow:0 18px 50px rgba(17,24,39,.08)}
    .brand{font-size:28px;font-weight:900;color:var(--violet);margin-bottom:6px}
    h1{margin:0 0 8px;font-size:24px}.muted{color:var(--muted);font-size:14px;margin-bottom:22px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.full{grid-column:1/-1}
    label{display:block;font-size:13px;font-weight:800;margin:14px 0 7px}
    input{width:100%;border:1px solid var(--line);border-radius:16px;padding:14px 15px;font-size:15px;outline:none}
    input:focus{border-color:var(--violet);box-shadow:0 0 0 4px rgba(111,69,233,.12)}
    button{width:100%;border:0;border-radius:16px;background:var(--violet);color:#fff;padding:14px 16px;font-weight:900;font-size:15px;margin-top:18px;cursor:pointer}
    .links{margin-top:18px;font-size:13px;text-align:center}
    a{color:var(--violet);font-weight:800;text-decoration:none}.error{display:none;margin-top:14px;color:#b91c1c;background:#fee2e2;border-radius:14px;padding:12px;font-size:13px}
    @media(max-width:520px){.grid{grid-template-columns:1fr}}
  </style>
</head>
<body>
<div class="wrap">
  <main class="card">
    <div class="brand">StudiMove</div>
    <h1>Créer ton compte</h1>
    <p class="muted">Rejoins ton campus, découvre les événements et suis la vie étudiante.</p>

    <form id="registerForm">
      <div class="grid">
        <div>
          <label>Prénom</label>
          <input name="first_name" autocomplete="given-name" required>
        </div>
        <div>
          <label>Nom</label>
          <input name="last_name" autocomplete="family-name">
        </div>
        <div class="full">
          <label>Email</label>
          <input name="email" type="email" autocomplete="email" required>
        </div>
        <div class="full">
          <label>Pseudo</label>
          <input name="username" placeholder="ex : sami.move" required>
        </div>
        <div class="full">
          <label>Mot de passe</label>
          <input name="password" type="password" autocomplete="new-password" minlength="8" required>
        </div>
      </div>

      <button type="submit">Créer mon compte</button>
      <div id="error" class="error"></div>
    </form>

    <div class="links">
      <a href="login.php">J’ai déjà un compte</a>
    </div>
  </main>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const error = document.getElementById('error');
  error.style.display = 'none';

  const fd = new FormData(e.currentTarget);
  const payload = {
    action: 'register',
    first_name: fd.get('first_name'),
    last_name: fd.get('last_name'),
    email: fd.get('email'),
    username: fd.get('username'),
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
    const messages = {
      EMAIL_ALREADY_EXISTS: 'Un compte existe déjà avec cet email.',
      USERNAME_INVALID: 'Le pseudo doit contenir au moins 3 caractères.',
      PASSWORD_TOO_SHORT: 'Le mot de passe doit contenir au moins 8 caractères.',
      REGISTER_FAILED: 'Création impossible. Le pseudo est peut-être déjà utilisé.'
    };
    error.textContent = messages[data.error] || 'Création impossible. Vérifie les informations.';
    error.style.display = 'block';
    return;
  }

  window.location.href = 'index.php';
});
</script>
</body>
</html>
