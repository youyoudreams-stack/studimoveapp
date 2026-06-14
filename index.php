<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Studimove App</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#ffffff; --text:#111111; --line:#e9eef7; --blue:#0B6CFF; --cyan:#00D4FF;
      --yellow:#FFD000; --soft:#f4f8ff; --shadow:0 6px 18px rgba(0,0,0,.08);
    }
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;background:#fff;color:var(--text)}
    body{overflow-x:hidden}
    .app-shell{width:100%;min-height:100vh;background:#fff;position:relative;overflow:hidden}
    .app-main{width:100%;margin:0;padding:16px 18px 96px;min-height:100vh}
    .topbar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding-top:2px;margin-bottom:18px;min-height:44px}
    .brand{font-size:22px;line-height:1;font-weight:950;letter-spacing:-.7px;margin:0;display:flex;align-items:center;height:44px}
    .brand-studi{color:var(--blue)} .brand-move{color:var(--yellow)}
    .intro{margin:0 0 18px}
    .hello{font-size:19px;line-height:1.12;margin:0 0 5px;font-weight:900;letter-spacing:-.35px;color:#111}
    .subtitle{margin:0;color:#111;font-size:13px;font-weight:650;line-height:1.35}
    .hamburger-btn{width:44px;height:44px;border:0;background:transparent;cursor:pointer;display:inline-flex;flex-direction:column;justify-content:center;align-items:flex-end;gap:6px;padding:0;box-shadow:none;flex-shrink:0}
    .hamburger-btn span{display:block;height:2px;border-radius:999px;background:#000}
    .hamburger-btn span:nth-child(1){width:26px}.hamburger-btn span:nth-child(2){width:16px}.hamburger-btn span:nth-child(3){width:26px}
    .hamburger-btn:hover span{background:var(--blue)}
    .section-title-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:18px 0 12px}
    .section-title{font-size:17px;font-weight:950;letter-spacing:-.2px;margin:0;color:#111}
    .section-link{border:0;background:transparent;color:var(--blue);font-size:12px;font-weight:850;cursor:pointer;padding:0}
    .h-scroll{display:flex;gap:12px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:2px 2px 12px;margin:0 -2px}
    .h-scroll::-webkit-scrollbar{display:none}
    .category-card{min-width:132px;height:94px;border:0;cursor:pointer;text-align:left;border-radius:22px;padding:14px;color:#fff;overflow:hidden;position:relative;box-shadow:0 12px 24px rgba(11,108,255,.16);background-size:cover;background-position:center;transition:transform .18s ease,box-shadow .18s ease,outline .18s ease;outline:3px solid transparent;outline-offset:2px}
    .category-card.category-active{outline:3px solid var(--blue);box-shadow:0 12px 28px rgba(11,108,255,.38);transform:scale(1.04)}
    .category-card:not(.category-active):hover{transform:scale(1.03)}
    .category-card:before{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.08),rgba(0,0,0,.52));z-index:0}
    .category-title{display:block;font-size:16px;font-weight:950;letter-spacing:-.2px;position:absolute;left:14px;bottom:13px;z-index:1;text-shadow:0 2px 10px rgba(0,0,0,.35)}
    .spotlight-intro{margin:8px 0 12px}
    .spotlight-kicker{margin:0;font-size:18px;font-weight:950;letter-spacing:-.25px;color:#111}
    .spotlight-kicker .blue{color:var(--blue)}
    .spotlight-text{margin:5px 0 0;color:#344054;font-size:13px;line-height:1.4;font-weight:650}
    .spotlight-row{display:flex;gap:14px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:2px 2px 14px;margin:0 -2px}
    .spotlight-row::-webkit-scrollbar{display:none}
    .spotlight-card{position:relative;min-width:218px;height:312px;border:0;border-radius:24px;background-size:cover;background-position:center;overflow:hidden;box-shadow:0 12px 28px rgba(0,0,0,.14);cursor:pointer;text-align:left;color:#fff;padding:0}
    .spotlight-card:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.03) 0%,rgba(0,0,0,.25) 48%,rgba(0,0,0,.72) 100%);z-index:1}
    .spotlight-rank{position:absolute;left:10px;bottom:-11px;z-index:2;font-size:82px;line-height:.8;font-weight:950;color:#fff;-webkit-text-stroke:2px rgba(0,0,0,.38);text-shadow:0 10px 22px rgba(0,0,0,.35);letter-spacing:-6px}
    .spotlight-content{position:absolute;left:76px;right:12px;bottom:14px;z-index:3}
    .spotlight-title{display:block;font-size:15px;font-weight:950;line-height:1.1;text-shadow:0 2px 10px rgba(0,0,0,.35)}
    .spotlight-meta{display:block;margin-top:5px;font-size:11px;font-weight:850;opacity:.9}
    .spotlight-badge{position:absolute;top:12px;left:12px;z-index:3;background:rgba(255,255,255,.95);color:#111;border-radius:999px;padding:6px 9px;font-size:11px;font-weight:950;box-shadow:0 8px 20px rgba(0,0,0,.12)}

    .feed-tabs-wrap{position:sticky;top:0;z-index:5;background:rgba(255,255,255,.94);backdrop-filter:blur(14px);padding:8px 0 10px;margin:0 -2px 4px}
    .feed-tabs{display:flex;gap:9px;overflow-x:auto;scrollbar-width:none;padding:0 2px}
    .feed-tabs::-webkit-scrollbar{display:none}
    .feed-tab{border:1px solid var(--line);cursor:pointer;white-space:nowrap;border-radius:999px;padding:10px 16px;font-weight:900;font-size:13px;color:#475467;background:#fff;box-shadow:0 4px 12px rgba(0,0,0,.04)}
    .feed-tab.active{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-color:transparent;box-shadow:0 8px 22px rgba(11,108,255,.20)}
    .feed-list{display:grid;grid-template-columns:1fr;gap:16px;margin-top:12px;width:100%}
    .post-card{background:#fff;border-radius:24px;overflow:hidden;box-shadow:var(--shadow);border:1px solid var(--line);cursor:pointer}
    .post-head{padding:14px 14px 11px;display:flex;align-items:center;justify-content:space-between;gap:12px}
    .post-author{display:flex;align-items:center;gap:10px;min-width:0}
    .entity-logo{width:42px;height:42px;border-radius:17px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:950;flex-shrink:0;box-shadow:0 8px 18px rgba(11,108,255,.18)}
    .author-name{font-size:14px;font-weight:950;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .post-meta{margin:2px 0 0;color:#667085;font-size:11px;font-weight:700}
    .more-btn{border:0;background:#f7faff;color:#98A2B3;border-radius:14px;width:34px;height:34px;cursor:pointer;font-weight:950}
    .post-media{height:245px;position:relative;overflow:hidden;background:#ddd}
    .post-img{width:100%;height:100%;background-size:cover;background-position:center;display:block}
    .media-badge{position:absolute;right:13px;top:13px;background:rgba(0,0,0,.42);color:#fff;border-radius:999px;padding:6px 10px;font-size:11px;font-weight:950;backdrop-filter:blur(10px)}
    .event-pill{position:absolute;left:13px;top:13px;background:#fff;color:#111827;border-radius:999px;padding:7px 11px;font-size:11px;font-weight:950;display:flex;align-items:center;gap:6px;box-shadow:0 10px 22px rgba(0,0,0,.12)}
    .favorite-btn{position:absolute;right:13px;bottom:13px;width:44px;height:44px;border:0;border-radius:999px;background:rgba(0,0,0,.28);backdrop-filter:blur(10px);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:none;transition:background .18s ease,box-shadow .18s ease}
    .favorite-btn.active{background:rgba(255,255,255,.97);color:#ed4956;box-shadow:0 4px 16px rgba(237,73,86,.3)}
    .post-body{padding:14px}
    .post-title{margin:0 0 7px;font-size:16px;line-height:1.2;font-weight:950;letter-spacing:-.2px}
    .post-text{margin:0;color:#475467;font-size:13px;line-height:1.45;font-weight:600}
    .event-social-proof{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:13px;padding:10px 11px;background:#f7faff;border:1px solid var(--line);border-radius:18px}
    .avatar-stack{display:flex;align-items:center;min-width:72px}.mini-avatar{width:28px;height:28px;border-radius:999px;border:2px solid #fff;background-size:cover;background-position:center;margin-left:-8px;box-shadow:0 4px 10px rgba(0,0,0,.12)}.mini-avatar:first-child{margin-left:0}
    .event-counts{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:6px;font-size:11px;font-weight:900;color:#111;text-align:right}.event-counts span{background:#fff;border:1px solid #e8eef8;border-radius:999px;padding:6px 8px}
    .post-actions{margin-top:14px;display:flex;align-items:center;gap:14px;color:#667085;font-size:13px;font-weight:850}.action{display:flex;align-items:center;gap:6px}.action-spacer{margin-left:auto}
    .bottom-nav{position:fixed;left:0;right:0;bottom:0;width:100%;height:56px;background:rgba(255,255,255,.96);backdrop-filter:blur(18px);border-top:1px solid var(--line);display:grid;grid-template-columns:repeat(4,1fr);padding:6px 18px 8px;z-index:20}
    .bottom-item{border:0;background:transparent;color:#98A2B3;font-size:0;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:0}
    .bottom-item .ico{width:34px;height:34px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:21px;line-height:1;transition:.18s ease}
    .bottom-item.active{background:transparent;color:var(--blue)}
    .bottom-item.active .ico{background:#f4f8ff;box-shadow:0 6px 16px rgba(11,108,255,.10)}
    .drawer-backdrop{position:fixed;inset:0;background:rgba(10,12,20,.42);z-index:50;opacity:0;pointer-events:none;transition:.22s ease}.drawer-backdrop.open{opacity:1;pointer-events:auto}
    .drawer{position:fixed;top:0;right:0;width:min(86vw,360px);height:100vh;background:#fff;z-index:60;transform:translateX(105%);transition:.25s ease;box-shadow:-24px 0 60px rgba(16,24,40,.18);padding:20px;display:flex;flex-direction:column;border-radius:30px 0 0 30px}.drawer.open{transform:translateX(0)}
    .drawer-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}.drawer-profile{display:flex;align-items:center;gap:12px;min-width:0}
    .avatar-btn{border:1px solid var(--line);cursor:pointer;background:#fff;box-shadow:var(--shadow);display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:18px;color:#fff;padding:3px}.avatar-inner{width:100%;height:100%;border-radius:15px;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-weight:950;font-size:15px;text-transform:uppercase}
    .drawer-profile strong{display:block;font-size:15px;line-height:1.1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.drawer-profile span{color:#667085;font-size:12px;font-weight:750}.close-btn{border:0;width:40px;height:40px;border-radius:16px;background:#f7faff;cursor:pointer;font-size:23px;line-height:1;color:#222}
    .drawer-menu{display:flex;flex-direction:column;gap:7px}.drawer-link{border:0;background:transparent;border-radius:18px;padding:14px 13px;display:flex;align-items:center;gap:13px;font-weight:900;font-size:14px;color:#222533;cursor:pointer;text-align:left}.drawer-link:hover,.drawer-link.active{background:#f4f8ff;color:var(--blue)}.drawer-link .menu-ico{width:26px;text-align:center;font-size:18px}.drawer-footer{margin-top:auto;padding-top:18px;border-top:1px solid var(--line)}.logout-link{color:#e11d48}
    .toast{position:fixed;left:50%;bottom:92px;transform:translateX(-50%) translateY(20px);background:#111827;color:#fff;border-radius:999px;padding:12px 16px;font-size:13px;font-weight:850;opacity:0;pointer-events:none;transition:.22s ease;z-index:100;max-width:88vw;text-align:center}.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
    .loading-card{background:#fff;border-radius:24px;padding:24px;box-shadow:var(--shadow);color:#667085;font-weight:800;border:1px solid var(--line)}
    .detail-overlay{position:fixed;inset:0;background:#fff;z-index:200;transform:translateX(105%);transition:.28s ease;overflow:auto;-webkit-overflow-scrolling:touch}
    .detail-overlay.open{transform:translateX(0)}
    .detail-shell{min-height:100%;background:#fff;padding-bottom:28px;width:100%;max-width:none;margin:0}
    .detail-top{position:sticky;top:0;z-index:4;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);height:58px;display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-bottom:1px solid var(--line)}
    .detail-icon-btn{width:42px;height:42px;border:0;border-radius:999px;background:#f7faff;color:#111;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900;cursor:pointer}
    .detail-title-small{font-size:14px;font-weight:950;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:62vw;text-align:center}
    .detail-hero{height:330px;background:#ddd;background-size:cover;background-position:center;position:relative}
    .detail-hero:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.04),rgba(0,0,0,.42))}
    .detail-gallery-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;padding:10px 14px 0}
    .detail-gallery-img{height:82px;border-radius:16px;background-size:cover;background-position:center;border:1px solid var(--line)}
    .detail-title-block{padding:16px 16px 0;width:100%}
    .detail-main-row{display:grid;grid-template-columns:1fr;gap:14px;width:100%}
    .detail-media-zone{width:100%}
    .detail-info-zone{width:100%}

    .detail-content{padding:16px 16px 0;width:100%;max-width:none;margin:0}
    .detail-author{display:flex;align-items:center;gap:10px;margin-bottom:14px}
    .detail-author .entity-logo{width:46px;height:46px}.detail-author strong{display:block;font-size:15px}.detail-author span{display:block;font-size:12px;color:#667085;font-weight:700;margin-top:2px}
    .detail-badge-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:12px}
    .detail-badge{border-radius:999px;background:#f4f8ff;border:1px solid var(--line);padding:7px 10px;font-size:12px;font-weight:900;color:#111}.detail-badge.blue{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-color:transparent}
    .detail-main-title{font-size:25px;line-height:1.08;letter-spacing:-.7px;margin:0 0 10px;font-weight:950;color:#111}
    .detail-text{font-size:15px;line-height:1.58;color:#344054;margin:0 0 16px;font-weight:600}
    .detail-event-box{margin:16px 0;padding:14px;background:#f7faff;border:1px solid var(--line);border-radius:22px}
    .detail-event-box h3{margin:0 0 10px;font-size:16px;font-weight:950}.event-detail-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px}.event-detail-stat{background:#fff;border:1px solid #e8eef8;border-radius:18px;padding:12px}.event-detail-stat strong{display:block;font-size:20px}.event-detail-stat span{font-size:12px;color:#667085;font-weight:800}
    .event-cta-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:14px 0}
    .event-cta{border:0;border-radius:18px;padding:13px 12px;font-weight:950;cursor:pointer}
    .event-cta.interest{background:#f4f8ff;color:#0B6CFF;border:1px solid #dbeafe}
    .event-cta.join{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;box-shadow:0 8px 20px rgba(11,108,255,.18)}
    .detail-tabs{position:sticky;top:58px;z-index:3;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);display:flex;gap:8px;overflow-x:auto;padding:10px 16px;border-bottom:1px solid var(--line);scrollbar-width:none}
    .detail-tabs::-webkit-scrollbar{display:none}
    .detail-tab{border:1px solid var(--line);background:#fff;color:#475467;border-radius:999px;padding:9px 13px;font-size:12px;font-weight:950;white-space:nowrap;cursor:pointer}
    .detail-tab.active{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-color:transparent}
    .detail-panel{display:none;padding-top:2px}.detail-panel.active{display:block}
    .people-list,.comments-list{display:flex;flex-direction:column;gap:10px;margin-top:12px}
    .person-row,.comment-row{display:flex;gap:10px;align-items:center;background:#fff;border:1px solid var(--line);border-radius:18px;padding:10px;box-shadow:0 4px 12px rgba(0,0,0,.035)}
    .person-avatar,.comment-avatar{width:42px;height:42px;border-radius:999px;background-size:cover;background-position:center;flex-shrink:0}
    .person-main,.comment-main{min-width:0;flex:1}.person-main strong,.comment-main strong{display:block;font-size:13px;font-weight:950}.person-main span,.comment-main span{display:block;font-size:12px;color:#667085;font-weight:700;margin-top:2px}.person-badge{background:#f4f8ff;border:1px solid var(--line);border-radius:999px;padding:6px 9px;font-size:11px;font-weight:950;color:#0B6CFF}
    .comment-text{font-size:13px!important;color:#344054!important;line-height:1.35!important;font-weight:600!important}
    .comment-box{margin-top:14px;display:flex;gap:8px;background:#f7faff;border:1px solid var(--line);border-radius:18px;padding:8px}
    .comment-input{flex:1;border:0;background:transparent;outline:none;font-size:13px;font-weight:650;min-width:0}.comment-send{border:0;background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-radius:999px;padding:9px 12px;font-size:12px;font-weight:950;cursor:pointer}
    
    /* À la une — design inspiré des cartes StudiMove smvu-* */
    .spotlight-intro{margin:8px 0 12px}
    .spotlight-kicker{margin:0;font-size:18px;font-weight:950;letter-spacing:-.25px;color:#111}
    .spotlight-kicker .blue{color:var(--blue)}
    .spotlight-text{margin:5px 0 0;color:#344054;font-size:13px;line-height:1.4;font-weight:650}

    .spotlight-row{
      display:flex;
      gap:18px;
      overflow-x:auto;
      -webkit-overflow-scrolling:touch;
      scroll-snap-type:x mandatory;
      scrollbar-width:none;
      padding:8px 2px 16px;
      margin:0 -2px;
    }
    .spotlight-row::-webkit-scrollbar{height:8px}
    .spotlight-row::-webkit-scrollbar-thumb{background:linear-gradient(90deg,var(--blue),var(--cyan));border-radius:999px}
    .spotlight-row::-webkit-scrollbar-track{background:transparent}

    .spotlight-card{
      position:relative;
      overflow:hidden;
      flex:0 0 auto;
      width:clamp(250px,72vw,340px);
      border-radius:18px;
      background:#fff;
      box-shadow:0 8px 24px rgba(0,0,0,.10);
      border:2px solid transparent;
      background:linear-gradient(#fff,#fff) padding-box, linear-gradient(90deg,var(--blue),var(--cyan)) border-box;
      cursor:pointer;
      transition:transform .18s ease, box-shadow .18s ease;
      scroll-snap-align:start;
      padding:0;
      text-align:left;
    }
    .spotlight-card:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.14)}
    .spotlight-media{position:relative;aspect-ratio:3/4;background:#000;overflow:hidden}
    .spotlight-media img{width:100%;height:100%;object-fit:cover;display:block;transform:scale(1);transition:transform .6s ease}
    @media (hover:hover){.spotlight-card:hover .spotlight-media img{transform:scale(1.06)}}
    .spotlight-media:after{content:"";position:absolute;left:0;right:0;bottom:0;height:38%;background:linear-gradient(180deg,transparent 0%,rgba(0,0,0,.55) 60%,rgba(0,0,0,.78) 100%);pointer-events:none}
    .spotlight-rank{
      position:absolute;
      left:12px;
      bottom:12px;
      z-index:2;
      font-weight:1000;
      line-height:.9;
      color:#fff;
      -webkit-text-stroke:3px rgba(0,0,0,.75);
      text-shadow:0 2px 6px rgba(0,0,0,.55),0 0 1px rgba(0,0,0,.35);
      letter-spacing:-1px;
      user-select:none;
      pointer-events:none;
    }
    .spotlight-rank[data-rank="1"]{font-size:clamp(72px,9vw,140px)}
    .spotlight-rank[data-rank="2"]{font-size:clamp(60px,7.5vw,120px)}
    .spotlight-rank[data-rank="3"]{font-size:clamp(50px,6.2vw,100px)}
    .spotlight-rank[data-rank="4"]{font-size:clamp(42px,5.2vw,84px)}
    .spotlight-rank[data-rank="5"],.spotlight-rank[data-rank="6"],.spotlight-rank[data-rank="7"],.spotlight-rank[data-rank="8"],.spotlight-rank[data-rank="9"],.spotlight-rank[data-rank="10"]{font-size:clamp(42px,5vw,78px)}
    .spotlight-body{padding:12px 14px 44px;display:flex;flex-direction:column;gap:8px;min-height:122px}
    .spotlight-title{margin:0;line-height:1.18;font-weight:900;font-size:clamp(18px,2vw,24px);color:var(--blue)}
    .spotlight-sub{display:flex;align-items:center;gap:8px;flex-wrap:wrap;color:#666;font-size:13px;font-weight:650}
    .spotlight-tag{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;padding:4px 9px;border-radius:999px;font-weight:800;font-size:12px}
    .spotlight-dot:before{content:"•";opacity:.7}
    .spotlight-price{margin-top:auto;font-weight:900;color:var(--blue);font-size:15px}
    .spotlight-cta{
      position:absolute;
      right:12px;
      bottom:12px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:10px 14px;
      border-radius:999px;
      font-weight:800;
      font-size:13px;
      background:linear-gradient(90deg,var(--blue),var(--cyan));
      color:#fff;
      text-decoration:none;
      box-shadow:0 6px 18px rgba(11,108,255,.25);
      border:none;
      cursor:pointer;
    }
    .spotlight-cta:hover{filter:brightness(.98);transform:translateY(-1px)}

    @media(min-width:780px){.app-main{padding:20px 32px 32px}.bottom-nav{display:none}.spotlight-card{width:clamp(300px,30vw,420px)}.spotlight-card{min-width:260px;height:360px}.spotlight-rank{font-size:96px}.spotlight-content{left:88px}.feed-list{grid-template-columns:repeat(2,minmax(0,1fr))}.category-card{min-width:170px;height:118px}.brand{font-size:24px}.hello{font-size:20px}.detail-hero{height:440px}.detail-gallery-img{height:120px}.detail-title-block{padding:20px 24px 0}.detail-content{padding:18px 24px 0}}
    @media(min-width:1180px){.app-main{max-width:none;padding-left:44px;padding-right:44px}.spotlight-card{width:clamp(340px,23vw,460px)}.spotlight-card{min-width:280px;height:385px}.feed-list{grid-template-columns:repeat(3,minmax(0,1fr))}.category-card{min-width:220px;height:130px}.detail-main-row{grid-template-columns:minmax(0,1.25fr) minmax(380px,.75fr);align-items:start;padding:18px 24px 0}.detail-media-zone .detail-hero{border-radius:24px;height:520px}.detail-media-zone .detail-gallery-grid{padding:10px 0 0}.detail-info-zone .detail-title-block{padding:0}.detail-info-zone .detail-content{padding:14px 0 0}.detail-info-zone .detail-tabs{position:static;border:0;padding:16px 0 10px;background:#fff}.detail-info-zone .detail-main-title{text-align:right;font-size:34px}.detail-info-zone .detail-badge-row{justify-content:flex-end}.detail-info-zone .detail-author{justify-content:flex-end;text-align:right}.detail-shell{max-width:none;margin:0;box-shadow:none}}
    @media(min-width:1560px){.feed-list{grid-template-columns:repeat(4,minmax(0,1fr))}.category-card{min-width:250px;height:140px}}
  
    /* V11 — À la une : cartes plus hautes + effet Netflix + scroll desktop */
    .spotlight-row{
      display:flex!important;
      gap:18px!important;
      overflow-x:auto!important;
      overflow-y:hidden!important;
      -webkit-overflow-scrolling:touch!important;
      scroll-snap-type:x mandatory!important;
      scroll-behavior:smooth!important;
      padding:12px 18px 26px!important;
      margin:0 -18px!important;
      scrollbar-width:thin!important;
      scrollbar-color:var(--blue) transparent!important;
    }
    .spotlight-row::-webkit-scrollbar,
    .h-scroll::-webkit-scrollbar,
    .feed-tabs::-webkit-scrollbar{
      display:block!important;
      height:8px!important;
    }
    .spotlight-row::-webkit-scrollbar-thumb,
    .h-scroll::-webkit-scrollbar-thumb,
    .feed-tabs::-webkit-scrollbar-thumb{
      background:linear-gradient(90deg,var(--blue),var(--cyan))!important;
      border-radius:999px!important;
    }
    .spotlight-row::-webkit-scrollbar-track,
    .h-scroll::-webkit-scrollbar-track,
    .feed-tabs::-webkit-scrollbar-track{
      background:transparent!important;
    }

    .spotlight-card{
      position:relative!important;
      flex:0 0 auto!important;
      width:240px!important;
      height:380px!important;
      border-radius:22px!important;
      overflow:hidden!important;
      scroll-snap-align:center!important;
      transform:scale(.92)!important;
      transform-origin:center bottom!important;
      opacity:.72!important;
      transition:transform .28s ease, opacity .28s ease, box-shadow .28s ease!important;
      box-shadow:0 8px 22px rgba(0,0,0,.10)!important;
      background:#000!important;
      border:0!important;
      padding:0!important;
    }
    .spotlight-card.is-focused{
      transform:scale(1)!important;
      opacity:1!important;
      box-shadow:0 18px 42px rgba(0,0,0,.20)!important;
    }
    .spotlight-media{
      position:absolute!important;
      inset:0!important;
      aspect-ratio:auto!important;
      height:100%!important;
      width:100%!important;
    }
    .spotlight-media img{
      width:100%!important;
      height:100%!important;
      object-fit:cover!important;
      display:block!important;
    }
    .spotlight-media:after{
      content:""!important;
      position:absolute!important;
      inset:0!important;
      background:linear-gradient(180deg,rgba(0,0,0,.05) 0%,rgba(0,0,0,.16) 45%,rgba(0,0,0,.84) 100%)!important;
      z-index:1!important;
      pointer-events:none!important;
    }
    .spotlight-rank{
      position:absolute!important;
      left:10px!important;
      bottom:10px!important;
      z-index:3!important;
      font-size:110px!important;
      line-height:.78!important;
      font-weight:1000!important;
      color:#fff!important;
      -webkit-text-stroke:3px rgba(0,0,0,.78)!important;
      text-shadow:0 4px 10px rgba(0,0,0,.62),0 0 1px rgba(0,0,0,.45)!important;
      letter-spacing:-5px!important;
      pointer-events:none!important;
    }
    .spotlight-rank[data-rank="10"]{
      font-size:92px!important;
      letter-spacing:-8px!important;
      left:6px!important;
    }
    .spotlight-body{
      position:absolute!important;
      left:92px!important;
      right:12px!important;
      bottom:18px!important;
      z-index:4!important;
      padding:0!important;
      min-height:auto!important;
      color:#fff!important;
      display:block!important;
    }
    .spotlight-title{
      margin:0!important;
      color:#fff!important;
      font-size:15px!important;
      line-height:1.08!important;
      font-weight:950!important;
      text-shadow:0 2px 10px rgba(0,0,0,.45)!important;
    }
    .spotlight-sub{
      margin-top:6px!important;
      color:#fff!important;
      opacity:.92!important;
      font-size:11px!important;
      line-height:1.2!important;
      display:flex!important;
      gap:6px!important;
      align-items:center!important;
      flex-wrap:wrap!important;
    }
    .spotlight-tag{
      background:rgba(255,255,255,.94)!important;
      color:#111!important;
      border-radius:999px!important;
      padding:4px 8px!important;
      font-size:10px!important;
      font-weight:950!important;
    }
    .spotlight-price{
      margin-top:7px!important;
      color:#fff!important;
      font-size:12px!important;
      font-weight:950!important;
      text-shadow:0 2px 10px rgba(0,0,0,.45)!important;
    }
    .spotlight-cta{
      display:none!important;
    }

    @media (max-width:480px){
      .spotlight-row{
        padding-left:18px!important;
        padding-right:18px!important;
      }
      .spotlight-card{
        width:215px!important;
        height:345px!important;
      }
      .spotlight-rank{
        font-size:96px!important;
      }
      .spotlight-rank[data-rank="10"]{
        font-size:80px!important;
      }
      .spotlight-body{
        left:82px!important;
      }
    }

    @media (min-width:780px){
      .spotlight-row{
        padding-left:32px!important;
        padding-right:32px!important;
        margin-left:-32px!important;
        margin-right:-32px!important;
      }
      .spotlight-card{
        width:260px!important;
        height:410px!important;
      }
      .spotlight-rank{
        font-size:122px!important;
      }
      .spotlight-rank[data-rank="10"]{
        font-size:100px!important;
      }
      .spotlight-body{
        left:102px!important;
      }
    }

    @media (min-width:1180px){
      .spotlight-row{
        padding-left:44px!important;
        padding-right:44px!important;
        margin-left:-44px!important;
        margin-right:-44px!important;
      }
      .spotlight-card{
        width:280px!important;
        height:440px!important;
      }
    }

    /* Scroll desktop sur tous les rails horizontaux */
    .h-scroll,.feed-tabs{
      overflow-x:auto!important;
      overflow-y:hidden!important;
      -webkit-overflow-scrolling:touch!important;
      scroll-behavior:smooth!important;
      scrollbar-width:thin!important;
      scrollbar-color:var(--blue) transparent!important;
    }

  

    /* V44 - Ouverture événement/post + favoris premium */
    .favorite-btn,.favorite-detail{overflow:visible}
    .favorite-btn .heart-svg,.favorite-detail .heart-svg{width:22px;height:22px;display:block;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;transition:fill .15s ease,stroke .15s ease,transform .15s ease;filter:drop-shadow(0 1px 3px rgba(0,0,0,.25))}
    .favorite-detail{width:44px;height:44px;border:0;border-radius:999px;background:rgba(0,0,0,.22);backdrop-filter:blur(10px);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .18s ease,box-shadow .18s ease}
    .favorite-btn.active,.favorite-detail.active{color:#ed4956}
    .favorite-btn.active .heart-svg,.favorite-detail.active .heart-svg{fill:#ed4956;stroke:#ed4956;transform:scale(1.1)}
    .favorite-btn.heart-pop .heart-svg,.favorite-detail.heart-pop .heart-svg{animation:heartPop .45s cubic-bezier(.17,.89,.32,1.49)}
    @keyframes heartPop{0%{transform:scale(.6)}40%{transform:scale(1.28)}70%{transform:scale(.93)}100%{transform:scale(1.1)}}
    .premium-detail{background:linear-gradient(180deg,#fff 0%,#f7faff 100%);padding-bottom:32px}
    .detail-top.premium{border-bottom:0;background:rgba(255,255,255,.88);box-shadow:0 8px 24px rgba(16,24,40,.06)}
    .detail-premium-header{padding:18px 18px 10px;text-align:center;max-width:860px;margin:0 auto}
    .detail-main-title.premium{font-size:clamp(26px,5vw,44px);line-height:1.02;margin:0;color:#101828;letter-spacing:-1.2px;text-align:center}
    .detail-slogan{margin:9px auto 0;max-width:620px;color:#667085;font-size:15px;line-height:1.45;font-weight:750}
    .detail-hero-card{padding:0 16px;max-width:980px;margin:0 auto}
    .detail-hero.premium{height:clamp(260px,54vw,520px);border-radius:28px;background-size:cover;background-position:center;box-shadow:0 24px 60px rgba(16,24,40,.18);overflow:hidden;position:relative}
    .detail-hero.premium:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.14))}
    .detail-author-card{margin:-22px auto 0;position:relative;z-index:2;width:calc(100% - 46px);max-width:620px;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);border:1px solid rgba(234,240,248,.95);border-radius:24px;padding:12px 14px;display:flex;align-items:center;justify-content:center;gap:12px;box-shadow:0 18px 38px rgba(16,24,40,.12)}
    .detail-org-logo{width:48px;height:48px;flex:0 0 48px}.detail-author-copy{display:flex;flex-direction:column;align-items:flex-start}.detail-author-copy span{font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#98A2B3}.detail-author-copy strong{font-size:16px;font-weight:950;color:#101828}.detail-author-copy small{font-size:12px;font-weight:750;color:#667085;margin-top:2px}
    .detail-tabs.premium{position:sticky;top:58px;justify-content:center;background:rgba(247,250,255,.92);border-bottom:0;padding:16px 12px 8px;margin-top:8px}
    .detail-tabs.premium .detail-tab{background:#fff;box-shadow:0 8px 20px rgba(16,24,40,.06);border-color:#eef2f7}
    .detail-tabs.premium .detail-tab.active{box-shadow:0 12px 26px rgba(36,107,253,.22)}
    .detail-content.premium{max-width:860px;margin:0 auto;padding:12px 16px 0}.detail-section-card{background:#fff;border:1px solid #edf2f7;border-radius:28px;padding:18px;box-shadow:0 16px 36px rgba(16,24,40,.08)}.detail-section-card h3{margin:0 0 10px;font-size:18px;font-weight:950;color:#101828}
    .detail-meta-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin:16px 0}.detail-meta-card{background:#f8fbff;border:1px solid #e8eef8;border-radius:20px;padding:13px}.detail-meta-card span{display:block;font-size:11px;color:#98A2B3;text-transform:uppercase;letter-spacing:.06em;font-weight:950;margin-bottom:5px}.detail-meta-card strong{display:block;color:#101828;font-size:13px;line-height:1.25;font-weight:950}
    .detail-event-box.premium{background:#f8fbff}.event-cta-row.premium{position:sticky;bottom:10px;z-index:5;background:rgba(255,255,255,.82);backdrop-filter:blur(14px);padding:10px;border:1px solid #edf2f7;border-radius:24px;box-shadow:0 16px 34px rgba(16,24,40,.12)}
    .detail-gallery-title{font-size:14px;font-weight:950;color:#101828;margin:16px 0 8px}.detail-gallery-grid.premium{padding:0;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.detail-gallery-img.premium{border:0;height:145px;border-radius:20px;box-shadow:inset 0 -30px 50px rgba(0,0,0,.1);cursor:pointer}
    .detail-video-box iframe{width:100%;height:260px;border:0;border-radius:22px;background:#000;box-shadow:0 14px 34px rgba(16,24,40,.14)}.detail-whatsapp{display:flex;justify-content:center;align-items:center;margin-top:14px;padding:13px 16px;border-radius:999px;background:#e9fbf0;color:#067647;text-decoration:none;font-weight:950;border:1px solid #bbf7d0}
    @media(min-width:900px){.detail-meta-grid{grid-template-columns:repeat(4,minmax(0,1fr))}.detail-gallery-grid.premium{grid-template-columns:repeat(4,minmax(0,1fr))}.detail-gallery-img.premium{height:150px}.detail-video-box iframe{height:360px}}

    /* ── Desktop À la une grid ── */
    .spotlight-desktop-grid{display:none}
    @media(min-width:780px){
      .spotlight-row{display:none!important}
      .spotlight-desktop-grid{display:grid;grid-template-columns:1.6fr 1fr;gap:10px;margin-bottom:8px;align-items:start}
      .sdg-hero{height:520px;border-radius:22px;overflow:hidden;position:relative;cursor:pointer;background:#111;flex-shrink:0}
      .sdg-right{height:520px;display:flex;flex-direction:column}
      .sdg-pages{flex:1;display:flex;flex-direction:column;gap:10px;min-height:0}
      .sdg-page{display:none;flex:1;flex-direction:column;gap:10px;min-height:0}
      .sdg-page.active{display:flex}
      .sdg-card{flex:1;min-height:0;border-radius:22px;overflow:hidden;position:relative;cursor:pointer;background:#111}
      .sdg-empty{flex:1;border-radius:22px;background:#f4f8ff}
      .sdg-img{position:absolute;inset:0;background-size:cover;background-position:center;transition:transform .55s ease}
      .sdg-hero:hover .sdg-img,.sdg-card:hover .sdg-img{transform:scale(1.05)}
      .sdg-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.02) 0%,rgba(0,0,0,.1) 35%,rgba(0,0,0,.75) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:20px}
      .sdg-rank{position:absolute;top:14px;left:16px;background:rgba(255,255,255,.95);color:#111;border-radius:999px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;font-weight:950;font-size:13px;box-shadow:0 4px 12px rgba(0,0,0,.22)}
      .sdg-hero .sdg-rank{width:46px;height:46px;font-size:20px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;box-shadow:0 8px 20px rgba(11,108,255,.35)}
      .sdg-tag{display:inline-block;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.22);color:#fff;border-radius:999px;padding:5px 11px;font-size:11px;font-weight:900;margin-bottom:9px;width:fit-content}
      .sdg-title{color:#fff;margin:0 0 8px;font-weight:950;line-height:1.1;text-shadow:0 2px 14px rgba(0,0,0,.45)}
      .sdg-hero .sdg-title{font-size:28px;letter-spacing:-.5px}
      .sdg-card .sdg-title{font-size:15px}
      .sdg-meta{color:rgba(255,255,255,.82);font-size:12px;font-weight:750;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
      .sdg-price{background:linear-gradient(90deg,var(--blue),var(--cyan));color:#fff;border-radius:999px;padding:4px 10px;font-size:11px;font-weight:950}
      .sdg-dots{flex-shrink:0;display:flex;justify-content:center;align-items:center;gap:7px;padding:10px 0 2px}
      .sdg-dot{border:0;padding:0;width:7px;height:7px;border-radius:999px;background:#d0d5dd;cursor:pointer;transition:width .22s ease,background .22s ease}
      .sdg-dot.active{background:var(--blue);width:20px}
    }
    @media(min-width:1180px){
      .sdg-hero{height:600px}
      .sdg-right{height:600px}
      .sdg-hero .sdg-title{font-size:34px}
      .sdg-overlay{padding:26px}
    }

  </style>
</head>
<body>
  <div class="app-shell">
    <main class="app-main">
      <section class="topbar">
        <div class="brand"><span class="brand-studi">Studi</span><span class="brand-move">move</span></div>
        <button class="hamburger-btn" id="openMenuBtn" type="button" aria-label="Ouvrir le menu"><span></span><span></span><span></span></button>
      </section>
      <section class="intro"><h1 class="hello" id="helloTitle">Hello !</h1><p class="subtitle">Prêt pour de nouvelles aventures ?</p></section>

      <section>
        <div class="spotlight-intro">
          <h2 class="spotlight-kicker"><span class="blue">À la une</span>, les incontournables du moment.</h2>
          <p class="spotlight-text">Découvre les expériences les plus plébiscitées par les étudiants.</p>
        </div>
        <div class="spotlight-row" id="spotlightRow"></div>
        <div class="spotlight-desktop-grid" id="spotlightDesktopGrid"></div>
      </section>

      <section><div class="section-title-row"><h2 class="section-title">Explorer</h2><button class="section-link" type="button" data-toast="Toutes les catégories arrivent bientôt">Voir tout</button></div><div class="h-scroll" id="categorySlider"></div></section>
      <section>
        <div class="section-title-row"><h2 class="section-title">Feed</h2><button class="section-link" type="button" data-toast="Le feed sera bientôt connecté">Actualiser</button></div>
        <div class="feed-tabs-wrap"><div class="feed-tabs"><button class="feed-tab active" type="button" data-feed="forYou">Pour toi</button><button class="feed-tab" type="button" data-feed="follow">Follow</button><button class="feed-tab" type="button" data-feed="campus">Ton Campus</button></div></div>
        <div class="feed-list" id="feedList"><div class="loading-card">Chargement de ton app...</div></div>
      </section>
    </main>
    <nav class="bottom-nav" aria-label="Navigation principale">
      <button class="bottom-item active" type="button" data-nav="home" aria-label="Accueil"><span class="ico">⌂</span></button>
      <button class="bottom-item" type="button" data-nav="search" aria-label="Recherche"><span class="ico">⌕</span></button>
      <button class="bottom-item" type="button" data-nav="favorites" aria-label="Favoris"><span class="ico">♡</span></button>
      <button class="bottom-item" type="button" data-nav="profile" aria-label="Profil"><span class="ico">◉</span></button>
    </nav>
  </div>
  <div class="drawer-backdrop" id="drawerBackdrop"></div>
  <aside class="drawer" id="drawer" aria-hidden="true">
    <div class="drawer-head"><div class="drawer-profile"><span class="avatar-btn" style="box-shadow:none"><span class="avatar-inner" id="drawerAvatarInitial">S</span></span><div><strong id="drawerName">Studimove</strong><span id="drawerUsername">@studimove</span></div></div><button class="close-btn" id="closeMenuBtn" type="button">×</button></div>
    <div class="drawer-menu"><button class="drawer-link active" type="button" data-menu="home"><span class="menu-ico">⌂</span>Accueil</button><button class="drawer-link" type="button" data-menu="community"><span class="menu-ico">👥</span>Communauté</button><button class="drawer-link" type="button" data-menu="profile"><span class="menu-ico">◉</span>Mon profil</button><button class="drawer-link" type="button" data-menu="favorites"><span class="menu-ico">♥</span>Mes favoris</button><button class="drawer-link" type="button" data-menu="reservations"><span class="menu-ico">▣</span>Mes réservations</button><button class="drawer-link" type="button" data-menu="settings"><span class="menu-ico">⚙</span>Paramètres</button><button class="drawer-link" type="button" data-menu="support"><span class="menu-ico">?</span>Aide & support</button></div>
    <div class="drawer-footer"><button class="drawer-link logout-link" id="logoutBtn" type="button"><span class="menu-ico">↩</span>Se déconnecter</button></div>
  </aside>
  <section class="detail-overlay" id="detailOverlay" aria-hidden="true"></section>
  <div class="toast" id="toast"></div>
  <script src="app.js?v=brand-v7"></script>
</body>
</html>

<style>
/* Patch V12 : suppression de l'ombre sur les images À la une */
.spotlight-media:after{
  display:none !important;
  background:none !important;
}


    /* V44 - Ouverture événement/post + favoris premium */
    .favorite-btn,.favorite-detail{overflow:visible}
    .favorite-btn .heart-svg,.favorite-detail .heart-svg{width:22px;height:22px;display:block;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;transition:fill .15s ease,stroke .15s ease,transform .15s ease;filter:drop-shadow(0 1px 3px rgba(0,0,0,.25))}
    .favorite-detail{width:44px;height:44px;border:0;border-radius:999px;background:rgba(0,0,0,.22);backdrop-filter:blur(10px);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .18s ease,box-shadow .18s ease}
    .favorite-btn.active,.favorite-detail.active{color:#ed4956}
    .favorite-btn.active .heart-svg,.favorite-detail.active .heart-svg{fill:#ed4956;stroke:#ed4956;transform:scale(1.1)}
    .favorite-btn.heart-pop .heart-svg,.favorite-detail.heart-pop .heart-svg{animation:heartPop .45s cubic-bezier(.17,.89,.32,1.49)}
    @keyframes heartPop{0%{transform:scale(.6)}40%{transform:scale(1.28)}70%{transform:scale(.93)}100%{transform:scale(1.1)}}
    .premium-detail{background:linear-gradient(180deg,#fff 0%,#f7faff 100%);padding-bottom:32px}
    .detail-top.premium{border-bottom:0;background:rgba(255,255,255,.88);box-shadow:0 8px 24px rgba(16,24,40,.06)}
    .detail-premium-header{padding:18px 18px 10px;text-align:center;max-width:860px;margin:0 auto}
    .detail-main-title.premium{font-size:clamp(26px,5vw,44px);line-height:1.02;margin:0;color:#101828;letter-spacing:-1.2px;text-align:center}
    .detail-slogan{margin:9px auto 0;max-width:620px;color:#667085;font-size:15px;line-height:1.45;font-weight:750}
    .detail-hero-card{padding:0 16px;max-width:980px;margin:0 auto}
    .detail-hero.premium{height:clamp(260px,54vw,520px);border-radius:28px;background-size:cover;background-position:center;box-shadow:0 24px 60px rgba(16,24,40,.18);overflow:hidden;position:relative}
    .detail-hero.premium:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.14))}
    .detail-author-card{margin:-22px auto 0;position:relative;z-index:2;width:calc(100% - 46px);max-width:620px;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);border:1px solid rgba(234,240,248,.95);border-radius:24px;padding:12px 14px;display:flex;align-items:center;justify-content:center;gap:12px;box-shadow:0 18px 38px rgba(16,24,40,.12)}
    .detail-org-logo{width:48px;height:48px;flex:0 0 48px}.detail-author-copy{display:flex;flex-direction:column;align-items:flex-start}.detail-author-copy span{font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#98A2B3}.detail-author-copy strong{font-size:16px;font-weight:950;color:#101828}.detail-author-copy small{font-size:12px;font-weight:750;color:#667085;margin-top:2px}
    .detail-tabs.premium{position:sticky;top:58px;justify-content:center;background:rgba(247,250,255,.92);border-bottom:0;padding:16px 12px 8px;margin-top:8px}
    .detail-tabs.premium .detail-tab{background:#fff;box-shadow:0 8px 20px rgba(16,24,40,.06);border-color:#eef2f7}
    .detail-tabs.premium .detail-tab.active{box-shadow:0 12px 26px rgba(36,107,253,.22)}
    .detail-content.premium{max-width:860px;margin:0 auto;padding:12px 16px 0}.detail-section-card{background:#fff;border:1px solid #edf2f7;border-radius:28px;padding:18px;box-shadow:0 16px 36px rgba(16,24,40,.08)}.detail-section-card h3{margin:0 0 10px;font-size:18px;font-weight:950;color:#101828}
    .detail-meta-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin:16px 0}.detail-meta-card{background:#f8fbff;border:1px solid #e8eef8;border-radius:20px;padding:13px}.detail-meta-card span{display:block;font-size:11px;color:#98A2B3;text-transform:uppercase;letter-spacing:.06em;font-weight:950;margin-bottom:5px}.detail-meta-card strong{display:block;color:#101828;font-size:13px;line-height:1.25;font-weight:950}
    .detail-event-box.premium{background:#f8fbff}.event-cta-row.premium{position:sticky;bottom:10px;z-index:5;background:rgba(255,255,255,.82);backdrop-filter:blur(14px);padding:10px;border:1px solid #edf2f7;border-radius:24px;box-shadow:0 16px 34px rgba(16,24,40,.12)}
    .detail-gallery-title{font-size:14px;font-weight:950;color:#101828;margin:16px 0 8px}.detail-gallery-grid.premium{padding:0;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.detail-gallery-img.premium{border:0;height:145px;border-radius:20px;box-shadow:inset 0 -30px 50px rgba(0,0,0,.1);cursor:pointer}
    .detail-video-box iframe{width:100%;height:260px;border:0;border-radius:22px;background:#000;box-shadow:0 14px 34px rgba(16,24,40,.14)}.detail-whatsapp{display:flex;justify-content:center;align-items:center;margin-top:14px;padding:13px 16px;border-radius:999px;background:#e9fbf0;color:#067647;text-decoration:none;font-weight:950;border:1px solid #bbf7d0}
    @media(min-width:900px){.detail-meta-grid{grid-template-columns:repeat(4,minmax(0,1fr))}.detail-gallery-grid.premium{grid-template-columns:repeat(4,minmax(0,1fr))}.detail-gallery-img.premium{height:150px}.detail-video-box iframe{height:360px}}

  </style>
