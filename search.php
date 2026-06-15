<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Recherche · Studimove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--bg:#ffffff;--text:#111111;--line:#e9eef7;--blue:#0B6CFF;--cyan:#00D4FF;--yellow:#FFD000;--soft:#f4f8ff;--shadow:0 6px 18px rgba(0,0,0,.08)}
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;background:#fff;color:var(--text)}
    body{overflow-x:hidden}
    .app-shell{width:100%;min-height:100vh;background:#fff}
    .app-main{padding:0 0 96px}
    .topbar{display:flex;align-items:center;gap:12px;padding:18px 18px 0}
    .back-btn{width:40px;height:40px;border:1px solid var(--line);background:#fff;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:20px;cursor:pointer;text-decoration:none;color:#111;box-shadow:var(--shadow);flex-shrink:0}
    .search-bar{flex:1;display:flex;align-items:center;gap:10px;background:var(--soft);border:1.5px solid var(--line);border-radius:999px;padding:0 16px;height:44px;transition:border-color .15s}
    .search-bar:focus-within{border-color:var(--blue)}
    .search-icon{font-size:18px;color:#98A2B3;flex-shrink:0}
    .search-input{flex:1;border:0;background:transparent;outline:none;font-size:15px;font-weight:700;color:#111;min-width:0}
    .search-input::placeholder{color:#98A2B3;font-weight:650}
    .search-clear{border:0;background:transparent;color:#98A2B3;font-size:20px;cursor:pointer;padding:0;display:none}
    .section-title{font-size:15px;font-weight:950;letter-spacing:-.2px;color:#111;margin:20px 18px 12px}
    .cats-row{display:flex;gap:10px;overflow-x:auto;scrollbar-width:none;padding:0 18px}
    .cats-row::-webkit-scrollbar{display:none}
    .cat-pill{display:flex;flex-direction:column;align-items:center;gap:6px;min-width:72px;cursor:pointer;border:0;background:transparent;padding:0}
    .cat-icon{width:56px;height:56px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:26px;background:var(--soft);border:1px solid var(--line)}
    .cat-name{font-size:11px;font-weight:850;color:#344054}
    .cat-pill:hover .cat-icon{background:#dbeafe;border-color:var(--blue)}
    .results-grid{display:grid;grid-template-columns:1fr;gap:14px;padding:4px 18px}
    .result-card{background:#fff;border-radius:20px;border:1px solid var(--line);box-shadow:var(--shadow);display:flex;gap:12px;align-items:center;padding:12px;cursor:pointer}
    .result-thumb{width:72px;height:72px;border-radius:14px;background-size:cover;background-position:center;flex-shrink:0;background-color:#ddd}
    .result-info{flex:1;min-width:0}
    .result-title{font-size:14px;font-weight:950;margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .result-meta{font-size:12px;color:#667085;font-weight:700;display:flex;gap:6px;flex-wrap:wrap}
    .result-tag{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-radius:999px;padding:2px 8px;font-size:10px;font-weight:900}
    .result-price{font-size:13px;font-weight:950;color:var(--blue);margin-top:4px}
    .empty-search{text-align:center;padding:40px 20px;color:#667085;font-size:14px;font-weight:700}
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
      <div class="search-bar" style="flex:1">
        <span class="search-icon">⌕</span>
        <input class="search-input" id="searchInput" type="search" placeholder="Rechercher un événement…" autocomplete="off" autofocus>
        <button class="search-clear" id="searchClear" type="button" aria-label="Effacer">×</button>
      </div>
      <button class="hamburger-btn" id="openMenuBtn" type="button" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>

    <div id="defaultView">
      <p class="section-title">Catégories</p>
      <div class="cats-row">
        <button class="cat-pill" type="button" onclick="filterByCategory('Voyage')"><div class="cat-icon">✈️</div><span class="cat-name">Voyages</span></button>
        <button class="cat-pill" type="button" onclick="filterByCategory('Soirée')"><div class="cat-icon">🎉</div><span class="cat-name">Soirées</span></button>
        <button class="cat-pill" type="button" onclick="filterByCategory('Sortie')"><div class="cat-icon">🎭</div><span class="cat-name">Sorties</span></button>
        <button class="cat-pill" type="button" onclick="filterByCategory('Bon plan')"><div class="cat-icon">💡</div><span class="cat-name">Bon plan</span></button>
        <button class="cat-pill" type="button" onclick="filterByCategory('Jeux concours')"><div class="cat-icon">🎮</div><span class="cat-name">Concours</span></button>
      </div>
      <p class="section-title">Tendances</p>
      <div class="results-grid" id="trendingList"></div>
    </div>

    <div id="searchResults" style="display:none;padding-top:16px">
      <div class="results-grid" id="resultsList"></div>
    </div>
  </main>

  <nav class="bottom-nav">
    <a class="bottom-item" href="index.php" aria-label="Accueil"><span class="ico">⌂</span></a>
    <a class="bottom-item active" href="search.php" aria-label="Recherche"><span class="ico">⌕</span></a>
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
    <a class="drawer-link" href="support.php"><span class="menu-ico">?</span>Aide & support</a>
  </div>
  <div class="drawer-footer"><button class="drawer-link logout-link" id="logoutBtn" type="button"><span class="menu-ico">↩</span>Se déconnecter</button></div>
</aside>
<div class="toast" id="toast"></div>

<script>
'use strict';
function showToast(msg){const t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');clearTimeout(t._tid);t._tid=setTimeout(()=>t.classList.remove('show'),2800)}
const openBtn=document.getElementById('openMenuBtn')||document.querySelector('.hamburger-btn'),closeBtn=document.getElementById('closeMenuBtn'),drawer=document.getElementById('drawer'),backdrop=document.getElementById('drawerBackdrop');
function openDrawer(){drawer.classList.add('open');backdrop.classList.add('open');drawer.setAttribute('aria-hidden','false')}
function closeDrawer(){drawer.classList.remove('open');backdrop.classList.remove('open');drawer.setAttribute('aria-hidden','true')}
if(openBtn)openBtn.addEventListener('click',openDrawer);closeBtn.addEventListener('click',closeDrawer);backdrop.addEventListener('click',closeDrawer);
document.getElementById('logoutBtn').addEventListener('click',async()=>{try{await fetch('api/auth.php?action=logout',{method:'POST',credentials:'include'})}catch{}window.location.href='login.php'});

const EVENTS=[
  {id:1,title:'Spain Break 2024 – Lloret de Mar',category:'Voyage',date:'12 juil. 2024',location:'Lloret de Mar',price:'À partir de 249€',img:'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80'},
  {id:2,title:'Gala Digital College Paris',category:'Soirée',date:'28 juin 2024',location:'Paris',price:'Gratuit',img:'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=400&q=80'},
  {id:3,title:'Festival des Étudiants 2024',category:'Sortie',date:'5 août 2024',location:'Lyon',price:'39€',img:'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=400&q=80'},
  {id:4,title:'Soirée BDE Sciences Po',category:'Soirée',date:'20 juin 2024',location:'Paris',price:'12€',img:'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=400&q=80'},
  {id:5,title:'Road Trip Côte d\'Azur',category:'Voyage',date:'15 juil. 2024',location:'Nice',price:'À partir de 189€',img:'https://images.unsplash.com/photo-1533104816931-20fa691ff6ca?w=400&q=80'},
  {id:6,title:'Bon plan : Resto étudiant -50%',category:'Bon plan',date:'Toute la semaine',location:'Paris',price:'Dès 8€',img:'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&q=80'},
  {id:7,title:'Karting challenge inter-écoles',category:'Sortie',date:'8 juil. 2024',location:'Marne-la-Vallée',price:'25€',img:'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80'},
  {id:8,title:'Jeux concours : Gagnez un iPhone',category:'Jeux concours',date:'Jusqu\'au 30 juin',location:'En ligne',price:'Gratuit',img:'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&q=80'},
];

function cardHTML(e){
  return `<div class="result-card" onclick="showToast('Détail de l\'événement bientôt disponible')">
    <div class="result-thumb" style="background-image:url('${e.img}')"></div>
    <div class="result-info">
      <p class="result-title">${e.title}</p>
      <div class="result-meta"><span class="result-tag">${e.category}</span><span>📅 ${e.date}</span><span>📍 ${e.location}</span></div>
      <p class="result-price">${e.price}</p>
    </div>
  </div>`;
}

document.getElementById('trendingList').innerHTML=EVENTS.slice(0,4).map(cardHTML).join('');

const input=document.getElementById('searchInput'),clearBtn=document.getElementById('searchClear');
const defaultView=document.getElementById('defaultView'),resultsView=document.getElementById('searchResults'),resultsList=document.getElementById('resultsList');

function filterByCategory(cat){
  input.value=cat;
  doSearch(cat);
}

function doSearch(q){
  q=q.trim().toLowerCase();
  clearBtn.style.display=q?'block':'none';
  if(!q){defaultView.style.display='';resultsView.style.display='none';return}
  defaultView.style.display='none';resultsView.style.display='';
  const matches=EVENTS.filter(e=>e.title.toLowerCase().includes(q)||e.category.toLowerCase().includes(q)||e.location.toLowerCase().includes(q));
  resultsList.innerHTML=matches.length?matches.map(cardHTML).join(''):'<div class="empty-search">Aucun résultat pour "'+q+'"</div>';
}

input.addEventListener('input',()=>doSearch(input.value));
clearBtn.addEventListener('click',()=>{input.value='';doSearch('');input.focus()});

async function fetchUser(){
  try{const r=await fetch('api/auth.php?action=me',{credentials:'include'});const d=await r.json();if(!d.ok||!d.user){window.location.href='login.php';return null}return d.user}catch{window.location.href='login.php';return null}
}
(async()=>{
  const user=await fetchUser();if(!user)return;
  const initial=(user.username||user.email||'S')[0].toUpperCase();
  const fullName=user.full_name||user.username||user.email?.split('@')[0]||'Mon compte';
  document.getElementById('drawerAvatarInitial').textContent=initial;
  document.getElementById('drawerName').textContent=fullName;
  document.getElementById('drawerUsername').textContent=user.username?'@'+user.username:user.email||'';
})();
</script>
</body>
</html>
