<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Paramètres · Studimove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--bg:#ffffff;--text:#111111;--line:#e9eef7;--blue:#0B6CFF;--cyan:#00D4FF;--yellow:#FFD000;--soft:#f4f8ff;--shadow:0 6px 18px rgba(0,0,0,.08)}
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;background:#f7faff;color:var(--text)}
    body{overflow-x:hidden}
    .app-shell{width:100%;min-height:100vh}
    .app-main{padding:0 0 96px}
    .topbar{display:flex;align-items:center;gap:12px;padding:18px 18px 0;background:#f7faff}
    .back-btn{width:40px;height:40px;border:1px solid var(--line);background:#fff;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:20px;cursor:pointer;text-decoration:none;color:#111;box-shadow:var(--shadow);flex-shrink:0}
    .page-heading{font-size:20px;font-weight:950;letter-spacing:-.4px;margin:0}
    .hamburger-btn{width:44px;height:44px;border:0;background:transparent;cursor:pointer;display:inline-flex;flex-direction:column;justify-content:center;align-items:flex-end;gap:6px;padding:0;margin-left:auto;flex-shrink:0}
    .hamburger-btn span{display:block;height:2px;border-radius:999px;background:#000}
    .hamburger-btn span:nth-child(1){width:26px}.hamburger-btn span:nth-child(2){width:16px}.hamburger-btn span:nth-child(3){width:26px}
    .hamburger-btn:hover span{background:var(--blue)}
    .page-body{padding:20px 18px;display:flex;flex-direction:column;gap:10px}
    .section-label{font-size:11px;font-weight:900;color:#98A2B3;text-transform:uppercase;letter-spacing:.08em;margin:8px 0 4px 4px}
    .settings-group{background:#fff;border-radius:20px;border:1px solid var(--line);box-shadow:var(--shadow);overflow:hidden}
    .settings-row{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--line);cursor:pointer;text-decoration:none;color:#111}
    .settings-row:last-child{border-bottom:none}
    .settings-row:hover{background:var(--soft)}
    .row-ico{width:36px;height:36px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .row-ico.blue{background:#dbeafe}.row-ico.green{background:#dcfce7}.row-ico.yellow{background:#fef9c3}.row-ico.red{background:#fff1f2}.row-ico.purple{background:#f3e8ff}.row-ico.gray{background:var(--soft)}
    .row-text{flex:1;min-width:0}
    .row-label{font-size:14px;font-weight:850;margin:0}
    .row-sub{font-size:12px;color:#667085;font-weight:700;margin:2px 0 0}
    .row-chevron{color:#98A2B3;font-size:18px;flex-shrink:0}
    .row-toggle{position:relative;width:44px;height:26px;flex-shrink:0}
    .row-toggle input{opacity:0;width:0;height:0;position:absolute}
    .toggle-track{position:absolute;inset:0;background:#d1d5db;border-radius:999px;cursor:pointer;transition:.2s}
    .toggle-track:before{content:"";position:absolute;left:3px;top:3px;width:20px;height:20px;background:#fff;border-radius:999px;transition:.2s;box-shadow:0 2px 6px rgba(0,0,0,.18)}
    input:checked+.toggle-track{background:linear-gradient(90deg,var(--blue),var(--cyan))}
    input:checked+.toggle-track:before{transform:translateX(18px)}
    .version-note{text-align:center;font-size:12px;color:#98A2B3;font-weight:700;margin-top:8px}
    .bottom-nav{position:fixed;left:0;right:0;bottom:0;width:100%;height:56px;background:rgba(255,255,255,.96);backdrop-filter:blur(18px);border-top:1px solid var(--line);display:grid;grid-template-columns:repeat(4,1fr);padding:6px 18px 8px;z-index:20}
    .bottom-item{border:0;background:transparent;color:#98A2B3;font-size:0;cursor:pointer;display:flex;align-items:center;justify-content:center;text-decoration:none}
    .bottom-item .ico{width:34px;height:34px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:21px;line-height:1;transition:.18s ease}
    .bottom-item.active{color:var(--blue)}.bottom-item.active .ico{background:#f4f8ff;box-shadow:0 6px 16px rgba(11,108,255,.10)}
    .drawer-backdrop{position:fixed;inset:0;background:rgba(10,12,20,.42);z-index:50;opacity:0;pointer-events:none;transition:.22s ease}.drawer-backdrop.open{opacity:1;pointer-events:auto}
    .drawer{position:fixed;top:0;right:0;width:min(86vw,360px);height:100vh;background:#fff;z-index:60;transform:translateX(105%);transition:.25s ease;box-shadow:-24px 0 60px rgba(16,24,40,.18);padding:20px;display:flex;flex-direction:column;border-radius:30px 0 0 30px}.drawer.open{transform:translateX(0)}
    .drawer-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}.drawer-profile{display:flex;align-items:center;gap:12px;min-width:0}
    .avatar-btn{border:1px solid var(--line);cursor:pointer;background:#fff;box-shadow:var(--shadow);display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:18px;color:#fff;padding:3px}.avatar-inner{width:100%;height:100%;border-radius:15px;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-weight:950;font-size:15px;text-transform:uppercase}
    .drawer-profile strong{display:block;font-size:15px;line-height:1.1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.drawer-profile span{color:#667085;font-size:12px;font-weight:750}.close-btn{border:0;width:40px;height:40px;border-radius:16px;background:#f7faff;cursor:pointer;font-size:23px;line-height:1;color:#222}
    .drawer-menu{display:flex;flex-direction:column;gap:7px;user-select:none}.drawer-link{border:0;background:transparent;border-radius:18px;padding:14px 13px;display:flex;align-items:center;gap:13px;font-weight:900;font-size:14px;color:#222533;cursor:pointer;text-align:left;text-decoration:none;user-select:none}.drawer-link:hover,.drawer-link.active{background:#f4f8ff;color:var(--blue)}.drawer-link .menu-ico{width:26px;text-align:center;font-size:18px}.drawer-footer{margin-top:auto;padding-top:18px;border-top:1px solid var(--line)}.logout-link{color:#e11d48}
    .toast{position:fixed;left:50%;bottom:92px;transform:translateX(-50%) translateY(20px);background:#111827;color:#fff;border-radius:999px;padding:12px 16px;font-size:13px;font-weight:850;opacity:0;pointer-events:none;transition:.22s ease;z-index:100;max-width:88vw;text-align:center}.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
  </style>
</head>
<body>
<div class="app-shell">
  <main class="app-main">
    <div class="topbar">
      <button class="back-btn" onclick="history.back()" aria-label="Retour">←</button>
      <h1 class="page-heading">Paramètres</h1>
      <button class="hamburger-btn" id="openMenuBtn" type="button" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>

    <div class="page-body">

      <p class="section-label">Compte</p>
      <div class="settings-group">
        <a class="settings-row" href="#" onclick="showToast('Modification du profil bientôt disponible');return false">
          <div class="row-ico blue">👤</div>
          <div class="row-text"><p class="row-label">Modifier mon profil</p><p class="row-sub">Nom, école, photo…</p></div>
          <span class="row-chevron">›</span>
        </a>
        <a class="settings-row" href="#" onclick="showToast('Changement de mot de passe bientôt disponible');return false">
          <div class="row-ico purple">🔑</div>
          <div class="row-text"><p class="row-label">Mot de passe</p><p class="row-sub">Modifier mon mot de passe</p></div>
          <span class="row-chevron">›</span>
        </a>
        <a class="settings-row" href="#" onclick="showToast('Email bientôt modifiable');return false">
          <div class="row-ico blue">✉️</div>
          <div class="row-text"><p class="row-label" id="emailLabel">Email</p><p class="row-sub" id="emailSub">Chargement…</p></div>
          <span class="row-chevron">›</span>
        </a>
      </div>

      <p class="section-label">Notifications</p>
      <div class="settings-group">
        <div class="settings-row" style="cursor:default">
          <div class="row-ico yellow">🔔</div>
          <div class="row-text"><p class="row-label">Nouveaux événements</p><p class="row-sub">Alertes pour les nouvelles offres</p></div>
          <label class="row-toggle"><input type="checkbox" checked onchange="showToast(this.checked?'Notifications activées':'Notifications désactivées')"><span class="toggle-track"></span></label>
        </div>
        <div class="settings-row" style="cursor:default">
          <div class="row-ico green">📋</div>
          <div class="row-text"><p class="row-label">Réservations</p><p class="row-sub">Confirmations et rappels</p></div>
          <label class="row-toggle"><input type="checkbox" checked onchange="showToast(this.checked?'Notifications activées':'Notifications désactivées')"><span class="toggle-track"></span></label>
        </div>
        <div class="settings-row" style="cursor:default">
          <div class="row-ico blue">👥</div>
          <div class="row-text"><p class="row-label">Communauté</p><p class="row-sub">Messages et activités</p></div>
          <label class="row-toggle"><input type="checkbox" onchange="showToast(this.checked?'Notifications activées':'Notifications désactivées')"><span class="toggle-track"></span></label>
        </div>
      </div>

      <p class="section-label">Paiement</p>
      <div class="settings-group">
        <a class="settings-row" href="#" onclick="showToast('Paiement bientôt disponible');return false">
          <div class="row-ico green">💳</div>
          <div class="row-text"><p class="row-label">Moyens de paiement</p><p class="row-sub">Carte, virement, ANCV</p></div>
          <span class="row-chevron">›</span>
        </a>
        <a class="settings-row" href="#" onclick="showToast('Chèques ANCV bientôt disponibles');return false">
          <div class="row-ico yellow">🎫</div>
          <div class="row-text"><p class="row-label">Chèques vacances ANCV</p><p class="row-sub">Lier mes chèques vacances</p></div>
          <span class="row-chevron">›</span>
        </a>
      </div>

      <p class="section-label">Confidentialité</p>
      <div class="settings-group">
        <a class="settings-row" href="#" onclick="showToast('Confidentialité bientôt disponible');return false">
          <div class="row-ico gray">🔒</div>
          <div class="row-text"><p class="row-label">Confidentialité du profil</p><p class="row-sub">Qui peut voir mon profil</p></div>
          <span class="row-chevron">›</span>
        </a>
        <a class="settings-row" href="#" onclick="showToast('CGU bientôt disponibles');return false">
          <div class="row-ico gray">📄</div>
          <div class="row-text"><p class="row-label">CGU & Politique de confidentialité</p></div>
          <span class="row-chevron">›</span>
        </a>
      </div>

      <p class="section-label">Danger</p>
      <div class="settings-group">
        <a class="settings-row" href="#" onclick="confirmDelete();return false" style="color:#e11d48">
          <div class="row-ico red">🗑️</div>
          <div class="row-text"><p class="row-label" style="color:#e11d48">Supprimer mon compte</p><p class="row-sub">Action irréversible</p></div>
          <span class="row-chevron">›</span>
        </a>
      </div>

      <p class="version-note">Studimove App · Version 1.0.0 (test)</p>
    </div>
  </main>

  <nav class="bottom-nav">
    <a class="bottom-item" href="index.php" aria-label="Accueil"><span class="ico">⌂</span></a>
    <a class="bottom-item" href="search.php" aria-label="Recherche"><span class="ico">⌕</span></a>
    <a class="bottom-item" href="favorites.php" aria-label="Favoris"><span class="ico">♡</span></a>
    <a class="bottom-item" href="profile.php" aria-label="Profil"><span class="ico">◉</span></a>
  </nav>
</div>

<div class="drawer-backdrop" id="drawerBackdrop"></div>
<aside class="drawer" id="drawer" aria-hidden="true">
  <div class="drawer-head">
    <div class="drawer-profile"><span class="avatar-btn" style="box-shadow:none"><span class="avatar-inner" id="drawerAvatarInitial">S</span></span><div><strong id="drawerName">Studimove</strong><span id="drawerUsername">@studimove</span></div></div>
    <button class="close-btn" id="closeMenuBtn" type="button">×</button>
  </div>
  <div class="drawer-menu">
    <a class="drawer-link" href="index.php"><span class="menu-ico">⌂</span>Accueil</a>
    <a class="drawer-link" href="community.php"><span class="menu-ico">👥</span>Communauté</a>
    <a class="drawer-link" href="profile.php"><span class="menu-ico">◉</span>Mon profil</a>
    <a class="drawer-link" href="favorites.php"><span class="menu-ico">♥</span>Mes favoris</a>
    <a class="drawer-link" href="reservations.php"><span class="menu-ico">▣</span>Mes réservations</a>
    <a class="drawer-link active" href="settings.php"><span class="menu-ico">⚙</span>Paramètres</a>
    <a class="drawer-link" href="support.php"><span class="menu-ico">?</span>Aide & support</a>
  </div>
  <div class="drawer-footer"><button class="drawer-link logout-link" id="logoutBtn" type="button"><span class="menu-ico">↩</span>Se déconnecter</button></div>
</aside>
<div class="toast" id="toast"></div>

<script>
'use strict';
function showToast(msg){const t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');clearTimeout(t._tid);t._tid=setTimeout(()=>t.classList.remove('show'),2800)}
const openBtn=document.getElementById('openMenuBtn'),closeBtn=document.getElementById('closeMenuBtn'),drawer=document.getElementById('drawer'),backdrop=document.getElementById('drawerBackdrop');
function openDrawer(){drawer.classList.add('open');backdrop.classList.add('open');drawer.setAttribute('aria-hidden','false')}
function closeDrawer(){drawer.classList.remove('open');backdrop.classList.remove('open');drawer.setAttribute('aria-hidden','true')}
openBtn.addEventListener('click',openDrawer);closeBtn.addEventListener('click',closeDrawer);backdrop.addEventListener('click',closeDrawer);
document.getElementById('logoutBtn').addEventListener('click',async()=>{try{await fetch('api/auth.php?action=logout',{method:'POST',credentials:'include'})}catch{}window.location.href='login.php'});
function confirmDelete(){if(confirm('Supprimer ton compte ? Cette action est irréversible.')){showToast('Suppression de compte bientôt disponible')}}
async function fetchUser(){try{const r=await fetch('api/auth.php?action=me',{credentials:'include'});const d=await r.json();if(!d.ok||!d.user){window.location.href='login.php';return null}return d.user}catch{window.location.href='login.php';return null}}
(async()=>{
  const user=await fetchUser();if(!user)return;
  const initial=(user.username||user.email||'S')[0].toUpperCase();
  const fullName=user.full_name||user.username||user.email?.split('@')[0]||'Mon compte';
  document.getElementById('drawerAvatarInitial').textContent=initial;
  document.getElementById('drawerName').textContent=fullName;
  document.getElementById('drawerUsername').textContent=user.username?'@'+user.username:user.email||'';
  document.getElementById('emailLabel').textContent='Email';
  document.getElementById('emailSub').textContent=user.email||'';
})();
</script>
</body>
</html>
