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
    :root{--blue:#0B6CFF;--cyan:#00D4FF;--yellow:#FFD000;--line:#e9eef7;--soft:#f4f8ff;--text:#111111;--muted:#667085}
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;color:var(--text)}
    body{overflow-x:hidden;background:#f4f8ff}

    .auth-shell{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}
    .auth-shell:before{content:"";position:absolute;top:-120px;left:-120px;width:420px;height:420px;background:radial-gradient(circle,rgba(11,108,255,.13) 0%,transparent 70%);border-radius:50%;pointer-events:none}
    .auth-shell:after{content:"";position:absolute;bottom:-80px;right:-80px;width:320px;height:320px;background:radial-gradient(circle,rgba(0,212,255,.10) 0%,transparent 70%);border-radius:50%;pointer-events:none}

    .auth-card{width:100%;max-width:480px;background:#fff;border:1px solid var(--line);border-radius:32px;padding:36px 32px 32px;box-shadow:0 24px 64px rgba(11,108,255,.10),0 4px 16px rgba(0,0,0,.06);position:relative;z-index:1}

    .brand{display:flex;align-items:center;gap:10px;margin-bottom:28px}
    .brand-logo{width:44px;height:44px;border-radius:16px;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(11,108,255,.28)}
    .brand-logo svg{width:24px;height:24px;fill:none;stroke:#fff;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
    .brand-name{font-size:22px;font-weight:950;letter-spacing:-.6px}
    .brand-studi{color:var(--blue)}.brand-move{color:var(--yellow)}

    .auth-title{font-size:26px;font-weight:950;letter-spacing:-.6px;margin:0 0 6px;color:#101828}
    .auth-sub{font-size:14px;color:var(--muted);font-weight:650;margin:0 0 28px;line-height:1.5}

    .fields-grid{display:grid;grid-template-columns:1fr 1fr;gap:0 14px}
    .field{margin-bottom:16px}
    .field.full{grid-column:1/-1}
    .field label{display:block;font-size:13px;font-weight:850;color:#344054;margin-bottom:7px}
    .field input{width:100%;border:1.5px solid var(--line);border-radius:14px;padding:13px 16px;font-size:15px;font-family:inherit;outline:none;background:#fff;color:var(--text);transition:border-color .18s ease,box-shadow .18s ease}
    .field input:focus{border-color:var(--blue);box-shadow:0 0 0 4px rgba(11,108,255,.10)}
    .field input::placeholder{color:#98A2B3}

    .submit-btn{width:100%;border:0;border-radius:16px;background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;padding:15px 16px;font-weight:950;font-size:15px;font-family:inherit;margin-top:8px;cursor:pointer;box-shadow:0 8px 24px rgba(11,108,255,.28);transition:filter .18s ease,transform .18s ease;letter-spacing:-.1px}
    .submit-btn:hover{filter:brightness(1.06);transform:translateY(-1px)}
    .submit-btn:active{transform:translateY(0)}

    .error-box{display:none;margin-top:14px;background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:14px;padding:12px 16px;font-size:13px;font-weight:750;line-height:1.4}

    .auth-links{text-align:center;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);font-size:13px;color:var(--muted);font-weight:700}
    .auth-link{color:var(--blue);font-weight:850;text-decoration:none;transition:opacity .15s ease}
    .auth-link:hover{opacity:.75}

    @media(max-width:520px){.fields-grid{grid-template-columns:1fr}.auth-card{padding:28px 22px 24px;border-radius:28px}}
  </style>
</head>
<body>
<div class="auth-shell">
  <main class="auth-card">
    <div class="brand">
      <div class="brand-logo">
        <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </div>
      <div class="brand-name"><span class="brand-studi">Studi</span><span class="brand-move">move</span></div>
    </div>

    <h1 class="auth-title">Crée ton compte 🚀</h1>
    <p class="auth-sub">Rejoins des milliers d'étudiants qui vivent des aventures uniques.</p>

    <form id="registerForm">
      <div class="fields-grid">
        <div class="field">
          <label>Prénom</label>
          <input name="first_name" autocomplete="given-name" placeholder="Léa" required>
        </div>
        <div class="field">
          <label>Nom</label>
          <input name="last_name" autocomplete="family-name" placeholder="Martin">
        </div>
        <div class="field full">
          <label>Email</label>
          <input name="email" type="email" autocomplete="email" placeholder="ton@email.fr" required>
        </div>
        <div class="field full">
          <label>Pseudo</label>
          <input name="username" placeholder="ex : lea.move" required>
        </div>
        <div class="field full">
          <label>Mot de passe</label>
          <input name="password" type="password" autocomplete="new-password" minlength="8" placeholder="8 caractères minimum" required>
        </div>
      </div>
      <button class="submit-btn" type="submit">Créer mon compte</button>
      <div id="error" class="error-box"></div>
    </form>

    <div class="auth-links">
      Déjà un compte ? <a class="auth-link" href="login.php">Se connecter</a>
    </div>
  </main>
</div>

<script>
const ERRORS = {
  EMAIL_ALREADY_EXISTS: 'Un compte existe déjà avec cet email.',
  USERNAME_INVALID: 'Le pseudo doit contenir au moins 3 caractères.',
  PASSWORD_TOO_SHORT: 'Le mot de passe doit contenir au moins 8 caractères.',
  REGISTER_FAILED: 'Création impossible. Le pseudo est peut-être déjà utilisé.'
};
document.getElementById('registerForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const error = document.getElementById('error');
  const btn = e.currentTarget.querySelector('.submit-btn');
  error.style.display = 'none';
  btn.textContent = 'Création en cours…';
  btn.disabled = true;

  const fd = new FormData(e.currentTarget);
  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ action:'register', first_name:fd.get('first_name'), last_name:fd.get('last_name'), email:fd.get('email'), username:fd.get('username'), password:fd.get('password'), marque:'studimove' })
  });

  const data = await res.json();
  if (!data.ok) {
    error.textContent = ERRORS[data.error] || 'Création impossible. Vérifie les informations.';
    error.style.display = 'block';
    btn.textContent = 'Créer mon compte';
    btn.disabled = false;
    return;
  }
  window.location.href = 'index.php';
});
</script>
</body>
</html>
