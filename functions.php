<?php

function najah_souss_scripts() {
    // We enqueue the main style.css.
    wp_enqueue_style( 'najah-souss-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'najah_souss_scripts' );

function najah_souss_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'najah_souss_setup' );

/**
 * CLEAN SLATE DATABASE RESET (Run Once)
 * Safely deletes all seeded posts to clear language collisions.
 */
function najah_souss_reset_database() {
    if ( ! isset($_GET['reset_ansae_db']) || ! current_user_can('manage_options') ) {
        return;
    }

    $cpts = array('champion', 'staff', 'event', 'presse', 'gallery', 'post');
    $all_posts = get_posts(array(
        'post_type'   => $cpts,
        'numberposts' => -1,
        'post_status' => 'any'
    ));

    foreach ($all_posts as $p) {
        wp_delete_post($p->ID, true);
    }

    // Reset all seeder options
    delete_option('najah_souss_seeder_run');
    delete_option('najah_souss_seeder_run_v2');
    delete_option('najah_souss_seeder_run_v3');
    delete_option('najah_souss_seeder_run_v4');
    delete_option('najah_souss_seeder_run_v5');
    delete_option('najah_souss_seeder_run_v6');
    delete_option('najah_souss_seeder_run_v7');

    wp_die('Database Cleaned! Please remove ?reset_ansae_db from URL and reload.');
}
add_action('init', 'najah_souss_reset_database');

// Include Custom Post Types and ACF Fields
require_once get_template_directory() . '/inc/custom-types-fields.php';

// Include the database seeder â€” always load, gate is internal to seeder
require_once get_template_directory() . '/inc/seeder.php';

/**
 * FIDE Rating Synchronization Logic
 */
function najah_souss_fide_sync() {
    $champions = new WP_Query(array(
        'post_type'      => 'champion',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'fide_id',
                'value'   => '',
                'compare' => '!='
            )
        )
    ));

    if ($champions->have_posts()) {
        while ($champions->have_posts()) {
            $champions->the_post();
            $post_id = get_the_ID();
            $fide_id = get_field('fide_id', $post_id);

            if ($fide_id) {
                $response = wp_remote_get("https://api.chesstools.org/rating/" . esc_attr($fide_id));
                if (!is_wp_error($response)) {
                    $body = wp_remote_retrieve_body($response);
                    $data = json_decode($body, true);
                    if (!empty($data['rating'])) {
                        update_field('elo_rating', intval($data['rating']), $post_id);
                    }
                }
            }
        }
        wp_reset_postdata();
    }
}
add_action('najah_souss_fide_sync_event', 'najah_souss_fide_sync');

// Schedule the daily synchronization
add_action('wp', function() {
    if (!wp_next_scheduled('najah_souss_fide_sync_event')) {
        wp_schedule_event(time(), 'daily', 'najah_souss_fide_sync_event');
    }
});

/**
 * POLYLANG STRING REGISTRATION
 */
function najah_souss_register_polylang_strings() {
    if ( ! function_exists('pll_register_string') ) {
        return;
    }
    $strings = array(
        "Agadir Â· Maroc â€” Depuis 1987", "NAJAH SOUSS ECHECS", "Champions du Maroc 2025",
        "L'excellence des Ã©checs Ã  Agadir.", "Formation, CompÃ©tition et Culture.",
        "â™ž Rejoindre le Club", "Voir le PalmarÃ¨s",
        "Coupe du TrÃ´ne 2025", "MÃ©dailles d'Or 2025-26", "AffiliÃ©", "AnnÃ©e de fondation",
        "Ã€ propos", "Ã€ PROPOS", "D'EXCELLENCE",
        "Najah Souss Echecs est un club d'Ã©checs prestigieux basÃ© Ã  Agadir, au cÅ“ur de la rÃ©gion Souss-Massa, fondÃ© en 1987.",
        "Officiellement affiliÃ© Ã  la FÃ©dÃ©ration Royale Marocaine des Ã‰checs (FRME) et reconnu par la FIDE, notre club reprÃ©sente la rÃ©gion Souss-Massa sur la scÃ¨ne nationale et internationale.",
        "Â« Aucun coup ne doit Ãªtre jouÃ© sans but. Â»",
        "AffiliÃ© FRME", "Reconnu FIDE", "Agadir, Maroc", "Depuis 1987",
        "Ã‰ducation", "Les Ã©checs comme outil pÃ©dagogique pour dÃ©velopper la concentration, la logique et la crÃ©ativitÃ©.",
        "CompÃ©tition", "Participation aux tournois rÃ©gionaux, nationaux et internationaux homologuÃ©s FIDE/FRME.",
        "Fair-play", "Respect mutuel, Ã©thique irrÃ©prochable et esprit sportif au cÅ“ur de chaque partie.",
        "Notre PalmarÃ¨s", "Coupe du TrÃ´ne 2025, 8 mÃ©dailles d'or et 4 sÃ©lections nationales â€” l'excellence de Najah Souss Echecs.",
        "Mur des Champions", "Les visages de notre excellence â€” ceux qui portent les couleurs de Najah Souss Echecs.",
        "Voir tous les champions",
        "Programmes pÃ©dagogiques pour tous les niveaux, encadrÃ©s par des entraÃ®neurs certifiÃ©s et des joueurs classÃ©s FIDE.",
        "DÃ©butant", "Les Pions", "RÃ¨gles & mouvements", "Tactiques simples", "Mat en 1-2 coups", "Parties guidÃ©es",
        "POPULAIRE", "IntermÃ©diaire", "Les Fous & Tours", "Principes d'ouverture", "Combinaisons tactiques", "Fins de partie", "Analyse de parties",
        "AvancÃ©", "Les Dames & Rois", "StratÃ©gie positionnelle", "RÃ©pertoires d'ouvertures", "PrÃ©paration tournois", "Analyse informatique",
        "Choisissez la formule qui vous convient et progressez Ã  votre rythme avec nos coachs FIDE.",
        "Galerie", "Galerie Najah Souss", "Tournois, cÃ©rÃ©monies, entraÃ®nements et victoires â€” la vie du club en images.",
        "Calendrier 2025", "Nos Ã‰vÃ©nements", "Des tournois pour tous les niveaux, des compÃ©titions officielles aux Ã©vÃ©nements culturels.",
        "MÃ©dias & Presse", "Ils parlent de nous", "La presse nationale et locale couvre les succÃ¨s de Najah Souss Echecs.",
        "Notre ActualitÃ©", "Les derniÃ¨res nouvelles, Ã©vÃ©nements et annonces de Najah Souss Echecs.",
        "Direction & Encadrement", "Staff Technique & Administratif", "Une Ã©quipe d'experts au service de l'excellence des Ã©checs.",
        "Voir tout le staff", "Supportez votre club", "Boutique Officielle", "Voir toute la boutique",
        "Portez fiÃ¨rement les couleurs de Najah Souss Echecs avec notre collection exclusive.",
        "Partenariat", "Devenir Partenaire", "des Champions",
        "Associez votre marque Ã  l'excellence d'Agadir. Najah Souss Echecs â€” Vainqueurs de la Coupe du TrÃ´ne 2025 â€” offre une visibilitÃ© nationale et internationale Ã  ses partenaires.",
        "VisibilitÃ© Nationale", "Tournois FRME / FIDE", "RÃ©seaux Sociaux", "CommunautÃ© engagÃ©e", "Impact Social", "Ã‰ducation jeunesse",
        "TÃ‰LÃ‰CHARGER LE DOSSIER SPONSORING", "NOUS CONTACTER", "Contact",
        "Rejoignez Najah Souss Echecs", "Une question, une inscription ou un partenariat ? Nous sommes lÃ  pour vous.",
        "Adresse", "Local Najah Souss Echecs, Corniche d'Agadir", "Email", "TÃ©lÃ©phone",
        "Comment s'inscrire ?", "Remplissez le formulaire ci-contre", "Un responsable vous contactera sous 48h",
        "Participez Ã  une sÃ©ance d'essai gratuite", "Rejoignez officiellement le club !",
        "Nom complet *", "Email *", "Message *", "Envoyer ma demande â™ž", "Tous droits rÃ©servÃ©s."
    );
    foreach ( $strings as $s ) {
        pll_register_string( 'najah-souss', $s, 'najah-souss' );
    }
    // URL strings for multilingual routing
    pll_register_string( 'url_actualites', '/actualites/', 'najah-souss' );
    pll_register_string( 'url_classement', '/classement/', 'najah-souss' );
}
add_action( 'init', 'najah_souss_register_polylang_strings' );

/**
 * THE ULTIMATE EXACT TRANSLATION FILTER (Arabic RTL)
 * Programmatically translates theme strings to Arabic when is_rtl() is true.
 * Uses exact matching + partial/normalized matching for spacing differences.
 */
function najah_souss_theme_translation_filter( $translated_text, $text, $domain ) {
    if ( $domain !== 'najah-souss' ) {
        return $translated_text;
    }

    $is_arabic = ( function_exists('pll_current_language') && pll_current_language() === 'ar' ) || is_rtl();
    if ( ! $is_arabic ) {
        return $translated_text;
    }

    // Full exact-match dictionary
    $dictionary = array(
        "Agadir Â· Maroc â€” Depuis 1987"                         => "Ø£ÙƒØ§Ø¯ÙŠØ± Â· Ø§Ù„Ù…ØºØ±Ø¨ â€” Ù…Ù†Ø° 1987",
        "NAJAH SOUSS ECHECS"                                    => "Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬",
        "Champions du Maroc 2025"                               => "Ø£Ø¨Ø·Ø§Ù„ Ø§Ù„Ù…ØºØ±Ø¨ 2025",
        "L'excellence des Ã©checs Ã  Agadir."                    => "Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ Ø¨Ø£ÙƒØ§Ø¯ÙŠØ±.",
        "Formation, CompÃ©tition et Culture."                    => "ØªÙƒÙˆÙŠÙ†ØŒ Ù…Ù†Ø§ÙØ³Ø©ØŒ ÙˆØ«Ù‚Ø§ÙØ©.",
        "â™ž Rejoindre le Club"                                   => "â™ž Ø§Ù†Ø¶Ù… Ø¥Ù„Ù‰ Ø§Ù„Ù†Ø§Ø¯ÙŠ",
        "Voir le PalmarÃ¨s"                                      => "Ø´Ø§Ù‡Ø¯ Ø³Ø¬Ù„ Ø§Ù„Ø¥Ù†Ø¬Ø§Ø²Ø§Øª",
        "Coupe du TrÃ´ne 2025"                                   => "ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025",
        "MÃ©dailles d'Or 2025-26"                               => "Ù…ÙŠØ¯Ø§Ù„ÙŠØ§Øª Ø°Ù‡Ø¨ÙŠØ© 2025-26",
        "AffiliÃ©"                                               => "Ù…Ù†Ø¶ÙˆÙ",
        "AnnÃ©e de fondation"                                    => "Ø³Ù†Ø© Ø§Ù„ØªØ£Ø³ÙŠØ³",
        "Ã€ propos"                                              => "Ù…Ù† Ù†Ø­Ù†",
        "Ã€ PROPOS"                                              => "Ù…Ù† Ù†Ø­Ù†",
        "D'EXCELLENCE"                                          => "Ù†Ø¨Ø°Ø© Ø¹Ù† Ø§Ù„ØªÙ…ÙŠØ²",
        "Najah Souss Echecs est un club d'Ã©checs prestigieux basÃ© Ã  Agadir, au cÅ“ur de la rÃ©gion Souss-Massa, fondÃ© en 1987." => "Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ Ù‡Ùˆ Ù†Ø§Ø¯ÙŠ Ø¹Ø±ÙŠÙ‚ Ù…Ù‚Ø±Ù‡ ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±ØŒ ÙÙŠ Ù‚Ù„Ø¨ Ø¬Ù‡Ø© Ø³ÙˆØ³ Ù…Ø§Ø³Ø©ØŒ ØªØ£Ø³Ø³ Ø¹Ø§Ù… 1987.",
        "Officiellement affiliÃ© Ã  la FÃ©dÃ©ration Royale Marocaine des Ã‰checs (FRME) et reconnu par la FIDE, notre club reprÃ©sente la rÃ©gion Souss-Massa sur la scÃ¨ne nationale et internationale." => "Ù…Ù†Ø¶ÙˆÙ Ø±Ø³Ù…ÙŠØ§Ù‹ ØªØ­Øª Ù„ÙˆØ§Ø¡ Ø§Ù„Ø¬Ø§Ù…Ø¹Ø© Ø§Ù„Ù…Ù„ÙƒÙŠØ© Ø§Ù„Ù…ØºØ±Ø¨ÙŠØ© Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ (FRME) ÙˆÙ…Ø¹ØªØ±Ù Ø¨Ù‡ Ù…Ù† Ø·Ø±Ù Ø§Ù„Ø§ØªØ­Ø§Ø¯ Ø§Ù„Ø¯ÙˆÙ„ÙŠ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ (FIDE)ØŒ ÙŠÙ…Ø«Ù„ Ù†Ø§Ø¯ÙŠÙ†Ø§ Ø¬Ù‡Ø© Ø³ÙˆØ³ Ù…Ø§Ø³Ø© Ø¹Ù„Ù‰ Ø§Ù„Ø³Ø§Ø­Ø© Ø§Ù„ÙˆØ·Ù†ÙŠØ© ÙˆØ§Ù„Ø¯ÙˆÙ„ÙŠØ©.",
        "Â« Aucun coup ne doit Ãªtre jouÃ© sans but. Â»"           => "Â« Ù„Ø§ ÙŠÙ†Ø¨ØºÙŠ Ù„Ø¹Ø¨ Ø£ÙŠ Ù†Ù‚Ù„Ø© Ø¯ÙˆÙ† Ù‡Ø¯Ù Â»",
        "AffiliÃ© FRME"                                          => "Ù…Ù†Ø¶ÙˆÙ ØªØ­Øª Ù„ÙˆØ§Ø¡ FRME",
        "Reconnu FIDE"                                          => "Ù…Ø¹ØªØ±Ù Ø¨Ù‡ Ù…Ù† FIDE",
        "Agadir, Maroc"                                         => "Ø£ÙƒØ§Ø¯ÙŠØ±ØŒ Ø§Ù„Ù…ØºØ±Ø¨",
        "Depuis 1987"                                           => "Ù…Ù†Ø° Ø¹Ø§Ù… 1987",
        "Ã‰ducation"                                             => "Ø§Ù„ØªØ¹Ù„ÙŠÙ…",
        "Les Ã©checs comme outil pÃ©dagogique pour dÃ©velopper la concentration, la logique et la crÃ©ativitÃ©." => "Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ ÙƒØ£Ø¯Ø§Ø© ØªØ±Ø¨ÙˆÙŠØ© Ù„ØªØ·ÙˆÙŠØ± Ø§Ù„ØªØ±ÙƒÙŠØ²ØŒ Ø§Ù„Ù…Ù†Ø·Ù‚ØŒ ÙˆØ§Ù„Ø¥Ø¨Ø¯Ø§Ø¹.",
        "CompÃ©tition"                                           => "Ø§Ù„Ù…Ù†Ø§ÙØ³Ø©",
        "Participation aux tournois rÃ©gionaux, nationaux et internationaux homologuÃ©s FIDE/FRME." => "Ø§Ù„Ù…Ø´Ø§Ø±ÙƒØ© ÙÙŠ Ø§Ù„Ø¨Ø·ÙˆÙ„Ø§Øª Ø§Ù„Ø¬Ù‡ÙˆÙŠØ©ØŒ Ø§Ù„ÙˆØ·Ù†ÙŠØ©ØŒ ÙˆØ§Ù„Ø¯ÙˆÙ„ÙŠØ© Ø§Ù„Ù…Ø¹ØªÙ…Ø¯Ø© Ù…Ù† Ø·Ø±Ù FIDE/FRME.",
        "Fair-play"                                             => "Ø§Ù„Ø±ÙˆØ­ Ø§Ù„Ø±ÙŠØ§Ø¶ÙŠØ©",
        "Respect mutuel, Ã©thique irrÃ©prochable et esprit sportif au cÅ“ur de chaque partie." => "Ø§Ù„Ø§Ø­ØªØ±Ø§Ù… Ø§Ù„Ù…ØªØ¨Ø§Ø¯Ù„ØŒ Ø§Ù„Ø£Ø®Ù„Ø§Ù‚ Ø§Ù„Ø¹Ø§Ù„ÙŠØ©ØŒ ÙˆØ§Ù„Ø±ÙˆØ­ Ø§Ù„Ø±ÙŠØ§Ø¶ÙŠØ© ÙÙŠ ØµÙ…ÙŠÙ… ÙƒÙ„ Ù…Ø¨Ø§Ø±Ø§Ø©.",
        "Notre PalmarÃ¨s"                                        => "Ø³Ø¬Ù„ Ø§Ù„Ø¥Ù†Ø¬Ø§Ø²Ø§Øª",
        "Coupe du TrÃ´ne 2025, 8 mÃ©dailles d'or et 4 sÃ©lections nationales â€” l'excellence de Najah Souss Echecs." => "ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025: 8 Ù…ÙŠØ¯Ø§Ù„ÙŠØ§Øª Ø°Ù‡Ø¨ÙŠØ© Ùˆ4 Ø§Ø®ØªÙŠØ§Ø±Ø§Øª Ù„Ù„Ù…Ù†ØªØ®Ø¨ Ø§Ù„ÙˆØ·Ù†ÙŠØŒ ØªÙ…ÙŠØ² Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.",
        "Mur des Champions"                                     => "Ø­Ø§Ø¦Ø· Ø§Ù„Ø£Ø¨Ø·Ø§Ù„",
        "Les visages de notre excellence â€” ceux qui portent les couleurs de Najah Souss Echecs." => "ÙˆØ¬ÙˆÙ‡ ØªÙ…ÙŠØ²Ù†Ø§ â€” Ø£ÙˆÙ„Ø¦Ùƒ Ø§Ù„Ø°ÙŠÙ† ÙŠØ­Ù…Ù„ÙˆÙ† Ø£Ù„ÙˆØ§Ù† Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.",
        "Voir tous les champions"                               => "Ø¹Ø±Ø¶ Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø£Ø¨Ø·Ø§Ù„",
        "Programmes pÃ©dagogiques pour tous les niveaux, encadrÃ©s par des entraÃ®neurs certifiÃ©s et des joueurs classÃ©s FIDE." => "Ø¨Ø±Ø§Ù…Ø¬ ØªØ¹Ù„ÙŠÙ…ÙŠØ© Ù„Ø¬Ù…ÙŠØ¹ Ø§Ù„Ù…Ø³ØªÙˆÙŠØ§ØªØŒ ØªØ­Øª Ø¥Ø´Ø±Ø§Ù Ù…Ø¯Ø±Ø¨ÙŠÙ† Ù…Ø¹ØªÙ…Ø¯ÙŠÙ† ÙˆÙ„Ø§Ø¹Ø¨ÙŠÙ† Ù…ØµÙ†ÙÙŠÙ† Ø¯ÙˆÙ„ÙŠØ§Ù‹ (FIDE).",
        "DÃ©butant"                                              => "Ù…Ø¨ØªØ¯Ø¦",
        "Les Pions"                                             => "Ø§Ù„Ø¨ÙŠØ§Ø¯Ù‚",
        "RÃ¨gles & mouvements"                                   => "Ø§Ù„Ù‚ÙˆØ§Ø¹Ø¯ ÙˆØ§Ù„Ø­Ø±ÙƒØ§Øª",
        "Tactiques simples"                                     => "ØªÙƒØªÙŠÙƒØ§Øª Ø¨Ø³ÙŠØ·Ø©",
        "Mat en 1-2 coups"                                      => "ÙƒØ´ Ù…Ø§Øª ÙÙŠ Ù†Ù‚Ù„Ø© Ø£Ùˆ Ù†Ù‚Ù„ØªÙŠÙ†",
        "Parties guidÃ©es"                                       => "Ù…Ø¨Ø§Ø±ÙŠØ§Øª Ù…ÙˆØ¬Ù‡Ø©",
        "POPULAIRE"                                             => "Ø´Ø§Ø¦Ø¹",
        "IntermÃ©diaire"                                         => "Ù…ØªÙˆØ³Ø·",
        "Les Fous & Tours"                                      => "Ø§Ù„Ø£ÙÙŠØ§Ù„ ÙˆØ§Ù„Ù‚Ù„Ø§Ø¹",
        "Principes d'ouverture"                                 => "Ù…Ø¨Ø§Ø¯Ø¦ Ø§Ù„Ø§ÙØªØªØ§Ø­",
        "Combinaisons tactiques"                                => "ØªØ±ÙƒÙŠØ¨Ø§Øª ØªÙƒØªÙŠÙƒÙŠØ©",
        "Fins de partie"                                        => "Ù†Ù‡Ø§ÙŠØ§Øª Ø§Ù„Ù…Ø¨Ø§Ø±ÙŠØ§Øª",
        "Analyse de parties"                                    => "ØªØ­Ù„ÙŠÙ„ Ø§Ù„Ù…Ø¨Ø§Ø±ÙŠØ§Øª",
        "AvancÃ©"                                                => "Ù…ØªÙ‚Ø¯Ù…",
        "Les Dames & Rois"                                      => "Ø§Ù„ÙˆØ²Ø±Ø§Ø¡ ÙˆØ§Ù„Ù…Ù„ÙˆÙƒ",
        "StratÃ©gie positionnelle"                               => "Ø§Ù„Ø§Ø³ØªØ±Ø§ØªÙŠØ¬ÙŠØ© Ø§Ù„Ù…ÙˆØ¶Ø¹ÙŠØ©",
        "RÃ©pertoires d'ouvertures"                              => "Ø§ÙØªØªØ§Ø­ÙŠØ§Øª Ù…ØªÙ†ÙˆØ¹Ø©",
        "PrÃ©paration tournois"                                  => "Ø§Ù„ØªØ­Ø¶ÙŠØ± Ù„Ù„Ø¨Ø·ÙˆÙ„Ø§Øª",
        "Analyse informatique"                                  => "Ø§Ù„ØªØ­Ù„ÙŠÙ„ Ø¨Ø§Ø³ØªØ®Ø¯Ø§Ù… Ø§Ù„Ø­Ø§Ø³ÙˆØ¨",
        "Choisissez la formule qui vous convient et progressez Ã  votre rythme avec nos coachs FIDE." => "Ø§Ø®ØªØ± Ø§Ù„ØµÙŠØºØ© Ø§Ù„ØªÙŠ ØªÙ†Ø§Ø³Ø¨Ùƒ ÙˆØªÙ‚Ø¯Ù… Ø¨Ø§Ù„ÙˆØªÙŠØ±Ø© Ø§Ù„ØªÙŠ ØªØ±ÙŠØ­Ùƒ Ù…Ø¹ Ù…Ø¯Ø±Ø¨ÙŠÙ†Ø§ Ø§Ù„Ù…Ø¹ØªÙ…Ø¯ÙŠÙ† Ù…Ù† FIDE.",
        "Galerie"                                               => "Ø§Ù„Ù…Ø¹Ø±Ø¶",
        "Galerie Najah Souss"                                   => "Ù…Ø¹Ø±Ø¶ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³",
        "Tournois, cÃ©rÃ©monies, entraÃ®nements et victoires â€” la vie du club en images." => "Ø¨Ø·ÙˆÙ„Ø§ØªØŒ Ø­ÙÙ„Ø§ØªØŒ ØªØ¯Ø§Ø±ÙŠØ¨ ÙˆØ§Ù†ØªØµØ§Ø±Ø§Øª â€” Ø­ÙŠØ§Ø© Ø§Ù„Ù†Ø§Ø¯ÙŠ Ø¨Ø§Ù„ØµÙˆØ±.",
        "Calendrier 2025"                                       => "Ø±ÙˆØ²Ù†Ø§Ù…Ø© 2025",
        "Nos Ã‰vÃ©nements"                                        => "Ø£Ù†Ø´Ø·ØªÙ†Ø§ ÙˆÙØ¹Ø§Ù„ÙŠØ§ØªÙ†Ø§",
        "Des tournois pour tous les niveaux, des compÃ©titions officielles aux Ã©vÃ©nements culturels." => "Ø¨Ø·ÙˆÙ„Ø§Øª Ù„Ø¬Ù…ÙŠØ¹ Ø§Ù„Ù…Ø³ØªÙˆÙŠØ§ØªØŒ Ù…Ù† Ø§Ù„Ù…Ù†Ø§ÙØ³Ø§Øª Ø§Ù„Ø±Ø³Ù…ÙŠØ© Ø¥Ù„Ù‰ Ø§Ù„ÙØ¹Ø§Ù„ÙŠØ§Øª Ø§Ù„Ø«Ù‚Ø§ÙÙŠØ©.",
        "MÃ©dias & Presse"                                       => "Ø§Ù„Ø¥Ø¹Ù„Ø§Ù… ÙˆØ§Ù„ØµØ­Ø§ÙØ©",
        "Ils parlent de nous"                                   => "ØªØºØ·ÙŠØ© Ø§Ù„ØµØ­Ø§ÙØ©",
        "La presse nationale et locale couvre les succÃ¨s de Najah Souss Echecs." => "Ø§Ù„ØµØ­Ø§ÙØ© Ø§Ù„ÙˆØ·Ù†ÙŠØ© ÙˆØ§Ù„Ù…Ø­Ù„ÙŠØ© ØªØºØ·ÙŠ Ù†Ø¬Ø§Ø­Ø§Øª Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.",
        "Notre ActualitÃ©"                                       => "Ø¢Ø®Ø± Ø£Ø®Ø¨Ø§Ø±Ù†Ø§",
        "Les derniÃ¨res nouvelles, Ã©vÃ©nements et annonces de Najah Souss Echecs." => "Ø¢Ø®Ø± Ø§Ù„Ù…Ø³ØªØ¬Ø¯Ø§ØªØŒ Ø§Ù„Ø£Ù†Ø´Ø·Ø© ÙˆØ§Ù„Ø¥Ø¹Ù„Ø§Ù†Ø§Øª Ø§Ù„Ø®Ø§ØµØ© Ø¨Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.",
        "Direction & Encadrement"                               => "Ø§Ù„Ø¥Ø¯Ø§Ø±Ø© ÙˆØ§Ù„ØªØ£Ø·ÙŠØ±",
        "Staff Technique & Administratif"                       => "Ø§Ù„Ø·Ø§Ù‚Ù… Ø§Ù„ØªÙ‚Ù†ÙŠ ÙˆØ§Ù„Ø¥Ø¯Ø§Ø±ÙŠ",
        "Une Ã©quipe d'experts au service de l'excellence des Ã©checs." => "ÙØ±ÙŠÙ‚ Ù…Ù† Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡ ÙÙŠ Ø®Ø¯Ù…Ø© Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬.",
        "Voir tout le staff"                                    => "Ø¹Ø±Ø¶ ÙƒÙ„ Ø§Ù„Ø·Ø§Ù‚Ù…",
        "Supportez votre club"                                  => "Ø§Ø¯Ø¹Ù… Ù†Ø§Ø¯ÙŠÙƒ",
        "Boutique Officielle"                                   => "Ø§Ù„Ù…ØªØ¬Ø± Ø§Ù„Ø±Ø³Ù…ÙŠ",
        "Voir toute la boutique"                                => "Ø¹Ø±Ø¶ ÙƒÙ„ Ø§Ù„Ù…ØªØ¬Ø±",
        "Portez fiÃ¨rement les couleurs de Najah Souss Echecs avec notre collection exclusive." => "Ø§Ø±ØªØ¯Ù Ø¨ÙØ®Ø± Ø£Ù„ÙˆØ§Ù† Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ Ù…Ø¹ ØªØ´ÙƒÙŠÙ„ØªÙ†Ø§ Ø§Ù„Ø­ØµØ±ÙŠØ©.",
        "Partenariat"                                           => "Ø§Ù„Ø´Ø±Ø§ÙƒØ§Øª",
        "Devenir Partenaire"                                    => "ÙƒÙ† Ø´Ø±ÙŠÙƒØ§Ù‹",
        "des Champions"                                         => "Ù„Ù„Ø£Ø¨Ø·Ø§Ù„",
        "Associez votre marque Ã  l'excellence d'Agadir. Najah Souss Echecs â€” Vainqueurs de la Coupe du TrÃ´ne 2025 â€” offre une visibilitÃ© nationale et internationale Ã  ses partenaires." => "Ø§Ø±Ø¨Ø· Ø¹Ù„Ø§Ù…ØªÙƒ Ø§Ù„ØªØ¬Ø§Ø±ÙŠØ© Ø¨Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±. ÙŠÙ‚Ø¯Ù… Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ - Ø£Ø¨Ø·Ø§Ù„ ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025 - Ø±Ø¤ÙŠØ© ÙˆØ¥Ø´Ø¹Ø§Ø¹Ø§Ù‹ ÙˆØ·Ù†ÙŠØ§Ù‹ ÙˆØ¯ÙˆÙ„ÙŠØ§Ù‹ Ù„Ø´Ø±ÙƒØ§Ø¦Ù‡.",
        "VisibilitÃ© Nationale"                                  => "Ø¥Ø´Ø¹Ø§Ø¹ ÙˆØ·Ù†ÙŠ",
        "Tournois FRME / FIDE"                                  => "Ø¨Ø·ÙˆÙ„Ø§Øª Ø§Ù„Ø¬Ø§Ù…Ø¹Ø© Ø§Ù„Ù…Ù„ÙƒÙŠØ© ÙˆØ§Ù„Ø§ØªØ­Ø§Ø¯ Ø§Ù„Ø¯ÙˆÙ„ÙŠ",
        "RÃ©seaux Sociaux"                                       => "Ø´Ø¨ÙƒØ§Øª Ø§Ù„ØªÙˆØ§ØµÙ„ Ø§Ù„Ø§Ø¬ØªÙ…Ø§Ø¹ÙŠ",
        "CommunautÃ© engagÃ©e"                                    => "Ù…Ø¬ØªÙ…Ø¹ Ù…ØªÙØ§Ø¹Ù„",
        "Impact Social"                                         => "ØªØ£Ø«ÙŠØ± Ø§Ø¬ØªÙ…Ø§Ø¹ÙŠ",
        "Ã‰ducation jeunesse"                                    => "ØªØ«Ù‚ÙŠÙ ÙˆØªÙˆØ¹ÙŠØ© Ø§Ù„Ø´Ø¨Ø§Ø¨",
        "TÃ‰LÃ‰CHARGER LE DOSSIER SPONSORING"                    => "ØªØ­Ù…ÙŠÙ„ Ù…Ù„Ù Ø§Ù„Ø±Ø¹Ø§ÙŠØ©/Ø§Ù„Ø§Ø³ØªØ´Ù‡Ø§Ø±",
        "NOUS CONTACTER"                                        => "Ø§ØªØµÙ„ Ø¨Ù†Ø§",
        "Contact"                                               => "Ø§ØªØµÙ„ Ø¨Ù†Ø§",
        "Rejoignez Najah Souss Echecs"                          => "Ø§Ù†Ø¶Ù… Ø¥Ù„Ù‰ Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³",
        "Une question, une inscription ou un partenariat ? Nous sommes lÃ  pour vous." => "Ù‡Ù„ Ù„Ø¯ÙŠÙƒ Ø³Ø¤Ø§Ù„ØŒ ØªØ±ØºØ¨ ÙÙŠ Ø§Ù„ØªØ³Ø¬ÙŠÙ„ Ø£Ùˆ Ø¹Ù‚Ø¯ Ø´Ø±Ø§ÙƒØ©ØŸ Ù†Ø­Ù† Ù‡Ù†Ø§ Ù…Ù† Ø£Ø¬Ù„Ùƒ.",
        "Adresse"                                               => "Ø§Ù„Ø¹Ù†ÙˆØ§Ù†",
        "Local Najah Souss Echecs, Corniche d'Agadir"          => "Ù…Ù‚Ø± Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ØŒ ÙƒÙˆØ±Ù†ÙŠØ´ Ø£ÙƒØ§Ø¯ÙŠØ±.",
        "Email"                                                 => "Ø§Ù„Ø¨Ø±ÙŠØ¯ Ø§Ù„Ø¥Ù„ÙƒØªØ±ÙˆÙ†ÙŠ",
        "TÃ©lÃ©phone"                                             => "Ø§Ù„Ù‡Ø§ØªÙ",
        "Comment s'inscrire ?"                                  => "ÙƒÙŠÙ ØªØ³Ø¬Ù„ØŸ",
        "Remplissez le formulaire ci-contre"                    => "Ø§Ù…Ù„Ø£ Ø§Ù„Ù†Ù…ÙˆØ°Ø¬",
        "Un responsable vous contactera sous 48h"               => "Ø³ÙŠØªÙˆØ§ØµÙ„ Ù…Ø¹Ùƒ Ø£Ø­Ø¯ Ø§Ù„Ù…Ø³Ø¤ÙˆÙ„ÙŠÙ† Ø®Ù„Ø§Ù„ 48 Ø³Ø§Ø¹Ø©",
        "Participez Ã  une sÃ©ance d'essai gratuite"              => "Ø´Ø§Ø±Ùƒ ÙÙŠ Ø­ØµØ© ØªØ¬Ø±ÙŠØ¨ÙŠØ© Ù…Ø¬Ø§Ù†ÙŠØ©",
        "Rejoignez officiellement le club !"                    => "Ø§Ù†Ø¶Ù… Ø±Ø³Ù…ÙŠØ§Ù‹ Ø¥Ù„Ù‰ Ø§Ù„Ù†Ø§Ø¯ÙŠ!",
        "Nom complet *"                                         => "Ø§Ù„Ø§Ø³Ù… Ø§Ù„ÙƒØ§Ù…Ù„ *",
        "Email *"                                               => "Ø§Ù„Ø¨Ø±ÙŠØ¯ Ø§Ù„Ø¥Ù„ÙƒØªØ±ÙˆÙ†ÙŠ *",
        "Message *"                                             => "Ø§Ù„Ø±Ø³Ø§Ù„Ø© *",
        "Envoyer ma demande â™ž"                                  => "Ø¥Ø±Ø³Ø§Ù„ Ø§Ù„Ø·Ù„Ø¨ â™ž",
        "Tous droits rÃ©servÃ©s."                                 => "Ø­Ù‚ÙˆÙ‚ Ø§Ù„Ù†Ø´Ø± Â© 2026 Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬. Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø­Ù‚ÙˆÙ‚ Ù…Ø­ÙÙˆØ¸Ø©.",
        // Additional UI strings
        "Voir tous"                                             => "Ø¹Ø±Ø¶ Ø§Ù„ÙƒÙ„",
        "Lire l'article"                                        => "Ø§Ù‚Ø±Ø£ Ø§Ù„Ù…Ù‚Ø§Ù„",
        "S'INSCRIRE"                                            => "Ø§Ù„ØªØ³Ø¬ÙŠÙ„ ÙÙŠ Ø§Ù„Ù†Ø§Ø¯ÙŠ",
        "S'inscrire"                                            => "Ø§Ù„ØªØ³Ø¬ÙŠÙ„ ÙÙŠ Ø§Ù„Ù†Ø§Ø¯ÙŠ",
        "Acheter"                                               => "Ø§Ø´ØªØ±Ù Ø§Ù„Ø¢Ù†",
        "Lire la suite"                                         => "Ø§Ù‚Ø±Ø£ Ø§Ù„Ù…Ø²ÙŠØ¯",
        "Retour"                                                => "Ø§Ù„Ø¹ÙˆØ¯Ø©",
        "Imprimer"                                              => "Ø·Ø¨Ø§Ø¹Ø©",
        "Partager"                                              => "Ù…Ø´Ø§Ø±ÙƒØ©",
        "Lien copiÃ© !"                                          => "ØªÙ… Ù†Ø³Ø® Ø§Ù„Ø±Ø§Ø¨Ø·!",
        "JOUEUR"                                                => "Ø§Ù„Ù„Ø§Ø¹Ø¨",
        "FIDE ID"                                               => "Ø±Ù‚Ù… FIDE",
        "CATÃ‰GORIE"                                             => "Ø§Ù„ÙØ¦Ø©",
        "ELO"                                                   => "Ø§Ù„ØªØµÙ†ÙŠÙ",
        "TITRE"                                                 => "Ø§Ù„Ù„Ù‚Ø¨",
        "Salle de Jeu en Ligne"                                 => "Ù‚Ø§Ø¹Ø© Ø§Ù„Ù„Ø¹Ø¨ Ø§Ù„Ø¥Ù„ÙƒØªØ±ÙˆÙ†ÙŠØ©",
        "Encadrement Expert"                                    => "ØªØ£Ø·ÙŠØ± Ø®Ø¨ÙŠØ±",
        "Cours individuels & collectifs disponibles"           => "Ø¯Ø±ÙˆØ³ ÙØ±Ø¯ÙŠØ© ÙˆØ¬Ù…Ø§Ø¹ÙŠØ© Ù…ØªØ§Ø­Ø©",
    );

    // --- Exact match first ---
    if ( isset( $dictionary[ $text ] ) ) {
        return $dictionary[ $text ];
    }

    // --- Normalized match (trim + collapse whitespace) to handle template spacing differences ---
    $normalized_text = preg_replace('/\s+/', ' ', trim($text));
    foreach ( $dictionary as $fr => $ar ) {
        $normalized_key = preg_replace('/\s+/', ' ', trim($fr));
        if ( $normalized_text === $normalized_key ) {
            return $ar;
        }
    }

    return $translated_text;
}
add_filter( 'gettext', 'najah_souss_theme_translation_filter', 20, 3 );

function ansae_t($french_text) {
    if (!function_exists('pll_current_language')) {
        return $french_text;
    }
    
    $lang = pll_current_language();
    
    if ($lang == 'fr') {
        return $french_text;
    }

    static $ansae_cache = array();
    if (isset($ansae_cache[$lang][$french_text])) {
        return $ansae_cache[$lang][$french_text];
    }

    static $normalize = null;
    if ($normalize === null) {
        $normalize = function($str) {
            $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
            $str = str_replace(array("'", "â€™", "\'", "\â€™"), "'", $str);
            $str = preg_replace('/\s+/', ' ', $str);
        $str = mb_strtolower(trim($str), 'UTF-8');
        $unwanted_array = array(
            'Å¡'=>'s', 'Å¾'=>'z', 'Ã '=>'a', 'Ã¡'=>'a', 'Ã¢'=>'a', 'Ã£'=>'a', 'Ã¤'=>'a', 'Ã¥'=>'a', 'Ã¦'=>'a', 'Ã§'=>'c',
            'Ã¨'=>'e', 'Ã©'=>'e', 'Ãª'=>'e', 'Ã«'=>'e', 'Ã¬'=>'i', 'Ã­'=>'i', 'Ã®'=>'i', 'Ã¯'=>'i', 'Ã±'=>'n', 'Ã²'=>'o', 'Ã³'=>'o', 'Ã´'=>'o', 'Ãµ'=>'o',
            'Ã¶'=>'o', 'Ã¸'=>'o', 'Ã¹'=>'u', 'Ãº'=>'u', 'Ã»'=>'u', 'Ã½'=>'y', 'Ã¾'=>'b', 'Ã¿'=>'y'
        );
        $str = strtr($str, $unwanted_array);
        // Remove weird characters and punctuation to make it purely alphanumeric text
        $str = preg_replace('/[^a-z0-9\s]/', '', $str);
        // Collapse spaces again
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
        };
    }
    
    $norm_key = $normalize($french_text);

    static $normalized_dict = null;
    static $normalized_mappings = null;

    if ($normalized_dict === null) {
        $dict = array(

        'Agadir Â· Maroc â€” Depuis 1987' => array('ar' => 'Ø£ÙƒØ§Ø¯ÙŠØ± Â· Ø§Ù„Ù…ØºØ±Ø¨ â€” Ù…Ù†Ø° 1987', 'en' => 'Agadir Â· Morocco â€” Since 1987'),
        // â”€â”€ FIDE TABLE HEADERS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        'JOUEUR'    => array('ar' => 'Ø§Ù„Ù„Ø§Ø¹Ø¨',    'en' => 'PLAYER'),
        'FIDE ID'   => array('ar' => 'Ø±Ù‚Ù… FIDE',  'en' => 'FIDE ID'),
        'CATÃ‰GORIES'=> array('ar' => 'Ø§Ù„ÙØ¦Ø©',     'en' => 'CATEGORY'),
        'CATÃ‰GORIE' => array('ar' => 'Ø§Ù„ÙØ¦Ø©',     'en' => 'CATEGORY'),
        'STANDARD'  => array('ar' => 'Ù‚ÙŠØ§Ø³ÙŠ',     'en' => 'STANDARD'),
        'RAPID'     => array('ar' => 'Ø³Ø±ÙŠØ¹',      'en' => 'RAPID'),
        'BLITZ'     => array('ar' => 'Ø®Ø§Ø·Ù',      'en' => 'BLITZ'),
        // â”€â”€ ABOUT SECTION â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        'Les Ã©checs comme outil pÃ©dagogique pour dÃ©velopper la concentration, la logique et la crÃ©ativitÃ©.' => array(
            'ar' => 'Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ ÙƒØ£Ø¯Ø§Ø© ØªØ¹Ù„ÙŠÙ…ÙŠØ© Ù„ØªØ·ÙˆÙŠØ± Ø§Ù„ØªØ±ÙƒÙŠØ²ØŒ Ø§Ù„Ù…Ù†Ø·Ù‚ØŒ ÙˆØ§Ù„Ø¥Ø¨Ø¯Ø§Ø¹.',
            'en' => 'Chess as an educational tool to develop concentration, logic, and creativity.'
        ),
        // â”€â”€ PARTNERSHIP PERKS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        'VisibilitÃ© Nationale'  => array('ar' => 'Ø¥Ø´Ø¹Ø§Ø¹ ÙˆØ·Ù†ÙŠ',                     'en' => 'National Visibility'),
        'Tournois FRME / FIDE'  => array('ar' => 'Ø¨Ø·ÙˆÙ„Ø§Øª FRME / FIDE',             'en' => 'FRME / FIDE Tournaments'),
        'RÃ©seaux Sociaux'       => array('ar' => 'Ø´Ø¨ÙƒØ§Øª Ø§Ù„ØªÙˆØ§ØµÙ„ Ø§Ù„Ø§Ø¬ØªÙ…Ø§Ø¹ÙŠ',        'en' => 'Social Networks'),
        'CommunautÃ© engagÃ©e'    => array('ar' => 'Ù…Ø¬ØªÙ…Ø¹ Ù…ØªÙØ§Ø¹Ù„',                   'en' => 'Engaged Community'),
        'Impact Social'         => array('ar' => 'ØªØ£Ø«ÙŠØ± Ù…Ø¬ØªÙ…Ø¹ÙŠ',                   'en' => 'Social Impact'),
        'Ã‰ducation jeunesse'    => array('ar' => 'ØªØ¹Ù„ÙŠÙ… Ø§Ù„Ø´Ø¨Ø§Ø¨',                   'en' => 'Youth Education'),
        'NAJAH SOUSS <span class="gradient-gold">ECHECS</span>' => array('ar' => 'Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬', 'en' => 'Najah Souss <span class="gradient-gold">Chess Club</span>'),
        'Vainqueurs de la Coupe du TrÃ´ne 2025' => array('ar' => 'Ø£Ø¨Ø·Ø§Ù„ ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025', 'en' => '2025 Throne Cup Champions'),
        'Champions du Maroc 2025' => array('ar' => 'Ø£Ø¨Ø·Ø§Ù„ Ø§Ù„Ù…ØºØ±Ø¨ 2025', 'en' => 'Morocco Champions 2025'),
        'L\'excellence des Ã©checs Ã  Agadir.' => array('ar' => 'Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ Ø¨Ø£ÙƒØ§Ø¯ÙŠØ±.', 'en' => 'Chess Excellence in Agadir.'),
        'Formation, CompÃ©tition et Culture.' => array('ar' => 'ØªÙƒÙˆÙŠÙ†ØŒ Ù…Ù†Ø§ÙØ³Ø© ÙˆØ«Ù‚Ø§ÙØ©.', 'en' => 'Training, Competition, and Culture.'),
        'Rejoindre le Club' => array('ar' => 'Ø§Ù†Ø¶Ù… Ø¥Ù„Ù‰ Ø§Ù„Ù†Ø§Ø¯ÙŠ', 'en' => 'Join the Club'),
        'Voir le PalmarÃ¨s' => array('ar' => 'Ø´Ø§Ù‡Ø¯ Ø³Ø¬Ù„ Ø§Ù„Ø¥Ù†Ø¬Ø§Ø²Ø§Øª', 'en' => 'View our Achievements'),
        'Coupe du TrÃ´ne 2025' => array('ar' => 'ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025', 'en' => '2025 Throne Cup'),
        'MÃ©dailles d\'Or 2025-26' => array('ar' => 'Ù…ÙŠØ¯Ø§Ù„ÙŠØ§Øª Ø°Ù‡Ø¨ÙŠØ© 2025-26', 'en' => 'Gold Medals 2025-26'),
        'Retour Ã  l\'accueil' => array('ar' => 'Ø§Ù„Ø¹ÙˆØ¯Ø© Ù„Ù„Ø±Ø¦ÙŠØ³ÙŠØ©', 'en' => 'Back to Home'),
        'Profil FIDE Officiel' => array('ar' => 'Ø§Ù„Ù…Ù„Ù Ø§Ù„Ø´Ø®ØµÙŠ FIDE', 'en' => 'Official FIDE Profile'),
        'Date & Heure' => array('ar' => 'Ø§Ù„ØªØ§Ø±ÙŠØ® ÙˆØ§Ù„ÙˆÙ‚Øª', 'en' => 'Date & Time'),
        'Lieu' => array('ar' => 'Ø§Ù„Ù…ÙƒØ§Ù†', 'en' => 'Location'),
        'Taille du texte' => array('ar' => 'Ø­Ø¬Ù… Ø§Ù„Ù†Øµ', 'en' => 'Text Size'),
        'AffiliÃ©' => array('ar' => 'Ø¹Ø¶Ùˆ', 'en' => 'Affiliated'),
        'AnnÃ©e de fondation' => array('ar' => 'Ø³Ù†Ø© Ø§Ù„ØªØ£Ø³ÙŠØ³', 'en' => 'Founded'),
        'Ã€ propos' => array('ar' => 'Ù…Ù† Ù†Ø­Ù†', 'en' => 'About Us'),
        'Ã€ PROPOS' => array('ar' => 'Ù…Ù† Ù†Ø­Ù†', 'en' => 'ABOUT US'),
        'D\'EXCELLENCE' => array('ar' => 'ÙˆØ§Ù„ØªÙ…ÙŠØ²', 'en' => 'OF EXCELLENCE'),
        'Â« Aucun coup ne doit Ãªtre jouÃ© sans but Â»' => array('ar' => 'Â« Ù„Ø§ ÙŠØ¬Ø¨ Ù„Ø¹Ø¨ Ø£ÙŠ Ù†Ù‚Ù„Ø© Ø¨Ø¯ÙˆÙ† Ù‡Ø¯Ù Â»', 'en' => 'Â« No move should be played without a purpose Â»'),
        'AffiliÃ© FRME' => array('ar' => 'Ø¹Ø¶Ùˆ ÙÙŠ Ø§Ù„Ø¬Ø§Ù…Ø¹Ø© Ø§Ù„Ù…Ù„ÙƒÙŠØ©', 'en' => 'FRME Affiliated'),
        'Reconnu FIDE' => array('ar' => 'Ù…Ø¹ØªØ±Ù Ø¨Ù‡ Ù…Ù† FIDE', 'en' => 'FIDE Recognized'),
        'Agadir, Maroc' => array('ar' => 'Ø£ÙƒØ§Ø¯ÙŠØ±ØŒ Ø§Ù„Ù…ØºØ±Ø¨', 'en' => 'Agadir, Morocco'),
        'Depuis 1987' => array('ar' => 'Ù…Ù†Ø° 1987', 'en' => 'Since 1987'),
        'Ã‰ducation' => array('ar' => 'Ø§Ù„ØªØ¹Ù„ÙŠÙ…', 'en' => 'Education'),
        'CompÃ©tition' => array('ar' => 'Ø§Ù„Ù…Ù†Ø§ÙØ³Ø©', 'en' => 'Competition'),
        'Fair-play' => array('ar' => 'Ø§Ù„Ø±ÙˆØ­ Ø§Ù„Ø±ÙŠØ§Ø¶ÙŠØ©', 'en' => 'Sportsmanship'),
        'Notre PalmarÃ¨s' => array('ar' => 'Ø³Ø¬Ù„ Ø§Ù„Ø¥Ù†Ø¬Ø§Ø²Ø§Øª', 'en' => 'Our Achievements'),
        'TITRE MAJEUR' => array('ar' => 'Ø§Ù„Ù„Ù‚Ø¨ Ø§Ù„Ø£Ø¨Ø±Ø²', 'en' => 'MAJOR TITLE'),
        'DÃ©butant' => array('ar' => 'Ù…Ø¨ØªØ¯Ø¦', 'en' => 'Beginner'),
        'Les Pions' => array('ar' => 'Ø§Ù„Ø¨ÙŠØ§Ø¯Ù‚', 'en' => 'The Pawns'),
        'IntermÃ©diaire' => array('ar' => 'Ù…ØªÙˆØ³Ø·', 'en' => 'Intermediate'),
        'Les Fous & Tours' => array('ar' => 'Ø§Ù„Ø£ÙÙŠØ§Ù„ ÙˆØ§Ù„Ù‚Ù„Ø§Ø¹', 'en' => 'Bishops & Rooks'),
        'AvancÃ©' => array('ar' => 'Ù…ØªÙ‚Ø¯Ù…', 'en' => 'Advanced'),
        'Les Dames & Rois' => array('ar' => 'Ø§Ù„ÙˆØ²Ø±Ø§Ø¡ ÙˆØ§Ù„Ù…Ù„ÙˆÙƒ', 'en' => 'Queens & Kings'),
        'Demander un cours d\'essai' => array('ar' => 'Ø·Ù„Ø¨ Ø­ØµØ© ØªØ¬Ø±ÙŠØ¨ÙŠØ©', 'en' => 'Request a trial class'),
        'Galerie' => array('ar' => 'Ù…Ø¹Ø±Ø¶', 'en' => 'Gallery'),
        'Voir toute la galerie' => array('ar' => 'Ø¹Ø±Ø¶ Ø§Ù„Ù…Ø¹Ø±Ø¶ Ø¨Ø§Ù„ÙƒØ§Ù…Ù„', 'en' => 'View full gallery'),
        'Ã‰vÃ©nements' => array('ar' => 'ÙØ¹Ø§Ù„ÙŠØ§Øª', 'en' => 'Events'),
        'Voir tous les Ã©vÃ©nements' => array('ar' => 'Ø¹Ø±Ø¶ Ø¬Ù…ÙŠØ¹ Ø§Ù„ÙØ¹Ø§Ù„ÙŠØ§Øª', 'en' => 'View all events'),
        'MÃ©dias & Presse' => array('ar' => 'Ø§Ù„ØµØ­Ø§ÙØ© ÙˆØ§Ù„Ø¥Ø¹Ù„Ø§Ù…', 'en' => 'Media & Press'),
        'Voir toute la presse' => array('ar' => 'Ø¹Ø±Ø¶ ÙƒÙ„ Ø§Ù„ØªØºØ·ÙŠØ§Øª', 'en' => 'View all press coverage'),
        'Notre ActualitÃ©' => array('ar' => 'Ø£Ø®Ø¨Ø§Ø±Ù†Ø§', 'en' => 'Latest News'),
        'Voir toutes les actualitÃ©s' => array('ar' => 'Ø¹Ø±Ø¶ Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø£Ø®Ø¨Ø§Ø±', 'en' => 'View all news'),
        'Direction & Encadrement' => array('ar' => 'Ø§Ù„Ø¥Ø¯Ø§Ø±Ø© ÙˆØ§Ù„ØªØ£Ø·ÙŠØ±', 'en' => 'Management & Coaching'),
        'Staff Technique' => array('ar' => 'Ø§Ù„Ø·Ø§Ù‚Ù… Ø§Ù„ØªÙ‚Ù†ÙŠ', 'en' => 'Technical'),
        '& Administratif' => array('ar' => 'ÙˆØ§Ù„Ø¥Ø¯Ø§Ø±ÙŠ', 'en' => '& Administrative Staff'),
        'Supportez votre club' => array('ar' => 'Ø§Ø¯Ø¹Ù… Ù†Ø§Ø¯ÙŠÙƒ', 'en' => 'Support your club'),
        'Boutique' => array('ar' => 'Ø§Ù„Ù…ØªØ¬Ø±', 'en' => 'Official'),
        'Officielle' => array('ar' => 'Ø§Ù„Ø±Ø³Ù…ÙŠ', 'en' => 'Store'),
        'NOUVEAU' => array('ar' => 'Ø¬Ø¯ÙŠØ¯', 'en' => 'NEW'),
        'Devenir Partenaire' => array('ar' => 'ÙƒÙ† Ø´Ø±ÙŠÙƒØ§Ù‹', 'en' => 'Become a Partner'),
        'des Champions' => array('ar' => 'Ù„Ù„Ø£Ø¨Ø·Ø§Ù„', 'en' => 'of the Champions'),
        'VisibilitÃ© Nationale' => array('ar' => 'Ø±Ø¤ÙŠØ© ÙˆØ·Ù†ÙŠØ©', 'en' => 'National Visibility'),
        'Tournois FRME / FIDE' => array('ar' => 'Ø¨Ø·ÙˆÙ„Ø§Øª FRME / FIDE', 'en' => 'FRME / FIDE Tournaments'),
        'RÃ©seaux Sociaux' => array('ar' => 'Ø´Ø¨ÙƒØ§Øª Ø§Ù„ØªÙˆØ§ØµÙ„', 'en' => 'Social Media'),
        'CommunautÃ© engagÃ©e' => array('ar' => 'Ù…Ø¬ØªÙ…Ø¹ Ù†Ø´Ø·', 'en' => 'Engaged Community'),
        'Impact Social' => array('ar' => 'ØªØ£Ø«ÙŠØ± Ø§Ø¬ØªÙ…Ø§Ø¹ÙŠ', 'en' => 'Social Impact'),
        'Ã‰ducation jeunesse' => array('ar' => 'ØªØ¹Ù„ÙŠÙ… Ø§Ù„Ø´Ø¨Ø§Ø¨', 'en' => 'Youth Education'),
        'TÃ‰LÃ‰CHARGER LE DOSSIER SPONSORING' => array('ar' => 'ØªØ­Ù…ÙŠÙ„ Ù…Ù„Ù Ø§Ù„Ø±Ø¹Ø§ÙŠØ©', 'en' => 'DOWNLOAD SPONSORSHIP DECK'),
        'Contact' => array('ar' => 'Ø§ØªØµÙ„ Ø¨Ù†Ø§', 'en' => 'Contact'),
        'NOUS CONTACTER' => array('ar' => 'Ø§ØªØµÙ„ Ø¨Ù†Ø§', 'en' => 'CONTACT US'),
        'Adresse' => array('ar' => 'Ø§Ù„Ø¹Ù†ÙˆØ§Ù†', 'en' => 'Address'),
        'Email' => array('ar' => 'Ø§Ù„Ø¨Ø±ÙŠØ¯ Ø§Ù„Ø¥Ù„ÙƒØªØ±ÙˆÙ†ÙŠ', 'en' => 'Email'),
        'TÃ©lÃ©phone' => array('ar' => 'Ø§Ù„Ù‡Ø§ØªÙ', 'en' => 'Phone'),
        'Comment s\'inscrire ?' => array('ar' => 'ÙƒÙŠÙ ØªØ³Ø¬Ù„ØŸ', 'en' => 'How to register?'),
        'Nom complet *' => array('ar' => 'Ø§Ù„Ø§Ø³Ù… Ø§Ù„ÙƒØ§Ù…Ù„ *', 'en' => 'Full Name *'),
        'Email *' => array('ar' => 'Ø§Ù„Ø¨Ø±ÙŠØ¯ Ø§Ù„Ø¥Ù„ÙƒØªØ±ÙˆÙ†ÙŠ *', 'en' => 'Email *'),
        'Message *' => array('ar' => 'Ø§Ù„Ø±Ø³Ø§Ù„Ø© *', 'en' => 'Message *'),
        'Envoyer ma demande â™ž' => array('ar' => 'Ø¥Ø±Ø³Ø§Ù„ Ø·Ù„Ø¨ÙŠ â™ž', 'en' => 'Send Request â™ž'),
        'PalmarÃ¨s' => array('ar' => 'Ù‚Ø§Ø¹Ø© Ø§Ù„Ù…Ø´Ø§Ù‡ÙŠØ±', 'en' => 'Hall of Fame'),
        'Presse' => array('ar' => 'Ø§Ù„ØµØ­Ø§ÙØ©', 'en' => 'Press'),
        'AcadÃ©mie' => array('ar' => 'Ø§Ù„Ø£ÙƒØ§Ø¯ÙŠÙ…ÙŠØ©', 'en' => 'Academy'),
        'Accueil' => array('ar' => 'Ø§Ù„Ø±Ø¦ÙŠØ³ÙŠØ©', 'en' => 'Home'),
        'Le Club' => array('ar' => 'Ø§Ù„Ù†Ø§Ø¯ÙŠ', 'en' => 'The Club'),
        'S\'inscrire' => array('ar' => 'Ø§Ù„ØªØ³Ø¬ÙŠÙ„', 'en' => 'Register'),
        'Tous droits rÃ©servÃ©s.' => array('ar' => 'Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø­Ù‚ÙˆÙ‚ Ù…Ø­ÙÙˆØ¸Ø©.', 'en' => 'All rights reserved.'),
        'RÃ©seaux sociaux' => array('ar' => 'Ø´Ø¨ÙƒØ§Øª Ø§Ù„ØªÙˆØ§ØµÙ„ Ø§Ù„Ø§Ø¬ØªÙ…Ø§Ø¹ÙŠ', 'en' => 'Social Networks'),
        'Champions du Maroc 2025 Â· Depuis 1987' => array('ar' => 'Ø£Ø¨Ø·Ø§Ù„ Ø§Ù„Ù…ØºØ±Ø¨ 2025 Â· Ù…Ù†Ø° 1987', 'en' => 'Morocco Champions 2025 Â· Since 1987'),
        'Echecs Â· Depuis 1987' => array('ar' => 'Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ Â· Ù…Ù†Ø° 1987', 'en' => 'Chess Â· Since 1987'),
        'Partenariat' => array('ar' => 'Ø§Ù„Ø´Ø±Ø§ÙƒØ§Øª', 'en' => 'Partnerships'),
        'Galerie Najah Souss' => array('ar' => 'Ù…Ø¹Ø±Ø¶ ØµÙˆØ± Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³', 'en' => 'Najah Souss Gallery'),
        'Calendrier des Ã‰vÃ©nements' => array('ar' => 'Ø¬Ø¯ÙˆÙ„ ÙØ¹Ø§Ù„ÙŠØ§Øª Ù†Ø§Ø¯ÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬', 'en' => 'Chess Club Events Calendar'),
        'Ils parlent de nous' => array('ar' => 'Ù‡Ù… ÙŠØªØ­Ø¯Ø«ÙˆÙ† Ø¹Ù†Ø§', 'en' => 'They talk about us'),
        'Notre ActualitÃ©' => array('ar' => 'Ø£Ø®Ø¨Ø§Ø±Ù†Ø§', 'en' => 'Latest News'),
        'Staff Technique & Administratif' => array('ar' => 'Ø§Ù„Ø·Ø§Ù‚Ù… Ø§Ù„ÙÙ†ÙŠ ÙˆØ§Ù„Ø¥Ø¯Ø§Ø±ÙŠ', 'en' => 'Technical & Administrative Staff'),
        'Une Ã©quipe d\'experts au service de l\'excellence des Ã©checs.' => array('ar' => 'ÙØ±ÙŠÙ‚ Ù…Ù† Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡ ÙÙŠ Ø®Ø¯Ù…Ø© Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬.', 'en' => 'A team of experts dedicated to chess excellence.'),
        'Boutique Officielle' => array('ar' => 'Ø§Ù„Ù…ØªØ¬Ø± Ø§Ù„Ø±Ø³Ù…ÙŠ', 'en' => 'Official Store'),
        'Voir toute la boutique' => array('ar' => 'Ø¹Ø±Ø¶ ÙƒÙ„ Ø§Ù„Ù…ØªØ¬Ø±', 'en' => 'View All Shop'),
        'Portez fiÃ¨rement les couleurs de Najah Souss Echecs avec notre collection exclusive.' => array('ar' => 'Ø§Ø±ØªØ¯ Ø£Ù„ÙˆØ§Ù† Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ Ø¨ÙØ®Ø± Ù…Ø¹ Ù…Ø¬Ù…ÙˆØ¹ØªÙ†Ø§ Ø§Ù„Ø­ØµØ±ÙŠØ©.', 'en' => 'Wear the colors of Najah Souss Chess with pride through our exclusive collection.'),
        'Devenir Partenaire des Champions' => array('ar' => 'ÙƒÙ† Ø´Ø±ÙŠÙƒØ§Ù‹ Ù„Ù„Ø£Ø¨Ø·Ø§Ù„', 'en' => 'Become a Partner of Champions'),
        'Associez votre marque Ã  l\'excellence d\'Agadir. Najah Souss Echecs â€” Vainqueurs de la Coupe du TrÃ´ne 2025 â€” offre une visibilitÃ© nationale et internationale Ã  ses partenaires.' => array('ar' => 'Ø§Ø±Ø¨Ø· Ø¹Ù„Ø§Ù…ØªÙƒ Ø§Ù„ØªØ¬Ø§Ø±ÙŠØ© Ø¨Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±. ÙŠÙ‚Ø¯Ù… Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ - Ø£Ø¨Ø·Ø§Ù„ ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025 - Ø±Ø¤ÙŠØ© ÙˆØ¥Ø´Ø¹Ø§Ø¹Ø§Ù‹ ÙˆØ·Ù†ÙŠØ§Ù‹ ÙˆØ¯ÙˆÙ„ÙŠØ§Ù‹ Ù„Ø´Ø±ÙƒØ§Ø¦Ù‡.', 'en' => 'Associate your brand with Agadir\'s excellence. Najah Souss Chess â€” 2025 Throne Cup Champions â€” offers national and international visibility to its partners.'),
        'Rejoignez Najah Souss Echecs' => array('ar' => 'Ø§Ù†Ø¶Ù… Ø¥Ù„Ù‰ Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬', 'en' => 'Join Najah Souss Chess'),
        'Une question, une inscription ou un partenariat ? Nous sommes lÃ  pour vous.' => array('ar' => 'Ù‡Ù„ Ù„Ø¯ÙŠÙƒ Ø³Ø¤Ø§Ù„ØŒ ØªØ±ØºØ¨ ÙÙŠ Ø§Ù„ØªØ³Ø¬ÙŠÙ„ Ø£Ùˆ Ø¹Ù‚Ø¯ Ø´Ø±Ø§ÙƒØ©ØŸ Ù†Ø­Ù† Ù‡Ù†Ø§ Ù…Ù† Ø£Ø¬Ù„Ùƒ.', 'en' => 'Do you have a question, want to register, or form a partnership? We are here for you.'),
        'Les Ã©checs comme outil pÃ©dagogique pour dÃ©velopper la concentration, la logique et la crÃ©ativitÃ©.' => array('ar' => 'Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ ÙƒØ£Ø¯Ø§Ø© ØªØ¹Ù„ÙŠÙ…ÙŠØ© Ù„ØªØ·ÙˆÙŠØ± Ø§Ù„ØªØ±ÙƒÙŠØ²ØŒ Ø§Ù„Ù…Ù†Ø·Ù‚ØŒ ÙˆØ§Ù„Ø¥Ø¨Ø¯Ø§Ø¹.', 'en' => 'Chess as an educational tool to develop concentration, logic, and creativity.'),
        'Mur des Champions' => array('ar' => 'Ø¬Ø¯Ø§Ø± Ø§Ù„Ø£Ø¨Ø·Ø§Ù„', 'en' => 'Wall of Champions'),
        'Voir tous les champions' => array('ar' => 'Ø¹Ø±Ø¶ Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø£Ø¨Ø·Ø§Ù„', 'en' => 'View all champions'),
        'RÃ¨gles & mouvements' => array('ar' => 'Ø§Ù„Ù‚ÙˆØ§Ø¹Ø¯ ÙˆØ§Ù„Ø­Ø±ÙƒØ§Øª', 'en' => 'Rules & movements'),
        'Tactiques simples' => array('ar' => 'ØªÙƒØªÙŠÙƒØ§Øª Ø¨Ø³ÙŠØ·Ø©', 'en' => 'Simple tactics'),
        'Mat en 1-2 coups' => array('ar' => 'ÙƒØ´ Ù…Ø§Øª ÙÙŠ Ù†Ù‚Ù„Ø© Ø£Ùˆ Ù†Ù‚Ù„ØªÙŠÙ†', 'en' => 'Mate in 1-2 moves'),
        'Parties guidÃ©es' => array('ar' => 'Ù…Ø¨Ø§Ø±ÙŠØ§Øª Ù…ÙˆØ¬Ù‡Ø©', 'en' => 'Guided games'),
        'Principes d\'ouverture' => array('ar' => 'Ù…Ø¨Ø§Ø¯Ø¦ Ø§Ù„Ø§ÙØªØªØ§Ø­', 'en' => 'Opening principles'),
        'Combinaisons tactiques' => array('ar' => 'ØªØ±ÙƒÙŠØ¨Ø§Øª ØªÙƒØªÙŠÙƒÙŠØ©', 'en' => 'Tactical combinations'),
        'Fins de partie' => array('ar' => 'Ø£ÙˆØ§Ø®Ø± Ø§Ù„Ø£Ø¯ÙˆØ§Ø±', 'en' => 'Endgames'),
        'Analyse de parties' => array('ar' => 'ØªØ­Ù„ÙŠÙ„ Ø§Ù„Ù…Ø¨Ø§Ø±ÙŠØ§Øª', 'en' => 'Game analysis'),
        'StratÃ©gie positionnelle' => array('ar' => 'Ø§Ù„Ø§Ø³ØªØ±Ø§ØªÙŠØ¬ÙŠØ© Ø§Ù„Ù…ÙˆÙ‚Ø¹ÙŠØ©', 'en' => 'Positional strategy'),
        'RÃ©pertoires d\'ouvertures' => array('ar' => 'Ù…ÙˆØ³ÙˆØ¹Ø© Ø§Ù„Ø§ÙØªØªØ§Ø­Ø§Øª', 'en' => 'Opening repertoires'),
        'PrÃ©paration tournois' => array('ar' => 'Ø§Ù„Ø§Ø³ØªØ¹Ø¯Ø§Ø¯ Ù„Ù„Ø¨Ø·ÙˆÙ„Ø§Øª', 'en' => 'Tournament preparation'),
        'Analyse informatique' => array('ar' => 'Ø§Ù„ØªØ­Ù„ÙŠÙ„ Ø§Ù„Ø±Ù‚Ù…ÙŠ Ø§Ù„Ù…Ø­ÙˆØ³Ø¨', 'en' => 'Computer analysis'),
        'Ã€ venir' => array('ar' => 'Ù‚Ø±ÙŠØ¨Ø§Ù‹', 'en' => 'Upcoming'),
        'BientÃ´t' => array('ar' => 'Ù‚Ø±ÙŠØ¨Ø§Ù‹', 'en' => 'Soon'),
        'Tournoi FIDE' => array('ar' => 'Ø¨Ø·ÙˆÙ„Ø© FIDE', 'en' => 'FIDE Tournament'),
        'Stage' => array('ar' => 'Ù…Ø¹Ø³ÙƒØ±', 'en' => 'Camp'),
        'CompÃ©tition' => array('ar' => 'Ù…Ù†Ø§ÙØ³Ø©', 'en' => 'Competition'),
        'U8 F' => array('ar' => 'Ø£Ù‚Ù„ Ù…Ù† 8 Ø¥Ù†Ø§Ø«', 'en' => 'U8 Girls'),
        'U14 M' => array('ar' => 'Ø£Ù‚Ù„ Ù…Ù† 14 Ø°ÙƒÙˆØ±', 'en' => 'U14 Boys'),
        'U20 F' => array('ar' => 'Ø£Ù‚Ù„ Ù…Ù† 20 Ø¥Ù†Ø§Ø«', 'en' => 'U20 Girls'),
        'U18 M' => array('ar' => 'Ø£Ù‚Ù„ Ù…Ù† 18 Ø°ÙƒÙˆØ±', 'en' => 'U18 Boys'),
        // 1. About Section
        "Najah Souss Echecs est un club d'Ã©checs prestigieux basÃ© Ã  Agadir, au cÅ“ur de la rÃ©gion Souss-Massa, fondÃ© en 1987." => array('ar' => "Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ Ù‡Ùˆ Ù†Ø§Ø¯ÙŠ Ø¹Ø±ÙŠÙ‚ Ù…Ù‚Ø±Ù‡ ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±ØŒ ÙÙŠ Ù‚Ù„Ø¨ Ø¬Ù‡Ø© Ø³ÙˆØ³ Ù…Ø§Ø³Ø©ØŒ ØªØ£Ø³Ø³ Ø¹Ø§Ù… 1987.", 'en' => "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987."),
        "Officiellement affiliÃ© Ã  la FÃ©dÃ©ration Royale Marocaine des Ã‰checs (FRME) et reconnu par la FIDE, notre club reprÃ©sente la rÃ©gion Souss-Massa sur la scÃ¨ne nationale et internationale." => array('ar' => "Ù…Ù†Ø¶ÙˆÙ Ø±Ø³Ù…ÙŠØ§Ù‹ ØªØ­Øª Ù„ÙˆØ§Ø¡ Ø§Ù„Ø¬Ø§Ù…Ø¹Ø© Ø§Ù„Ù…Ù„ÙƒÙŠØ© Ø§Ù„Ù…ØºØ±Ø¨ÙŠØ© Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ (FRME) ÙˆÙ…Ø¹ØªØ±Ù Ø¨Ù‡ Ù…Ù† Ø·Ø±Ù Ø§Ù„Ø§ØªØ­Ø§Ø¯ Ø§Ù„Ø¯ÙˆÙ„ÙŠ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ (FIDE)ØŒ ÙŠÙ…Ø«Ù„ Ù†Ø§Ø¯ÙŠÙ†Ø§ Ø¬Ù‡Ø© Ø³ÙˆØ³ Ù…Ø§Ø³Ø© Ø¹Ù„Ù‰ Ø§Ù„Ø³Ø§Ø­Ø© Ø§Ù„ÙˆØ·Ù†ÙŠØ© ÙˆØ§Ù„Ø¯ÙˆÙ„ÙŠØ©.", 'en' => "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage."),
        
        // 2. Academy & Play Sections
        "AcadÃ©mie & Formation" => array('ar' => "Ø§Ù„Ø£ÙƒØ§Ø¯ÙŠÙ…ÙŠØ© ÙˆØ§Ù„ØªÙƒÙˆÙŠÙ†", 'en' => "Academy & Training"),
        "Formez-vous avec des <span class=\"gradient-gold\">Experts</span>" => array('ar' => "ØªØ¯Ø±Ø¨ Ù…Ø¹ <span class=\"gradient-gold\">Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡</span>", 'en' => "Train with <span class=\"gradient-gold\">Experts</span>"),
        "Encadrement Expert" => array('ar' => "ØªØ£Ø·ÙŠØ± Ù…Ù† Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡", 'en' => "Expert Coaching"),
        "Cours individuels & collectifs disponibles" => array('ar' => "Ø¯Ø±ÙˆØ³ ÙØ±Ø¯ÙŠØ© ÙˆØ¬Ù…Ø§Ø¹ÙŠØ© Ù…ØªØ§Ø­Ø©", 'en' => "Individual & group lessons available"),
        "PrÃªt Ã  relever le dÃ©fi ?" => array('ar' => "Ù‡Ù„ Ø£Ù†Øª Ù…Ø³ØªØ¹Ø¯ Ù„Ø±ÙØ¹ Ø§Ù„ØªØ­Ø¯ÙŠØŸ", 'en' => "Ready for the challenge?"),
        "Affrontez des joueurs du monde entier ou entraÃ®nez-vous contre l'IA sur notre espace dÃ©diÃ©." => array('ar' => "ÙˆØ§Ø¬Ù‡ Ù„Ø§Ø¹Ø¨ÙŠÙ† Ù…Ù† Ø¬Ù…ÙŠØ¹ Ø£Ù†Ø­Ø§Ø¡ Ø§Ù„Ø¹Ø§Ù„Ù… Ø£Ùˆ ØªØ¯Ø±Ø¨ Ø¶Ø¯ Ø§Ù„Ø°ÙƒØ§Ø¡ Ø§Ù„Ø§ØµØ·Ù†Ø§Ø¹ÙŠ ÙÙŠ Ù…Ø³Ø§Ø­ØªÙ†Ø§ Ø§Ù„Ù…Ø®ØµØµØ©.", 'en' => "Face players from around the world or practice against AI in our dedicated arena."),
        "Jouer aux Ã‰checs Maintenant â™ž" => array('ar' => "Ø§Ù„Ø¹Ø¨ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ Ø§Ù„Ø¢Ù† â™ž", 'en' => "Play Chess Now â™ž"),
        
        // 3. Various Headings & Buttons
        "Notre Ã‰quipe - <span class=\"gradient-gold\">Classement FIDE</span>" => array('ar' => "ÙØ±ÙŠÙ‚Ù†Ø§ - <span class=\"gradient-gold\">ØªØµÙ†ÙŠÙ FIDE Ø§Ù„Ø¯ÙˆÙ„ÙŠ</span>", 'en' => "Our Team - <span class=\"gradient-gold\">FIDE Ratings</span>"),
        "Nos Events" => array('ar' => "Ø£Ù†Ø´Ø·ØªÙ†Ø§ ÙˆÙØ¹Ø§Ù„ÙŠØ§ØªÙ†Ø§", 'en' => "Our Events"),
        "Notre <span class=\"gradient-gold\">ActualitÃ©</span>" => array('ar' => "Ø¢Ø®Ø± <span class=\"gradient-gold\">Ø£Ø®Ø¨Ø§Ø±Ù†Ø§</span>", 'en' => "Latest <span class=\"gradient-gold\">News</span>"),
        "Voir tout le staff" => array('ar' => "Ø¹Ø±Ø¶ ÙƒÙ„ Ø§Ù„Ø·Ø§Ù‚Ù…", 'en' => "View all staff"),
        // About Section
        "Najah Souss Echecs est un club d'Ã©checs prestigieux basÃ© Ã  Agadir, au cÅ“ur de la rÃ©gion Souss-Massa, fondÃ© en 1987." => array('en' => "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987.", 'ar' => "Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ Ù‡Ùˆ Ù†Ø§Ø¯ÙŠ Ø¹Ø±ÙŠÙ‚ Ù…Ù‚Ø±Ù‡ ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±ØŒ ÙÙŠ Ù‚Ù„Ø¨ Ø¬Ù‡Ø© Ø³ÙˆØ³ Ù…Ø§Ø³Ø©ØŒ ØªØ£Ø³Ø³ Ø¹Ø§Ù… 1987."),
        "Officiellement affiliÃ© Ã  la FÃ©dÃ©ration Royale Marocaine des Ã‰checs (FRME) et reconnu par la FIDE, notre club reprÃ©sente la rÃ©gion Souss-Massa sur la scÃ¨ne nationale et internationale." => array('en' => "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage.", 'ar' => "Ù…Ù†Ø¶ÙˆÙ Ø±Ø³Ù…ÙŠØ§Ù‹ ØªØ­Øª Ù„ÙˆØ§Ø¡ Ø§Ù„Ø¬Ø§Ù…Ø¹Ø© Ø§Ù„Ù…Ù„ÙƒÙŠØ© Ø§Ù„Ù…ØºØ±Ø¨ÙŠØ© Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ (FRME) ÙˆÙ…Ø¹ØªØ±Ù Ø¨Ù‡ Ù…Ù† Ø·Ø±Ù Ø§Ù„Ø§ØªØ­Ø§Ø¯ Ø§Ù„Ø¯ÙˆÙ„ÙŠ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ (FIDE)ØŒ ÙŠÙ…Ø«Ù„ Ù†Ø§Ø¯ÙŠÙ†Ø§ Ø¬Ù‡Ø© Ø³ÙˆØ³ Ù…Ø§Ø³Ø© Ø¹Ù„Ù‰ Ø§Ù„Ø³Ø§Ø­Ø© Ø§Ù„ÙˆØ·Ù†ÙŠØ© ÙˆØ§Ù„Ø¯ÙˆÙ„ÙŠØ©."),
        "Â« Aucun coup ne doit Ãªtre jouÃ© sans but. Â»" => array('en' => "Â« No move should be played without a purpose. Â»", 'ar' => "Â« Ù„Ø§ ÙŠÙ†Ø¨ØºÙŠ Ù„Ø¹Ø¨ Ø£ÙŠ Ù†Ù‚Ù„Ø© Ø¯ÙˆÙ† Ù‡Ø¯Ù Â»"),

        // Academy, Play & Challenge Sections
        "AcadÃ©mie & Formation" => array('en' => 'Academy & Training', 'ar' => 'Ø§Ù„Ø£ÙƒØ§Ø¯ÙŠÙ…ÙŠØ© ÙˆØ§Ù„ØªÙƒÙˆÙŠÙ†'),
        "Formez-vous avec des Experts" => array('en' => 'Train with Experts', 'ar' => 'ØªØ¯Ø±Ø¨ Ù…Ø¹ Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡'),
        "Encadrement Expert" => array('en' => 'Expert Coaching', 'ar' => 'ØªØ£Ø·ÙŠØ± Ù…Ù† Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡'),
        "Cours individuels & collectifs disponibles" => array('en' => 'Individual & group lessons available', 'ar' => 'Ø¯Ø±ÙˆØ³ ÙØ±Ø¯ÙŠØ© ÙˆØ¬Ù…Ø§Ø¹ÙŠØ© Ù…ØªØ§Ø­Ø©'),
        "PrÃªt Ã  relever le dÃ©fi ?" => array('en' => 'Ready for the challenge?', 'ar' => 'Ù‡Ù„ Ø£Ù†Øª Ù…Ø³ØªØ¹Ø¯ Ù„Ø±ÙØ¹ Ø§Ù„ØªØ­Ø¯ÙŠØŸ'),
        "Affrontez des joueurs du monde entier ou entraÃ®nez-vous contre l'IA sur notre espace dÃ©diÃ©." => array('en' => "Face players from around the world or practice against AI in our dedicated arena.", 'ar' => "ÙˆØ§Ø¬Ù‡ Ù„Ø§Ø¹Ø¨ÙŠÙ† Ù…Ù† Ø¬Ù…ÙŠØ¹ Ø£Ù†Ø­Ø§Ø¡ Ø§Ù„Ø¹Ø§Ù„Ù… Ø£Ùˆ ØªØ¯Ø±Ø¨ Ø¶Ø¯ Ø§Ù„Ø°ÙƒØ§Ø¡ Ø§Ù„Ø§ØµØ·Ù†Ø§Ø¹ÙŠ ÙÙŠ Ù…Ø³Ø§Ø­ØªÙ†Ø§ Ø§Ù„Ù…Ø®ØµØµØ©."),
        "Jouer aux Ã‰checs Maintenant â™ž" => array('en' => 'Play Chess Now â™ž', 'ar' => 'Ø§Ù„Ø¹Ø¨ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ Ø§Ù„Ø¢Ù† â™ž'),

        // Headings, Staff & Contact
        "Notre Ã‰quipe - Classement FIDE" => array('en' => 'Our Team - FIDE Ratings', 'ar' => 'ÙØ±ÙŠÙ‚Ù†Ø§ - ØªØµÙ†ÙŠÙ FIDE Ø§Ù„Ø¯ÙˆÙ„ÙŠ'),
        "Nos Events" => array('en' => 'Our Events', 'ar' => 'Ø£Ù†Ø´Ø·ØªÙ†Ø§ ÙˆÙØ¹Ø§Ù„ÙŠØ§ØªÙ†Ø§'),
        "Notre ActualitÃ©" => array('en' => 'Latest News', 'ar' => 'Ø¢Ø®Ø± Ø£Ø®Ø¨Ø§Ø±Ù†Ø§'),
        "Voir tout le staff" => array('en' => 'View all staff', 'ar' => 'Ø¹Ø±Ø¶ ÙƒÙ„ Ø§Ù„Ø·Ø§Ù‚Ù…'),
        "Une question, une inscription ou un partenariat ? Nous sommes lÃ  pour vous." => array('en' => "Got a question, want to register, or discuss a partnership? We are here for you.", 'ar' => "Ù‡Ù„ Ù„Ø¯ÙŠÙƒ Ø³Ø¤Ø§Ù„ØŒ ØªØ±ØºØ¨ ÙÙŠ Ø§Ù„ØªØ³Ø¬ÙŠÙ„ Ø£Ùˆ Ø¹Ù‚Ø¯ Ø´Ø±Ø§ÙƒØ©ØŸ Ù†Ø­Ù† Ù‡Ù†Ø§ Ù…Ù† Ø£Ø¬Ù„Ùƒ."),

        // Partnership Section
        "Associez votre marque Ã  l'excellence d'Agadir. Najah Souss Echecs â€” Vainqueurs de la Coupe du TrÃ´ne 2025 â€” offre une visibilitÃ© nationale et internationale Ã  ses partenaires." => array('en' => "Associate your brand with Agadir's standard of excellence. Najah Souss Chess â€” 2025 Throne Cup Champions â€” offers national and international visibility to its partners.", 'ar' => "Ø§Ø±Ø¨Ø· Ø¹Ù„Ø§Ù…ØªÙƒ Ø§Ù„ØªØ¬Ø§Ø±ÙŠØ© Ø¨Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±. ÙŠÙ‚Ø¯Ù… Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ - Ø£Ø¨Ø·Ø§Ù„ ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025 - Ø±Ø¤ÙŠØ© ÙˆØ¥Ø´Ø¹Ø§Ø¹Ø§Ù‹ ÙˆØ·Ù†ÙŠØ§Ù‹ ÙˆØ¯ÙˆÙ„ÙŠØ§Ù‹ Ù„Ø´Ø±ÙƒØ§Ø¦Ù‡."),
        
        // Tags
        "Ã€ venir" => array('en' => 'Upcoming', 'ar' => 'Ù‚Ø§Ø¯Ù…'),
        "TerminÃ©" => array('en' => 'Past', 'ar' => 'Ù…Ù†ØªÙ‡ÙŠ'),
        "Lire l'article" => array('en' => 'Read Article', 'ar' => 'Ø§Ù‚Ø±Ø£ Ø§Ù„Ù…Ù‚Ø§Ù„'),

        
        // 4. Partnership & Contact
        "Associez votre marque Ã  l'excellence d'Agadir. Najah Souss Echecs â€” Vainqueurs de la Coupe du TrÃ´ne 2025 â€” offre une visibilitÃ© nationale et internationale Ã  ses partenaires." => array('ar' => "Ø§Ø±Ø¨Ø· Ø¹Ù„Ø§Ù…ØªÙƒ Ø§Ù„ØªØ¬Ø§Ø±ÙŠØ© Ø¨Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø£ÙƒØ§Ø¯ÙŠØ±. ÙŠÙ‚Ø¯Ù… Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ - Ø£Ø¨Ø·Ø§Ù„ ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025 - Ø±Ø¤ÙŠØ© ÙˆØ¥Ø´Ø¹Ø§Ø¹Ø§Ù‹ ÙˆØ·Ù†ÙŠØ§Ù‹ ÙˆØ¯ÙˆÙ„ÙŠØ§Ù‹ Ù„Ø´Ø±ÙƒØ§Ø¦Ù‡.", 'en' => "Associate your brand with Agadir's standard of excellence. Najah Souss Chess â€” 2025 Throne Cup Champions â€” offers national and international visibility to its partners."),
        "Une question, une inscription ou un partenariat ? Nous sommes lÃ  pour vous." => array('ar' => "Ù‡Ù„ Ù„Ø¯ÙŠÙƒ Ø³Ø¤Ø§Ù„ØŒ ØªØ±ØºØ¨ ÙÙŠ Ø§Ù„ØªØ³Ø¬ÙŠÙ„ Ø£Ùˆ Ø¹Ù‚Ø¯ Ø´Ø±Ø§ÙƒØ©ØŸ Ù†Ø­Ù† Ù‡Ù†Ø§ Ù…Ù† Ø£Ø¬Ù„Ùƒ.", 'en' => "Got a question, want to register, or discuss a partnership? We are here for you."),
        "PrÃªt Ã  relever le dÃ©fi !" => array('ar' => "Ù‡Ù„ Ø£Ù†Øª Ù…Ø³ØªØ¹Ø¯ Ù„Ø±ÙØ¹ Ø§Ù„ØªØ­Ø¯ÙŠØŸ", 'en' => "Ready for the challenge!"),
        "Galerie Najah Souss" => array('ar' => "Ù…Ø¹Ø±Ø¶ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³", 'en' => "Najah Souss Gallery"),
        "Rejoignez Najah Souss Echecs" => array('ar' => "Ø§Ù†Ø¶Ù… Ø¥Ù„Ù‰ Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³", 'en' => "Join Najah Souss Chess"),
        "Devenir Partenaire" => array('ar' => "ÙƒÙ† Ø´Ø±ÙŠÙƒØ§Ù‹", 'en' => "Become a Partner"),
        "des Champions" => array('ar' => "Ù„Ù„Ø£Ø¨Ø·Ø§Ù„", 'en' => "of the Champions"),
        "Participez Ã  une sÃ©ance d'essai gratuite" => array('ar' => "Ø´Ø§Ø±Ùƒ ÙÙŠ Ø¬Ù„Ø³Ø© ØªØ¬Ø±ÙŠØ¨ÙŠØ© Ù…Ø¬Ø§Ù†ÙŠØ©", 'en' => "Join a free trial session"),
        "Une Ã©quipe d'experts au service de l'excellence des Ã©checs." => array('ar' => "ÙØ±ÙŠÙ‚ Ù…Ù† Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡ ÙÙŠ Ø®Ø¯Ù…Ø© Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬.", 'en' => "A team of experts dedicated to chess excellence."),
        "Album Photo" => array('ar' => "Ø£Ù„Ø¨ÙˆÙ… Ø§Ù„ØµÙˆØ±", 'en' => "Photo Album"),
        "Image" => array('ar' => "ØµÙˆØ±Ø©", 'en' => "Image"),
        "Agrandir l'image" => array('ar' => "ØªÙƒØ¨ÙŠØ± Ø§Ù„ØµÙˆØ±Ø©", 'en' => "Enlarge image"),
        "AperÃ§u de l'image" => array('ar' => "Ù…Ø¹Ø§ÙŠÙ†Ø© Ø§Ù„ØµÙˆØ±Ø©", 'en' => "Image preview"),
        "Fermer" => array('ar' => "Ø¥ØºÙ„Ø§Ù‚", 'en' => "Close"),
        "AperÃ§u" => array('ar' => "Ù…Ø¹Ø§ÙŠÙ†Ø©", 'en' => "Preview"),
        "PrÃ©cÃ©dent" => array('ar' => "Ø§Ù„Ø³Ø§Ø¨Ù‚", 'en' => "Previous"),
        "Suivant" => array('ar' => "Ø§Ù„ØªØ§Ù„ÙŠ", 'en' => "Next"),
        "Aucune image dans cet album pour le moment." => array('ar' => "Ù„Ø§ ØªÙˆØ¬Ø¯ ØµÙˆØ± ÙÙŠ Ù‡Ø°Ø§ Ø§Ù„Ø£Ù„Ø¨ÙˆÙ… Ø­Ø§Ù„ÙŠØ§Ù‹.", 'en' => "No images in this album yet."),
        "Retour aux albums" => array('ar' => "Ø§Ù„Ø¹ÙˆØ¯Ø© Ø¥Ù„Ù‰ Ø§Ù„Ø£Ù„Ø¨ÙˆÙ…Ø§Øª", 'en' => "Back to Albums"),
            );
        
        $normalized_dict = array();
        foreach ($dict as $key => $trans) {
            $normalized_dict[$normalize($key)] = $trans;
        }
        
        $mappings = array(

        'Les Ã©checs comme outil pÃ©dagog' => array('ar' => 'Ø§Ù„Ø´Ø·Ø±Ù†Ø¬ ÙƒØ£Ø¯Ø§Ø© ØªØ±Ø¨ÙˆÙŠØ© Ù„ØªØ·ÙˆÙŠØ± Ø§Ù„ØªØ±ÙƒÙŠØ²ØŒ Ø§Ù„Ù…Ù†Ø·Ù‚ØŒ ÙˆØ§Ù„Ø¥Ø¨Ø¯Ø§Ø¹.', 'en' => 'Chess as an educational tool to develop focus, logic, and creativity.'),
        'Participation aux tournois rÃ©g' => array('ar' => 'Ø§Ù„Ù…Ø´Ø§Ø±ÙƒØ© ÙÙŠ Ø§Ù„Ø¨Ø·ÙˆÙ„Ø§Øª Ø§Ù„Ø¬Ù‡ÙˆÙŠØ©ØŒ Ø§Ù„ÙˆØ·Ù†ÙŠØ©ØŒ ÙˆØ§Ù„Ø¯ÙˆÙ„ÙŠØ© Ø§Ù„Ù…Ø¹ØªÙ…Ø¯Ø© Ù…Ù† Ø·Ø±Ù FIDE/FRME.', 'en' => 'Participation in FIDE/FRME rated regional, national, and international tournaments.'),
        'Respect mutuel, Ã©thique irrÃ©pr' => array('ar' => 'Ø§Ù„Ø§Ø­ØªØ±Ø§Ù… Ø§Ù„Ù…ØªØ¨Ø§Ø¯Ù„ØŒ Ø§Ù„Ø£Ø®Ù„Ø§Ù‚ Ø§Ù„Ø¹Ø§Ù„ÙŠØ©ØŒ ÙˆØ§Ù„Ø±ÙˆØ­ Ø§Ù„Ø±ÙŠØ§Ø¶ÙŠØ© ÙÙŠ ØµÙ…ÙŠÙ… ÙƒÙ„ Ù…Ø¨Ø§Ø±Ø§Ø©.', 'en' => 'Mutual respect, impeccable ethics, and sportsmanship at the heart of every game.'),
        'Coupe du TrÃ´ne 2025, 8 mÃ©daill' => array('ar' => 'ÙƒØ£Ø³ Ø§Ù„Ø¹Ø±Ø´ 2025: 8 Ù…ÙŠØ¯Ø§Ù„ÙŠØ§Øª Ø°Ù‡Ø¨ÙŠØ© Ùˆ4 Ø§Ø®ØªÙŠØ§Ø±Ø§Øª Ù„Ù„Ù…Ù†ØªØ®Ø¨ Ø§Ù„ÙˆØ·Ù†ÙŠØŒ ØªÙ…ÙŠØ² Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.', 'en' => '2025 Throne Cup, 8 gold medals, and 4 national team selections â€” the standard of excellence at Najah Souss Chess.'),
        'Les visages de notre excellenc' => array('ar' => 'ÙˆØ¬ÙˆÙ‡ ØªÙ…ÙŠØ²Ù†Ø§ â€” Ø£ÙˆÙ„Ø¦Ùƒ Ø§Ù„Ø°ÙŠÙ† ÙŠØ­Ù…Ù„ÙˆÙ† Ø£Ù„ÙˆØ§Ù† Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.', 'en' => 'The faces of our excellence â€” those who proudly wear the colors of Najah Souss Chess.'),
        'Programmes pÃ©dagogiques pour t' => array('ar' => 'Ø¨Ø±Ø§Ù…Ø¬ ØªØ¹Ù„ÙŠÙ…ÙŠØ© Ù„Ø¬Ù…ÙŠØ¹ Ø§Ù„Ù…Ø³ØªÙˆÙŠØ§ØªØŒ ØªØ­Øª Ø¥Ø´Ø±Ø§Ù Ù…Ø¯Ø±Ø¨ÙŠÙ† Ù…Ø¹ØªÙ…Ø¯ÙŠÙ† ÙˆÙ„Ø§Ø¹Ø¨ÙŠÙ† Ù…ØµÙ†ÙÙŠÙ† Ø¯ÙˆÙ„ÙŠØ§Ù‹ (FIDE).', 'en' => 'Educational programs for all levels, led by certified coaches and FIDE-rated players.'),
        'Choisissez la formule qui vous' => array('ar' => 'Ø§Ø®ØªØ± Ø§Ù„ØµÙŠØºØ© Ø§Ù„ØªÙŠ ØªÙ†Ø§Ø³Ø¨Ùƒ ÙˆØªÙ‚Ø¯Ù… Ø¨Ø§Ù„ÙˆØªÙŠØ±Ø© Ø§Ù„ØªÙŠ ØªØ±ÙŠØ­Ùƒ Ù…Ø¹ Ù…Ø¯Ø±Ø¨ÙŠÙ†Ø§ Ø§Ù„Ù…Ø¹ØªÙ…Ø¯ÙŠÙ† Ù…Ù† FIDE.', 'en' => 'Choose the program that suits you and progress at your own pace with our FIDE coaches.'),
        'Tournois, cÃ©rÃ©monies, entraÃ®ne' => array('ar' => 'Ø¨Ø·ÙˆÙ„Ø§ØªØŒ Ø­ÙÙ„Ø§ØªØŒ ØªØ¯Ø§Ø±ÙŠØ¨ ÙˆØ§Ù†ØªØµØ§Ø±Ø§Øª â€” Ø­ÙŠØ§Ø© Ø§Ù„Ù†Ø§Ø¯ÙŠ Ø¨Ø§Ù„ØµÙˆØ±.', 'en' => 'Tournaments, ceremonies, training, and victories â€” club life in pictures.'),
        'Des tournois pour tous les niv' => array('ar' => 'Ø¨Ø·ÙˆÙ„Ø§Øª Ù„Ø¬Ù…ÙŠØ¹ Ø§Ù„Ù…Ø³ØªÙˆÙŠØ§ØªØŒ Ù…Ù† Ø§Ù„Ù…Ù†Ø§ÙØ³Ø§Øª Ø§Ù„Ø±Ø³Ù…ÙŠØ© Ø¥Ù„Ù‰ Ø§Ù„ÙØ¹Ø§Ù„ÙŠØ§Øª Ø§Ù„Ø«Ù‚Ø§ÙÙŠØ©.', 'en' => 'Tournaments for all levels, from official competitions to cultural events.'),
        'La presse nationale et locale ' => array('ar' => 'Ø§Ù„ØµØ­Ø§ÙØ© Ø§Ù„ÙˆØ·Ù†ÙŠØ© ÙˆØ§Ù„Ù…Ø­Ù„ÙŠØ© ØªØºØ·ÙŠ Ù†Ø¬Ø§Ø­Ø§Øª Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.', 'en' => 'National and local press covering the success of Najah Souss Chess.'),
        'Les derniÃ¨res nouvelles, Ã©vÃ©ne' => array('ar' => 'Ø¢Ø®Ø± Ø§Ù„Ù…Ø³ØªØ¬Ø¯Ø§ØªØŒ Ø§Ù„Ø£Ù†Ø´Ø·Ø© ÙˆØ§Ù„Ø¥Ø¹Ù„Ø§Ù†Ø§Øª Ø§Ù„Ø®Ø§ØµØ© Ø¨Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬.', 'en' => 'The latest news, events, and announcements from Najah Souss Chess.'),
        'Une Ã©quipe d\'experts au servic' => array('ar' => 'ÙØ±ÙŠÙ‚ Ù…Ù† Ø§Ù„Ø®Ø¨Ø±Ø§Ø¡ ÙÙŠ Ø®Ø¯Ù…Ø© Ø§Ù„ØªÙ…ÙŠØ² ÙÙŠ Ø§Ù„Ø´Ø·Ø±Ù†Ø¬.', 'en' => 'A team of experts dedicated to chess excellence.'),
        'Portez fiÃ¨rement les couleurs ' => array('ar' => 'Ø§Ø±ØªØ¯Ù Ø¨ÙØ®Ø± Ø£Ù„ÙˆØ§Ù† Ù†Ø§Ø¯ÙŠ Ù†Ø¬Ø§Ø­ Ø³ÙˆØ³ Ù„Ù„Ø´Ø·Ø±Ù†Ø¬ Ù…Ø¹ ØªØ´ÙƒÙŠÙ„ØªÙ†Ø§ Ø§Ù„Ø­ØµØ±ÙŠØ©.', 'en' => 'Wear the Najah Souss Chess colors with pride through our exclusive collection.'),
        'Remplissez le formulaire ci-co' => array('ar' => 'Ø§Ù…Ù„Ø£ Ø§Ù„Ù†Ù…ÙˆØ°Ø¬', 'en' => 'Fill out the form'),
        'Un responsable vous contactera' => array('ar' => 'Ø³ÙŠØªÙˆØ§ØµÙ„ Ù…Ø¹Ùƒ Ø£Ø­Ø¯ Ø§Ù„Ù…Ø³Ø¤ÙˆÙ„ÙŠÙ† Ø®Ù„Ø§Ù„ 48 Ø³Ø§Ø¹Ø©', 'en' => 'A representative will contact you within 48h'),
        'Participez Ã  une sÃ©ance d\'essai' => array('ar' => 'Ø´Ø§Ø±Ùƒ ÙÙŠ Ø­ØµØ© ØªØ¬Ø±ÙŠØ¨ÙŠØ© Ù…Ø¬Ø§Ù†ÙŠØ©', 'en' => 'Attend a free trial session'),
        'Rejoignez officiellement le cl' => array('ar' => 'Ø§Ù†Ø¶Ù… Ø±Ø³Ù…ÙŠØ§Ù‹ Ø¥Ù„Ù‰ Ø§Ù„Ù†Ø§Ø¯ÙŠ!', 'en' => 'Officially join the club!'),
            );
        
        $normalized_mappings = array();
        foreach ($mappings as $prefix => $trans) {
            $normalized_mappings[$normalize($prefix)] = $trans;
        }
    }

    if (isset($normalized_dict[$norm_key]) && isset($normalized_dict[$norm_key][$lang])) {
        $ansae_cache[$lang][$french_text] = $normalized_dict[$norm_key][$lang];
        return $ansae_cache[$lang][$french_text];
    }
    
    foreach ($normalized_mappings as $prefix => $translations) {
        if (strpos($norm_key, $prefix) === 0) {
            $ansae_cache[$lang][$french_text] = $translations[$lang];
            return $ansae_cache[$lang][$french_text];
        }
    }

    $ansae_cache[$lang][$french_text] = $french_text;
    return $ansae_cache[$lang][$french_text];
}

// Shortcode for Champion Ratings Table

// AJAX handler for infinite scroll on Champions archive
function load_more_champions() {
    // Verify nonce for security
    check_ajax_referer('load_more_champions_nonce', 'nonce');
    $page = intval($_POST['page']);
    $args = array(
        'post_type' => 'champion',
        'posts_per_page' => 8,
        'paged' => $page,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <article class="group rounded-xl overflow-hidden border border-gold/20 bg-neutral-900/30 hover:bg-neutral-900/50 transition-colors shadow-lg">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="block">
                        <?php the_post_thumbnail('large', array('class' => 'w-full h-48 object-cover')); ?>
                    </a>
                <?php endif; ?>
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gold group-hover:text-white transition-colors">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <?php if ( $category = get_field('categorie') ) : ?>
                        <p class="text-sm text-muted-foreground mt-1"><?php echo esc_html($category); ?></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php
        }
        wp_reset_postdata();
        $html = ob_get_clean();
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => ($page < $query->max_num_pages)
        ));
    } else {
        wp_send_json_error();
    }
    wp_die();
}
add_action('wp_ajax_load_more_champions', 'load_more_champions');
add_action('wp_ajax_nopriv_load_more_champions', 'load_more_champions');

// Enqueue infinite scroll script on champions archive page
function enqueue_champion_infinite_scroll() {
    if ( is_post_type_archive('champion') ) {
        wp_enqueue_script('champion-infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll.js', array(), '1.1', true);
        wp_localize_script('champion-infinite-scroll', 'championScroll', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('load_more_champions_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_champion_infinite_scroll');
function champion_ratings_table_shortcode() {
    $leaderboard = new WP_Query(array(
        'post_type'      => 'champion',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'suppress_filters' => false,
    ));
    if (!$leaderboard->have_posts()) {
        return '<p class="text-center text-muted-foreground">' . esc_html(ansae_t('Aucun classement disponible.')) . '</p>';
    }
    ob_start();
    ?>
    <table class="w-full text-start border-collapse">
        <thead>
            <tr class="bg-neutral-900 text-gold uppercase text-[10px] tracking-widest font-bold">
                <th class="px-4 py-4 text-start"><?php echo ansae_t('JOUEUR'); ?></th>
                <th class="px-4 py-4 text-start"><?php echo ansae_t('FIDE ID'); ?></th>
                <th class="px-4 py-4 text-start"><?php echo ansae_t('CATÃ‰GORIE'); ?></th>
                <th class="px-4 py-4 text-center"><?php echo ansae_t('STANDARD'); ?></th>
                <th class="px-4 py-4 text-center"><?php echo ansae_t('RAPID'); ?></th>
                <th class="px-4 py-4 text-center"><?php echo ansae_t('BLITZ'); ?></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gold/10">
            <?php while ($leaderboard->have_posts()) : $leaderboard->the_post(); ?>
                <tr class="hover:bg-gold/5 transition-colors group">
                    <td class="px-4 py-4 font-semibold text-white group-hover:text-gold transition-colors">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </td>
                    <td class="px-4 py-4 text-muted-foreground text-xs font-mono">
                        <?php $f_id = get_field('fide_id');
                        if ($f_id): ?>
                            <a href="https://ratings.fide.com/profile/<?php echo esc_attr($f_id); ?>" target="_blank" class="text-gold hover:underline font-mono"><?php echo esc_html($f_id); ?></a>
                        <?php else: ?>-
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-muted-foreground text-xs"><?php echo esc_html(ansae_t(get_field('categorie') ?: '-')); ?></td>
                    <td class="px-4 py-4 text-center"><span class="inline-block px-2 py-1 rounded bg-gold/10 text-gold font-bold font-mono text-sm"><?php echo esc_html(get_field('rating_standard') ?: '-'); ?></span></td>
                    <td class="px-4 py-4 text-center text-muted-foreground font-mono text-sm"><?php echo esc_html(get_field('rating_rapid') ?: '-'); ?></td>
                    <td class="px-4 py-4 text-center text-muted-foreground font-mono text-sm"><?php echo esc_html(get_field('rating_blitz') ?: '-'); ?></td>
                </tr>
            <?php endwhile; wp_reset_postdata(); ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}
add_shortcode('champion_ratings_table', 'champion_ratings_table_shortcode');


// =========================================================================
// BREADCRUMBS
// =========================================================================
function ansae_breadcrumbs() {
    if ( is_front_page() ) return;

    $home_url = esc_url( function_exists('pll_home_url') ? pll_home_url() : home_url('/') );
    $home_label = ansae_t('Accueil');

    echo '<nav aria-label="Breadcrumb" class="flex-1">';
    echo '<ol class="flex flex-wrap items-center gap-2 text-sm text-muted-foreground">';
    
    // Home
    echo '<li class="inline-flex items-center">';
    echo '<a href="' . $home_url . '" class="hover:text-gold transition-colors font-medium">' . $home_label . '</a>';
    echo '</li>';

    // Separator
    $separator = '<li aria-hidden="true" class="text-gold/50 rtl:rotate-180">/</li>';

    if ( is_home() || is_singular('post') ) {
        echo $separator;
        
        $news_url = get_permalink( get_option('page_for_posts') );
        if(function_exists('pll_get_post')){
             $actualites_obj = get_page_by_path('actualites');
             $news_url = $actualites_obj ? get_permalink( pll_get_post($actualites_obj->ID) ) : home_url('/actualites/');
        }
        $news_label = ansae_t('ActualitÃ©s');
        
        if ( is_home() ) {
            echo '<li class="text-white font-semibold" aria-current="page">' . $news_label . '</li>';
        } else {
            echo '<li class="inline-flex items-center">';
            echo '<a href="' . esc_url($news_url) . '" class="hover:text-gold transition-colors font-medium">' . $news_label . '</a>';
            echo '</li>';
        }
    }
    
    if ( is_post_type_archive('champion') || is_singular('champion') ) {
        echo $separator;
        $champion_url = get_post_type_archive_link('champion');
        $champion_label = ansae_t('Champions');
        if ( is_post_type_archive('champion') ) {
             echo '<li class="text-white font-semibold" aria-current="page">' . $champion_label . '</li>';
        } else {
             echo '<li class="inline-flex items-center">';
             echo '<a href="' . esc_url($champion_url) . '" class="hover:text-gold transition-colors font-medium">' . $champion_label . '</a>';
             echo '</li>';
        }
    }

    if ( is_singular() && !is_front_page() && !is_singular('post') && !is_singular('champion') ) {
        echo $separator;
        echo '<li class="text-white font-semibold truncate max-w-[200px] md:max-w-xs" aria-current="page">' . get_the_title() . '</li>';
    } elseif ( is_page() && !is_front_page() ) {
        echo $separator;
        echo '<li class="text-white font-semibold" aria-current="page">' . get_the_title() . '</li>';
    } elseif ( is_archive() && !is_post_type_archive('champion') && !is_home() ) {
        echo $separator;
        echo '<li class="text-white font-semibold" aria-current="page">' . get_the_archive_title() . '</li>';
    } elseif ( is_search() ) {
        echo $separator;
        echo '<li class="text-white font-semibold" aria-current="page">' . ansae_t('Recherche') . '</li>';
    }
    
    if ( is_singular('post') || is_singular('champion') ) {
        echo $separator;
        echo '<li class="text-white font-semibold truncate max-w-[200px] md:max-w-xs" aria-current="page">' . get_the_title() . '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}



// =========================================================================
// FIDE API INTEGRATION & CHAMPION METABOX
// =========================================================================

// 1. Register Custom Meta Box for FIDE ID
function ansae_add_champion_metaboxes() {
    add_meta_box(
        'ansae_champion_fide_box',
        ansae_t('ID FIDE du Champion'),
        'ansae_champion_fide_callback',
        'champion',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'ansae_add_champion_metaboxes');

function ansae_champion_fide_callback($post) {
    wp_nonce_field('ansae_save_champion_fide', 'ansae_champion_fide_nonce');
    $fide_id = get_post_meta($post->ID, '_champion_fide_id', true);
    
    // Fallback to ACF 'fide_id' if exists and meta is empty
    if (empty($fide_id) && function_exists('get_field')) {
        $fide_id = get_field('fide_id', $post->ID);
    }

    echo '<label for="ansae_fide_id">' . esc_html(ansae_t('Entrez l\'ID FIDE :')) . '</label><br>';
    echo '<input type="text" id="ansae_fide_id" name="ansae_fide_id" value="' . esc_attr($fide_id) . '" style="width:100%; margin-top:5px;" />';
}

function ansae_save_champion_fide_meta($post_id) {
    if (!isset($_POST['ansae_champion_fide_nonce']) || !wp_verify_nonce($_POST['ansae_champion_fide_nonce'], 'ansae_save_champion_fide')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['ansae_fide_id'])) {
        $new_fide_id = sanitize_text_field($_POST['ansae_fide_id']);
        update_post_meta($post_id, '_champion_fide_id', $new_fide_id);
        // Force clear cache for this ID
        delete_transient('fide_data_' . $new_fide_id);
    }
}
add_action('save_post_champion', 'ansae_save_champion_fide_meta');

// 2. API Fetch Function with Transients
function ansae_fetch_fide_data($fide_id) {
    if (empty($fide_id)) return false;

    $transient_key = 'fide_data_' . $fide_id;
    $cached_data = get_transient($transient_key);

    if (false !== $cached_data) {
        return $cached_data;
    }

    $api_url = 'https://fide-api.vercel.app/player_info/?fide_id=' . urlencode($fide_id) . '&history=true';
    $response = wp_remote_get($api_url, array('timeout' => 15));

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!empty($data)) {
        // Cache for 7 days
        set_transient($transient_key, $data, 7 * DAY_IN_SECONDS);
        return $data;
    }

    return false;
}

// =========================================================================
// PWA (PROGRESSIVE WEB APP) ROOT SCOPE REWRITE RULES
// =========================================================================
function ansae_pwa_rewrite_rules() {
    add_rewrite_rule('^manifest\.json$', 'index.php?ansae_pwa_file=manifest', 'top');
    add_rewrite_rule('^service-worker\.js$', 'index.php?ansae_pwa_file=sw', 'top');
}
add_action('init', 'ansae_pwa_rewrite_rules');

function ansae_pwa_query_vars($vars) {
    $vars[] = 'ansae_pwa_file';
    return $vars;
}
add_filter('query_vars', 'ansae_pwa_query_vars');

function ansae_pwa_serve_files() {
    $file = get_query_var('ansae_pwa_file');
    if (empty($file)) return;

    $theme_dir = get_template_directory();
    
    if ($file === 'manifest') {
        $file_path = $theme_dir . '/manifest.json';
        header('Content-Type: application/json; charset=utf-8');
    } elseif ($file === 'sw') {
        $file_path = $theme_dir . '/service-worker.js';
        header('Content-Type: application/javascript; charset=utf-8');
        header('Service-Worker-Allowed: /');
    } else {
        return;
    }

    if (file_exists($file_path)) {
        readfile($file_path);
        exit;
    }
}
add_action('template_redirect', 'ansae_pwa_serve_files');
