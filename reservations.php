<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mes réservations · Studimove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--bg:#ffffff;--text:#111111;--line:#e9eef7;--blue:#0B6CFF;--cyan:#00D4FF;--yellow:#FFD000;--soft:#f4f8ff;--shadow:0 6px 18px rgba(0,0,0,.08)}
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;background:#fff;color:var(--text)}
    body{overflow-x:hidden}
    .app-shell{width:100%;min-height:100vh;background:#fff}
    .app-main{padding:0 0 96px}
    .topbar{display:flex;align-items:center;gap:12px;padding:18px 18px 0;min-height:44px}
    .back-btn{width:40px;height:40px;border:1px solid var(--line);background:#fff;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:20px;cursor:pointer;text-decoration:none;color:#111;box-shadow:var(--shadow);flex-shrink:0}
    .page-heading{font-size:20px;font-weight:950;letter-spacing:-.4px;margin:0}
    .hamburger-btn{width:44px;height:44px;border:0;background:transparent;cursor:pointer;display:inline-flex;flex-direction:column;justify-content:center;align-items:flex-end;gap:6px;padding:0;margin-left:auto;flex-shrink:0}
    .hamburger-btn span{display:block;height:2px;border-radius:999px;background:#000}
    .hamburger-btn span:nth-child(1){width:26px}.hamburger-btn span:nth-child(2){width:16px}.hamburger-btn span:nth-child(3){width:26px}
    .hamburger-btn:hover span{background:var(--blue)}
    .filter-tabs{display:flex;gap:8px;overflow-x:auto;scrollbar-width:none;padding:16px 18px 0}
    .filter-tabs::-webkit-scrollbar{display:none}
    .filter-tab{border:1px solid var(--line);background:#fff;color:#475467;border-radius:999px;padding:9px 16px;font-size:13px;font-weight:900;white-space:nowrap;cursor:pointer}
    .filter-tab.active{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-color:transparent;box-shadow:0 8px 22px rgba(11,108,255,.20)}
    .page-body{padding:16px 18px;display:flex;flex-direction:column;gap:14px}
    .empty-state{text-align:center;padding:60px 20px}
    .empty-icon{font-size:56px;margin-bottom:16px}
    .empty-title{font-size:18px;font-weight:950;margin:0 0 8px;color:#111}
    .empty-sub{font-size:14px;color:#667085;font-weight:700;margin:0 0 24px;line-height:1.5}
    .cta-btn{display:inline-block;background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-radius:999px;padding:12px 24px;font-size:14px;font-weight:950;text-decoration:none}
    .resa-card{background:#fff;border-radius:22px;border:1px solid var(--line);box-shadow:var(--shadow);overflow:hidden}
    .resa-media{height:150px;background-size:cover;background-position:center;position:relative}
    .resa-media-overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 30%,rgba(0,0,0,.65) 100%)}
    .resa-status-badge{position:absolute;top:12px;right:12px;border-radius:999px;padding:6px 12px;font-size:11px;font-weight:950}
    .resa-status-badge.confirmed{background:#e9fbf0;color:#067647;border:1px solid #bbf7d0}
    .resa-status-badge.pending{background:#fffbeb;color:#b45309;border:1px solid #fde68a}
    .resa-status-badge.cancelled{background:#fff1f2;color:#be123c;border:1px solid #fecdd3}
    .resa-overlay-title{position:absolute;bottom:12px;left:14px;right:14px;color:#fff;font-size:16px;font-weight:950;text-shadow:0 2px 10px rgba(0,0,0,.4)}
    .resa-body{padding:14px}
    .resa-meta{display:flex;flex-wrap:wrap;gap:8px;font-size:12px;font-weight:700;color:#667085;margin-bottom:12px}
    .resa-tag{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-radius:999px;padding:3px 9px;font-size:11px;font-weight:900}
    .resa-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    .resa-btn{border-radius:14px;padding:11px;font-size:13px;font-weight:950;cursor:pointer;border:0;text-align:center}
    .resa-btn.secondary{background:var(--soft);color:var(--blue);border:1px solid #dbeafe}
    .resa-btn.primary{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;box-shadow:0 6px 16px rgba(11,108,255,.18)}
    .loading-card{background:#fff;border-radius:22px;padding:24px;box-shadow:var(--shadow);color:#667085;font-weight:800;border:1px solid var(--line)}
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
      <h1 class="page-heading">Mes réservations</h1>
      <button class="hamburger-btn" id="openMenuBtn" type="button" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>

    <div class="filter-tabs" id="filterTabs">
      <button class="filter-tab active" type="button" data-filter="all">Toutes</button>
      <button class="filter-tab" type="button" data-filter="confirmed">Confirmées</button>
      <button class="filter-tab" type="button" data-filter="pending">En attente</button>
      <button class="filter-tab" type="button" data-filter="cancelled">Annulées</button>
    </div>

    <div class="page-body" id="pageBody">
      <div class="loading-card">Chargement de tes réservations…</div>
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
    <a class="drawer-link active" href="reservations.php"><span class="menu-ico">▣</span>Mes réservations</a>
    <a class="drawer-link" href="settings.php"><span class="menu-ico">⚙</span>Paramètres</a>
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

const EMOJIS={'Voyage':'🏝️','Soirée':'🎉','Sortie':'🎭','Bon plan':'💡','Jeux concours':'🎮'};
const IMGS={
  'Voyage':'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80',
  'Soirée':'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80',
  'Sortie':'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=600&q=80',
  'Bon plan':'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&q=80',
  'Jeux concours':'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=600&q=80',
};

let allReservations=[], activeFilter='all';

function statusLabel(s){return s==='confirmed'?'Confirmé':s==='cancelled'?'Annulé':'En attente'}
function statusClass(s){return s==='confirmed'?'confirmed':s==='cancelled'?'cancelled':'pending'}

function renderReservations(){
  const filtered=activeFilter==='all'?allReservations:allReservations.filter(r=>r.status===activeFilter||(!r.status&&activeFilter==='pending'));
  const container=document.getElementById('pageBody');
  if(!filtered.length){
    container.innerHTML='<div class="empty-state"><div class="empty-icon">▣</div><p class="empty-title">Aucune réservation</p><p class="empty-sub">Rejoins un événement depuis le feed pour voir tes réservations ici.</p><a class="cta-btn" href="index.php">Voir les événements</a></div>';
    return;
  }
  const img=r=>IMGS[r.category]||'https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=600&q=80';
  const date=r=>r.event_date?new Date(r.event_date).toLocaleDateString('fr-FR',{day:'numeric',month:'long',year:'numeric'}):'';
  container.innerHTML=filtered.map(r=>`
    <div class="resa-card">
      <div class="resa-media" style="background-image:url('${img(r)}')">
        <div class="resa-media-overlay"></div>
        <span class="resa-status-badge ${statusClass(r.status)}">${statusLabel(r.status)}</span>
        <p class="resa-overlay-title">${r.event_title||'Événement'}</p>
      </div>
      <div class="resa-body">
        <div class="resa-meta">
          <span class="resa-tag">${r.category||'Événement'}</span>
          ${date(r)?`<span>📅 ${date(r)}</span>`:''}
          ${r.location?`<span>📍 ${r.location}</span>`:''}
        </div>
        <div class="resa-actions">
          <button class="resa-btn secondary" onclick="showToast('Détails bientôt disponibles')">Voir détails</button>
          ${r.status!=='cancelled'?`<button class="resa-btn primary" onclick="showToast('Contact organisateur bientôt disponible')">Contacter</button>`:`<button class="resa-btn secondary" style="color:#e11d48;border-color:#fecdd3" onclick="showToast('Réservation annulée')">Annulée</button>`}
        </div>
      </div>
    </div>`).join('');
}

document.getElementById('filterTabs').addEventListener('click',e=>{
  const btn=e.target.closest('.filter-tab');
  if(!btn)return;
  document.querySelectorAll('.filter-tab').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  activeFilter=btn.dataset.filter;
  renderReservations();
});

async function fetchUser(){
  try{const r=await fetch('api/auth.php?action=me',{credentials:'include'});const d=await r.json();if(!d.ok||!d.user){window.location.href='login.php';return null}return d.user}catch{window.location.href='login.php';return null}
}

(async()=>{
  const user=await fetchUser();
  if(!user)return;
  const initial=(user.username||user.email||'S')[0].toUpperCase();
  const fullName=user.full_name||user.username||user.email?.split('@')[0]||'Mon compte';
  document.getElementById('drawerAvatarInitial').textContent=initial;
  document.getElementById('drawerName').textContent=fullName;
  document.getElementById('drawerUsername').textContent=user.username?'@'+user.username:user.email||'';
  try{
    const r=await fetch('api/reservations.php?action=list',{credentials:'include'});
    const d=await r.json();
    allReservations=d.ok?(d.reservations||[]):[];
  }catch{allReservations=[]}
  renderReservations();
})();
</script>
</body>
</html>
