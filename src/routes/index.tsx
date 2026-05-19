import { createFileRoute } from "@tanstack/react-router";

export const Route = createFileRoute("/")({
  component: Index,
});

// All images served from /public/assets/images/ → simple relative paths,
// easy to swap with get_template_directory_uri() in a WordPress theme.
const IMG = "/assets/images";

const navItems = [
  ["Accueil", "#accueil"],
  ["Le Club", "#club"],
  ["Palmarès", "#palmares"],
  ["Académie", "#academie"],
  ["Galerie", "#galerie"],
  ["Événements", "#evenements"],
  ["Presse", "#presse"],
  ["Partenariat", "#partenariat"],
  ["Contact", "#contact"],
];

/* ---------------------------------------------------------------- HEADER */
function SiteHeader() {
  return (
    <header id="site-header" className="fixed top-0 inset-x-0 z-50 backdrop-blur-md bg-background/70 border-b border-border">
      <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <a href="#accueil" className="flex items-center gap-3" aria-label="Najah Souss Echecs — Accueil">
          <div className="size-11 rounded-md bg-gradient-gold flex items-center justify-center text-primary-foreground font-bold text-xl gold-glow" aria-hidden="true">♞</div>
          <div className="leading-tight">
            <div className="font-royal font-semibold text-base tracking-[0.18em] text-gold">NAJAH SOUSS</div>
            <div className="text-[10px] text-muted-foreground tracking-[0.3em] uppercase">Echecs · Depuis 1987</div>
          </div>
        </a>
        <nav aria-label="Navigation principale" className="hidden lg:flex items-center gap-7 text-sm">
          {navItems.map(([label, href]) => (
            <a key={href} href={href} className="text-muted-foreground hover:text-gold transition-colors">{label}</a>
          ))}
        </nav>
        <a href="#contact" className="px-5 py-2.5 rounded-md bg-gold text-primary-foreground font-semibold text-sm tracking-wide hover:opacity-90 transition gold-shadow">
          S'INSCRIRE
        </a>
      </div>
    </header>
  );
}

/* ------------------------------------------------------------------ HERO */
function Hero() {
  const stats: Array<[string, string]> = [
    ["🏆", "Coupe du Trône 2025"],
    ["8×", "Médailles d'Or 2025-26"],
    ["FIDE", "Affilié"],
    ["1987", "Année de fondation"],
  ];
  return (
    <section id="accueil" aria-label="Hero" className="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
      <img src={`${IMG}/hero-chess.jpg`} alt="Échiquier Najah Souss Echecs" className="absolute inset-0 w-full h-full object-cover opacity-50" width={1920} height={1080} />
      <div className="absolute inset-0 bg-gradient-to-b from-background/60 via-background/40 to-background" aria-hidden="true" />
      <div className="relative z-10 max-w-5xl mx-auto px-6 text-center py-24">
        <div className="inline-flex items-center gap-3 px-6 py-3 rounded-full border border-gold/40 bg-background/60 backdrop-blur mb-10 gold-glow">
          <span className="text-gold" aria-hidden="true">✦</span>
          <span className="text-xs tracking-[0.35em] uppercase text-gold font-royal">Vainqueurs de la Coupe du Trône 2025</span>
          <span className="text-gold" aria-hidden="true">✦</span>
        </div>
        <p className="eyebrow mb-6">Agadir · Maroc — Depuis 1987</p>
        <h1 className="font-royal text-4xl md:text-6xl font-semibold tracking-[0.06em] mb-3">NAJAH SOUSS <span className="gradient-gold">ECHECS</span></h1>
        <p className="font-display italic text-lg md:text-2xl text-gold-soft/90 mb-8 ornament inline-block">Aucun coup ne doit être joué sans but</p>
        <h2 className="text-3xl md:text-5xl font-display gradient-gold mb-8 italic">Champions du Maroc 2025</h2>
        <p className="text-lg md:text-xl text-muted-foreground mb-2">L'excellence des échecs à Agadir.</p>
        <p className="text-lg md:text-xl italic text-muted-foreground mb-10">Formation, Compétition et Culture.</p>
        <div className="flex flex-wrap justify-center gap-4 mb-16">
          <a href="#contact" className="px-8 py-4 rounded-md bg-gradient-gold text-primary-foreground font-semibold gold-shadow hover:scale-105 transition">♞ Rejoindre le Club</a>
          <a href="#palmares" className="px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold hover:bg-gold/10 transition">Voir le Palmarès</a>
        </div>
        <ul className="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto list-none">
          {stats.map(([big, label]) => (
            <li key={label} className="text-center">
              <div className="text-3xl md:text-4xl text-gold font-display font-bold mb-1">{big}</div>
              <div className="text-xs uppercase tracking-widest text-muted-foreground">{label}</div>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}

/* ----------------------------------------------------------- SECTION SHELL */
function SectionShell({ id, eyebrow, title, subtitle, children, className = "" }: { id: string; eyebrow?: string; title: React.ReactNode; subtitle?: string; children: React.ReactNode; className?: string }) {
  return (
    <section id={id} className={`py-24 px-6 ${className}`}>
      <div className="max-w-7xl mx-auto">
        <header className="text-center mb-16">
          {eyebrow && <p className="eyebrow mb-4">{eyebrow}</p>}
          <h2 className="text-4xl md:text-5xl font-bold mb-4">{title}</h2>
          {subtitle && <p className="text-muted-foreground max-w-2xl mx-auto">{subtitle}</p>}
          <div className="divider-gold w-24 mx-auto mt-6" aria-hidden="true" />
        </header>
        {children}
      </div>
    </section>
  );
}

/* ----------------------------------------------------------------- ABOUT */
function About() {
  const pillars: Array<[string, string, string]> = [
    ["📚", "Éducation", "Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité."],
    ["🏆", "Compétition", "Participation aux tournois régionaux, nationaux et internationaux homologués FIDE/FRME."],
    ["🤝", "Fair-play", "Respect mutuel, éthique irréprochable et esprit sportif au cœur de chaque partie."],
  ];
  return (
    <section id="club" className="py-24 px-6">
      <div className="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
        <div>
          <p className="eyebrow mb-4">À propos</p>
          <h2 className="text-4xl md:text-5xl font-bold mb-2">À PROPOS</h2>
          <h2 className="text-4xl md:text-5xl font-bold gradient-gold mb-8">D'EXCELLENCE</h2>
          <div className="space-y-5 text-muted-foreground leading-relaxed">
            <p><strong className="text-foreground font-semibold">Najah Souss Echecs</strong> est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987.</p>
            <p>Officiellement affilié à la <span className="text-gold font-semibold">Fédération Royale Marocaine des Échecs (FRME)</span> et reconnu par la <span className="text-gold font-semibold">FIDE</span>, notre club représente la région Souss-Massa sur la scène nationale et internationale.</p>
            <p className="font-display italic text-gold-soft/90 text-lg pt-2">« Aucun coup ne doit être joué sans but. »</p>
          </div>
          <ul className="flex flex-wrap gap-2 mt-8 list-none">
            {["Affilié FRME", "Reconnu FIDE", "Agadir, Maroc", "Depuis 1987"].map((b) => (
              <li key={b} className="px-3 py-1.5 rounded-full bg-surface border border-gold/30 text-xs text-gold">{b}</li>
            ))}
          </ul>
        </div>
        <div className="space-y-4">
          {pillars.map(([icon, title, desc]) => (
            <article key={title} className="surface-card rounded-xl p-6 flex gap-5 hover:border-gold/40 transition">
              <div className="size-12 shrink-0 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-2xl" aria-hidden="true">{icon}</div>
              <div>
                <h3 className="font-display font-bold text-lg text-gold mb-1">{title}</h3>
                <p className="text-sm text-muted-foreground">{desc}</p>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

/* -------------------------------------------------------------- PALMARÈS */
function Palmares() {
  // Real palmarès — Saison 2025 / 2026
  const titles: Array<[string, string, string]> = [
    ["🥇", "Inès Boufous — U8 Féminines", "Championne du Maroc 2025 & 2026"],
    ["🥇", "Wissale Cherbini — U20 Féminines", "Championne du Maroc 2025 & 2026"],
    ["🥇", "Houda Cherbini — U18 Féminines", "Championne du Maroc 2025"],
    ["🥇", "Houssine Fana — U14 Masculins", "Champion du Maroc 2025 · Sélection Nationale"],
    ["🥇", "Fahd Amrani — U18 Masculins", "Champion du Maroc 2026"],
    ["🥇", "Maryam Herraq — U14 Féminines", "Championne du Maroc 2026"],
    ["🌍", "Équipe Nationale 2026", "4 sélections — U14 M / U14 F / U16 F / U18 M"],
    ["🏅", "8 Médailles d'Or", "Championnats nationaux — 2 ans consécutifs"],
  ];
  return (
    <SectionShell id="palmares" eyebrow="Hall of Fame" title="Notre Palmarès" subtitle="Coupe du Trône 2025, 8 médailles d'or et 4 sélections nationales — l'excellence de Najah Souss Echecs.">
      <div className="surface-card rounded-2xl p-8 mb-6 text-center gold-shadow relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-r from-gold/5 via-gold/15 to-gold/5" aria-hidden="true" />
        <div className="relative">
          <div className="inline-block px-4 py-1 rounded-full bg-gold text-primary-foreground text-xs font-bold tracking-widest mb-4">✦ TITRE MAJEUR ✦</div>
          <div className="text-5xl mb-3" aria-hidden="true">🏆</div>
          <h3 className="text-3xl md:text-4xl font-bold gradient-gold mb-2">Coupe du Trône 2025</h3>
          <p className="text-muted-foreground">Champion National — Catégorie Ouverte · Jerrada, Août 2025</p>
        </div>
      </div>
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {titles.map(([icon, t, sub]) => (
          <article key={t} className="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
            <div className="text-3xl mb-3" aria-hidden="true">{icon}</div>
            <h3 className="font-display font-bold text-base mb-1">{t}</h3>
            <p className="text-xs text-muted-foreground tracking-wide">{sub}</p>
          </article>
        ))}
      </div>
    </SectionShell>
  );
}

/* ------------------------------------------------------- MUR DES CHAMPIONS */
function MurChampions() {
  const players: Array<[string, string, string]> = [
    ["Inès Boufous", "Championne U8 Féminines", `${IMG}/club-tournament.jpg`],
    ["Wissale Cherbini", "Championne U20 Féminines", `${IMG}/champions.jpg`],
    ["Houssine Fana", "Champion U14 — Sélection Nationale", `${IMG}/news-1.jpg`],
    ["Fahd Amrani", "Champion U18 Masculins", `${IMG}/news-2.jpg`],
  ];
  return (
    <SectionShell id="champions" title={<>Mur des <span className="gradient-gold">Champions</span></>} subtitle="Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs.">
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {players.map(([name, title, img]) => (
          <article key={name} className="group relative rounded-xl overflow-hidden surface-card aspect-[3/4]">
            <img src={img} alt={`${name} — ${title}`} loading="lazy" className="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-105 transition duration-500" />
            <div className="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent" aria-hidden="true" />
            <div className="absolute bottom-0 inset-x-0 p-5">
              <h3 className="font-display font-bold text-xl">{name}</h3>
              <p className="text-sm text-gold">{title}</p>
            </div>
          </article>
        ))}
      </div>
    </SectionShell>
  );
}

/* -------------------------------------------------------------- ACADÉMIE */
function Academie() {
  const programs: Array<[string, string, string, string[], boolean]> = [
    ["♟", "Débutant", "Les Pions", ["Règles & mouvements", "Tactiques simples", "Mat en 1-2 coups", "Parties guidées"], false],
    ["♝", "Intermédiaire", "Les Fous & Tours", ["Principes d'ouverture", "Combinaisons tactiques", "Fins de partie", "Analyse de parties"], true],
    ["♛", "Avancé", "Les Dames & Rois", ["Stratégie positionnelle", "Répertoires d'ouvertures", "Préparation tournois", "Analyse informatique"], false],
  ];
  return (
    <SectionShell id="academie" eyebrow="Académie & Formation" title={<>Formez-vous avec des <span className="gradient-gold">Experts</span></>} subtitle="Programmes pédagogiques pour tous les niveaux, encadrés par des entraîneurs certifiés et des joueurs classés FIDE.">
      <div className="grid md:grid-cols-3 gap-6 mb-12">
        {programs.map(([icon, level, name, items, popular]) => (
          <article key={level} className={`surface-card rounded-2xl p-8 relative ${popular ? "border-gold/60 gold-shadow scale-[1.02]" : ""}`}>
            {popular && <span className="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-gold text-primary-foreground text-[11px] font-bold tracking-widest">POPULAIRE</span>}
            <div className="text-5xl text-gold mb-4" aria-hidden="true">{icon}</div>
            <p className="eyebrow mb-2">{level}</p>
            <h3 className="font-display font-bold text-2xl mb-4">{name}</h3>
            <ul className="space-y-2 text-sm text-muted-foreground">
              {items.map((it) => (
                <li key={it} className="flex gap-2"><span className="text-gold" aria-hidden="true">✦</span>{it}</li>
              ))}
            </ul>
          </article>
        ))}
      </div>
      <div className="surface-card rounded-2xl overflow-hidden grid md:grid-cols-2 items-center">
        <img src={`${IMG}/academy.jpg`} alt="Cours d'échecs Najah Souss Echecs" loading="lazy" className="h-full w-full object-cover aspect-video" />
        <div className="p-10">
          <p className="eyebrow mb-3">Encadrement Expert</p>
          <h3 className="font-display font-bold text-2xl mb-3">Cours individuels & collectifs disponibles</h3>
          <p className="text-muted-foreground mb-6">Choisissez la formule qui vous convient et progressez à votre rythme avec nos coachs FIDE.</p>
          <a href="#contact" className="inline-block px-6 py-3 rounded-md bg-gold text-primary-foreground font-semibold">Demander un cours d'essai</a>
        </div>
      </div>
    </SectionShell>
  );
}

/* ----------------------------------------------------------------- GALERIE */
function Galerie() {
  const photos: Array<[string, string]> = [
    [`${IMG}/club-tournament.jpg`, "Tournoi du club"],
    [`${IMG}/champions.jpg`, "Cérémonie des champions"],
    [`${IMG}/academy.jpg`, "Entraînement à l'académie"],
    [`${IMG}/news-1.jpg`, "Coupe du Trône 2025"],
    [`${IMG}/news-2.jpg`, "Open Nouvel An Amazigh"],
    [`${IMG}/hero-chess.jpg`, "Échiquier officiel"],
  ];
  return (
    <SectionShell id="galerie" eyebrow="Galerie" title={<>Galerie <span className="gradient-gold">Najah Souss</span></>} subtitle="Tournois, cérémonies, entraînements et victoires — la vie du club en images.">
      <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
        {photos.map(([src, caption]) => (
          <figure key={src} className="group relative rounded-xl overflow-hidden surface-card aspect-[4/3]">
            <img src={src} alt={caption} loading="lazy" className="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-110" />
            <div className="absolute inset-0 bg-gradient-to-t from-background/90 via-background/10 to-transparent opacity-0 group-hover:opacity-100 transition" aria-hidden="true" />
            <figcaption className="absolute bottom-0 inset-x-0 p-4 text-sm font-medium text-gold opacity-0 group-hover:opacity-100 transition">{caption}</figcaption>
          </figure>
        ))}
      </div>
    </SectionShell>
  );
}

/* ----------------------------------------------------------- ÉVÉNEMENTS */
function Evenements() {
  const events: Array<[string, string, string, string, string, string, string]> = [
    ["🌍", "International", "Tournoi des Marocains du Monde", "Grand tournoi international réunissant les Marocains du monde entier. Homologué FRME et ouvert à tous les niveaux.", "Été 2025", "Agadir, Maroc", "À venir"],
    ["⚡", "Blitz — Rapid", "Open Nouvel An Amazigh", "Tournoi Blitz emblématique célébrant le Nouvel An Amazigh (Yennayer). Ambiance festive et compétition de haut niveau.", "Janvier — Annuel", "Agadir, Maroc", "Récurrent"],
    ["💻", "Innovation", "Hackathon des Échecs", "Événement unique mêlant technologie et échecs : IA, plateformes d'entraînement, et innovations pédagogiques.", "2025", "Agadir, Maroc", "Nouveau"],
  ];
  return (
    <SectionShell id="evenements" eyebrow="Calendrier 2025" title={<>Nos <span className="gradient-gold">Événements</span></>} subtitle="Des tournois pour tous les niveaux, des compétitions officielles aux événements culturels.">
      <div className="grid md:grid-cols-3 gap-6">
        {events.map(([icon, cat, title, desc, date, place, badge]) => (
          <article key={title} className="surface-card rounded-2xl p-7 flex flex-col hover:border-gold/40 transition">
            <div className="flex items-center justify-between mb-4">
              <span className="text-3xl" aria-hidden="true">{icon}</span>
              <span className="text-[10px] tracking-widest uppercase px-2 py-1 rounded-full bg-gold/10 text-gold border border-gold/30">{badge}</span>
            </div>
            <p className="eyebrow mb-2">{cat}</p>
            <h3 className="font-display font-bold text-xl mb-3">{title}</h3>
            <p className="text-sm text-muted-foreground mb-5 flex-1">{desc}</p>
            <footer className="border-t border-border pt-4 text-xs text-muted-foreground space-y-1">
              <div>📅 {date}</div>
              <div>📍 {place}</div>
            </footer>
          </article>
        ))}
      </div>
    </SectionShell>
  );
}

/* -------------------------------------------------------------- PRESSE */
function Presse() {
  const articles: Array<[string, string, string, string, string]> = [
    [`${IMG}/news-1.jpg`, "Événement", "📰 AgadirToday", "Succès de la 2ème édition du Blitz Nouvel An Amazigh", "Najah Souss Echecs a organisé avec brio la deuxième édition de son tournoi Blitz Nouvel An Amazigh."],
    [`${IMG}/champions.jpg`, "Titre", "🏆 Agadir24", "L'équipe Najah Souss remporte la Coupe du Trône 2025", "Historique ! L'équipe Najah Souss Echecs remporte la Coupe du Trône 2025 à Jerrada."],
    [`${IMG}/news-2.jpg`, "Interview", "🎙️ FRME", "Déclaration de la Présidente Mme Maryam Yousoufi", "La Présidente s'exprime sur la vision du club et son engagement pour le développement des échecs."],
  ];
  return (
    <SectionShell id="presse" eyebrow="Médias & Presse" title={<>Ils parlent <span className="gradient-gold">de nous</span></>} subtitle="La presse nationale et locale couvre les succès de Najah Souss Echecs.">
      <div className="grid md:grid-cols-3 gap-6">
        {articles.map(([img, tag, source, title, desc]) => (
          <article key={title} className="surface-card rounded-2xl overflow-hidden flex flex-col hover:border-gold/40 transition">
            <div className="relative aspect-video">
              <img src={img} alt={title} loading="lazy" className="w-full h-full object-cover" />
              <span className="absolute top-3 left-3 text-[10px] uppercase tracking-widest px-2 py-1 rounded bg-background/80 text-gold border border-gold/30">{tag}</span>
            </div>
            <div className="p-6 flex flex-col flex-1">
              <p className="text-xs text-gold mb-2 font-semibold">{source}</p>
              <h3 className="font-display font-bold text-lg mb-3">{title}</h3>
              <p className="text-sm text-muted-foreground mb-4 flex-1">{desc}</p>
              <a href="#" className="text-gold text-sm font-semibold border-b border-gold/40 self-start">Lire l'article →</a>
            </div>
          </article>
        ))}
      </div>
    </SectionShell>
  );
}

/* ------------------------------------------------------------- ACTUALITÉ */
function Actualite() {
  const posts: Array<[string, string, string, string]> = [
    [`${IMG}/news-1.jpg`, "Tournoi", "Retour sur la Coupe du Trône 2025", "Récit complet d'une victoire historique pour le club et pour toute la région Souss-Massa."],
    [`${IMG}/champions.jpg`, "Académie", "Lancement de la nouvelle saison à l'Académie", "Inscriptions ouvertes : nouveaux créneaux, nouveaux coachs FIDE et programme renouvelé."],
    [`${IMG}/news-2.jpg`, "Communauté", "Tournoi inter-écoles d'Agadir", "Najah Souss Echecs accueille les jeunes talents de la ville pour une journée d'échecs."],
  ];
  return (
    <SectionShell id="actualites" eyebrow="Notre Actualité" title={<>Notre <span className="gradient-gold">Actualité</span></>} subtitle="Les dernières nouvelles, événements et annonces de Najah Souss Echecs.">
      <div className="grid md:grid-cols-3 gap-6">
        {posts.map(([img, tag, title, desc]) => (
          <article key={title} className="surface-card rounded-2xl overflow-hidden flex flex-col group hover:border-gold/50 transition gold-glow">
            <div className="relative aspect-video overflow-hidden">
              <img src={img} alt={title} loading="lazy" className="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
              <div className="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent" aria-hidden="true" />
              <span className="absolute top-3 left-3 text-[10px] uppercase tracking-[0.25em] px-3 py-1 rounded-full bg-background/80 text-gold border border-gold/40">{tag}</span>
            </div>
            <div className="p-7 flex flex-col flex-1">
              <h3 className="font-display font-semibold text-xl mb-3 leading-snug">{title}</h3>
              <p className="text-sm text-muted-foreground mb-6 flex-1">{desc}</p>
              <a href="#" className="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-md border border-gold/40 text-gold text-sm font-semibold tracking-wide hover:bg-gold hover:text-primary-foreground hover:gold-shadow transition">
                Lire la suite <span aria-hidden="true">→</span>
              </a>
            </div>
          </article>
        ))}
      </div>
    </SectionShell>
  );
}

/* --------------------------------------------------------------- STAFF */
function StaffSection() {
  const staff: Array<[string, string, string, string]> = [
    [`${IMG}/staff-1.jpg`, "Mme Maryam Yousoufi", "Présidente Fondatrice", "Présidente de la Ligue Régionale Souss-Massa et fondatrice de Najah Souss Echecs."],
    [`${IMG}/staff-2.jpg`, "Coach Senior", "Entraîneur Principal", "Entraîneur certifié FIDE, spécialiste de la préparation aux compétitions nationales."],
    [`${IMG}/staff-3.jpg`, "Coach Académie", "Entraîneur Jeunes", "Responsable de l'académie, formateur des catégories U10 à U16."],
    [`${IMG}/staff-4.jpg`, "Responsable Comm.", "Administration", "Gestion des partenariats, communication digitale et relations presse du club."],
  ];
  return (
    <SectionShell id="staff" eyebrow="Direction & Encadrement" title={<>Staff Technique <span className="gradient-gold">& Administratif</span></>} subtitle="Une équipe d'experts au service de l'excellence des échecs.">
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {staff.map(([img, name, role, bio]) => (
          <article key={name} className="surface-card rounded-2xl overflow-hidden hover:border-gold/40 transition group">
            <div className="aspect-square overflow-hidden">
              <img src={img} alt={name} loading="lazy" className="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
            </div>
            <div className="p-5">
              <p className="eyebrow mb-2">{role}</p>
              <h3 className="font-display font-bold text-lg mb-2">{name}</h3>
              <p className="text-xs text-muted-foreground leading-relaxed">{bio}</p>
            </div>
          </article>
        ))}
      </div>
    </SectionShell>
  );
}

/* ------------------------------------------------------------ PARTENARIAT */
function Partenariat() {
  const perks: Array<[string, string, string]> = [
    ["🏆", "Visibilité Nationale", "Tournois FRME / FIDE"],
    ["📱", "Réseaux Sociaux", "Communauté engagée"],
    ["🤝", "Impact Social", "Éducation jeunesse"],
  ];
  return (
    <section id="partenariat" className="py-24 px-6">
      <div className="max-w-7xl mx-auto text-center">
        <div className="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-gold/40 bg-background/60 backdrop-blur mb-10 gold-glow">
          <span className="text-gold" aria-hidden="true">★</span>
          <span className="text-xs tracking-[0.35em] uppercase text-gold font-royal">Partenariat</span>
        </div>
        <h2 className="text-4xl md:text-6xl font-bold mb-2">Devenir Partenaire</h2>
        <h2 className="text-4xl md:text-6xl font-bold gradient-gold mb-8">des Champions</h2>
        <p className="font-display text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto mb-14 leading-relaxed">
          Associez votre marque à l'excellence d'Agadir. <span className="text-foreground font-semibold">Najah Souss Echecs</span> — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires.
        </p>
        <div className="grid md:grid-cols-3 gap-6 mb-14 max-w-5xl mx-auto">
          {perks.map(([icon, title, desc]) => (
            <article key={title} className="surface-card rounded-xl p-8 hover:border-gold/40 transition">
              <div className="text-4xl mb-4" aria-hidden="true">{icon}</div>
              <h3 className="font-display font-bold text-lg text-gold mb-1">{title}</h3>
              <p className="text-sm text-muted-foreground">{desc}</p>
            </article>
          ))}
        </div>
        <div className="flex flex-wrap justify-center gap-4">
          <a href="/assets/Dossier_Sponsoring_Najah_Souss.pdf" className="inline-flex items-center gap-3 px-8 py-4 rounded-md bg-gradient-gold text-primary-foreground font-semibold tracking-wide gold-shadow hover:scale-105 transition">
            <span aria-hidden="true">⬇</span> TÉLÉCHARGER LE DOSSIER SPONSORING
          </a>
          <a href="#contact" className="inline-flex items-center px-8 py-4 rounded-md border border-foreground/80 text-foreground font-semibold tracking-wide hover:bg-foreground hover:text-background transition">
            NOUS CONTACTER
          </a>
        </div>
      </div>
    </section>
  );
}

/* -------------------------------------------------------------- CONTACT */
function Contact() {
  const info: Array<[string, string, string]> = [
    ["📍", "Adresse", "Local Najah Souss Echecs, Corniche d'Agadir"],
    ["✉️", "Email", "najahsoussechecs@gmail.com"],
    ["📞", "Téléphone", "+212 6XX XXX XXX"],
  ];
  const fields: Array<[string, string]> = [["Nom complet *", "text"], ["Email *", "email"], ["Téléphone", "tel"]];
  return (
    <SectionShell id="contact" eyebrow="Contact" title={<>Rejoignez <span className="gradient-gold">Najah Souss Echecs</span></>} subtitle="Une question, une inscription ou un partenariat ? Nous sommes là pour vous.">
      <div className="grid md:grid-cols-2 gap-10">
        <div className="space-y-6">
          {info.map(([icon, label, value]) => (
            <div key={label} className="flex gap-4 surface-card rounded-xl p-5">
              <div className="size-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-xl" aria-hidden="true">{icon}</div>
              <div>
                <p className="text-xs uppercase tracking-widest text-gold mb-1">{label}</p>
                <p className="font-medium">{value}</p>
              </div>
            </div>
          ))}
          <div className="surface-card rounded-xl p-6">
            <h3 className="font-display font-bold text-lg mb-4">🏅 Comment s'inscrire ?</h3>
            <ol className="space-y-3 text-sm text-muted-foreground">
              {["Remplissez le formulaire ci-contre", "Un responsable vous contactera sous 48h", "Participez à une séance d'essai gratuite", "Rejoignez officiellement le club !"].map((s, i) => (
                <li key={s} className="flex gap-3"><span className="size-6 shrink-0 rounded-full bg-gold text-primary-foreground flex items-center justify-center text-xs font-bold">{i + 1}</span>{s}</li>
              ))}
            </ol>
          </div>
        </div>
        <form className="surface-card rounded-2xl p-8 space-y-5">
          {fields.map(([label, type]) => (
            <div key={label}>
              <label className="block text-xs uppercase tracking-widest text-gold mb-2">{label}</label>
              <input type={type} className="w-full px-4 py-3 rounded-md bg-input border border-border focus:border-gold focus:outline-none transition" />
            </div>
          ))}
          <div>
            <label className="block text-xs uppercase tracking-widest text-gold mb-2">Message *</label>
            <textarea rows={4} className="w-full px-4 py-3 rounded-md bg-input border border-border focus:border-gold focus:outline-none transition resize-none" />
          </div>
          <button type="button" className="w-full py-4 rounded-md bg-gold text-primary-foreground font-semibold gold-shadow hover:opacity-90 transition">Envoyer ma demande ♞</button>
        </form>
      </div>
    </SectionShell>
  );
}

/* ---------------------------------------------------------------- FOOTER */
function SiteFooter() {
  return (
    <footer id="site-footer" className="border-t border-border py-12 px-6">
      <div className="max-w-7xl mx-auto grid md:grid-cols-3 gap-8 items-center">
        <div className="flex items-center gap-3">
          <div className="size-11 rounded-md bg-gold flex items-center justify-center text-primary-foreground font-bold text-xl" aria-hidden="true">♞</div>
          <div>
            <div className="font-display font-bold">Najah Souss Echecs</div>
            <div className="text-xs text-muted-foreground tracking-widest uppercase">Champions du Maroc 2025 · Depuis 1987</div>
          </div>
        </div>
        <p className="text-sm text-muted-foreground text-center font-display italic">« Aucun coup ne doit être joué sans but »</p>
        <nav aria-label="Réseaux sociaux" className="flex md:justify-end gap-4 text-sm text-muted-foreground">
          <a href="#" className="hover:text-gold">Facebook</a>
          <a href="#" className="hover:text-gold">Instagram</a>
          <a href="#" className="hover:text-gold">YouTube</a>
        </nav>
      </div>
      <p className="max-w-7xl mx-auto mt-8 text-center text-xs text-muted-foreground">© 2025 Najah Souss Echecs. Tous droits réservés.</p>
    </footer>
  );
}

/* ------------------------------------------------------------------ PAGE */
function Index() {
  return (
    <div className="min-h-screen bg-background text-foreground">
      <SiteHeader />
      <main>
        <Hero />
        <About />
        <Palmares />
        <MurChampions />
        <Academie />
        <Galerie />
        <Evenements />
        <Presse />
        <Actualite />
        <StaffSection />
        <Partenariat />
        <Contact />
      </main>
      <SiteFooter />
    </div>
  );
}
