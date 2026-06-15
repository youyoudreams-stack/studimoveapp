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
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif}

    .auth-shell{
      min-height:100vh;
      display:flex;flex-direction:column;align-items:center;justify-content:center;
      padding:24px;
      position:relative;overflow:hidden;
      background:url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80') center/cover no-repeat;
    }
    .auth-shell:before{
      content:"";position:absolute;inset:0;
      background:linear-gradient(160deg,rgba(5,10,30,.72) 0%,rgba(11,30,80,.60) 50%,rgba(0,0,0,.75) 100%);
      z-index:0;
    }

    .auth-brand{
      position:relative;z-index:1;
      text-align:center;margin-bottom:28px;
    }
    .auth-brand-name{
      font-size:42px;font-weight:950;letter-spacing:-1.5px;line-height:1;
    }
    .brand-studi{color:#fff}
    .brand-move{color:#FFD000}
    .auth-brand-tagline{
      font-size:13px;color:rgba(255,255,255,.55);font-weight:500;
      margin-top:6px;letter-spacing:.3px;
    }

    .auth-card{
      width:100%;max-width:400px;
      background:rgba(255,255,255,.10);
      backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
      border:1px solid rgba(255,255,255,.18);
      border-radius:28px;
      padding:32px 28px 28px;
      position:relative;z-index:1;
      box-shadow:0 32px 64px rgba(0,0,0,.35),inset 0 1px 0 rgba(255,255,255,.15);
    }

    .auth-title{font-size:22px;font-weight:850;color:#fff;letter-spacing:-.4px;margin-bottom:4px}
    .auth-sub{font-size:13px;color:rgba(255,255,255,.55);font-weight:500;margin-bottom:24px;line-height:1.5}

    .field{margin-bottom:14px}
    .field label{display:block;font-size:12px;font-weight:750;color:rgba(255,255,255,.7);margin-bottom:6px;letter-spacing:.2px}
    .field input{
      width:100%;
      background:rgba(255,255,255,.10);
      border:1.5px solid rgba(255,255,255,.18);
      border-radius:14px;
      padding:13px 16px;
      font-size:15px;font-family:inherit;
      color:#fff;
      outline:none;
      transition:border-color .18s,box-shadow .18s,background .18s;
    }
    .field input::placeholder{color:rgba(255,255,255,.35)}
    .field input:focus{
      border-color:rgba(255,255,255,.5);
      background:rgba(255,255,255,.16);
      box-shadow:0 0 0 4px rgba(255,255,255,.08);
    }

    .submit-btn{
      width:100%;border:0;border-radius:14px;
      background:linear-gradient(90deg,#0B6CFF,#00D4FF);
      color:#fff;padding:14px 16px;
      font-weight:850;font-size:15px;font-family:inherit;
      margin-top:6px;cursor:pointer;
      box-shadow:0 8px 24px rgba(11,108,255,.40);
      transition:filter .18s,transform .18s;
      letter-spacing:-.1px;
    }
    .submit-btn:hover{filter:brightness(1.08);transform:translateY(-1px)}
    .submit-btn:active{transform:translateY(0)}
    .submit-btn:disabled{opacity:.6;cursor:not-allowed;transform:none}

    .error-box{
      display:none;margin-top:12px;
      background:rgba(190,18,60,.2);
      border:1px solid rgba(254,205,211,.3);
      color:#fda4af;
      border-radius:12px;padding:11px 14px;
      font-size:13px;font-weight:700;line-height:1.4;
    }

    .auth-links{
      display:flex;justify-content:space-between;align-items:center;
      gap:10px;margin-top:20px;padding-top:18px;
      border-top:1px solid rgba(255,255,255,.1);
    }
    .auth-link{
      color:rgba(255,255,255,.7);font-weight:750;text-decoration:none;
      font-size:13px;transition:color .15s;
    }
    .auth-link:hover{color:#fff}

    @media(max-width:480px){
      .auth-card{padding:24px 20px 22px;border-radius:24px}
      .auth-brand-name{font-size:36px}
    }
  </style>
</head>
<body>
<div class="auth-shell">

  <div class="auth-brand">
    <div class="auth-brand-name"><span class="brand-studi">Studi</span><span class="brand-move">move</span></div>
    <div class="auth-brand-tagline">Découvre. Sors. Vis.</div>
  </div>

  <main class="auth-card">
    <h1 class="auth-title">Bon retour 👋</h1>
    <p class="auth-sub">Connecte-toi pour accéder à tes aventures étudiantes.</p>

    <form id="loginForm">
      <div class="field">
        <label>Email</label>
        <input name="email" type="email" autocomplete="email" placeholder="ton@email.fr" required>
      </div>
      <div class="field">
        <label>Mot de passe</label>
        <input name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
      </div>
      <button class="submit-btn" type="submit">Se connecter</button>
      <div id="error" class="error-box"></div>
    </form>

    <div class="auth-links">
      <a class="auth-link" href="register.php">Créer un compte</a>
      <a class="auth-link" href="forgot-password.php">Mot de passe oublié ?</a>
    </div>
  </main>

</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const error = document.getElementById('error');
  const btn = e.currentTarget.querySelector('.submit-btn');
  error.style.display = 'none';
  btn.textContent = 'Connexion…';
  btn.disabled = true;

  const fd = new FormData(e.currentTarget);
  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ action:'login', email:fd.get('email'), password:fd.get('password'), marque:'studimove' })
  });

  const data = await res.json();
  if (!data.ok) {
    error.textContent = data.error === 'INVALID_CREDENTIALS' ? 'Email ou mot de passe incorrect.' : 'Connexion impossible. Réessaie.';
    error.style.display = 'block';
    btn.textContent = 'Se connecter';
    btn.disabled = false;
    return;
  }
  window.location.href = 'index.php';
});
</script>
</body>
</html>
