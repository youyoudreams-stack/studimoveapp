<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';

$session_token = $_COOKIE[AUTH_COOKIE_NAME] ?? '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mon profil · Studimove</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#ffffff;--text:#111111;--line:#e9eef7;--blue:#0B6CFF;--cyan:#00D4FF;
      --yellow:#FFD000;--soft:#f4f8ff;--shadow:0 6px 18px rgba(0,0,0,.08);
    }
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;background:#fff;color:var(--text)}
    body{overflow-x:hidden}
    .app-shell{width:100%;min-height:100vh;background:#fff;position:relative}
    .app-main{width:100%;margin:0;padding:0 0 96px;min-height:100vh}

    /* ── Topbar ── */
    .topbar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:18px 18px 0;min-height:44px}
    .brand{font-size:22px;line-height:1;font-weight:950;letter-spacing:-.7px;margin:0;display:flex;align-items:center;height:44px;text-decoration:none}
    .brand-studi{color:var(--blue)}.brand-move{color:var(--yellow)}
    .hamburger-btn{width:44px;height:44px;border:0;background:transparent;cursor:pointer;display:inline-flex;flex-direction:column;justify-content:center;align-items:flex-end;gap:6px;padding:0;flex-shrink:0}
    .hamburger-btn span{display:block;height:2px;border-radius:999px;background:#000}
    .hamburger-btn span:nth-child(1){width:26px}.hamburger-btn span:nth-child(2){width:16px}.hamburger-btn span:nth-child(3){width:26px}
    .hamburger-btn:hover span{background:var(--blue)}

    /* ── Hero profil ── */
    .profile-hero{background:linear-gradient(160deg,#e8f0ff 0%,#f4f8ff 60%,#fff 100%);padding:24px 18px 0;position:relative;overflow:hidden}
    .profile-hero:before{content:"";position:absolute;top:-60px;right:-60px;width:220px;height:220px;background:radial-gradient(circle,rgba(11,108,255,.10) 0%,transparent 70%);border-radius:50%;pointer-events:none}
    .profile-top-row{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px}
    .avatar-circle{width:78px;height:78px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:950;color:#fff;box-shadow:0 8px 24px rgba(11,108,255,.28);flex-shrink:0;border:3px solid #fff}
    .edit-profile-btn{display:flex;align-items:center;gap:6px;border:1px solid var(--line);background:#fff;border-radius:999px;padding:8px 14px;font-size:13px;font-weight:850;color:#111;cursor:pointer;box-shadow:var(--shadow)}
    .edit-profile-btn:hover{border-color:var(--blue);color:var(--blue)}
    .profile-name{font-size:21px;font-weight:950;letter-spacing:-.4px;margin:0 0 3px;color:#111}
    .profile-school{font-size:13px;font-weight:700;color:#667085;margin:0 0 6px}
    .profile-location{display:flex;align-items:center;gap:5px;font-size:12px;color:#98A2B3;font-weight:700;margin-bottom:20px}
    .profile-location svg{flex-shrink:0}

    .stats-row{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--line);margin:0 -18px}
    .stat-item{text-align:center;padding:16px 0;border-right:1px solid var(--line)}
    .stat-item:last-child{border-right:none}
    .stat-num{font-size:22px;font-weight:950;letter-spacing:-.5px;background:linear-gradient(90deg,var(--blue),var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .stat-label{font-size:11px;font-weight:800;color:#98A2B3;margin-top:1px}

    /* ── Sections ── */
    .page-body{padding:20px 18px;display:flex;flex-direction:column;gap:16px}
    .smv-card{background:#fff;border-radius:22px;border:1px solid var(--line);box-shadow:var(--shadow);padding:16px}
    .card-title{font-size:15px;font-weight:950;letter-spacing:-.2px;margin:0 0 14px;color:#111}

    /* ── Niveau XP ── */
    .level-row{display:flex;align-items:center;gap:10px;margin-bottom:12px}
    .level-badge{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-radius:999px;padding:7px 14px;font-size:13px;font-weight:950}
    .xp-text{margin-left:auto;font-size:12px;font-weight:800;color:#667085}
    .progress-bg{height:7px;border-radius:999px;background:var(--line);overflow:hidden;margin-bottom:8px}
    .progress-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--blue),var(--cyan));transition:width .6s ease}
    .xp-next{font-size:12px;font-weight:700;color:#98A2B3}
    .xp-next span{color:var(--yellow);font-weight:950}

    /* ── Badges ── */
    .badges-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
    .badge-item{background:var(--soft);border:1px solid var(--line);border-radius:16px;padding:12px 6px 10px;text-align:center;transition:transform .15s ease}
    .badge-item:hover{transform:translateY(-2px)}
    .badge-item.locked{opacity:.38}
    .badge-emoji{font-size:24px;display:block;margin-bottom:5px}
    .badge-name{font-size:10px;font-weight:850;color:#344054;line-height:1.25}
    .badge-item.locked .badge-name{color:#98A2B3}

    /* ── Réservations ── */
    .resa-list{display:flex;flex-direction:column;gap:10px}
    .resa-card{display:flex;align-items:center;gap:12px;background:var(--soft);border:1px solid var(--line);border-radius:18px;padding:12px 14px}
    .resa-thumb{width:50px;height:50px;border-radius:14px;background:linear-gradient(135deg,var(--blue),var(--cyan));flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:22px}
    .resa-info{flex:1;min-width:0}
    .resa-name{font-size:14px;font-weight:950;margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .resa-date{font-size:12px;font-weight:700;color:#667085}
    .resa-status{font-size:11px;font-weight:950;padding:5px 11px;border-radius:999px;flex-shrink:0;white-space:nowrap}
    .resa-status.confirmed{background:#e9fbf0;color:#067647;border:1px solid #bbf7d0}
    .resa-status.pending{background:#fffbeb;color:#b45309;border:1px solid #fde68a}
    .resa-empty{text-align:center;padding:20px 0;color:#98A2B3;font-size:13px;font-weight:700}

    /* ── Préférences ── */
    .prefs-wrap{display:flex;flex-wrap:wrap;gap:8px}
    .pref-tag{background:var(--soft);border:1px solid #dbeafe;color:var(--blue);border-radius:999px;padding:7px 14px;font-size:12px;font-weight:900;cursor:default}

    /* ── Paramètres ── */
    .settings-list{display:flex;flex-direction:column}
    .settings-item{display:flex;align-items:center;gap:12px;padding:13px 0;border-bottom:1px solid var(--line);cursor:pointer;text-decoration:none;color:#111}
    .settings-item:last-child{border-bottom:none}
    .settings-item:hover .settings-label{color:var(--blue)}
    .settings-ico{width:36px;height:36px;border-radius:12px;background:var(--soft);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
    .settings-label{font-size:14px;font-weight:850}
    .settings-chevron{margin-left:auto;color:#98A2B3;font-size:18px;line-height:1}

    .logout-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;border:1px solid #fecdd3;background:#fff5f6;color:#e11d48;border-radius:18px;padding:14px;font-size:14px;font-weight:950;cursor:pointer;transition:background .15s ease}
    .logout-btn:hover{background:#fee2e2}

    /* ── Bottom nav ── */
    .bottom-nav{position:fixed;left:0;right:0;bottom:0;width:100%;height:56px;background:rgba(255,255,255,.96);backdrop-filter:blur(18px);border-top:1px solid var(--line);display:grid;grid-template-columns:repeat(4,1fr);padding:6px 18px 8px;z-index:20}
    @media(min-width:780px){.bottom-nav{display:none}.app-main{padding-bottom:28px}}
    .bottom-item{border:0;background:transparent;color:#98A2B3;font-size:0;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:0;text-decoration:none}
    .bottom-item .ico{width:34px;height:34px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:21px;line-height:1;transition:.18s ease}
    .bottom-item.active{color:var(--blue)}
    .bottom-item.active .ico{background:#f4f8ff;box-shadow:0 6px 16px rgba(11,108,255,.10)}

    /* ── Drawer ── */
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
      <a href="index.php" class="brand"><span class="brand-studi">Studi</span><span class="brand-move">move</span></a>
      <button class="hamburger-btn" id="openMenuBtn" type="button" aria-label="Ouvrir le menu"><span></span><span></span><span></span></button>
    </div>

    <!-- Hero -->
    <div class="profile-hero">
      <div class="profile-top-row">
        <div class="avatar-circle" id="avatarCircle">S</div>
        <button class="edit-profile-btn" type="button" id="editProfileBtn">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
          Modifier
        </button>
      </div>
      <p class="profile-name" id="profileName">Chargement…</p>
      <p class="profile-school" id="profileSchool">Étudiant</p>
      <div class="profile-location">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span id="profileLocation">France</span>
      </div>
      <div class="stats-row">
        <div class="stat-item"><div class="stat-num" id="statVoyages">0</div><div class="stat-label">Voyages</div></div>
        <div class="stat-item"><div class="stat-num" id="statEvents">0</div><div class="stat-label">Événements</div></div>
        <div class="stat-item"><div class="stat-num" id="statBadges">0</div><div class="stat-label">Badges</div></div>
      </div>
    </div>

    <div class="page-body">

      <!-- Niveau & XP -->
      <div class="smv-card">
        <p class="card-title">Niveau & XP</p>
        <div class="level-row">
          <div class="level-badge">🏆 <span id="levelName">Explorateur</span></div>
          <span class="xp-text"><span id="xpCurrent">0</span> / <span id="xpMax">1000</span> XP</span>
        </div>
        <div class="progress-bg"><div class="progress-fill" id="xpBar" style="width:0%"></div></div>
        <p class="xp-next">Plus que <strong id="xpLeft">1000</strong> XP pour atteindre <span id="nextLevel">Aventurier</span></p>
      </div>

      <!-- Badges -->
      <div class="smv-card">
        <p class="card-title">Badges obtenus</p>
        <div class="badges-grid" id="badgesGrid">
          <div class="badge-item earned"><span class="badge-emoji">✈️</span><div class="badge-name">Premier voyage</div></div>
          <div class="badge-item earned"><span class="badge-emoji">🎉</span><div class="badge-name">Noctambule</div></div>
          <div class="badge-item earned"><span class="badge-emoji">💡</span><div class="badge-name">Bon plan</div></div>
          <div class="badge-item locked"><span class="badge-emoji">🌍</span><div class="badge-name">Globe-trotter</div></div>
          <div class="badge-item locked"><span class="badge-emoji">🎓</span><div class="badge-name">Campus star</div></div>
          <div class="badge-item locked"><span class="badge-emoji">🏅</span><div class="badge-name">Légende</div></div>
          <div class="badge-item locked"><span class="badge-emoji">🤝</span><div class="badge-name">Ambassadeur</div></div>
          <div class="badge-item locked"><span class="badge-emoji">⚡</span><div class="badge-name">Early bird</div></div>
        </div>
      </div>

      <!-- Réservations -->
      <div class="smv-card">
        <p class="card-title">Mes réservations</p>
        <div class="resa-list" id="reservationsList">
          <p class="resa-empty">Chargement…</p>
        </div>
      </div>

      <!-- Préférences -->
      <div class="smv-card">
        <p class="card-title">Mes préférences</p>
        <div class="prefs-wrap">
          <span class="pref-tag">Voyages</span>
          <span class="pref-tag">Soirées</span>
          <span class="pref-tag">Sorties</span>
          <span class="pref-tag">Bon plan</span>
          <span class="pref-tag">Jeux concours</span>
        </div>
      </div>

      <!-- Paramètres -->
      <div class="smv-card">
        <p class="card-title">Paramètres</p>
        <div class="settings-list">
          <a class="settings-item" href="#" onclick="showToast('Notifications bientôt disponibles');return false">
            <div class="settings-ico">🔔</div>
            <span class="settings-label">Notifications</span>
            <span class="settings-chevron">›</span>
          </a>
          <a class="settings-item" href="#" onclick="showToast('Confidentialité bientôt disponible');return false">
            <div class="settings-ico">🔒</div>
            <span class="settings-label">Confidentialité</span>
            <span class="settings-chevron">›</span>
          </a>
          <a class="settings-item" href="#" onclick="showToast('Paiement bientôt disponible');return false">
            <div class="settings-ico">💳</div>
            <span class="settings-label">Paiement & ANCV</span>
            <span class="settings-chevron">›</span>
          </a>
          <a class="settings-item" href="#" onclick="showToast('Aide & support bientôt disponible');return false">
            <div class="settings-ico">❓</div>
            <span class="settings-label">Aide & support</span>
            <span class="settings-chevron">›</span>
          </a>
        </div>
      </div>

      <button class="logout-btn" id="logoutBtn" type="button">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Se déconnecter
      </button>

    </div>
  </main>

  <nav class="bottom-nav" aria-label="Navigation principale">
    <a class="bottom-item" href="index.php" aria-label="Accueil"><span class="ico">⌂</span></a>
    <a class="bottom-item" href="search.php" aria-label="Recherche"><span class="ico">⌕</span></a>
    <a class="bottom-item" href="favorites.php" aria-label="Favoris"><span class="ico">♡</span></a>
    <a class="bottom-item active" href="profile.php" aria-label="Profil"><span class="ico">◉</span></a>
  </nav>
</div>

<div class="drawer-backdrop" id="drawerBackdrop"></div>
<aside class="drawer" id="drawer" aria-hidden="true">
  <div class="drawer-head">
    <div class="drawer-profile">
      <span class="avatar-btn" style="box-shadow:none"><span class="avatar-inner" id="drawerAvatarInitial">S</span></span>
      <div><strong id="drawerName">Studimove</strong><span id="drawerUsername">@studimove</span></div>
    </div>
    <button class="close-btn" id="closeMenuBtn" type="button">×</button>
  </div>
  <div class="drawer-menu">
    <a class="drawer-link" href="index.php"><span class="menu-ico">⌂</span>Accueil</a>
    <a class="drawer-link" href="community.php"><span class="menu-ico">👥</span>Communauté</a>
    <a class="drawer-link active" href="profile.php"><span class="menu-ico">◉</span>Mon profil</a>
    <a class="drawer-link" href="favorites.php"><span class="menu-ico">♥</span>Mes favoris</a>
    <a class="drawer-link" href="reservations.php"><span class="menu-ico">▣</span>Mes réservations</a>
    <a class="drawer-link" href="settings.php"><span class="menu-ico">⚙</span>Paramètres</a>
    <a class="drawer-link" href="support.php"><span class="menu-ico">?</span>Aide & support</a>
  </div>
  <div class="drawer-footer">
    <button class="drawer-link logout-link" id="drawerLogoutBtn" type="button"><span class="menu-ico">↩</span>Se déconnecter</button>
  </div>
</aside>

<div class="toast" id="toast"></div>

<script>
'use strict';

const LEVELS = [
  { name: 'Débutant',    xp: 0    },
  { name: 'Explorateur', xp: 200  },
  { name: 'Aventurier',  xp: 600  },
  { name: 'Voyageur',    xp: 1200 },
  { name: 'Nomade',      xp: 2000 },
  { name: 'Légende',     xp: 3500 },
];

function getLevelInfo(xp) {
  let current = LEVELS[0], next = LEVELS[1];
  for (let i = 0; i < LEVELS.length; i++) {
    if (xp >= LEVELS[i].xp) { current = LEVELS[i]; next = LEVELS[i + 1] || null; }
  }
  return { current, next };
}

function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(t._tid);
  t._tid = setTimeout(() => t.classList.remove('show'), 2800);
}

async function fetchUser() {
  try {
    const res = await fetch('api/auth.php?action=me', { credentials: 'include' });
    const data = await res.json();
    if (!data.ok || !data.user) { window.location.href = 'login.php'; return null; }
    return data.user;
  } catch { window.location.href = 'login.php'; return null; }
}

async function fetchReservations() {
  try {
    const res = await fetch('api/reservations.php?action=list', { credentials: 'include' });
    const data = await res.json();
    return data.ok ? (data.reservations || []) : [];
  } catch { return []; }
}

function renderUser(user) {
  const initial = (user.username || user.email || 'S')[0].toUpperCase();
  const displayName = user.username ? '@' + user.username : user.email || 'Étudiant';
  const fullName = user.full_name || user.username || user.email?.split('@')[0] || 'Mon compte';

  document.getElementById('avatarCircle').textContent = initial;
  document.getElementById('profileName').textContent = fullName;
  document.getElementById('profileSchool').textContent = user.school || 'Étudiant';
  document.getElementById('profileLocation').textContent = user.city || 'France';
  document.getElementById('drawerAvatarInitial').textContent = initial;
  document.getElementById('drawerName').textContent = fullName;
  document.getElementById('drawerUsername').textContent = displayName;

  const xp = user.xp || 180;
  const { current, next } = getLevelInfo(xp);
  document.getElementById('levelName').textContent = current.name;
  document.getElementById('xpCurrent').textContent = xp;
  document.getElementById('xpMax').textContent = next ? next.xp : xp;
  const pct = next ? Math.round(((xp - current.xp) / (next.xp - current.xp)) * 100) : 100;
  document.getElementById('xpBar').style.width = pct + '%';
  document.getElementById('xpLeft').textContent = next ? (next.xp - xp) : 0;
  document.getElementById('nextLevel').textContent = next ? next.name : '— niveau max !';

  const earnedBadges = Math.min(3, Math.floor(xp / 60));
  document.getElementById('statBadges').textContent = earnedBadges;
}

function renderReservations(reservations) {
  const container = document.getElementById('reservationsList');
  if (!reservations.length) {
    container.innerHTML = '<p class="resa-empty">Aucune réservation pour le moment.</p>';
    document.getElementById('statEvents').textContent = 0;
    document.getElementById('statVoyages').textContent = 0;
    return;
  }

  const emojis = { 'Voyage': '🏝️', 'Soirée': '🎉', 'Sortie': '🎭', 'Bon plan': '💡', 'Jeux concours': '🎮' };
  let voyages = 0, events = 0;

  container.innerHTML = reservations.slice(0, 5).map(r => {
    const isVoyage = r.category === 'Voyage';
    if (isVoyage) voyages++; else events++;
    const emoji = emojis[r.category] || '📅';
    const status = r.status === 'confirmed' ? 'confirmed' : 'pending';
    const statusLabel = r.status === 'confirmed' ? 'Confirmé' : 'En attente';
    const date = r.event_date ? new Date(r.event_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
    return `<div class="resa-card">
      <div class="resa-thumb">${emoji}</div>
      <div class="resa-info">
        <p class="resa-name">${r.event_title || 'Événement'}</p>
        <p class="resa-date">${date}${r.location ? ' · ' + r.location : ''}</p>
      </div>
      <span class="resa-status ${status}">${statusLabel}</span>
    </div>`;
  }).join('');

  document.getElementById('statVoyages').textContent = voyages;
  document.getElementById('statEvents').textContent = events;
}

async function logout() {
  try { await fetch('api/auth.php?action=logout', { method: 'POST', credentials: 'include' }); } catch {}
  window.location.href = 'login.php';
}

document.getElementById('logoutBtn').addEventListener('click', logout);
document.getElementById('drawerLogoutBtn').addEventListener('click', logout);
document.getElementById('editProfileBtn').addEventListener('click', () => showToast('Modification du profil bientôt disponible'));

const openBtn = document.getElementById('openMenuBtn');
const closeBtn = document.getElementById('closeMenuBtn');
const drawer = document.getElementById('drawer');
const backdrop = document.getElementById('drawerBackdrop');
function openDrawer() { drawer.classList.add('open'); backdrop.classList.add('open'); drawer.setAttribute('aria-hidden','false'); }
function closeDrawer() { drawer.classList.remove('open'); backdrop.classList.remove('open'); drawer.setAttribute('aria-hidden','true'); }
openBtn.addEventListener('click', openDrawer);
closeBtn.addEventListener('click', closeDrawer);
backdrop.addEventListener('click', closeDrawer);

(async () => {
  const user = await fetchUser();
  if (!user) return;
  renderUser(user);
  const reservations = await fetchReservations();
  renderReservations(reservations);
})();
</script>
</body>
</html>
