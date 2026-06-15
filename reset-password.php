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
    :root{--blue:#0B6CFF;--cyan:#00D4FF;--yellow:#FFD000;--line:#e9eef7;--soft:#f4f8ff;--text:#111111;--muted:#667085}
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;color:var(--text)}
    body{overflow-x:hidden;background:#f4f8ff}

    .auth-shell{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}
    .auth-shell:before{content:"";position:absolute;top:-120px;left:-120px;width:420px;height:420px;background:radial-gradient(circle,rgba(11,108,255,.13) 0%,transparent 70%);border-radius:50%;pointer-events:none}
    .auth-shell:after{content:"";position:absolute;bottom:-80px;right:-80px;width:320px;height:320px;background:radial-gradient(circle,rgba(0,212,255,.10) 0%,transparent 70%);border-radius:50%;pointer-events:none}

    .auth-card{width:100%;max-width:440px;background:#fff;border:1px solid var(--line);border-radius:32px;padding:36px 32px 32px;box-shadow:0 24px 64px rgba(11,108,255,.10),0 4px 16px rgba(0,0,0,.06);position:relative;z-index:1}

    .brand{display:flex;align-items:center;gap:10px;margin-bottom:28px}
    .brand-logo{width:44px;height:44px;border-radius:16px;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(11,108,255,.28)}
    .brand-logo svg{width:24px;height:24px;fill:none;stroke:#fff;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
    .brand-name{font-size:22px;font-weight:950;letter-spacing:-.6px}
    .brand-studi{color:var(--blue)}.brand-move{color:var(--yellow)}

    .auth-title{font-size:26px;font-weight:950;letter-spacing:-.6px;margin:0 0 6px;color:#101828}
    .auth-sub{font-size:14px;color:var(--muted);font-weight:650;margin:0 0 28px;line-height:1.5}

    .field{margin-bottom:16px}
    .field label{display:block;font-size:13px;font-weight:850;color:#344054;margin-bottom:7px}
    .field input{width:100%;border:1.5px solid var(--line);border-radius:14px;padding:13px 16px;font-size:15px;font-family:inherit;outline:none;background:#fff;color:var(--text);transition:border-color .18s ease,box-shadow .18s ease}
    .field input:focus{border-color:var(--blue);box-shadow:0 0 0 4px rgba(11,108,255,.10)}
    .field input::placeholder{color:#98A2B3}

    .submit-btn{width:100%;border:0;border-radius:16px;background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;padding:15px 16px;font-weight:950;font-size:15px;font-family:inherit;margin-top:8px;cursor:pointer;box-shadow:0 8px 24px rgba(11,108,255,.28);transition:filter .18s ease,transform .18s ease;letter-spacing:-.1px}
    .submit-btn:hover{filter:brightness(1.06);transform:translateY(-1px)}
    .submit-btn:active{transform:translateY(0)}

    .msg-box{display:none;margin-top:14px;border-radius:14px;padding:12px 16px;font-size:13px;font-weight:750;line-height:1.4}
    .msg-box.ok{background:#e9fbf0;border:1px solid #bbf7d0;color:#067647}
    .msg-box.err{background:#fff1f2;border:1px solid #fecdd3;color:#be123c}

    .auth-links{text-align:center;margin-top:24px;padding-top:20px;border-top:1px solid var(--line)}
    .auth-link{color:var(--blue);font-weight:850;text-decoration:none;font-size:13px;transition:opacity .15s ease}
    .auth-link:hover{opacity:.75}

    @media(max-width:480px){.auth-card{padding:28px 22px 24px;border-radius:28px}}
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

    <h1 class="auth-title">Nouveau mot de passe 🔐</h1>
    <p class="auth-sub">Choisis un mot de passe sécurisé d'au moins 8 caractères.</p>

    <form id="resetForm">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars((string)$token, ENT_QUOTES, 'UTF-8'); ?>">
      <div class="field">
        <label>Nouveau mot de passe</label>
        <input name="password" type="password" minlength="8" autocomplete="new-password" placeholder="8 caractères minimum" required>
      </div>
      <button class="submit-btn" type="submit">Mettre à jour</button>
      <div id="msg" class="msg-box"></div>
    </form>

    <div class="auth-links">
      <a class="auth-link" href="login.php">← Retour à la connexion</a>
    </div>
  </main>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const msg = document.getElementById('msg');
  const btn = e.currentTarget.querySelector('.submit-btn');
  msg.style.display = 'none';
  btn.textContent = 'Mise à jour…';
  btn.disabled = true;

  const fd = new FormData(e.currentTarget);
  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ action:'reset_password', token:fd.get('token'), password:fd.get('password') })
  });

  const data = await res.json();
  if (!data.ok) {
    msg.className = 'msg-box err';
    msg.textContent = data.error === 'RESET_TOKEN_INVALID_OR_EXPIRED' ? 'Le lien est invalide ou expiré.' : 'Impossible de changer le mot de passe.';
    msg.style.display = 'block';
    btn.textContent = 'Mettre à jour';
    btn.disabled = false;
    return;
  }
  msg.className = 'msg-box ok';
  msg.textContent = 'Mot de passe modifié avec succès !';
  msg.style.display = 'block';
  btn.textContent = 'Modifié ✓';
  setTimeout(() => window.location.href = 'login.php', 1400);
});
</script>
</body>
</html>
