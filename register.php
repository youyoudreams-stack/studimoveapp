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

    .interest-chip{background:#f4f8ff;border:1.5px solid #e0ecff;border-radius:999px;padding:8px 14px;font-size:13px;font-weight:750;color:#344054;cursor:pointer;transition:all .15s ease;white-space:nowrap}
    .interest-chip.selected{background:linear-gradient(90deg,#0B6CFF,#00D4FF);border-color:transparent;color:#fff;box-shadow:0 4px 12px rgba(11,108,255,.25)}
    @media(max-width:520px){.fields-grid{grid-template-columns:1fr}.auth-card{padding:24px 20px 22px;border-radius:24px}.auth-brand-name{font-size:38px}}
  </style>
</head>
<body>
<div class="auth-shell">

  <div class="auth-brand">
    <div class="auth-brand-name"><span class="brand-studi">Studi</span><span class="brand-move">move</span></div>
    <div class="auth-brand-tagline">Partagez, voyagez, vibrez !</div>
  </div>

  <main class="auth-card" id="authCard">

    <!-- Étape 1 : Infos -->
    <div id="step1">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
        <div style="flex:1;height:4px;border-radius:999px;background:linear-gradient(90deg,#0B6CFF,#00D4FF)"></div>
        <div style="flex:1;height:4px;border-radius:999px;background:#e9eef7"></div>
        <span style="font-size:11px;font-weight:850;color:#98A2B3;white-space:nowrap">1 / 2</span>
      </div>
      <h1 class="auth-title">Crée ton compte 🚀</h1>
      <p class="auth-sub">Rejoins des milliers d'étudiants qui vivent des aventures uniques.</p>
      <div class="fields-grid">
        <div class="field">
          <label>Prénom</label>
          <input id="f_first_name" name="first_name" autocomplete="given-name" placeholder="Léa" required>
        </div>
        <div class="field">
          <label>Nom</label>
          <input id="f_last_name" name="last_name" autocomplete="family-name" placeholder="Martin">
        </div>
        <div class="field full">
          <label>Email</label>
          <input id="f_email" name="email" type="email" autocomplete="email" placeholder="ton@email.fr" required>
        </div>
        <div class="field full">
          <label>Pseudo</label>
          <input id="f_username" name="username" placeholder="ex : lea.move" required>
        </div>
        <div class="field full">
          <label>Mot de passe</label>
          <div class="pw-wrap">
            <input id="passwordInput" name="password" type="password" autocomplete="new-password" minlength="8" placeholder="8 caractères minimum" required>
            <button type="button" class="pw-toggle" id="togglePassword" aria-label="Voir le mot de passe">
              <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>
      </div>
      <button class="submit-btn" id="nextStepBtn" type="button">Suivant →</button>
      <div id="error1" class="error-box"></div>
    </div>

    <!-- Étape 2 : Centres d'intérêt -->
    <div id="step2" style="display:none">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
        <div style="flex:1;height:4px;border-radius:999px;background:linear-gradient(90deg,#0B6CFF,#00D4FF)"></div>
        <div style="flex:1;height:4px;border-radius:999px;background:linear-gradient(90deg,#0B6CFF,#00D4FF)"></div>
        <span style="font-size:11px;font-weight:850;color:#98A2B3;white-space:nowrap">2 / 2</span>
      </div>
      <h1 class="auth-title">Tes centres d'intérêt 🎯</h1>
      <p class="auth-sub">Choisis au moins 3 centres d'intérêt pour personnaliser ton expérience.</p>
      <div id="interestsGrid" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:6px"></div>
      <p id="interestCount" style="font-size:12px;color:#98A2B3;font-weight:750;margin-bottom:16px;margin-top:8px">0 sélectionné(s) — min. 3</p>
      <button class="submit-btn" id="submitBtn" type="button" disabled style="opacity:.5">Créer mon compte</button>
      <div id="error2" class="error-box"></div>
      <button type="button" id="backStepBtn" style="width:100%;margin-top:10px;background:none;border:0;color:#667085;font-size:13px;font-weight:750;cursor:pointer;padding:8px">← Retour</button>
    </div>

    <div class="auth-links">
      Déjà un compte ? <a class="auth-link" href="login.php">Se connecter</a>
    </div>
  </main>

</div>

<script>
const INTERESTS = [
  {emoji:'🌍',label:'Voyages'},{emoji:'🎉',label:'Soirées'},{emoji:'🏀',label:'Sport'},
  {emoji:'🎵',label:'Musique'},{emoji:'🎨',label:'Culture'},{emoji:'🍕',label:'Food'},
  {emoji:'🎮',label:'Gaming'},{emoji:'📸',label:'Photo'},{emoji:'🎬',label:'Cinéma'},
  {emoji:'📚',label:'Études'},{emoji:'🏋️',label:'Fitness'},{emoji:'🎸',label:'Concerts'},
  {emoji:'🌿',label:'Nature'},{emoji:'✈️',label:'Road trips'},{emoji:'🏖️',label:'Plage'},
  {emoji:'🎭',label:'Théâtre'},{emoji:'🍻',label:'Bar & apéro'},{emoji:'🏔️',label:'Montagne'},
];
const selectedInterests = new Set();

// Build interests grid
const grid = document.getElementById('interestsGrid');
INTERESTS.forEach(({emoji, label}) => {
  const chip = document.createElement('button');
  chip.type = 'button';
  chip.className = 'interest-chip';
  chip.innerHTML = `${emoji} ${label}`;
  chip.dataset.label = label;
  chip.addEventListener('click', () => {
    if(selectedInterests.has(label)){
      selectedInterests.delete(label);
      chip.classList.remove('selected');
    } else {
      selectedInterests.add(label);
      chip.classList.add('selected');
    }
    const count = selectedInterests.size;
    document.getElementById('interestCount').textContent = `${count} sélectionné(s) — min. 3`;
    const btn = document.getElementById('submitBtn');
    btn.disabled = count < 3;
    btn.style.opacity = count < 3 ? '.5' : '1';
  });
  grid.appendChild(chip);
});

// Password toggle
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

// Step navigation
document.getElementById('nextStepBtn').addEventListener('click', () => {
  const err = document.getElementById('error1');
  err.style.display = 'none';
  const fn = document.getElementById('f_first_name').value.trim();
  const em = document.getElementById('f_email').value.trim();
  const un = document.getElementById('f_username').value.trim();
  const pw = document.getElementById('passwordInput').value;
  if(!fn || !em || !un || pw.length < 8){
    err.textContent = !fn ? 'Le prénom est requis.' : !em ? 'L\'email est requis.' : !un ? 'Le pseudo est requis.' : 'Mot de passe trop court (min. 8 caractères).';
    err.style.display = 'block'; return;
  }
  document.getElementById('step1').style.display = 'none';
  document.getElementById('step2').style.display = 'block';
  document.getElementById('authCard').scrollTop = 0;
});
document.getElementById('backStepBtn').addEventListener('click', () => {
  document.getElementById('step2').style.display = 'none';
  document.getElementById('step1').style.display = 'block';
});

// Submit
const ERRORS = {
  EMAIL_ALREADY_EXISTS: 'Un compte existe déjà avec cet email.',
  USERNAME_INVALID: 'Le pseudo doit contenir au moins 3 caractères.',
  PASSWORD_TOO_SHORT: 'Le mot de passe doit contenir au moins 8 caractères.',
  REGISTER_FAILED: 'Création impossible. Le pseudo est peut-être déjà utilisé.'
};
document.getElementById('submitBtn').addEventListener('click', async function(){
  const error = document.getElementById('error2');
  const btn = this;
  error.style.display = 'none';
  btn.textContent = 'Création en cours…';
  btn.disabled = true;

  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({
      action:'register',
      first_name: document.getElementById('f_first_name').value.trim(),
      last_name: document.getElementById('f_last_name').value.trim(),
      email: document.getElementById('f_email').value.trim(),
      username: document.getElementById('f_username').value.trim(),
      password: document.getElementById('passwordInput').value,
      interests: Array.from(selectedInterests),
      marque:'studimove'
    })
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
