
(function injectReservationsStyle(){
  if(document.getElementById('reservations-style')) return;
  const s=document.createElement('style');
  s.id='reservations-style';
  s.textContent=`
    .reservation-hero-card{display:flex;align-items:center;gap:18px;background:linear-gradient(135deg,#fff,#F6F7FF);border:1px solid rgba(15,23,42,.08);border-radius:28px;padding:22px;margin-bottom:18px;box-shadow:0 18px 44px rgba(15,23,42,.08)}
    .reservation-hero-icon{width:54px;height:54px;border-radius:20px;background:#EEE8FF;color:#7C3AED;display:grid;place-items:center;font-size:24px;font-weight:900}.reservation-hero-card h3{margin:0 0 6px;color:#101828;font-size:22px}.reservation-hero-card p{margin:0;color:#667085}.reservation-hero-illu{margin-left:auto;font-size:42px}
    .reservation-list{display:flex;flex-direction:column;gap:16px;margin-top:14px}.reservation-card-v3{background:rgba(255,255,255,.92);border:1px solid rgba(15,23,42,.08);border-radius:28px;box-shadow:0 18px 46px rgba(15,23,42,.10);overflow:hidden}.reservation-card-v3.open{border-color:rgba(124,58,237,.22);box-shadow:0 22px 60px rgba(15,23,42,.14)}
    .reservation-summary{width:100%;border:0;background:transparent;display:grid;grid-template-columns:72px 1fr 34px;gap:16px;align-items:center;text-align:left;padding:18px;cursor:pointer}.reservation-cover{width:72px;height:72px;border-radius:22px;background:linear-gradient(135deg,#1EA7E1,#7C3AED);color:#fff;display:grid;place-items:center;font-size:28px;font-weight:1000}.reservation-summary-top{display:flex;align-items:center;gap:12px;justify-content:space-between}.reservation-summary h3{margin:0;color:#101828;font-size:21px}.reservation-summary p{margin:5px 0 0;color:#667085;font-weight:700}.reservation-brand{background:#101828;color:#fff;border-radius:999px;padding:7px 11px;font-weight:900;font-size:12px;white-space:nowrap}.reservation-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}.reservation-chips span{background:#F2F4F7;color:#344054;border-radius:999px;padding:7px 10px;font-size:12px;font-weight:900}.reservation-chips .warn{background:#FFF4E5;color:#B54708}.reservation-chips .ok{background:#EAFBF1;color:#079455}.reservation-chevron{color:#344054;font-size:22px;font-weight:1000;text-align:center}
    .reservation-detail-v3{padding:0 18px 18px}.reservation-toolbar{border-top:1px solid rgba(15,23,42,.08);padding:18px 0;display:flex;align-items:center;justify-content:space-between;gap:12px}.reservation-toolbar strong{display:block;color:#101828;font-size:16px}.reservation-toolbar span{display:block;color:#667085;font-size:13px;margin-top:3px}.reservation-mail-btn{border:1px solid rgba(124,58,237,.20);background:#fff;color:#7C3AED;border-radius:999px;padding:10px 14px;font-weight:1000;cursor:pointer}
    .reservation-group{margin-top:14px}.reservation-group h4{margin:0;color:#101828;font-size:19px}.reservation-group h4 span{display:inline-grid;place-items:center;min-width:26px;height:26px;border-radius:999px;background:#EEE8FF;color:#7C3AED;font-size:13px;margin-left:6px}.reservation-group p{margin:4px 0 10px;color:#667085}.reservation-lines{border:1px solid rgba(15,23,42,.08);border-radius:22px;overflow:hidden;background:#fff}
    .reservation-line{display:grid;grid-template-columns:52px 1fr auto auto;gap:14px;align-items:center;padding:14px;border-bottom:1px solid rgba(15,23,42,.06)}.reservation-line:last-child{border-bottom:0}.reservation-line.cancelled{opacity:.55;background:#F9FAFB}.reservation-line-avatar{width:48px;height:48px;border-radius:18px;background:linear-gradient(135deg,#12B76A,#1EA7E1);color:#fff;display:grid;place-items:center;font-weight:1000}.reservation-line-main strong{display:block;color:#101828}.reservation-line-main span{display:block;color:#667085;font-size:13px;margin-top:3px}.reservation-line-status{text-align:right;min-width:95px}.reservation-line-status span{display:block;color:#667085;font-size:12px}.reservation-line-status em{display:block;color:#12B76A;font-style:normal;font-weight:1000;font-size:13px;margin-top:2px}.reservation-line.cancelled .reservation-line-status em{color:#B42318}.reservation-line-actions{display:flex;flex-direction:column;gap:8px;min-width:168px}.reservation-action{border:0;border-radius:999px;padding:10px 13px;font-weight:1000;background:#101828;color:#fff;cursor:pointer}.reservation-action.secondary{background:#F2F4F7;color:#344054}.reservation-action:disabled{cursor:not-allowed;opacity:.45}
    .reservation-help{margin-top:18px;border:1px dashed rgba(124,58,237,.25);border-radius:20px;padding:14px;display:flex;align-items:center;justify-content:space-between;gap:12px;background:#FBFAFF}.reservation-help strong{color:#101828}.reservation-help p{margin:4px 0 0;color:#667085}.reservation-help button{border:1px solid rgba(124,58,237,.22);background:#fff;color:#7C3AED;border-radius:999px;padding:10px 14px;font-weight:1000}.reservation-empty{background:#fff;border-radius:24px;padding:22px;border:1px solid rgba(15,23,42,.08)}
    @media(max-width:720px){.reservation-summary{grid-template-columns:54px 1fr 24px;padding:14px}.reservation-cover{width:54px;height:54px;border-radius:18px}.reservation-summary h3{font-size:17px}.reservation-line{grid-template-columns:44px 1fr}.reservation-line-status{text-align:left;grid-column:2}.reservation-line-actions{grid-column:1 / -1;display:grid;grid-template-columns:1fr 1fr;min-width:0}.reservation-toolbar,.reservation-help{align-items:flex-start;flex-direction:column}.reservation-hero-illu{display:none}}
  `
  document.head.appendChild(s);
})();

const state = { user: null, activeFeed: 'forYou', activeCategory: null, favoriteIds: new Set(), eventActions: {}, reservations: [], reservationsLoaded: false, openReservationId: '', cards: [], cardsLoaded: false };

window.dataLayer = window.dataLayer || [];
function track(eventName, params = {}) {
  const payload = { event: eventName, ...params };
  window.dataLayer.push(payload);
  if (typeof window.gtag === 'function') window.gtag('event', eventName, params);
}

const img = {
  voyages:'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80',
  soirees:'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=900&q=80',
  sorties:'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
  bons:'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=900&q=80',
  jeux:'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=900&q=80',
  beach:'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
  campus:'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1200&q=80',
  campus2:'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80',
  campus3:'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1200&q=80',
  campus4:'https://images.unsplash.com/photo-1498243691581-b145c3f54a5a?auto=format&fit=crop&w=1200&q=80',
  night:'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80',
  drink:'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=1200&q=80',
  party:'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80',
  calendar:'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=1200&q=80',
  team:'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
  mykonos:'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=1200&q=80',
  ski:'https://images.unsplash.com/photo-1551524559-8af4e6624178?auto=format&fit=crop&w=1200&q=80',
  rome:'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=1200&q=80',
  london:'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=1200&q=80',
  prague:'https://images.unsplash.com/photo-1541849546-216549ae216d?auto=format&fit=crop&w=1200&q=80',
  barcelona:'https://images.unsplash.com/photo-1539037116277-4db20889f2d4?auto=format&fit=crop&w=1200&q=80',
  amsterdam:'https://images.unsplash.com/photo-1534351590666-13e3e96b5017?auto=format&fit=crop&w=1200&q=80',
  lisbon:'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
  berlin:'https://images.unsplash.com/photo-1559598467-f8b76c8155d0?auto=format&fit=crop&w=1200&q=80',
  festival:'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&w=1200&q=80',
  sport:'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=1200&q=80',
  food:'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80',
  cinema:'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1200&q=80',
  hiking:'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1200&q=80',
  rooftop:'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=1200&q=80',
  escape:'https://images.unsplash.com/photo-1560472355-536de3962603?auto=format&fit=crop&w=1200&q=80',
};
const people = [
  {name:'Lina Martin', school:'Kedge · Marseille', avatar:'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=160&q=80'},
  {name:'Yanis Benali', school:'Digital College · Paris', avatar:'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=160&q=80'},
  {name:'Emma Leroy', school:'ESG · Bordeaux', avatar:'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=160&q=80'},
  {name:'Nolan Petit', school:'Campus Paris', avatar:'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=160&q=80'},
  {name:'Sofia Moreau', school:'Kedge · Marseille', avatar:'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=160&q=80'},
];
const comments = [
  {name:'Lina Martin', text:"Trop chaud, ça a l'air incroyable !", time:'il y a 8 min', avatar:people[0].avatar},
  {name:'Yanis Benali', text:"On peut venir avec des potes d'une autre école ?", time:'il y a 22 min', avatar:people[1].avatar},
  {name:'Emma Leroy', text:"Je veux plus d'infos sur le transport.", time:'il y a 1 h', avatar:people[2].avatar},
];

const profileImgs = people.slice(0,4).map(p=>p.avatar);
const categories = [
  { title:'Voyages', image:img.voyages }, { title:'Soirées', image:img.soirees }, { title:'Sorties', image:img.sorties },
  { title:'Bon plan', image:img.bons }, { title:'Jeux concours', image:img.jeux },
];

// Spotlight et feeds : alimentés dynamiquement depuis les cards CRM.
// Les tableaux hardcodés ci-dessous servent de fallback si l'API est indisponible.
let spotlightItems = [
  { id:'barcelona-student-break', rank:1, title:'Barcelona Student Break', tag:'Voyage', date:'Mars 2026', price:'Dès 299€', image:img.barcelona },
  { id:'nuit-etudiante-paris', rank:2, title:'La Grande Nuit Etudiante Paris', tag:'Soirée', date:'Samedi 21 fév', price:'Entrée 12€', image:img.party },
  { id:'ski-les-2-alpes', rank:3, title:'Ski Trip Les 2 Alpes', tag:'Sport', date:'Fevrier 2026', price:'Dès 389€', image:img.ski },
  { id:'amsterdam-weekend', rank:4, title:'Amsterdam Student Weekend', tag:'Voyage', date:'Avril 2026', price:'Dès 249€', image:img.amsterdam },
  { id:'festival-printemps', rank:5, title:'Festival du Printemps Etudiant', tag:'Festival', date:'Mai 2026', price:'Dès 35€', image:img.festival },
];

const DEMO_EVENTS = [
  {
    id:'barcelona-student-break', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Barcelone · Mars 2026',
    title:'Barcelona Student Break', badge:'Voyage',
    text:"4 jours à Barcelone avec 200 étudiants. Vol + hôtel + soirées inclus. L'expérience la plus dingue de l'année.",
    image:img.barcelona, hero_image:img.barcelona,
    date:'Mars 2026', place:'Barcelone, Espagne', price:'Dès 299€', category:'Voyage',
    interested:312, going:87, likes:248, comments:34,
    slogan:"Le soleil, la Sagrada Familia et 200 étudiants en folie.",
    details:"4 jours inoubliables à Barcelone : vol A/R, hôtel en centre-ville, soirée welcome party, visite guidée de la Sagrada Familia, beach day, et soirée de clôture sur le toit d'un hôtel 4 étoiles. Places limitées à 200 étudiants.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'nuit-etudiante-paris', type:'event', entity:'BDE Alliance', initials:'BA',
    meta:'Soirée · Paris · 21 fév',
    title:'La Grande Nuit Etudiante Paris', badge:'Soirée',
    text:'La plus grande soirée étudiante de Paris réunit 8 écoles pour une nuit inoubliable au Wanderlust.',
    image:img.night, hero_image:img.night,
    date:'Samedi 21 fév 22h', place:'Wanderlust, Paris 12e', price:'Entrée 12€', category:'Soirée',
    interested:890, going:412, likes:631, comments:89,
    slogan:'8 écoles, 1 dancefloor, 1 nuit.',
    details:"Le Wanderlust ouvre ses portes aux étudiants parisiens pour la soirée de l'année. 3 salles, 5 DJs, open bar premium les 2 premières heures. Dress code : tenue de soirée.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'ski-les-2-alpes', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Sport · Les 2 Alpes · Fév 2026',
    title:'Ski Trip Les 2 Alpes', badge:'Sport',
    text:'Une semaine au ski avec des centaines d\'étudiants. Forfait, logement, soirées après-ski inclus.',
    image:img.ski, hero_image:img.ski,
    date:'7-14 Fév 2026', place:'Les 2 Alpes, Isère', price:'Dès 389€', category:'Sport',
    interested:540, going:198, likes:412, comments:56,
    slogan:'La montagne comme tu ne l\'as jamais vécue.',
    details:"7 nuits en chalet partagé, forfait ski 6 jours, navette aller-retour depuis Paris, Lyon et Bordeaux incluse. Soirées après-ski organisées chaque soir. Niveau débutant à expert accepté.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'amsterdam-weekend', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Amsterdam · Avr 2026',
    title:'Amsterdam Student Weekend', badge:'Voyage',
    text:'3 jours à Amsterdam avec une centaine d\'étudiants. Vol + auberge + city tour + soirée inclus.',
    image:img.amsterdam, hero_image:img.amsterdam,
    date:'Avr 2026', place:'Amsterdam, Pays-Bas', price:'Dès 249€', category:'Voyage',
    interested:278, going:94, likes:203, comments:27,
    slogan:'Vélos, canaux et bonne humeur garantis.',
    details:"3 jours dans la capitale néerlandaise : vol A/R, hébergement en auberge premium, city tour en vélo, visite du Rijksmuseum, soirée dans un bar local. Groupe de 80-100 étudiants.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'festival-printemps', type:'event', entity:'Campus Events', initials:'CE',
    meta:'Festival · Lyon · Mai 2026',
    title:'Festival du Printemps Etudiant', badge:'Festival',
    text:'2 jours de musique live, food trucks et animations sur le campus de la Doua à Lyon.',
    image:img.festival, hero_image:img.festival,
    date:'15-16 Mai 2026', place:'Campus La Doua, Lyon', price:'Dès 35€', category:'Festival',
    interested:1240, going:567, likes:892, comments:143,
    slogan:'Le festival que les étudiants ont créé pour les étudiants.',
    details:"2 jours de festival en plein air : 12 artistes, 4 scènes, 30 food trucks, ateliers créatifs, village associatif. Camping possible sur site. Pass 2 jours disponible.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'rome-weekend', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Rome · Mars 2026',
    title:'Rome en 3 Jours', badge:'Voyage',
    text:'Vol + hôtel + visite du Colisée + soirée aperitivo. Rome n\'attend que toi.',
    image:img.rome, hero_image:img.rome,
    date:'Mars 2026', place:'Rome, Italie', price:'Dès 279€', category:'Voyage',
    interested:198, going:62, likes:174, comments:19,
    slogan:'La dolce vita version étudiante.',
    details:"3 jours à Rome tout compris : vol A/R, hôtel 3 étoiles en centre-ville, visite guidée du Colisée et du Vatican, soirée aperitivo typique. Groupe de 60 étudiants max.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'soiree-karaoké', type:'event', entity:'BDE Kedge', initials:'BK',
    meta:'Soirée · Marseille · 14 fév',
    title:'Soirée Karaoké Géante', badge:'Soirée',
    text:'Valentine\'s Day karaoké pour célibataires (et les autres). 3 salles, micro ouvert toute la nuit.',
    image:img.drink, hero_image:img.drink,
    date:'14 Fév 2026 20h', place:'Le Réservoir, Marseille', price:'Entrée 8€', category:'Soirée',
    interested:234, going:98, likes:187, comments:41,
    slogan:'Chante tes peines ou ta joie, peu importe.',
    details:"3 salles thématiques (pop, rap, variété française), micro ouvert, cocktails à prix étudiant, élection du meilleur karaoké de la nuit avec un bon cadeau à gagner.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'lisbon-trip', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Lisbonne · Avr 2026',
    title:'Lisbonne Spring Break', badge:'Voyage',
    text:'5 jours à Lisbonne : fado, pasteis de nata et soirées sur les toits. Le trip de printemps parfait.',
    image:img.lisbon, hero_image:img.lisbon,
    date:'Avr 2026', place:'Lisbonne, Portugal', price:'Dès 319€', category:'Voyage',
    interested:445, going:131, likes:367, comments:48,
    slogan:'Lisbonne te donnera envie de rester.',
    details:"5 jours dans la ville la plus tendance d\'Europe : vol A/R, hôtel boutique en centre, visite de Sintra, soirée fado, rooftop party le dernier soir. 100 étudiants max.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'tournoi-sport-etudiant', type:'event', entity:'FFSU Bordeaux', initials:'FF',
    meta:'Sport · Bordeaux · 7 mars',
    title:'Tournoi Inter-Ecoles Bordeaux', badge:'Sport',
    text:'Foot, basket, volley : 24 équipes, 6 écoles, 1 trophée. Inscris ton équipe avant le 28 fév.',
    image:img.sport, hero_image:img.sport,
    date:'7 Mars 2026', place:'Stade Universitaire, Bordeaux', price:'Gratuit', category:'Sport',
    interested:312, going:144, likes:228, comments:33,
    slogan:'Montrez ce que votre école vaut vraiment.',
    details:"Tournoi inter-écoles sur une journée : foot 5v5, basket 3x3, volley mixte. 8 équipes par sport, matchs toutes les 30 min. Remise des trophées à 18h, after party offerte.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'brunch-etudiant-lyon', type:'event', entity:'Cercle Lyon', initials:'CL',
    meta:'Bon plan · Lyon · Dimanches',
    title:'Brunch Etudiant -50%', badge:'Bon plan',
    text:'Tous les dimanches, brunch illimité à 12€ au lieu de 24€ sur présentation de ta carte étudiant.',
    image:img.food, hero_image:img.food,
    date:'Tous les dimanches 11h-15h', place:'Le Cercle, Lyon 2e', price:'12€ (-50%)', category:'Bon plan',
    interested:678, going:289, likes:512, comments:67,
    slogan:'Le meilleur dimanche commence par un bon brunch.',
    details:"Brunch illimité : viennoiseries, oeufs cocotte, pancakes, jus frais, café illimité, planche charcuterie. Réservation conseillée. Sur présentation de la carte étudiante uniquement.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'cinema-plein-air', type:'event', entity:'Campus Events', initials:'CE',
    meta:'Sortie · Paris · Juil 2026',
    title:'Cinema en Plein Air - Parc de la Villette', badge:'Sortie',
    text:'Soirées cinéma gratuites chaque mardi de juillet au Parc de la Villette. Amène ta couverture.',
    image:img.cinema, hero_image:img.cinema,
    date:'Chaque mardi - Juil 2026', place:'Parc de la Villette, Paris 19e', price:'Gratuit', category:'Sortie',
    interested:892, going:341, likes:634, comments:88,
    slogan:'Les plus belles projections se font sous les étoiles.',
    details:"Films projetés à la tombée de la nuit (vers 22h). Programme disponible sur le site de la Villette. Food trucks sur place. Pensez à apporter couverture et coussin. Entrée libre dans la limite des places disponibles.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'berlin-culture-trip', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Berlin · Mai 2026',
    title:'Berlin Culture & Nuits', badge:'Voyage',
    text:'4 jours à Berlin entre musées, street art et clubs légendaires. La ville qui ne dort jamais.',
    image:img.berlin, hero_image:img.berlin,
    date:'Mai 2026', place:'Berlin, Allemagne', price:'Dès 269€', category:'Voyage',
    interested:367, going:112, likes:294, comments:39,
    slogan:'Berlin ne ressemble à aucune autre ville.',
    details:"4 jours à Berlin : vol A/R, auberge premium, visite du mémorial du mur, East Side Gallery, Museumsinsel. Soirée au Berghain ou Tresor pour ceux qui le souhaitent. Groupe de 60 étudiants.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'randonnee-vercors', type:'event', entity:'Club Montagne ESG', initials:'CM',
    meta:'Sport · Vercors · 22 mars',
    title:'Randonnee dans le Vercors', badge:'Sport',
    text:'Une journée de randonnée en pleine nature dans le Vercors. Niveau débutant, pique-nique inclus.',
    image:img.hiking, hero_image:img.hiking,
    date:'22 Mars 2026', place:'Vercors, Isère', price:'25€', category:'Sport',
    interested:156, going:48, likes:123, comments:17,
    slogan:'La nature, le vrai luxe etudiant.',
    details:"Randonnée de 12 km (niveau facile) avec guide certifié. Départ en covoiturage organisé depuis Grenoble à 7h30. Pique-nique tiré du sac, retour vers 18h. Prévoir bonnes chaussures.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'soiree-rooftop-bordeaux', type:'event', entity:'BDE Kedge Bordeaux', initials:'BK',
    meta:'Soirée · Bordeaux · 28 mars',
    title:'Rooftop Party Bordeaux', badge:'Soirée',
    text:'Soirée sur le toit du Mama Shelter Bordeaux avec vue sur la Garonne. Places très limitées.',
    image:img.rooftop, hero_image:img.rooftop,
    date:'28 Mars 2026 20h', place:'Mama Shelter, Bordeaux', price:'Entrée 18€', category:'Soirée',
    interested:567, going:134, likes:432, comments:72,
    slogan:'La soirée dont tout le monde parlera.',
    details:"200 places max sur le rooftop du Mama Shelter avec vue panoramique sur Bordeaux. DJ set, cocktails signature, animation photo booth. Dress code élégant exigé. Open bar 20h-22h inclus.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'escape-game-equipes', type:'event', entity:'Asso Ludique', initials:'AL',
    meta:'Sortie · Paris · 5 mars',
    title:'Escape Game Inter-Ecoles', badge:'Sortie',
    text:'10 équipes, 10 salles, 60 minutes chrono. L\'ecole la plus rapide remporte un weekend offert.',
    image:img.escape, hero_image:img.escape,
    date:'5 Mars 2026 18h', place:'Clue Academy, Paris 3e', price:'15€/joueur', category:'Sortie',
    interested:234, going:89, likes:178, comments:23,
    slogan:'Ton QI contre la montre.',
    details:"10 équipes de 4 joueurs (1 équipe par école) s\'affrontent dans 10 salles d\'escape game simultanément. Le temps est comptabilisé, l\'équipe gagnante reçoit un weekend pour 4 personnes. Inscription en équipe uniquement.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'prague-weekend', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Prague · Avr 2026',
    title:'Prague Long Weekend', badge:'Voyage',
    text:'3 nuits à Prague sur le pont du 1er mai. La plus belle ville d\'Europe de l\'Est t\'attend.',
    image:img.prague, hero_image:img.prague,
    date:'30 Avr - 3 Mai 2026', place:'Prague, Republique Tcheque', price:'Dès 229€', category:'Voyage',
    interested:289, going:78, likes:234, comments:31,
    slogan:'Prague, la ville qui t\'ensorcelle.',
    details:"3 nuits à Prague : vol A/R, hôtel en vieille ville, walking tour gratuit, croisière sur la Vltava, soirée dans un bar cave traditionnel. 80 étudiants max.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'hackathon-etudiant', type:'event', entity:'Student Tech Hub', initials:'ST',
    meta:'Sortie · Paris · 14-15 mars',
    title:'Hackathon Etudiant 48h', badge:'Sortie',
    text:'48h pour créer une app, une startup, un projet qui change le monde. Mentors, pizza et Red Bull inclus.',
    image:img.campus2, hero_image:img.campus2,
    date:'14-15 Mars 2026', place:'Station F, Paris 13e', price:'Gratuit', category:'Sortie',
    interested:678, going:234, likes:489, comments:94,
    slogan:'48h pour changer le monde (ou essayer).',
    details:"Hackathon ouvert à tous les étudiants, seul ou en équipe jusqu\'à 4. Thème révélé au lancement. Accès 24h/24, meals et snacks fournis, mentors disponibles en continu. Prix : incubation à Station F pour le projet gagnant.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'mykonos-summer', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Mykonos · Juil 2026',
    title:'Mykonos Summer Break', badge:'Voyage',
    text:'Une semaine à Mykonos. Vol, villa partagée, beach clubs, soirées. Le summer de ta vie.',
    image:img.mykonos, hero_image:img.mykonos,
    date:'Juil 2026', place:'Mykonos, Grece', price:'Dès 699€', category:'Voyage',
    interested:1120, going:287, likes:934, comments:167,
    slogan:'L\'ete etudiant ultime.',
    details:"7 nuits à Mykonos dans une villa partagée avec piscine, vol A/R, accès VIP aux meilleurs beach clubs (Paradise, Super Paradise), soirée bateau coucher de soleil. 40 étudiants selectionnés.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'concert-live-toulouse', type:'event', entity:'BDE INP Toulouse', initials:'BI',
    meta:'Festival · Toulouse · 12 avr',
    title:'Nuit des BDE - Concert Live', badge:'Festival',
    text:'6 BDE toulousains réunis pour une nuit de concerts live avec 4 groupes locaux et 2 DJs.',
    image:img.night, hero_image:img.night,
    date:'12 Avr 2026 20h', place:'Le Bikini, Toulouse', price:'Entrée 14€', category:'Festival',
    interested:445, going:189, likes:356, comments:52,
    slogan:'La nuit ou Toulouse vibre.',
    details:"6 BDE d\'ecoles toulousaines s\'unissent pour organiser la plus grande soirée musicale étudiante de l\'année. 4 groupes de rock/electro, 2 DJs, 2 bars, cloakroom gratuit. Billetterie en ligne uniquement.",
    ticketing_url:'#', whatsapp_url:'#',
  },
  {
    id:'london-citytrip', type:'event', entity:'StudiMove', initials:'SM',
    meta:'Voyage · Londres · Mai 2026',
    title:'London City Trip 3J', badge:'Voyage',
    text:'3 jours à Londres : British Museum, Shoreditch, pubs et comédie musicale sur le West End.',
    image:img.london, hero_image:img.london,
    date:'Mai 2026', place:'Londres, Royaume-Uni', price:'Dès 349€', category:'Voyage',
    interested:334, going:98, likes:267, comments:36,
    slogan:'London calling. Tu reponds ?',
    details:"3 jours à Londres : Eurostar A/R, hôtel 3 étoiles à Shoreditch, visite du British Museum et de Notting Hill, place de comedie musicale au choix (Mamma Mia ou Le Roi Lion), soiree dans un pub typique. 70 etudiants.",
    ticketing_url:'#', whatsapp_url:'#',
  },
];

let feeds = {
  forYou: DEMO_EVENTS,
  follow: DEMO_EVENTS.filter(e => ['soiree','festival','sortie'].includes(e.category.toLowerCase())),
  campus: DEMO_EVENTS.filter(e => ['sport','bon plan','sortie'].includes(e.category.toLowerCase())),
};

// ─── Cards CRM → feed items ───────────────────────────────────────────────────

function cardToFeedItem(card) {
  const marqueRaw = (card.marque || 'studimove').toLowerCase();
  const entity = marqueRaw.charAt(0).toUpperCase() + marqueRaw.slice(1);
  const initials = marqueRaw.slice(0, 2).toUpperCase();
  const metaParts = [card.tag, card.ville || card.cities, card.date_text].filter(Boolean);
  return {
    id:            card.id,
    _card:         true,
    type:          'event',
    entity:        entity,
    initials:      initials,
    meta:          metaParts.join(' · '),
    title:         card.title,
    text:          card.slogan || (card.desc_text || '').slice(0, 200),
    image:         card.image,
    likes:         0,
    comments:      0,
    badge:         card.tag || 'Événement',
    interested:    0,
    going:         0,
    date:          card.date_text,
    place:         card.ville || card.cities || '',
    price:         card.price,
    // détail enrichi
    slogan:        card.slogan,
    details:       card.desc_text,
    hero_image:    card.image,
    image1:        card.image1,
    image2:        card.image2,
    image3:        card.image3,
    image4:        card.image4,
    video_url:     card.video,
    whatsapp_url:  card.whatsapp,
    ticketing_url: card.billetterie || card.link,
    organizer_name: entity,
    organizer_logo: initials,
    category:      card.tag,
    duree:         card.duree,
    stock:         card.stock,
  };
}

function buildSpotlightFromCards(cards) {
  return cards
    .filter(c => c.featured)
    .map((card, i) => ({
      id:    card.id,
      rank:  i + 1,
      title: card.title,
      tag:   card.tag,
      date:  card.date_text,
      price: card.price,
      image: card.image,
    }));
}

async function fetchCards() {
  try {
    const res  = await fetch('api/cards.php', { credentials: 'same-origin' });
    const data = await res.json();
    if (!data.ok || !Array.isArray(data.cards)) return;

    state.cards      = data.cards;
    state.cardsLoaded = true;

    // Spotlight = cards featured uniquement
    const spotlight = buildSpotlightFromCards(data.cards);
    if (spotlight.length) {
      spotlightItems = spotlight;
      renderSpotlight();
    }

    // Feed "Pour toi" = toutes les cards
    const feedItems = data.cards.map(cardToFeedItem);
    feeds.forYou  = feedItems;
    feeds.follow  = feedItems;  // même contenu pour l'instant
    feeds.campus  = feedItems;
    renderFeed();

  } catch (e) {
    console.warn('fetchCards error:', e);
    // Pas de crash : le feed reste vide ou affiche un message
  }
}


function $(s){return document.querySelector(s)} function $all(s){return Array.from(document.querySelectorAll(s))}
function escapeHtml(v){return String(v??'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;")}
function initialsFromUser(u){const f=(u?.first_name||u?.username||'S').trim();const l=(u?.last_name||'').trim();return `${f[0]||'S'}${l[0]||''}`.toUpperCase()}
function displayName(u){return u?.first_name||u?.username||'toi'}
function allItems(){return Object.values(feeds).flat()}
function findItemById(id){
  // Cherche d'abord dans les feeds, puis dans state.cards converti
  const inFeed = allItems().find(x=>x.id===id);
  if (inFeed) return inFeed;
  const card = state.cards.find(c=>c.id===id);
  return card ? cardToFeedItem(card) : null;
}
function formatEventDate(start, end){
  if(!start) return '';
  try{
    const opts={day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'};
    const s=new Date(start);
    const e=end?new Date(end):null;
    if(Number.isNaN(s.getTime())) return '';
    const startLabel=s.toLocaleDateString('fr-FR',opts).replace('.', '');
    if(!e||Number.isNaN(e.getTime())) return startLabel;
    const sameDay=s.toDateString()===e.toDateString();
    const endLabel=sameDay?e.toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'}):e.toLocaleDateString('fr-FR',opts).replace('.', '');
    return `${startLabel} → ${endLabel}`;
  }catch(e){return ''}
}
function favoriteIcon(isActive=false){
  return `<span class="heart-svg-wrap" aria-hidden="true"><svg class="heart-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>`;
}
function safeUrl(v){ const s=String(v||'').trim(); return /^https?:\/\//i.test(s)?s:'#'; }
async function fetchCurrentUser(){const res=await fetch('api/auth.php?action=me',{credentials:'same-origin'});const data=await res.json();if(!data.ok||!data.authenticated){const qs=new URLSearchParams(window.location.search);const rt=qs.get('reservation_token')||qs.get('token')||'';window.location.href='login.php'+(rt?('?reservation_token='+encodeURIComponent(rt)):'');return null}return data.user}
function hydrateUser(user){const firstName=displayName(user);const initials=initialsFromUser(user);const username=user?.username?`@${user.username}`:'@studimove';$('#helloTitle').textContent=`Hello ${firstName} !`;$('#drawerAvatarInitial').textContent=initials;$('#drawerName').textContent=`${user?.first_name||''} ${user?.last_name||''}`.trim()||firstName;$('#drawerUsername').textContent=username}
const CATEGORY_MAP = { 'Voyages':'Voyage', 'Soirées':'Soirée', 'Sorties':'Sortie', 'Bon plan':'Bon plan', 'Jeux concours':'Jeux concours' };
function renderCategories(){
  $('#categorySlider').innerHTML=categories.map(cat=>`<button class="category-card${state.activeCategory===cat.title?' category-active':''}" type="button" data-category="${escapeHtml(cat.title)}" style="background-image:url('${cat.image}')"><span class="category-title">${escapeHtml(cat.title)}</span></button>`).join('');
}
function setActiveCategory(title){
  state.activeCategory = state.activeCategory===title ? null : title;
  renderCategories();
  renderFeed();
  track('category_filter',{category:title,active:!!state.activeCategory});
}
function renderSpotlightDesktop(){
  const el=$('#spotlightDesktopGrid');
  if(!el||!spotlightItems.length) return;

  const hero=spotlightItems[0];
  const rest=spotlightItems.slice(1);
  const perPage=2;

  // Découpe les cartes restantes en pages de 2
  const pages=[];
  for(let i=0;i<rest.length;i+=perPage) pages.push(rest.slice(i,i+perPage));

  function cardHtml(item){
    return `<article class="sdg-card" data-open-id="${escapeHtml(item.id)}">
      <div class="sdg-img" style="background-image:url('${item.image}')"></div>
      <div class="sdg-overlay">
        <span class="sdg-rank">${item.rank}</span>
        <span class="sdg-tag">${escapeHtml(item.tag||'À la une')}</span>
        <h3 class="sdg-title">${escapeHtml(item.title)}</h3>
        <div class="sdg-meta"><span>${escapeHtml(item.date||'')}</span>${item.price?`<span class="sdg-price">${escapeHtml(item.price)}</span>`:''}</div>
      </div>
    </article>`;
  }

  const heroHtml=`<article class="sdg-hero" data-open-id="${escapeHtml(hero.id)}">
    <div class="sdg-img" style="background-image:url('${hero.image}')"></div>
    <div class="sdg-overlay">
      <span class="sdg-rank">${hero.rank}</span>
      <span class="sdg-tag">${escapeHtml(hero.tag||'À la une')}</span>
      <h3 class="sdg-title">${escapeHtml(hero.title)}</h3>
      <div class="sdg-meta"><span>${escapeHtml(hero.date||'')}</span>${hero.price?`<span class="sdg-price">${escapeHtml(hero.price)}</span>`:''}</div>
    </div>
  </article>`;

  const pagesHtml=pages.map((page,pi)=>`
    <div class="sdg-page${pi===0?' active':''}" data-page="${pi}">
      ${page.map(cardHtml).join('')}
      ${page.length<perPage?'<div class="sdg-empty"></div>':''}
    </div>`).join('');

  const dotsHtml=pages.length>1
    ?`<div class="sdg-dots">${pages.map((_,i)=>`<button class="sdg-dot${i===0?' active':''}" data-goto="${i}" aria-label="Page ${i+1}"></button>`).join('')}</div>`
    :'';

  el.innerHTML=`${heroHtml}<div class="sdg-right"><div class="sdg-pages">${pagesHtml}</div>${dotsHtml}</div>`;

  // Navigation par les dots
  el.querySelectorAll('.sdg-dot').forEach(dot=>{
    dot.addEventListener('click',e=>{
      e.stopPropagation();
      const p=parseInt(dot.dataset.goto);
      el.querySelectorAll('.sdg-page').forEach((pg,i)=>pg.classList.toggle('active',i===p));
      el.querySelectorAll('.sdg-dot').forEach((d,i)=>d.classList.toggle('active',i===p));
    });
  });
}
function renderSpotlight(){
  const el=$('#spotlightRow');
  if(!el) return;
  el.innerHTML=spotlightItems.map(item=>`
    <article class="spotlight-card" data-open-id="${escapeHtml(item.id)}">
      <div class="spotlight-media">
        <img src="${item.image}" alt="${escapeHtml(item.title)}" loading="lazy" decoding="async">
        <span class="spotlight-rank" data-rank="${item.rank}">${item.rank}</span>
      </div>
      <div class="spotlight-body">
        <h3 class="spotlight-title">${escapeHtml(item.title)}</h3>
        <div class="spotlight-sub">
          <span class="spotlight-tag">${escapeHtml(item.tag || 'À la une')}</span>
          <span class="spotlight-dot"></span>
          <time>${escapeHtml(item.date || '')}</time>
        </div>
        <div class="spotlight-price">${escapeHtml(item.price || '')}</div>
      </div>
      <button class="spotlight-cta" type="button" data-open-id="${escapeHtml(item.id)}">Voir</button>
    </article>
  `).join('');
  renderSpotlightDesktop();
}

function renderEventSocialProof(item){if(item.type!=='event')return ''; const avatars=profileImgs.map(url=>`<span class="mini-avatar" style="background-image:url('${url}')"></span>`).join(''); return `<div class="event-social-proof"><div class="avatar-stack">${avatars}</div><div class="event-counts"><span>${item.interested||0} intéressés</span><span>${item.going||0} inscrits</span></div></div>`}
function renderFeed(){
  let items=feeds[state.activeFeed]||[];
  if(state.activeCategory){
    const target=CATEGORY_MAP[state.activeCategory]||state.activeCategory;
    items=items.filter(e=>e.category===target||e.badge===target);
  }
  const feedList=$('#feedList');
  if(!items.length){
    feedList.innerHTML=state.activeCategory
      ? `<div class="loading-card">Aucun événement dans la catégorie <strong>${escapeHtml(state.activeCategory)}</strong>.</div>`
      : `<div class="loading-card">Aucun contenu pour le moment.</div>`;
    return;
  }
  feedList.innerHTML=items.map(item=>{
    const isFav=state.favoriteIds.has(item.id);
    return `<article class="post-card" data-open-id="${escapeHtml(item.id)}">
      <div class="post-head"><div class="post-author"><div class="entity-logo">${escapeHtml(item.initials)}</div><div><p class="author-name">${escapeHtml(item.entity)}</p><p class="post-meta">${escapeHtml(item.meta)}</p></div></div><button class="more-btn" type="button" data-toast="Options bientôt disponibles">•••</button></div>
      <div class="post-media"><div class="post-img" style="background-image:url('${item.image}')"></div><span class="${item.type==='event'?'event-pill':'media-badge'}">${item.type==='event'?'◷ ':''}${escapeHtml(item.badge)}</span><button class="favorite-btn ${isFav?'active':''}" type="button" data-fav="${escapeHtml(item.id)}" aria-label="Ajouter aux favoris">${favoriteIcon(isFav)}</button></div>
      <div class="post-body"><h3 class="post-title">${escapeHtml(item.title)}</h3><p class="post-text">${escapeHtml(item.text)}</p>${renderEventSocialProof(item)}<div class="post-actions"><span class="action">♥ ${item.likes}</span><span class="action">💬 ${item.comments}</span><span class="action-spacer"></span><span class="action">➤</span></div></div>
    </article>`}).join('')
}
function setActiveFeed(feed){state.activeFeed=feed;$all('.feed-tab').forEach(btn=>btn.classList.toggle('active',btn.dataset.feed===feed));track('feed_tab_change',{feed_tab:feed});renderFeed()}
function renderPeopleList(kind){
  const label = kind === 'going' ? 'Inscrit' : kind === 'interested' ? 'Intéressé' : null;
  return `<div class="people-list">${people.map(p=>`<div class="person-row"><div class="person-avatar" style="background-image:url('${p.avatar}')"></div><div class="person-main"><strong>${escapeHtml(p.name)}</strong><span>${escapeHtml(p.school)}</span></div>${label?`<span class="person-badge">${label}</span>`:''}</div>`).join('')}</div>`;
}
function renderComments(){
  return `<div class="comments-list">${comments.map(c=>`<div class="comment-row"><div class="comment-avatar" style="background-image:url('${c.avatar}')"></div><div class="comment-main"><strong>${escapeHtml(c.name)} · <span style="display:inline;color:#98A2B3">${escapeHtml(c.time)}</span></strong><span class="comment-text">${escapeHtml(c.text)}</span></div></div>`).join('')}</div><div class="comment-box"><input class="comment-input" placeholder="Écrire un commentaire..." /><button class="comment-send" data-comment-send="1">Envoyer</button></div>`;
}
function renderEventMetaGrid(item){
  if(item.type!=='event') return '';
  const startLabel = formatEventDate(item.start_datetime, item.end_datetime) || item.date || 'Date à venir';
  const meta = [
    ['Date', startLabel],
    ['Lieu', item.place || 'Lieu à venir'],
    ['Prix', item.price || 'Prix à venir'],
    ['Catégorie', item.category || item.badge || 'Événement']
  ];
  return `<div class="detail-meta-grid">${meta.map(([label,value])=>`<div class="detail-meta-card"><span>${escapeHtml(label)}</span><strong>${escapeHtml(value)}</strong></div>`).join('')}</div>`;
}
function renderMediaGallery(item){
  const gallery=[item.image1,item.image2,item.image3,item.image4].filter(Boolean);
  const imgs=(gallery.length?gallery:(item.gallery||[])).filter(Boolean).slice(0,4);
  if(!imgs.length) return '';
  return `<div class="detail-gallery-title">Photos</div><div class="detail-gallery-grid premium">${imgs.map((u,i)=>`<button class="detail-gallery-img premium" type="button" style="background-image:url('${u}')" aria-label="Image ${i+1}"></button>`).join('')}</div>`;
}
function renderVideo(item){
  if(!item.video_url) return '';
  return `<div class="detail-video-box"><div class="detail-gallery-title">Vidéo</div><iframe src="${safeUrl(item.video_url)}" title="Vidéo ${escapeHtml(item.title)}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>`;
}
function renderEventBox(item){
  const hasBilletterie = item.ticketing_url && item.ticketing_url !== '#';
  const hasWhatsApp    = item.whatsapp_url  && item.whatsapp_url  !== '#';

  let ctaHtml = '';
  if (hasBilletterie && hasWhatsApp) {
    ctaHtml = `
      <a class="event-cta join" href="${safeUrl(item.ticketing_url)}" target="_blank" rel="noopener" data-track-cta="billetterie" data-event-id="${escapeHtml(item.id)}">Réserver ma place</a>
      <a class="event-cta interest" href="${safeUrl(item.whatsapp_url)}" target="_blank" rel="noopener" data-track-cta="whatsapp" data-event-id="${escapeHtml(item.id)}">Rejoindre le WhatsApp</a>`;
  } else if (hasBilletterie) {
    ctaHtml = `
      <button class="event-cta interest" data-event-action="interest" data-event-id="${escapeHtml(item.id)}">Je suis intéressé</button>
      <a class="event-cta join" href="${safeUrl(item.ticketing_url)}" target="_blank" rel="noopener" data-track-cta="billetterie" data-event-id="${escapeHtml(item.id)}">Réserver ma place</a>`;
  } else if (hasWhatsApp) {
    ctaHtml = `
      <button class="event-cta interest" data-event-action="interest" data-event-id="${escapeHtml(item.id)}">Je suis intéressé</button>
      <a class="event-cta join" href="${safeUrl(item.whatsapp_url)}" target="_blank" rel="noopener" data-track-cta="whatsapp" data-event-id="${escapeHtml(item.id)}">Rejoindre le groupe</a>`;
  } else {
    ctaHtml = `
      <button class="event-cta interest" data-event-action="interest" data-event-id="${escapeHtml(item.id)}">Je suis intéressé</button>
      <button class="event-cta join" data-event-action="join" data-event-id="${escapeHtml(item.id)}">Je participe</button>`;
  }

  return `<div class="detail-event-box premium"><h3>Statut de l'événement</h3><div class="event-detail-stats"><div class="event-detail-stat"><strong>${item.interested||0}</strong><span>intéressés</span></div><div class="event-detail-stat"><strong>${item.going||0}</strong><span>inscrits</span></div></div>${renderEventSocialProof(item)}</div><div class="event-cta-row premium">${ctaHtml}</div>`;
}
function renderDetailPanels(item){
  const isEvent=item.type==='event';
  const descText = item.details || item.text || '';
  const programText = item.program || '';

  const infoPanel = `<div class="detail-panel active" data-panel="infos">
    <div class="detail-section-card">
      <h3>À propos</h3>
      <p class="detail-text">${escapeHtml(descText)}</p>
      ${isEvent?renderEventBox(item):''}
    </div>
  </div>`;

  const detailsPanel = `<div class="detail-panel" data-panel="details">
    <div class="detail-section-card">
      <h3>Détails</h3>
      ${renderEventMetaGrid(item)}
      ${renderMediaGallery(item)}
      ${renderVideo(item)}
      ${item.whatsapp_url?`<a class="detail-whatsapp" href="${safeUrl(item.whatsapp_url)}" target="_blank" rel="noopener">Rejoindre le WhatsApp</a>`:''}
    </div>
  </div>`;

  const programPanel = `<div class="detail-panel" data-panel="programme">
    <div class="detail-section-card">
      <h3>Programme</h3>
      ${programText
        ? `<p class="detail-text">${escapeHtml(programText)}</p>`
        : `<p class="detail-text" style="color:#98A2B3;font-style:italic">Le programme détaillé sera publié prochainement par l'organisateur.</p>`
      }
    </div>
  </div>`;

  const participantsPanel = isEvent ? `<div class="detail-panel" data-panel="participants">
    <div class="detail-section-card">
      <h3>Participants</h3>
      <div class="participants-filter">
        <button class="pf-btn active" data-pf="all">Tous</button>
        <button class="pf-btn" data-pf="interested">Intéressés</button>
        <button class="pf-btn" data-pf="going">Inscrits</button>
      </div>
      <div id="participantsList">
        <div data-pf-group="all">${renderPeopleList('all')}</div>
        <div data-pf-group="interested" style="display:none">${renderPeopleList('interested')}</div>
        <div data-pf-group="going" style="display:none">${renderPeopleList('going')}</div>
      </div>
    </div>
  </div>` : '';

  const commentsPanel = `<div class="detail-panel" data-panel="comments">
    <div class="detail-section-card">
      <h3>Commentaires</h3>
      ${renderComments()}
    </div>
  </div>`;

  return infoPanel + detailsPanel + programPanel + (isEvent?participantsPanel:'') + commentsPanel;
}
function renderDetail(item){
  const isEvent=item.type==='event';
  const hero=item.hero_image||item.image;
  const organizerName=item.organizer_name||item.entity;
  const organizerLogo=item.organizer_logo||item.initials;
  const subtitle=item.slogan||item.meta||'';
  const tabs = isEvent
    ? `<button class="detail-tab active" data-detail-tab="infos">Infos</button><button class="detail-tab" data-detail-tab="details">Détails</button><button class="detail-tab" data-detail-tab="programme">Programme</button><button class="detail-tab" data-detail-tab="participants">Participants</button><button class="detail-tab" data-detail-tab="comments">Commentaires</button>`
    : `<button class="detail-tab active" data-detail-tab="infos">Infos</button><button class="detail-tab" data-detail-tab="details">Détails</button><button class="detail-tab" data-detail-tab="comments">Commentaires</button>`;

  return `<div class="detail-shell premium-detail">
    <div class="detail-top premium">
      <button class="detail-icon-btn" type="button" id="closeDetailBtn">‹</button>
      <div class="detail-title-small">${escapeHtml(item.type==='event'?'Événement':'Publication')}</div>
      <button class="detail-icon-btn favorite-detail ${state.favoriteIds.has(item.id)?'active':''}" type="button" data-fav="${escapeHtml(item.id)}" aria-label="Ajouter aux favoris">${favoriteIcon(state.favoriteIds.has(item.id))}</button>
    </div>

    <div class="detail-premium-header">
      <h1 class="detail-main-title premium">${escapeHtml(item.title)}</h1>
      ${subtitle?`<p class="detail-slogan">${escapeHtml(subtitle)}</p>`:''}
    </div>

    <div class="detail-hero-card">
      <div class="detail-hero premium" style="background-image:url('${hero}')"></div>
    </div>

    <div class="detail-author-card">
      <div class="entity-logo detail-org-logo">${escapeHtml(organizerLogo)}</div>
      <div class="detail-author-copy"><span>Par</span><strong>${escapeHtml(organizerName)}</strong><small>${escapeHtml(item.meta||'Organisateur')}</small></div>
    </div>

    <div class="detail-tabs premium">${tabs}</div>
    <div class="detail-content premium">${renderDetailPanels(item)}</div>
  </div>`;
}
function openDetail(id){
  const item=findItemById(id); if(!item) return;
  const overlay=$('#detailOverlay'); overlay.innerHTML=renderDetail(item); overlay.classList.add('open'); overlay.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden';
  track(item.type==='event'?'event_view':'post_view',{id:item.id,title:item.title,entity_name:item.entity,feed_tab:state.activeFeed,interested_count:item.interested||0,going_count:item.going||0});
}
function closeDetail(){const overlay=$('#detailOverlay'); overlay.classList.remove('open'); overlay.setAttribute('aria-hidden','true'); document.body.style.overflow=''; setTimeout(()=>overlay.innerHTML='',260)}
function setDetailTab(tab){
  $all('.detail-tab').forEach(b=>b.classList.toggle('active',b.dataset.detailTab===tab));
  $all('.detail-panel').forEach(p=>p.classList.toggle('active',p.dataset.panel===tab));
  const itemId=$('#detailOverlay [data-fav]')?.dataset.fav || '';
  const item=findItemById(itemId);
  if(tab==='interested') track('event_interested_list_view',{id:itemId,title:item?.title});
  if(tab==='going') track('event_registered_list_view',{id:itemId,title:item?.title});
  if(tab==='comments') track('comments_view',{id:itemId,title:item?.title,type:item?.type});
}
function handleEventAction(action,id){
  const item=findItemById(id); if(!item) return;
  state.eventActions[id]=action;
  if(action==='interest'){ item.interested=(item.interested||0)+1; track('event_interest_click',{id,title:item.title,entity_name:item.entity}); showToast('Ajouté aux intéressés'); }
  if(action==='join'){ item.going=(item.going||0)+1; track('event_join_click',{id,title:item.title,entity_name:item.entity}); showToast('Participation enregistrée'); }
  $('#detailOverlay').innerHTML=renderDetail(item);
  renderFeed();
}
function openDrawer(){ $('#drawerBackdrop').classList.add('open'); $('#drawer').classList.add('open'); $('#drawer').setAttribute('aria-hidden','false'); track('menu_open') }
function closeDrawer(){ $('#drawerBackdrop').classList.remove('open'); $('#drawer').classList.remove('open'); $('#drawer').setAttribute('aria-hidden','true') }
function showToast(message){const toast=$('#toast');toast.textContent=message;toast.classList.add('show');clearTimeout(showToast._timer);showToast._timer=setTimeout(()=>toast.classList.remove('show'),1800)}
async function logout(){track('logout_click');await fetch('api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify({action:'logout'})});window.location.href='login.php'}
function toggleFavorite(id, btn){
  if(!id)return; const item=findItemById(id);
  const adding=!state.favoriteIds.has(id);
  if(!adding){state.favoriteIds.delete(id);showToast('Retiré des favoris');track('favorite_remove',{id,title:item?.title,type:item?.type})}
  else{state.favoriteIds.add(id);showToast('Ajouté aux favoris');track('favorite_add',{id,title:item?.title,type:item?.type})}
  if(btn){btn.classList.remove('heart-pop'); void btn.offsetWidth; btn.classList.add('heart-pop')}
  setTimeout(()=>{renderFeed(); if($('#detailOverlay').classList.contains('open') && item) $('#detailOverlay').innerHTML=renderDetail(item);}, 180);
}

function formatReservationDate(v){
  if(!v) return '';
  try{
    const d=new Date(v);
    if(Number.isNaN(d.getTime())) return String(v).slice(0,10);
    return d.toLocaleDateString('fr-FR',{day:'2-digit',month:'short',year:'numeric'}).replace('.', '');
  }catch(e){return String(v).slice(0,10)}
}
function labelItemType(t){
  const map={PARTICIPANT:'Participant',TRANSPORT:'Transport',OPTION:'Option',ASSURANCE:'Assurance',WAITING:'Pré-inscription'};
  return map[String(t||'').toUpperCase()]||String(t||'Billet');
}
function statusLabel(v){
  const s=String(v||'').toUpperCase();
  if(s==='CONFIRME') return 'Confirmé';
  if(s==='ANNULE') return 'Annulé';
  if(s==='WAITING') return 'En attente';
  return v||'—';
}
async function fetchMyReservations(){
  const res=await fetch('api/reservations.php?action=my_reservations',{credentials:'same-origin'});
  const data=await res.json().catch(()=>({ok:false,error:'INVALID_JSON'}));
  if(!res.ok||!data.ok) throw new Error(data.error||`HTTP_${res.status}`);
  state.reservations=Array.isArray(data.data)?data.data:[];
  state.reservationsLoaded=true;
  return state.reservations;
}
async function reservationApi(action,payload={}){
  const res=await fetch('api/reservations.php',{method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify({action,...payload})});
  const data=await res.json().catch(()=>({ok:false,error:'INVALID_JSON'}));
  if(!res.ok||!data.ok) throw new Error(data.error||`HTTP_${res.status}`);
  return data;
}
async function sendReservationAccessEmail(reservationId){return reservationApi('send_reservation_access_email',{reservation_id:reservationId});}
function reservationTokenFromUrl(){
  const qs=new URLSearchParams(window.location.search);
  return qs.get('reservation_token')||qs.get('token')||'';
}
async function resolveReservationToken(token){
  if(!token) return '';
  const data=await reservationApi('reservation_from_token',{token});
  return data.reservation_id||'';
}
function renderReservationsContent(rows,targetReservationId=''){
  if(!rows||!rows.length){
    return `<div class="reservation-empty"><h3>Aucune réservation</h3><p>Aucune réservation trouvée pour ce compte pour le moment.</p></div>`;
  }
  if(targetReservationId && !state.openReservationId) state.openReservationId=String(targetReservationId);
  const sorted=[...rows].sort((a,b)=>String(a.id)===String(targetReservationId)?-1:String(b.id)===String(targetReservationId)?1:0);
  return `<div class="reservation-hero-card"><div class="reservation-hero-icon">▣</div><div><h3>Prépare ton séjour</h3><p>Ouvre une réservation pour attribuer les billets aux bonnes personnes.</p></div><div class="reservation-hero-illu">🧳</div></div>
  <div class="reservation-list">${sorted.map(r=>{
    const eventName=r.event?.name||'Réservation';
    const dates=[r.event?.start_date,r.event?.end_date].filter(Boolean).join(' → ');
    const roles=Array.isArray(r.linked_as)?r.linked_as.join(' / '):(r.linked_as||'');
    const items=Array.isArray(r.items)?r.items:[];
    const participants=items.filter(x=>String(x.item_type).toUpperCase()==='PARTICIPANT');
    const options=items.filter(x=>String(x.item_type).toUpperCase()!=='PARTICIPANT');
    const toAssign=items.filter(x=>String(x.item_status||'').toUpperCase()!=='ANNULE' && !x.is_confirmed_by_buyer).length;
    const opened=String(state.openReservationId||'')===String(r.id);
    return `<article class="reservation-card-v3 ${opened?'open':''}">
      <button class="reservation-summary" type="button" data-toggle-reservation="${escapeHtml(r.id)}">
        <div class="reservation-cover">${escapeHtml((eventName||'E').slice(0,1))}</div>
        <div class="reservation-summary-main"><div class="reservation-summary-top"><h3>${escapeHtml(eventName)}</h3><span class="reservation-brand">${escapeHtml(r.marque||'')}</span></div>
        <p>${escapeHtml(dates||formatReservationDate(r.created_at)||'')}</p>
        <div class="reservation-chips"><span>Commande : ${escapeHtml(r.source_order_id||'—')}</span><span>${participants.length} participant(s)</span><span>${options.length} option(s)</span><span class="${toAssign>0?'warn':'ok'}">${toAssign>0?toAssign+' à attribuer':'Complet'}</span></div></div>
        <div class="reservation-chevron">${opened?'▴':'▾'}</div>
      </button>
      ${opened?`<div class="reservation-detail-v3">
        <div class="reservation-toolbar"><div><strong>Attribution des billets</strong><span>Rôle : ${escapeHtml(roles||'BUYER')} · Statut : ${escapeHtml(r.validation_status||r.status||'—')}</span></div><button class="reservation-mail-btn" type="button" data-send-reservation-email="${escapeHtml(r.id)}">Envoyer le lien</button></div>
        ${renderReservationGroup('Participants','Billets participant au séjour',participants)}
        ${renderReservationGroup('Options','Options et extras du séjour',options)}
        <div class="reservation-help"><div><strong>Conseil</strong><p>Attribue chaque billet avant le départ pour gagner du temps le jour J.</p></div><button type="button" data-menu="support">Besoin d'aide ?</button></div>
      </div>`:''}
    </article>`;
  }).join('')}</div>`;
}
function renderReservationGroup(title, subtitle, items){
  if(!items.length) return '';
  return `<section class="reservation-group"><div class="reservation-group-head"><div><h4>${escapeHtml(title)} <span>${items.length}</span></h4><p>${escapeHtml(subtitle)}</p></div></div><div class="reservation-lines">${items.map(it=>renderReservationLine(it)).join('')}</div></section>`;
}
function renderReservationLine(it){
  const isAssignedToMe=state.user&&String(it.assigned_user_id||'')===String(state.user.id||'');
  const status=String(it.item_status||'').toUpperCase();
  const isCancelled=status==='ANNULE';
  const confirmed=!!it.is_confirmed_by_buyer;
  const assignLabel=isAssignedToMe?'Attribué à moi':confirmed?'Attribué':'À attribuer';
  const initials=([it.first_name,it.last_name].filter(Boolean).join(' ')||labelItemType(it.item_type)||'B').split(/\s+/).map(x=>x[0]).join('').slice(0,2).toUpperCase();
  return `<div class="reservation-line ${isCancelled?'cancelled':''}" data-item-id="${escapeHtml(it.id)}"><div class="reservation-line-avatar">${escapeHtml(initials)}</div><div class="reservation-line-main"><strong>${escapeHtml([it.first_name,it.last_name].filter(Boolean).join(' ')||'Participant à attribuer')}</strong><span>${escapeHtml(it.title||labelItemType(it.item_type))}</span></div><div class="reservation-line-status"><span>${escapeHtml(statusLabel(it.item_status))}</span><em>${escapeHtml(isCancelled?'Non attribuable':assignLabel)}</em></div><div class="reservation-line-actions"><button class="reservation-action" type="button" data-confirm-me="${escapeHtml(it.id)}" ${isCancelled?'disabled':''}>C'est moi</button><button class="reservation-action secondary" type="button" data-confirm-other="${escapeHtml(it.id)}" ${isCancelled?'disabled':''}>Quelqu'un d'autre</button></div></div>`;
}
function openReservations(targetReservationId=''){
  const overlay=$('#detailOverlay');
  overlay.innerHTML=`<div class="detail-shell premium-detail">
    <div class="detail-top premium">
      <button class="detail-icon-btn" type="button" id="closeDetailBtn">‹</button>
      <div class="detail-title-small">Mes réservations</div>
      <span></span>
    </div>
    <div class="detail-premium-header"><h1 class="detail-main-title premium">Mes réservations</h1><p class="detail-slogan">Chargement de tes réservations...</p></div>
    <div class="detail-content premium"><div class="loading-card">Chargement…</div></div>
  </div>`;
  overlay.classList.add('open');
  overlay.setAttribute('aria-hidden','false');
  document.body.style.overflow='hidden';
  track('my_reservations_open',{target_reservation_id:targetReservationId||''});
  fetchMyReservations()
    .then(rows=>{
      overlay.innerHTML=`<div class="detail-shell premium-detail">
        <div class="detail-top premium">
          <button class="detail-icon-btn" type="button" id="closeDetailBtn">‹</button>
          <div class="detail-title-small">Mes réservations</div>
          <span></span>
        </div>
        <div class="detail-premium-header"><h1 class="detail-main-title premium">Mes réservations</h1><p class="detail-slogan">${rows.length} réservation(s) liée(s) à ton compte.</p></div>
        <div class="detail-content premium">${renderReservationsContent(rows,targetReservationId)}</div>
      </div>`;
    })
    .catch(err=>{
      overlay.innerHTML=`<div class="detail-shell premium-detail">
        <div class="detail-top premium"><button class="detail-icon-btn" type="button" id="closeDetailBtn">‹</button><div class="detail-title-small">Mes réservations</div><span></span></div>
        <div class="detail-content premium"><div class="detail-section-card"><h3>Erreur</h3><p class="detail-text">${escapeHtml(err.message||'Impossible de charger les réservations.')}</p></div></div>
      </div>`;
    });
}

function bindEvents(){
  $('#openMenuBtn').addEventListener('click',openDrawer); $('#closeMenuBtn').addEventListener('click',closeDrawer); $('#drawerBackdrop').addEventListener('click',closeDrawer); $('#logoutBtn').addEventListener('click',logout);
  $all('.feed-tab').forEach(btn=>btn.addEventListener('click',()=>setActiveFeed(btn.dataset.feed)));
  document.addEventListener('click',(e)=>{
    const closeBtn=e.target.closest('#closeDetailBtn'); if(closeBtn){closeDetail();return}
    const tab=e.target.closest('[data-detail-tab]'); if(tab){setDetailTab(tab.dataset.detailTab);return}
    const eventAction=e.target.closest('[data-event-action]'); if(eventAction){handleEventAction(eventAction.dataset.eventAction,eventAction.dataset.eventId);return}
    const pfBtn=e.target.closest('[data-pf]'); if(pfBtn&&pfBtn.classList.contains('pf-btn')){const filter=pfBtn.dataset.pf;document.querySelectorAll('.pf-btn').forEach(b=>b.classList.toggle('active',b.dataset.pf===filter));document.querySelectorAll('[data-pf-group]').forEach(g=>g.style.display=g.dataset.pfGroup===filter?'':'none');return}
    const commentSend=e.target.closest('[data-comment-send]'); if(commentSend){track('comment_send_click');showToast('Commentaire bientôt connecté');return}
    const favBtn=e.target.closest('[data-fav]'); if(favBtn){e.preventDefault();e.stopPropagation();toggleFavorite(favBtn.dataset.fav,favBtn);return}
    const category=e.target.closest('[data-category]'); if(category){setActiveCategory(category.dataset.category);return}
    const ctaLink=e.target.closest('[data-track-cta]'); if(ctaLink){const item=findItemById(ctaLink.dataset.eventId||''); track('event_cta_click',{cta:ctaLink.dataset.trackCta,id:ctaLink.dataset.eventId,title:item?.title});return}
    const card=e.target.closest('[data-open-id]'); if(card){openDetail(card.dataset.openId);return}
    const toastTarget=e.target.closest('[data-toast]'); if(toastTarget) showToast(toastTarget.dataset.toast);
    const nav=e.target.closest('[data-nav]'); if(nav){$all('.bottom-item').forEach(btn=>btn.classList.remove('active'));nav.classList.add('active');track('bottom_nav_click',{target:nav.dataset.nav}); if(nav.dataset.nav==='home')showToast('Accueil'); if(nav.dataset.nav==='search')showToast('Recherche bientôt disponible'); if(nav.dataset.nav==='favorites')showToast('Favoris bientôt disponible'); if(nav.dataset.nav==='profile')showToast('Profil bientôt disponible')}
    const toggleReservation=e.target.closest('[data-toggle-reservation]'); if(toggleReservation){const rid=toggleReservation.dataset.toggleReservation; state.openReservationId=String(state.openReservationId||'')===String(rid)?'':rid; $('#detailOverlay .detail-content').innerHTML=renderReservationsContent(state.reservations); return}
    const sendResEmail=e.target.closest('[data-send-reservation-email]'); if(sendResEmail){const rid=sendResEmail.dataset.sendReservationEmail; sendReservationAccessEmail(rid).then(data=>{showToast(data.sent?'Lien envoyé par email':'Lien créé'); if(data.link) console.log('Reservation link:',data.link)}).catch(err=>{console.error('Reservation email error',err);showToast(err.message||'Erreur email')}); return}
    const confirmMe=e.target.closest('[data-confirm-me]'); if(confirmMe){const itemId=confirmMe.dataset.confirmMe; reservationApi('confirm_item_for_me',{item_id:itemId}).then(()=>{showToast('Billet attribué à ton compte');return fetchMyReservations()}).then(rows=>{$('#detailOverlay .detail-content').innerHTML=renderReservationsContent(rows,state.openReservationId)}).catch(err=>showToast(err.message||'Erreur attribution'));return}
    const confirmOther=e.target.closest('[data-confirm-other]'); if(confirmOther){const itemId=confirmOther.dataset.confirmOther; const first_name=prompt('Prénom du participant'); if(first_name===null)return; const last_name=prompt('Nom du participant'); if(last_name===null)return; const email=prompt('Email du participant (optionnel)')||''; const phone=prompt('Téléphone du participant (optionnel)')||''; reservationApi('confirm_item_external',{item_id:itemId,first_name,last_name,email,phone}).then(()=>{showToast('Participant enregistré');return fetchMyReservations()}).then(rows=>{$('#detailOverlay .detail-content').innerHTML=renderReservationsContent(rows,state.openReservationId)}).catch(err=>showToast(err.message||'Erreur attribution'));return}
    const menu=e.target.closest('[data-menu]'); if(menu){const label=menu.textContent.trim();track('menu_click',{label,target:menu.dataset.menu});closeDrawer(); if(menu.dataset.menu==='reservations'){openReservations();return} showToast(`${label} bientôt disponible`)}
  });
  document.addEventListener('keydown',(e)=>{if(e.key==='Escape'&&$('#detailOverlay').classList.contains('open'))closeDetail()})
}

function enableHorizontalWheel(selector) {
  document.querySelectorAll(selector).forEach((el) => {
    if (el.dataset.wheelBound === '1') return;
    el.dataset.wheelBound = '1';

    el.addEventListener('wheel', (e) => {
      if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) return;
      if (el.scrollWidth <= el.clientWidth) return;

      e.preventDefault();
      el.scrollLeft += e.deltaY;
    }, { passive: false });
  });
}

function updateSpotlightFocus() {
  const row = document.getElementById('spotlightRow');
  if (!row) return;

  const cards = Array.from(row.querySelectorAll('.spotlight-card'));
  if (!cards.length) return;

  const rowRect = row.getBoundingClientRect();
  const center = rowRect.left + rowRect.width / 2;

  let closest = null;
  let closestDist = Infinity;

  cards.forEach((card) => {
    const rect = card.getBoundingClientRect();
    const cardCenter = rect.left + rect.width / 2;
    const dist = Math.abs(center - cardCenter);

    if (dist < closestDist) {
      closestDist = dist;
      closest = card;
    }
  });

  cards.forEach((card) => card.classList.toggle('is-focused', card === closest));
}

function bindScrollUX() {
  enableHorizontalWheel('.spotlight-row');
  enableHorizontalWheel('.h-scroll');
  enableHorizontalWheel('.feed-tabs');

  const row = document.getElementById('spotlightRow');
  if (row && row.dataset.focusBound !== '1') {
    row.dataset.focusBound = '1';
    row.addEventListener('scroll', () => {
      window.requestAnimationFrame(updateSpotlightFocus);
    }, { passive: true });

    window.addEventListener('resize', () => {
      window.requestAnimationFrame(updateSpotlightFocus);
    });

    setTimeout(updateSpotlightFocus, 80);
    setTimeout(updateSpotlightFocus, 350);
  }
}

async function init(){
  bindEvents(); renderCategories(); renderSpotlight(); renderFeed(); bindScrollUX(); track('app_home_view');

  try {
    const [user] = await Promise.all([
      fetchCurrentUser(),
    ]);
    if(!user) return;
    state.user = user;
    hydrateUser(user);
    const rt = reservationTokenFromUrl();
    if(rt){ resolveReservationToken(rt).then(rid=>openReservations(rid)).catch(()=>openReservations()); }
  } catch(error) {
    $('#feedList').innerHTML=`<div class="loading-card">Erreur de chargement. Vérifie ta connexion ou reconnecte-toi.</div>`;
  }
}
init();
