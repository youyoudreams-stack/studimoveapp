<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Aide & support · Studimove</title>
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
    .contact-card{background:linear-gradient(135deg,var(--blue),var(--cyan));border-radius:24px;padding:24px;color:#fff;margin-bottom:6px}
    .contact-title{font-size:20px;font-weight:950;margin:0 0 6px}
    .contact-sub{font-size:13px;font-weight:700;opacity:.88;margin:0 0 20px;line-height:1.5}
    .contact-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    .contact-btn{background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.35);border-radius:14px;padding:12px;color:#fff;font-size:13px;font-weight:950;cursor:pointer;text-align:center;text-decoration:none;display:block}
    .contact-btn:hover{background:rgba(255,255,255,.3)}
    .section-label{font-size:11px;font-weight:900;color:#98A2B3;text-transform:uppercase;letter-spacing:.08em;margin:8px 0 4px 4px}
    .faq-group{background:#fff;border-radius:20px;border:1px solid var(--line);box-shadow:var(--shadow);overflow:hidden}
    .faq-item{border-bottom:1px solid var(--line)}
    .faq-item:last-child{border-bottom:none}
    .faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 16px;background:transparent;border:0;text-align:left;font-size:14px;font-weight:850;color:#111;cursor:pointer}
    .faq-q:hover{background:var(--soft)}
    .faq-arrow{color:#98A2B3;font-size:18px;transition:transform .2s;flex-shrink:0}
    .faq-item.open .faq-arrow{transform:rotate(90deg)}
    .faq-a{display:none;padding:0 16px 14px;font-size:13px;color:#475467;font-weight:700;line-height:1.6}
    .faq-item.open .faq-a{display:block}
    .links-group{background:#fff;border-radius:20px;border:1px solid var(--line);box-shadow:var(--shadow);overflow:hidden}
    .link-row{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--line);cursor:pointer;text-decoration:none;color:#111}
    .link-row:last-child{border-bottom:none}
    .link-row:hover{background:var(--soft)}
    .link-ico{font-size:20px;width:28px;text-align:center;flex-shrink:0}
    .link-label{font-size:14px;font-weight:850;flex:1}
    .link-chevron{color:#98A2B3;font-size:18px}
    .bottom-nav{position:fixed;left:0;right:0;bottom:0;width:100%;height:56px;background:rgba(255,255,255,.96);backdrop-filter:blur(18px);border-top:1px solid var(--line);display:grid;grid-template-columns:repeat(4,1fr);padding:6px 18px 8px;z-index:20}
    .bottom-item{border:0;background:transparent;color:#98A2B3;font-size:0;cursor:pointer;display:flex;align-items:center;justify-content:center;text-decoration:none}
    .bottom-item .ico{width:34px;height:34px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:21px;line-height:1;transition:.18s ease}
    .bottom-item.active{color:var(--blue)}.bottom-item.active .ico{background:#f4f8ff;box-shadow:0 6px 16px rgba(11,108,255,.10)}
    .drawer-backdrop{position:fixed;inset:0;background:rgba(10,12,20,.42);z-index:50;opacity:0;pointer-events:none;transition:.22s ease}.drawer-backdrop.open{opacity:1;pointer-events:auto}
    .drawer{position:fixed;top:0;right:0;width:min(86vw,360px);height:100vh;background:#fff;z-index:60;transform:translateX(105%);transition:.25s ease;box-shadow:-24px 0 60px rgba(16,24,40,.18);padding:20px;display:flex;flex-direction:column;border-radius:30px 0 0 30px}.drawer.open{transform:translateX(0)}
    .drawer-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}.drawer-profile{display:flex;align-items:center;gap:12px;min-width:0}
    .avatar-btn{border:1px solid var(--line);cursor:pointer;background:#fff;box-shadow:var(--shadow);display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:18px;color:#fff;padding:3px}.avatar-inner{width:100%;height:100%;border-radius:15px;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-weight:950;font-size:15px;text-transform:uppercase}
    .drawer-profile strong{display:block;font-size:15px;line-height:1.1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.drawer-profile span{color:#667085;font-size:12px;font-weight:750}.close-btn{border:0;width:40px;height:40px;border-radius:16px;background:#f7faff;cursor:pointer;font-size:23px;line-height:1;color:#222}
    .drawer-menu{display:flex;flex-direction:column;gap:7px}.drawer-link{border:0;background:transparent;border-radius:18px;padding:14px 13px;display:flex;align-items:center;gap:13px;font-weight:900;font-size:14px;color:#222533;cursor:pointer;text-align:left;text-decoration:none}.drawer-link:hover,.drawer-link.active{background:#f4f8ff;color:var(--blue)}.drawer-link .menu-ico{width:26px;text-align:center;font-size:18px}.drawer-footer{margin-top:auto;padding-top:18px;border-top:1px solid var(--line)}.logout-link{color:#e11d48}
    .toast{position:fixed;left:50%;bottom:92px;transform:translateX(-50%) translateY(20px);background:#111827;color:#fff;border-radius:999px;padding:12px 16px;font-size:13px;font-weight:850;opacity:0;pointer-events:none;transition:.22s ease;z-index:100;max-width:88vw;text-align:center}.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
  </style>
</head>
<body>
<div class="app-shell">
  <main class="app-main">
    <div class="topbar">
      <a class="back-btn" href="profile.php" aria-label="Retour">←</a>
      <h1 class="page-heading">Aide & support</h1>
      <button class="hamburger-btn" id="openMenuBtn" type="button" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>

    <div class="page-body">

      <div class="contact-card">
        <p class="contact-title">On est là pour toi 👋</p>
        <p class="contact-sub">Une question sur ta réservation ou l'app ? Notre équipe répond en moins de 24h.</p>
        <div class="contact-actions">
          <a class="contact-btn" href="mailto:support@studimove.fr">✉️ Email</a>
          <a class="contact-btn" href="https://wa.me/33600000000" onclick="showToast('WhatsApp bientôt disponible');return false">💬 WhatsApp</a>
        </div>
      </div>

      <p class="section-label">FAQ</p>
      <div class="faq-group" id="faqGroup">
        <div class="faq-item">
          <button class="faq-q" type="button">Comment réserver un événement ?<span class="faq-arrow">›</span></button>
          <div class="faq-a">Parcours le feed, clique sur l'événement qui t'intéresse, puis appuie sur "Je participe". Tu recevras une confirmation par email.</div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">Puis-je annuler une réservation ?<span class="faq-arrow">›</span></button>
          <div class="faq-a">Oui, jusqu'à 7 jours avant l'événement sans frais. Au-delà, contacte notre équipe support.</div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">Comment utiliser mes chèques ANCV ?<span class="faq-arrow">›</span></button>
          <div class="faq-a">Rends-toi dans Paramètres → Paiement → Chèques vacances ANCV pour lier tes chèques. 12% de nos utilisateurs en profitent déjà !</div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">Comment gagner des XP et des badges ?<span class="faq-arrow">›</span></button>
          <div class="faq-a">Tu gagnes des XP en réservant des événements, en invitant des amis et en participant à la communauté. Les badges se débloquent automatiquement selon ton activité.</div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">Studimove est-il disponible dans toute la France ?<span class="faq-arrow">›</span></button>
          <div class="faq-a">Oui ! On couvre toute la France et propose également des événements à l'international (Espagne, Portugal, Italie…).</div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">L'app est-elle gratuite ?<span class="faq-arrow">›</span></button>
          <div class="faq-a">L'inscription et la navigation sont gratuites. Tu paies uniquement les événements auxquels tu participes, souvent à tarif étudiant préférentiel.</div>
        </div>
      </div>

      <p class="section-label">Ressources</p>
      <div class="links-group">
        <a class="link-row" href="#" onclick="showToast('CGU bientôt disponibles');return false"><span class="link-ico">📄</span><span class="link-label">Conditions d'utilisation</span><span class="link-chevron">›</span></a>
        <a class="link-row" href="#" onclick="showToast('Politique bientôt disponible');return false"><span class="link-ico">🔒</span><span class="link-label">Politique de confidentialité</span><span class="link-chevron">›</span></a>
        <a class="link-row" href="#" onclick="showToast('Mentions bientôt disponibles');return false"><span class="link-ico">ℹ️</span><span class="link-label">Mentions légales</span><span class="link-chevron">›</span></a>
        <a class="link-row" href="mailto:contact@studimove.fr"><span class="link-ico">💌</span><span class="link-label">Nous contacter</span><span class="link-chevron">›</span></a>
      </div>

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
    <a class="drawer-link" href="settings.php"><span class="menu-ico">⚙</span>Paramètres</a>
    <a class="drawer-link active" href="support.php"><span class="menu-ico">?</span>Aide & support</a>
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

document.getElementById('faqGroup').addEventListener('click',e=>{
  const q=e.target.closest('.faq-q');if(!q)return;
  const item=q.closest('.faq-item');
  const wasOpen=item.classList.contains('open');
  document.querySelectorAll('.faq-item.open').forEach(i=>i.classList.remove('open'));
  if(!wasOpen)item.classList.add('open');
});

async function fetchUser(){try{const r=await fetch('api/auth.php?action=me',{credentials:'include'});const d=await r.json();if(!d.ok||!d.user){window.location.href='login.php';return null}return d.user}catch{window.location.href='login.php';return null}}
(async()=>{const user=await fetchUser();if(!user)return;const initial=(user.username||user.email||'S')[0].toUpperCase();const fullName=user.full_name||user.username||user.email?.split('@')[0]||'Mon compte';document.getElementById('drawerAvatarInitial').textContent=initial;document.getElementById('drawerName').textContent=fullName;document.getElementById('drawerUsername').textContent=user.username?'@'+user.username:user.email||''})();
</script>
</body>
</html>
