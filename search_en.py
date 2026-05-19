# -*- coding: utf-8 -*-
import os, sys, re, subprocess

sys.stdout = open(sys.stdout.fileno(), 'w', encoding='utf-8', closefd=False)

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
PHP   = r'C:\xampp\php\php.exe'

# Mapping FR -> EN
translations = {
    "Agadir · Maroc — Depuis 1987": "Agadir · Morocco — Since 1987",
    "NAJAH SOUSS ECHECS": "Najah Souss Chess Club",
    "Champions du Maroc 2025": "Morocco Champions 2025",
    "L'excellence des échecs à Agadir.": "Chess Excellence in Agadir.",
    "Formation, Compétition et Culture.": "Training, Competition, and Culture.",
    "♞ Rejoindre le Club": "♞ Join the Club",
    "Voir le Palmarès": "View Achievements",
    "Coupe du Trône 2025": "2025 Throne Cup",
    "VAINQUEURS DE LA COUPE DU TRONE 2025": "2025 THRONE CUP CHAMPIONS",
    "Médailles d'Or 2025-26": "Gold Medals 2025-26",
    "Affilié": "Affiliated",
    "Année de fondation": "Founded In",
    "S'INSCRIRE": "REGISTER",
    "À propos": "About Us",
    "À PROPOS": "ABOUT US",
    "D'EXCELLENCE": "OUR EXCELLENCE",
    "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987.": "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987.",
    "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale.": "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage.",
    "« Aucun coup ne doit être joué sans but. »": "« No move should be played without a purpose. »",
    "Affilié FRME": "FRME Affiliated",
    "Reconnu FIDE": "FIDE Recognized",
    "Agadir, Maroc": "Agadir, Morocco",
    "Depuis 1987": "Since 1987",
    "Éducation": "Education",
    "Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité.": "Chess as an educational tool to develop focus, logic, and creativity.",
    "Compétition": "Competition",
    "Participation aux tournois régionaux, nationaux et internationaux homologués FIDE/FRME.": "Participation in FIDE/FRME rated regional, national, and international tournaments.",
    "Fair-play": "Sportsmanship",
    "Respect mutuel, éthique irréprochable et esprit sportif au cœur de chaque partie.": "Mutual respect, impeccable ethics, and sportsmanship at the heart of every game.",
    "Notre Palmarès": "Our Achievements",
    "Coupe du Trône 2025, 8 médailles d'or et 4 sélections nationales — l'excellence de Najah Souss Echecs.": "2025 Throne Cup, 8 gold medals, and 4 national team selections — the standard of excellence at Najah Souss Chess.",
    "Mur des Champions": "Wall of Champions",
    "Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs.": "The faces of our excellence — those who proudly wear the colors of Najah Souss Chess.",
    "Voir tous les champions": "View all champions",
    "Notre Équipe - Classement FIDE": "Our Team - FIDE Ratings",
    "TITRE MAJEUR": "MAJOR TITLE",
    "ACADEMIE & FORMATION": "ACADEMY & TRAINING",
    "Programmes pédagogiques pour tous les niveaux, encadrés par des entraîneurs certifiés et des joueurs classés FIDE.": "Educational programs for all levels, led by certified coaches and FIDE-rated players.",
    "Débutant": "Beginner",
    "Les Pions": "The Pawns",
    "Intermédiaire": "Intermediate",
    "Les Fous & Tours": "Bishops & Rooks",
    "Avancé": "Advanced",
    "Les Dames & Rois": "Queens & Kings",
    "Choisissez la formule qui vous convient et progressez à votre rythme avec nos coachs FIDE.": "Choose the program that suits you and progress at your own pace with our FIDE coaches.",
    "Formez-vous avec des Experts": "Train with Experts",
    "Demander un cours d'essai": "Request a trial class",
    "Prêt à relever le défi": "Ready for the challenge",
    "Affrontez des joueurs du monde entier ou entrainez-vous contre l'IA sur notre espace dédie": "Face players from around the world or practice against AI in our dedicated arena.",
    "Jouer aux Echecs Maintenant": "Play Chess Now",
    "Galerie": "Gallery",
    "Galerie Najah Souss": "Najah Souss Gallery",
    "Tournois, cérémonies, entraînements et victoires — la vie du club en images.": "Tournaments, ceremonies, training, and victories — club life in pictures.",
    "Voir toute la galerie": "View full gallery",
    "Nos Événements": "Our Events",
    "Des tournois pour tous les niveaux, des compétitions officielles aux événements culturels.": "Tournaments for all levels, from official competitions to cultural events.",
    "CALENDRIER 2025": "2025 CALENDAR",
    "Voir tous les événements": "View all events",
    "Médias & Presse": "Media & Press",
    "Ils parlent de nous": "In the Press",
    "La presse nationale et locale couvre les succès de Najah Souss Echecs.": "National and local press covering the success of Najah Souss Chess.",
    "ARTICLE": "ARTICLE",
    "Voir toute la presse": "View all press coverage",
    "Notre Actualité": "Latest News",
    "Les dernières nouvelles, événements et annonces de Najah Souss Echecs.": "The latest news, events, and announcements from Najah Souss Chess.",
    "Voir toutes les actualités": "View all news",
    "Direction & Encadrement": "Management & Coaching",
    "Staff Technique & Administratif": "Technical & Administrative Staff",
    "Une équipe d'experts au service de l'excellence des échecs.": "A team of experts dedicated to chess excellence.",
    "Supportez votre club": "Support your club",
    "Boutique Officielle": "Official Store",
    "Portez fièrement les couleurs de Najah Souss Echecs avec notre collection exclusive.": "Wear the Najah Souss Chess colors with pride through our exclusive collection.",
    "NOUVEAU": "NEW",
    "Partenariat": "Partnerships",
    "Devenir Partenaire": "Become a Partner",
    "des Champions": "of the Champions",
    "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires.": "Associate your brand with Agadir's standard of excellence. Najah Souss Chess — 2025 Throne Cup Champions — offers national and international visibility to its partners.",
    "Visibilité Nationale": "National Visibility",
    "Tournois FRME / FIDE": "FRME / FIDE Tournaments",
    "Réseaux Sociaux": "Social Media",
    "Communauté engagée": "Engaged Community",
    "Impact Social": "Social Impact",
    "Éducation jeunesse": "Youth Education",
    "TÉLÉCHARGER LE DOSSIER SPONSORING": "DOWNLOAD SPONSORSHIP DECK",
    "NOUS CONTACTER": "CONTACT US",
    "Contact": "Contact",
    "Rejoignez Najah Souss Echecs": "Join Najah Souss Chess",
    "Une question, une inscription ou un partenariat ? Nous sommes là pour vous.": "Got a question, want to register, or discuss a partnership? We are here for you.",
    "Adresse": "Address",
    "Local Najah Souss Echecs, Corniche d'Agadir": "Najah Souss Chess Club, Agadir Corniche",
    "Email": "Email",
    "Téléphone": "Phone",
    "Comment s'inscrire ?": "How to register?",
    "Remplissez le formulaire ci-contre": "Fill out the form",
    "Un responsable vous contactera sous 48h": "A representative will contact you within 48h",
    "Participez à une séance d'essai gratuite": "Attend a free trial session",
    "Rejoignez officiellement le club !": "Officially join the club!",
    "Nom complet *": "Full Name *",
    "Email *": "Email *",
    "Message *": "Message *",
    "Envoyer ma demande ♞": "Send Request ♞",
    "Tous droits réservés.": "All rights reserved."
}

# The files to search
files_to_check = ['front-page.php', 'header.php', 'footer.php']

for filename in files_to_check:
    path = os.path.join(THEME, filename)
    if not os.path.exists(path):
        continue
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    print(f"\n{'='*60}\nSearching in {filename}\n{'='*60}")
    
    # Let's search for fragments of French strings to see how they are formatted
    for fr_text in translations.keys():
        # find the most significant part (e.g., longest word or first 3 words)
        words = fr_text.split()
        if len(words) > 3:
            frag = " ".join(words[:3]).replace("'", "").replace("’", "").replace("«", "").strip()
        else:
            frag = fr_text.replace("'", "").replace("’", "").replace("«", "").strip()
        
        # We also want to find it with apostrophes properly handled
        # Try to find the exact string if possible
        
        fr_escaped = fr_text.replace("'", "\\'")
        fr_escaped_dq = fr_text.replace('"', '\\"')
        
        found = False
        for search_str in [fr_text, fr_escaped, fr_escaped_dq, frag]:
            idx = content.find(search_str)
            if idx != -1:
                context = content[max(0, idx - 40) : min(len(content), idx + len(search_str) + 40)]
                print(f"[FOUND] {fr_text[:30]}... -> {context}")
                found = True
                break
        
        if not found:
            print(f"[NOT FOUND] {fr_text[:50]}")
