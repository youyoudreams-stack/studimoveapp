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
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif}

    .auth-shell{
      min-height:100vh;
      display:flex;flex-direction:column;align-items:center;justify-content:center;
      padding:24px;
      position:relative;overflow:hidden;
      background:url('https://images.unsplash.com/photo-1539635278303-d4002c07eae3?w=1920&q=80') center/cover no-repeat;
    }
    .auth-shell:before{
      content:"";position:absolute;inset:0;
      background:linear-gradient(170deg,rgba(5,10,30,.55) 0%,rgba(11,30,80,.45) 40%,rgba(0,0,0,.60) 100%);
      z-index:0;
    }

    .auth-brand{position:relative;z-index:1;text-align:center;margin-bottom:24px}
    .auth-brand-name{font-size:44px;font-weight:950;letter-spacing:-1.5px;line-height:1;text-shadow:0 2px 20px rgba(0,0,0,.3)}
    .brand-studi{color:#fff}.brand-move{color:#FFD000}
    .auth-brand-tagline{font-size:14px;color:rgba(255,255,255,.85);font-weight:600;margin-top:8px;letter-spacing:.2px;text-shadow:0 1px 8px rgba(0,0,0,.4)}

    .auth-card{
      width:100%;max-width:480px;
      background:#fff;
      border-radius:28px;
      padding:32px 28px 28px;
      position:relative;z-index:1;
      box-shadow:0 32px 80px rgba(0,0,0,.30),0 8px 24px rgba(0,0,0,.12);
    }

    .auth-title{font-size:22px;font-weight:850;color:#101828;letter-spacing:-.4px;margin-bottom:4px}
    .auth-sub{font-size:13px;color:#667085;font-weight:500;margin-bottom:24px;line-height:1.5}

    .fields-grid{display:grid;grid-template-columns:1fr 1fr;gap:0 14px}
    .field{margin-bottom:14px}
    .field.full{grid-column:1/-1}
    .field label{display:block;font-size:12px;font-weight:750;color:#344054;margin-bottom:6px;letter-spacing:.2px}
    .field input{
      width:100%;background:#f9fafb;border:1.5px solid #e9eef7;
      border-radius:14px;padding:13px 16px;font-size:15px;font-family:inherit;
      color:#111;outline:none;
      transition:border-color .18s,box-shadow .18s,background .18s;
    }
    .field input::placeholder{color:#98A2B3}
    .field input:focus{border-color:#0B6CFF;background:#fff;box-shadow:0 0 0 4px rgba(11,108,255,.10)}

    .pw-wrap{position:relative}
    .pw-wrap input{padding-right:46px}
    .pw-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:0;cursor:pointer;padding:0;color:#98A2B3;display:flex;align-items:center}

    .submit-btn{
      width:100%;border:0;border-radius:14px;
      background:linear-gradient(90deg,#0B6CFF,#00D4FF);
      color:#fff;padding:14px 16px;font-weight:850;font-size:15px;font-family:inherit;
      margin-top:6px;cursor:pointer;
      box-shadow:0 8px 24px rgba(11,108,255,.30);
      transition:filter .18s,transform .18s;letter-spacing:-.1px;
    }
    .submit-btn:hover{filter:brightness(1.08);transform:translateY(-1px)}
    .submit-btn:active{transform:translateY(0)}
    .submit-btn:disabled{opacity:.6;cursor:not-allowed;transform:none}

    .error-box{display:none;margin-top:12px;background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:12px;padding:11px 14px;font-size:13px;font-weight:700;line-height:1.4}

    .auth-links{text-align:center;margin-top:20px;padding-top:18px;border-top:1px solid #e9eef7;font-size:13px;color:#667085;font-weight:600}
    .auth-link{color:#0B6CFF;font-weight:750;text-decoration:none;transition:opacity .15s}
    .auth-link:hover{opacity:.75}

    @media(max-width:520px){.fields-grid{grid-template-columns:1fr}.auth-card{padding:24px 20px 22px;border-radius:24px}.auth-brand-name{font-size:38px}}
  </style>
</head>
<body>
<div class="auth-shell">

  <div class="auth-brand">
    <div class="auth-brand-name"><span class="brand-studi">Studi</span><span class="brand-move">move</span></div>
    <div class="auth-brand-tagline">Partagez, voyagez, vibrez !</div>
  </div>

  <main class="auth-card">
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
          <div class="pw-wrap">
            <input name="password" id="passwordInput" type="password" autocomplete="new-password" minlength="8" placeholder="8 caractères minimum" required>
            <button type="button" class="pw-toggle" id="togglePassword" aria-label="Voir le mot de passe">
              <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
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
document.getElementById('togglePassword').addEventListener('click', function(){
  const inp = document.getElementById('passwordInput');
  const icon = document.getElementById('eyeIcon');
  if(inp.type === 'password'){
    inp.type = 'text';
    icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
  } else {
    inp.type = 'password';
    icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }
});

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
