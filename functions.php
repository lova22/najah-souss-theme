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

// Include the database seeder — always load, gate is internal to seeder
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
        "Agadir · Maroc — Depuis 1987", "NAJAH SOUSS ECHECS", "Champions du Maroc 2025",
        "L'excellence des échecs à Agadir.", "Formation, Compétition et Culture.",
        "♞ Rejoindre le Club", "Voir le Palmarès",
        "Coupe du Trône 2025", "Médailles d'Or 2025-26", "Affilié", "Année de fondation",
        "À propos", "À PROPOS", "D'EXCELLENCE",
        "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987.",
        "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale.",
        "« Aucun coup ne doit être joué sans but. »",
        "Affilié FRME", "Reconnu FIDE", "Agadir, Maroc", "Depuis 1987",
        "Éducation", "Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité.",
        "Compétition", "Participation aux tournois régionaux, nationaux et internationaux homologués FIDE/FRME.",
        "Fair-play", "Respect mutuel, éthique irréprochable et esprit sportif au cœur de chaque partie.",
        "Notre Palmarès", "Coupe du Trône 2025, 8 médailles d'or et 4 sélections nationales — l'excellence de Najah Souss Echecs.",
        "Mur des Champions", "Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs.",
        "Voir tous les champions",
        "Programmes pédagogiques pour tous les niveaux, encadrés par des entraîneurs certifiés et des joueurs classés FIDE.",
        "Débutant", "Les Pions", "Règles & mouvements", "Tactiques simples", "Mat en 1-2 coups", "Parties guidées",
        "POPULAIRE", "Intermédiaire", "Les Fous & Tours", "Principes d'ouverture", "Combinaisons tactiques", "Fins de partie", "Analyse de parties",
        "Avancé", "Les Dames & Rois", "Stratégie positionnelle", "Répertoires d'ouvertures", "Préparation tournois", "Analyse informatique",
        "Choisissez la formule qui vous convient et progressez à votre rythme avec nos coachs FIDE.",
        "Galerie", "Galerie Najah Souss", "Tournois, cérémonies, entraînements et victoires — la vie du club en images.",
        "Calendrier 2025", "Nos Événements", "Des tournois pour tous les niveaux, des compétitions officielles aux événements culturels.",
        "Médias & Presse", "Ils parlent de nous", "La presse nationale et locale couvre les succès de Najah Souss Echecs.",
        "Notre Actualité", "Les dernières nouvelles, événements et annonces de Najah Souss Echecs.",
        "Direction & Encadrement", "Staff Technique & Administratif", "Une équipe d'experts au service de l'excellence des échecs.",
        "Voir tout le staff", "Supportez votre club", "Boutique Officielle", "Voir toute la boutique",
        "Portez fièrement les couleurs de Najah Souss Echecs avec notre collection exclusive.",
        "Partenariat", "Devenir Partenaire", "des Champions",
        "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires.",
        "Visibilité Nationale", "Tournois FRME / FIDE", "Réseaux Sociaux", "Communauté engagée", "Impact Social", "Éducation jeunesse",
        "TÉLÉCHARGER LE DOSSIER SPONSORING", "NOUS CONTACTER", "Contact",
        "Rejoignez Najah Souss Echecs", "Une question, une inscription ou un partenariat ? Nous sommes là pour vous.",
        "Adresse", "Local Najah Souss Echecs, Corniche d'Agadir", "Email", "Téléphone",
        "Comment s'inscrire ?", "Remplissez le formulaire ci-contre", "Un responsable vous contactera sous 48h",
        "Participez à une séance d'essai gratuite", "Rejoignez officiellement le club !",
        "Nom complet *", "Email *", "Message *", "Envoyer ma demande ♞", "Tous droits réservés."
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
        "Agadir · Maroc — Depuis 1987"                         => "أكادير · المغرب — منذ 1987",
        "NAJAH SOUSS ECHECS"                                    => "نادي نجاح سوس للشطرنج",
        "Champions du Maroc 2025"                               => "أبطال المغرب 2025",
        "L'excellence des échecs à Agadir."                    => "التميز في الشطرنج بأكادير.",
        "Formation, Compétition et Culture."                    => "تكوين، منافسة، وثقافة.",
        "♞ Rejoindre le Club"                                   => "♞ انضم إلى النادي",
        "Voir le Palmarès"                                      => "شاهد سجل الإنجازات",
        "Coupe du Trône 2025"                                   => "كأس العرش 2025",
        "Médailles d'Or 2025-26"                               => "ميداليات ذهبية 2025-26",
        "Affilié"                                               => "منضوٍ",
        "Année de fondation"                                    => "سنة التأسيس",
        "À propos"                                              => "من نحن",
        "À PROPOS"                                              => "من نحن",
        "D'EXCELLENCE"                                          => "نبذة عن التميز",
        "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987." => "نادي نجاح سوس للشطرنج هو نادي عريق مقره في أكادير، في قلب جهة سوس ماسة، تأسس عام 1987.",
        "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale." => "منضوٍ رسمياً تحت لواء الجامعة الملكية المغربية للشطرنج (FRME) ومعترف به من طرف الاتحاد الدولي للشطرنج (FIDE)، يمثل نادينا جهة سوس ماسة على الساحة الوطنية والدولية.",
        "« Aucun coup ne doit être joué sans but. »"           => "« لا ينبغي لعب أي نقلة دون هدف »",
        "Affilié FRME"                                          => "منضوٍ تحت لواء FRME",
        "Reconnu FIDE"                                          => "معترف به من FIDE",
        "Agadir, Maroc"                                         => "أكادير، المغرب",
        "Depuis 1987"                                           => "منذ عام 1987",
        "Éducation"                                             => "التعليم",
        "Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité." => "الشطرنج كأداة تربوية لتطوير التركيز، المنطق، والإبداع.",
        "Compétition"                                           => "المنافسة",
        "Participation aux tournois régionaux, nationaux et internationaux homologués FIDE/FRME." => "المشاركة في البطولات الجهوية، الوطنية، والدولية المعتمدة من طرف FIDE/FRME.",
        "Fair-play"                                             => "الروح الرياضية",
        "Respect mutuel, éthique irréprochable et esprit sportif au cœur de chaque partie." => "الاحترام المتبادل، الأخلاق العالية، والروح الرياضية في صميم كل مباراة.",
        "Notre Palmarès"                                        => "سجل الإنجازات",
        "Coupe du Trône 2025, 8 médailles d'or et 4 sélections nationales — l'excellence de Najah Souss Echecs." => "كأس العرش 2025: 8 ميداليات ذهبية و4 اختيارات للمنتخب الوطني، تميز نجاح سوس للشطرنج.",
        "Mur des Champions"                                     => "حائط الأبطال",
        "Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs." => "وجوه تميزنا — أولئك الذين يحملون ألوان نادي نجاح سوس للشطرنج.",
        "Voir tous les champions"                               => "عرض جميع الأبطال",
        "Programmes pédagogiques pour tous les niveaux, encadrés par des entraîneurs certifiés et des joueurs classés FIDE." => "برامج تعليمية لجميع المستويات، تحت إشراف مدربين معتمدين ولاعبين مصنفين دولياً (FIDE).",
        "Débutant"                                              => "مبتدئ",
        "Les Pions"                                             => "البيادق",
        "Règles & mouvements"                                   => "القواعد والحركات",
        "Tactiques simples"                                     => "تكتيكات بسيطة",
        "Mat en 1-2 coups"                                      => "كش مات في نقلة أو نقلتين",
        "Parties guidées"                                       => "مباريات موجهة",
        "POPULAIRE"                                             => "شائع",
        "Intermédiaire"                                         => "متوسط",
        "Les Fous & Tours"                                      => "الأفيال والقلاع",
        "Principes d'ouverture"                                 => "مبادئ الافتتاح",
        "Combinaisons tactiques"                                => "تركيبات تكتيكية",
        "Fins de partie"                                        => "نهايات المباريات",
        "Analyse de parties"                                    => "تحليل المباريات",
        "Avancé"                                                => "متقدم",
        "Les Dames & Rois"                                      => "الوزراء والملوك",
        "Stratégie positionnelle"                               => "الاستراتيجية الموضعية",
        "Répertoires d'ouvertures"                              => "افتتاحيات متنوعة",
        "Préparation tournois"                                  => "التحضير للبطولات",
        "Analyse informatique"                                  => "التحليل باستخدام الحاسوب",
        "Choisissez la formule qui vous convient et progressez à votre rythme avec nos coachs FIDE." => "اختر الصيغة التي تناسبك وتقدم بالوتيرة التي تريحك مع مدربينا المعتمدين من FIDE.",
        "Galerie"                                               => "المعرض",
        "Galerie Najah Souss"                                   => "معرض نجاح سوس",
        "Tournois, cérémonies, entraînements et victoires — la vie du club en images." => "بطولات، حفلات، تداريب وانتصارات — حياة النادي بالصور.",
        "Calendrier 2025"                                       => "روزنامة 2025",
        "Nos Événements"                                        => "أنشطتنا وفعالياتنا",
        "Des tournois pour tous les niveaux, des compétitions officielles aux événements culturels." => "بطولات لجميع المستويات، من المنافسات الرسمية إلى الفعاليات الثقافية.",
        "Médias & Presse"                                       => "الإعلام والصحافة",
        "Ils parlent de nous"                                   => "تغطية الصحافة",
        "La presse nationale et locale couvre les succès de Najah Souss Echecs." => "الصحافة الوطنية والمحلية تغطي نجاحات نادي نجاح سوس للشطرنج.",
        "Notre Actualité"                                       => "آخر أخبارنا",
        "Les dernières nouvelles, événements et annonces de Najah Souss Echecs." => "آخر المستجدات، الأنشطة والإعلانات الخاصة بنادي نجاح سوس للشطرنج.",
        "Direction & Encadrement"                               => "الإدارة والتأطير",
        "Staff Technique & Administratif"                       => "الطاقم التقني والإداري",
        "Une équipe d'experts au service de l'excellence des échecs." => "فريق من الخبراء في خدمة التميز في الشطرنج.",
        "Voir tout le staff"                                    => "عرض كل الطاقم",
        "Supportez votre club"                                  => "ادعم ناديك",
        "Boutique Officielle"                                   => "المتجر الرسمي",
        "Voir toute la boutique"                                => "عرض كل المتجر",
        "Portez fièrement les couleurs de Najah Souss Echecs avec notre collection exclusive." => "ارتدِ بفخر ألوان نادي نجاح سوس للشطرنج مع تشكيلتنا الحصرية.",
        "Partenariat"                                           => "الشراكات",
        "Devenir Partenaire"                                    => "كن شريكاً",
        "des Champions"                                         => "للأبطال",
        "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires." => "اربط علامتك التجارية بالتميز في أكادير. يقدم نادي نجاح سوس - أبطال كأس العرش 2025 - رؤية وإشعاعاً وطنياً ودولياً لشركائه.",
        "Visibilité Nationale"                                  => "إشعاع وطني",
        "Tournois FRME / FIDE"                                  => "بطولات الجامعة الملكية والاتحاد الدولي",
        "Réseaux Sociaux"                                       => "شبكات التواصل الاجتماعي",
        "Communauté engagée"                                    => "مجتمع متفاعل",
        "Impact Social"                                         => "تأثير اجتماعي",
        "Éducation jeunesse"                                    => "تثقيف وتوعية الشباب",
        "TÉLÉCHARGER LE DOSSIER SPONSORING"                    => "تحميل ملف الرعاية/الاستشهار",
        "NOUS CONTACTER"                                        => "اتصل بنا",
        "Contact"                                               => "اتصل بنا",
        "Rejoignez Najah Souss Echecs"                          => "انضم إلى نادي نجاح سوس",
        "Une question, une inscription ou un partenariat ? Nous sommes là pour vous." => "هل لديك سؤال، ترغب في التسجيل أو عقد شراكة؟ نحن هنا من أجلك.",
        "Adresse"                                               => "العنوان",
        "Local Najah Souss Echecs, Corniche d'Agadir"          => "مقر نادي نجاح سوس للشطرنج، كورنيش أكادير.",
        "Email"                                                 => "البريد الإلكتروني",
        "Téléphone"                                             => "الهاتف",
        "Comment s'inscrire ?"                                  => "كيف تسجل؟",
        "Remplissez le formulaire ci-contre"                    => "املأ النموذج",
        "Un responsable vous contactera sous 48h"               => "سيتواصل معك أحد المسؤولين خلال 48 ساعة",
        "Participez à une séance d'essai gratuite"              => "شارك في حصة تجريبية مجانية",
        "Rejoignez officiellement le club !"                    => "انضم رسمياً إلى النادي!",
        "Nom complet *"                                         => "الاسم الكامل *",
        "Email *"                                               => "البريد الإلكتروني *",
        "Message *"                                             => "الرسالة *",
        "Envoyer ma demande ♞"                                  => "إرسال الطلب ♞",
        "Tous droits réservés."                                 => "حقوق النشر © 2026 نادي نجاح سوس للشطرنج. جميع الحقوق محفوظة.",
        // Additional UI strings
        "Voir tous"                                             => "عرض الكل",
        "Lire l'article"                                        => "اقرأ المقال",
        "S'INSCRIRE"                                            => "التسجيل في النادي",
        "S'inscrire"                                            => "التسجيل في النادي",
        "Acheter"                                               => "اشترِ الآن",
        "Lire la suite"                                         => "اقرأ المزيد",
        "Retour"                                                => "العودة",
        "Imprimer"                                              => "طباعة",
        "Partager"                                              => "مشاركة",
        "Lien copié !"                                          => "تم نسخ الرابط!",
        "JOUEUR"                                                => "اللاعب",
        "FIDE ID"                                               => "رقم FIDE",
        "CATÉGORIE"                                             => "الفئة",
        "ELO"                                                   => "التصنيف",
        "TITRE"                                                 => "اللقب",
        "Salle de Jeu en Ligne"                                 => "قاعة اللعب الإلكترونية",
        "Encadrement Expert"                                    => "تأطير خبير",
        "Cours individuels & collectifs disponibles"           => "دروس فردية وجماعية متاحة",
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
            $str = str_replace(array("'", "’", "\'", "\’"), "'", $str);
            $str = preg_replace('/\s+/', ' ', $str);
        $str = mb_strtolower(trim($str), 'UTF-8');
        $unwanted_array = array(
            'š'=>'s', 'ž'=>'z', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
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

        'Agadir · Maroc — Depuis 1987' => array('ar' => 'أكادير · المغرب — منذ 1987', 'en' => 'Agadir · Morocco — Since 1987'),
        // ── FIDE TABLE HEADERS ──────────────────────────────────────────────────
        'JOUEUR'    => array('ar' => 'اللاعب',    'en' => 'PLAYER'),
        'FIDE ID'   => array('ar' => 'رقم FIDE',  'en' => 'FIDE ID'),
        'CATÉGORIES'=> array('ar' => 'الفئة',     'en' => 'CATEGORY'),
        'CATÉGORIE' => array('ar' => 'الفئة',     'en' => 'CATEGORY'),
        'STANDARD'  => array('ar' => 'قياسي',     'en' => 'STANDARD'),
        'RAPID'     => array('ar' => 'سريع',      'en' => 'RAPID'),
        'BLITZ'     => array('ar' => 'خاطف',      'en' => 'BLITZ'),
        // ── ABOUT SECTION ────────────────────────────────────────────────────────
        'Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité.' => array(
            'ar' => 'الشطرنج كأداة تعليمية لتطوير التركيز، المنطق، والإبداع.',
            'en' => 'Chess as an educational tool to develop concentration, logic, and creativity.'
        ),
        // ── PARTNERSHIP PERKS ────────────────────────────────────────────────────
        'Visibilité Nationale'  => array('ar' => 'إشعاع وطني',                     'en' => 'National Visibility'),
        'Tournois FRME / FIDE'  => array('ar' => 'بطولات FRME / FIDE',             'en' => 'FRME / FIDE Tournaments'),
        'Réseaux Sociaux'       => array('ar' => 'شبكات التواصل الاجتماعي',        'en' => 'Social Networks'),
        'Communauté engagée'    => array('ar' => 'مجتمع متفاعل',                   'en' => 'Engaged Community'),
        'Impact Social'         => array('ar' => 'تأثير مجتمعي',                   'en' => 'Social Impact'),
        'Éducation jeunesse'    => array('ar' => 'تعليم الشباب',                   'en' => 'Youth Education'),
        'NAJAH SOUSS <span class="gradient-gold">ECHECS</span>' => array('ar' => 'نادي نجاح سوس للشطرنج', 'en' => 'Najah Souss <span class="gradient-gold">Chess Club</span>'),
        'Vainqueurs de la Coupe du Trône 2025' => array('ar' => 'أبطال كأس العرش 2025', 'en' => '2025 Throne Cup Champions'),
        'Champions du Maroc 2025' => array('ar' => 'أبطال المغرب 2025', 'en' => 'Morocco Champions 2025'),
        'L\'excellence des échecs à Agadir.' => array('ar' => 'التميز في الشطرنج بأكادير.', 'en' => 'Chess Excellence in Agadir.'),
        'Formation, Compétition et Culture.' => array('ar' => 'تكوين، منافسة وثقافة.', 'en' => 'Training, Competition, and Culture.'),
        'Rejoindre le Club' => array('ar' => 'انضم إلى النادي', 'en' => 'Join the Club'),
        'Voir le Palmarès' => array('ar' => 'شاهد سجل الإنجازات', 'en' => 'View our Achievements'),
        'Coupe du Trône 2025' => array('ar' => 'كأس العرش 2025', 'en' => '2025 Throne Cup'),
        'Médailles d\'Or 2025-26' => array('ar' => 'ميداليات ذهبية 2025-26', 'en' => 'Gold Medals 2025-26'),
        'Retour à l\'accueil' => array('ar' => 'العودة للرئيسية', 'en' => 'Back to Home'),
        'Profil FIDE Officiel' => array('ar' => 'الملف الشخصي FIDE', 'en' => 'Official FIDE Profile'),
        'Date & Heure' => array('ar' => 'التاريخ والوقت', 'en' => 'Date & Time'),
        'Lieu' => array('ar' => 'المكان', 'en' => 'Location'),
        'Taille du texte' => array('ar' => 'حجم النص', 'en' => 'Text Size'),
        'Affilié' => array('ar' => 'عضو', 'en' => 'Affiliated'),
        'Année de fondation' => array('ar' => 'سنة التأسيس', 'en' => 'Founded'),
        'À propos' => array('ar' => 'من نحن', 'en' => 'About Us'),
        'À PROPOS' => array('ar' => 'من نحن', 'en' => 'ABOUT US'),
        'D\'EXCELLENCE' => array('ar' => 'والتميز', 'en' => 'OF EXCELLENCE'),
        '« Aucun coup ne doit être joué sans but »' => array('ar' => '« لا يجب لعب أي نقلة بدون هدف »', 'en' => '« No move should be played without a purpose »'),
        'Affilié FRME' => array('ar' => 'عضو في الجامعة الملكية', 'en' => 'FRME Affiliated'),
        'Reconnu FIDE' => array('ar' => 'معترف به من FIDE', 'en' => 'FIDE Recognized'),
        'Agadir, Maroc' => array('ar' => 'أكادير، المغرب', 'en' => 'Agadir, Morocco'),
        'Depuis 1987' => array('ar' => 'منذ 1987', 'en' => 'Since 1987'),
        'Éducation' => array('ar' => 'التعليم', 'en' => 'Education'),
        'Compétition' => array('ar' => 'المنافسة', 'en' => 'Competition'),
        'Fair-play' => array('ar' => 'الروح الرياضية', 'en' => 'Sportsmanship'),
        'Notre Palmarès' => array('ar' => 'سجل الإنجازات', 'en' => 'Our Achievements'),
        'TITRE MAJEUR' => array('ar' => 'اللقب الأبرز', 'en' => 'MAJOR TITLE'),
        'Débutant' => array('ar' => 'مبتدئ', 'en' => 'Beginner'),
        'Les Pions' => array('ar' => 'البيادق', 'en' => 'The Pawns'),
        'Intermédiaire' => array('ar' => 'متوسط', 'en' => 'Intermediate'),
        'Les Fous & Tours' => array('ar' => 'الأفيال والقلاع', 'en' => 'Bishops & Rooks'),
        'Avancé' => array('ar' => 'متقدم', 'en' => 'Advanced'),
        'Les Dames & Rois' => array('ar' => 'الوزراء والملوك', 'en' => 'Queens & Kings'),
        'Demander un cours d\'essai' => array('ar' => 'طلب حصة تجريبية', 'en' => 'Request a trial class'),
        'Galerie' => array('ar' => 'معرض', 'en' => 'Gallery'),
        'Voir toute la galerie' => array('ar' => 'عرض المعرض بالكامل', 'en' => 'View full gallery'),
        'Événements' => array('ar' => 'فعاليات', 'en' => 'Events'),
        'Voir tous les événements' => array('ar' => 'عرض جميع الفعاليات', 'en' => 'View all events'),
        'Médias & Presse' => array('ar' => 'الصحافة والإعلام', 'en' => 'Media & Press'),
        'Voir toute la presse' => array('ar' => 'عرض كل التغطيات', 'en' => 'View all press coverage'),
        'Notre Actualité' => array('ar' => 'أخبارنا', 'en' => 'Latest News'),
        'Voir toutes les actualités' => array('ar' => 'عرض جميع الأخبار', 'en' => 'View all news'),
        'Direction & Encadrement' => array('ar' => 'الإدارة والتأطير', 'en' => 'Management & Coaching'),
        'Staff Technique' => array('ar' => 'الطاقم التقني', 'en' => 'Technical'),
        '& Administratif' => array('ar' => 'والإداري', 'en' => '& Administrative Staff'),
        'Supportez votre club' => array('ar' => 'ادعم ناديك', 'en' => 'Support your club'),
        'Boutique' => array('ar' => 'المتجر', 'en' => 'Official'),
        'Officielle' => array('ar' => 'الرسمي', 'en' => 'Store'),
        'NOUVEAU' => array('ar' => 'جديد', 'en' => 'NEW'),
        'Devenir Partenaire' => array('ar' => 'كن شريكاً', 'en' => 'Become a Partner'),
        'des Champions' => array('ar' => 'للأبطال', 'en' => 'of the Champions'),
        'Visibilité Nationale' => array('ar' => 'رؤية وطنية', 'en' => 'National Visibility'),
        'Tournois FRME / FIDE' => array('ar' => 'بطولات FRME / FIDE', 'en' => 'FRME / FIDE Tournaments'),
        'Réseaux Sociaux' => array('ar' => 'شبكات التواصل', 'en' => 'Social Media'),
        'Communauté engagée' => array('ar' => 'مجتمع نشط', 'en' => 'Engaged Community'),
        'Impact Social' => array('ar' => 'تأثير اجتماعي', 'en' => 'Social Impact'),
        'Éducation jeunesse' => array('ar' => 'تعليم الشباب', 'en' => 'Youth Education'),
        'TÉLÉCHARGER LE DOSSIER SPONSORING' => array('ar' => 'تحميل ملف الرعاية', 'en' => 'DOWNLOAD SPONSORSHIP DECK'),
        'Contact' => array('ar' => 'اتصل بنا', 'en' => 'Contact'),
        'NOUS CONTACTER' => array('ar' => 'اتصل بنا', 'en' => 'CONTACT US'),
        'Adresse' => array('ar' => 'العنوان', 'en' => 'Address'),
        'Email' => array('ar' => 'البريد الإلكتروني', 'en' => 'Email'),
        'Téléphone' => array('ar' => 'الهاتف', 'en' => 'Phone'),
        'Comment s\'inscrire ?' => array('ar' => 'كيف تسجل؟', 'en' => 'How to register?'),
        'Nom complet *' => array('ar' => 'الاسم الكامل *', 'en' => 'Full Name *'),
        'Email *' => array('ar' => 'البريد الإلكتروني *', 'en' => 'Email *'),
        'Message *' => array('ar' => 'الرسالة *', 'en' => 'Message *'),
        'Envoyer ma demande ♞' => array('ar' => 'إرسال طلبي ♞', 'en' => 'Send Request ♞'),
        'Palmarès' => array('ar' => 'قاعة المشاهير', 'en' => 'Hall of Fame'),
        'Presse' => array('ar' => 'الصحافة', 'en' => 'Press'),
        'Académie' => array('ar' => 'الأكاديمية', 'en' => 'Academy'),
        'Accueil' => array('ar' => 'الرئيسية', 'en' => 'Home'),
        'Le Club' => array('ar' => 'النادي', 'en' => 'The Club'),
        'S\'inscrire' => array('ar' => 'التسجيل', 'en' => 'Register'),
        'Tous droits réservés.' => array('ar' => 'جميع الحقوق محفوظة.', 'en' => 'All rights reserved.'),
        'Réseaux sociaux' => array('ar' => 'شبكات التواصل الاجتماعي', 'en' => 'Social Networks'),
        'Champions du Maroc 2025 · Depuis 1987' => array('ar' => 'أبطال المغرب 2025 · منذ 1987', 'en' => 'Morocco Champions 2025 · Since 1987'),
        'Echecs · Depuis 1987' => array('ar' => 'الشطرنج · منذ 1987', 'en' => 'Chess · Since 1987'),
        'Partenariat' => array('ar' => 'الشراكات', 'en' => 'Partnerships'),
        'Galerie Najah Souss' => array('ar' => 'معرض صور نجاح سوس', 'en' => 'Najah Souss Gallery'),
        'Calendrier des Événements' => array('ar' => 'جدول فعاليات نادي الشطرنج', 'en' => 'Chess Club Events Calendar'),
        'Ils parlent de nous' => array('ar' => 'هم يتحدثون عنا', 'en' => 'They talk about us'),
        'Notre Actualité' => array('ar' => 'أخبارنا', 'en' => 'Latest News'),
        'Staff Technique & Administratif' => array('ar' => 'الطاقم الفني والإداري', 'en' => 'Technical & Administrative Staff'),
        'Une équipe d\'experts au service de l\'excellence des échecs.' => array('ar' => 'فريق من الخبراء في خدمة التميز في الشطرنج.', 'en' => 'A team of experts dedicated to chess excellence.'),
        'Boutique Officielle' => array('ar' => 'المتجر الرسمي', 'en' => 'Official Store'),
        'Voir toute la boutique' => array('ar' => 'عرض كل المتجر', 'en' => 'View All Shop'),
        'Portez fièrement les couleurs de Najah Souss Echecs avec notre collection exclusive.' => array('ar' => 'ارتد ألوان نادي نجاح سوس للشطرنج بفخر مع مجموعتنا الحصرية.', 'en' => 'Wear the colors of Najah Souss Chess with pride through our exclusive collection.'),
        'Devenir Partenaire des Champions' => array('ar' => 'كن شريكاً للأبطال', 'en' => 'Become a Partner of Champions'),
        'Associez votre marque à l\'excellence d\'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires.' => array('ar' => 'اربط علامتك التجارية بالتميز في أكادير. يقدم نادي نجاح سوس - أبطال كأس العرش 2025 - رؤية وإشعاعاً وطنياً ودولياً لشركائه.', 'en' => 'Associate your brand with Agadir\'s excellence. Najah Souss Chess — 2025 Throne Cup Champions — offers national and international visibility to its partners.'),
        'Rejoignez Najah Souss Echecs' => array('ar' => 'انضم إلى نادي نجاح سوس للشطرنج', 'en' => 'Join Najah Souss Chess'),
        'Une question, une inscription ou un partenariat ? Nous sommes là pour vous.' => array('ar' => 'هل لديك سؤال، ترغب في التسجيل أو عقد شراكة؟ نحن هنا من أجلك.', 'en' => 'Do you have a question, want to register, or form a partnership? We are here for you.'),
        'Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité.' => array('ar' => 'الشطرنج كأداة تعليمية لتطوير التركيز، المنطق، والإبداع.', 'en' => 'Chess as an educational tool to develop concentration, logic, and creativity.'),
        'Mur des Champions' => array('ar' => 'جدار الأبطال', 'en' => 'Wall of Champions'),
        'Voir tous les champions' => array('ar' => 'عرض جميع الأبطال', 'en' => 'View all champions'),
        'Règles & mouvements' => array('ar' => 'القواعد والحركات', 'en' => 'Rules & movements'),
        'Tactiques simples' => array('ar' => 'تكتيكات بسيطة', 'en' => 'Simple tactics'),
        'Mat en 1-2 coups' => array('ar' => 'كش مات في نقلة أو نقلتين', 'en' => 'Mate in 1-2 moves'),
        'Parties guidées' => array('ar' => 'مباريات موجهة', 'en' => 'Guided games'),
        'Principes d\'ouverture' => array('ar' => 'مبادئ الافتتاح', 'en' => 'Opening principles'),
        'Combinaisons tactiques' => array('ar' => 'تركيبات تكتيكية', 'en' => 'Tactical combinations'),
        'Fins de partie' => array('ar' => 'أواخر الأدوار', 'en' => 'Endgames'),
        'Analyse de parties' => array('ar' => 'تحليل المباريات', 'en' => 'Game analysis'),
        'Stratégie positionnelle' => array('ar' => 'الاستراتيجية الموقعية', 'en' => 'Positional strategy'),
        'Répertoires d\'ouvertures' => array('ar' => 'موسوعة الافتتاحات', 'en' => 'Opening repertoires'),
        'Préparation tournois' => array('ar' => 'الاستعداد للبطولات', 'en' => 'Tournament preparation'),
        'Analyse informatique' => array('ar' => 'التحليل الرقمي المحوسب', 'en' => 'Computer analysis'),
        'À venir' => array('ar' => 'قريباً', 'en' => 'Upcoming'),
        'Bientôt' => array('ar' => 'قريباً', 'en' => 'Soon'),
        'Tournoi FIDE' => array('ar' => 'بطولة FIDE', 'en' => 'FIDE Tournament'),
        'Stage' => array('ar' => 'معسكر', 'en' => 'Camp'),
        'Compétition' => array('ar' => 'منافسة', 'en' => 'Competition'),
        'U8 F' => array('ar' => 'أقل من 8 إناث', 'en' => 'U8 Girls'),
        'U14 M' => array('ar' => 'أقل من 14 ذكور', 'en' => 'U14 Boys'),
        'U20 F' => array('ar' => 'أقل من 20 إناث', 'en' => 'U20 Girls'),
        'U18 M' => array('ar' => 'أقل من 18 ذكور', 'en' => 'U18 Boys'),
        // 1. About Section
        "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987." => array('ar' => "نادي نجاح سوس للشطرنج هو نادي عريق مقره في أكادير، في قلب جهة سوس ماسة، تأسس عام 1987.", 'en' => "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987."),
        "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale." => array('ar' => "منضوٍ رسمياً تحت لواء الجامعة الملكية المغربية للشطرنج (FRME) ومعترف به من طرف الاتحاد الدولي للشطرنج (FIDE)، يمثل نادينا جهة سوس ماسة على الساحة الوطنية والدولية.", 'en' => "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage."),
        
        // 2. Academy & Play Sections
        "Académie & Formation" => array('ar' => "الأكاديمية والتكوين", 'en' => "Academy & Training"),
        "Formez-vous avec des <span class=\"gradient-gold\">Experts</span>" => array('ar' => "تدرب مع <span class=\"gradient-gold\">الخبراء</span>", 'en' => "Train with <span class=\"gradient-gold\">Experts</span>"),
        "Encadrement Expert" => array('ar' => "تأطير من الخبراء", 'en' => "Expert Coaching"),
        "Cours individuels & collectifs disponibles" => array('ar' => "دروس فردية وجماعية متاحة", 'en' => "Individual & group lessons available"),
        "Prêt à relever le défi ?" => array('ar' => "هل أنت مستعد لرفع التحدي؟", 'en' => "Ready for the challenge?"),
        "Affrontez des joueurs du monde entier ou entraînez-vous contre l'IA sur notre espace dédié." => array('ar' => "واجه لاعبين من جميع أنحاء العالم أو تدرب ضد الذكاء الاصطناعي في مساحتنا المخصصة.", 'en' => "Face players from around the world or practice against AI in our dedicated arena."),
        "Jouer aux Échecs Maintenant ♞" => array('ar' => "العب الشطرنج الآن ♞", 'en' => "Play Chess Now ♞"),
        
        // 3. Various Headings & Buttons
        "Notre Équipe - <span class=\"gradient-gold\">Classement FIDE</span>" => array('ar' => "فريقنا - <span class=\"gradient-gold\">تصنيف FIDE الدولي</span>", 'en' => "Our Team - <span class=\"gradient-gold\">FIDE Ratings</span>"),
        "Nos Events" => array('ar' => "أنشطتنا وفعالياتنا", 'en' => "Our Events"),
        "Notre <span class=\"gradient-gold\">Actualité</span>" => array('ar' => "آخر <span class=\"gradient-gold\">أخبارنا</span>", 'en' => "Latest <span class=\"gradient-gold\">News</span>"),
        "Voir tout le staff" => array('ar' => "عرض كل الطاقم", 'en' => "View all staff"),
        // About Section
        "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987." => array('en' => "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987.", 'ar' => "نادي نجاح سوس للشطرنج هو نادي عريق مقره في أكادير، في قلب جهة سوس ماسة، تأسس عام 1987."),
        "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale." => array('en' => "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage.", 'ar' => "منضوٍ رسمياً تحت لواء الجامعة الملكية المغربية للشطرنج (FRME) ومعترف به من طرف الاتحاد الدولي للشطرنج (FIDE)، يمثل نادينا جهة سوس ماسة على الساحة الوطنية والدولية."),
        "« Aucun coup ne doit être joué sans but. »" => array('en' => "« No move should be played without a purpose. »", 'ar' => "« لا ينبغي لعب أي نقلة دون هدف »"),

        // Academy, Play & Challenge Sections
        "Académie & Formation" => array('en' => 'Academy & Training', 'ar' => 'الأكاديمية والتكوين'),
        "Formez-vous avec des Experts" => array('en' => 'Train with Experts', 'ar' => 'تدرب مع الخبراء'),
        "Encadrement Expert" => array('en' => 'Expert Coaching', 'ar' => 'تأطير من الخبراء'),
        "Cours individuels & collectifs disponibles" => array('en' => 'Individual & group lessons available', 'ar' => 'دروس فردية وجماعية متاحة'),
        "Prêt à relever le défi ?" => array('en' => 'Ready for the challenge?', 'ar' => 'هل أنت مستعد لرفع التحدي؟'),
        "Affrontez des joueurs du monde entier ou entraînez-vous contre l'IA sur notre espace dédié." => array('en' => "Face players from around the world or practice against AI in our dedicated arena.", 'ar' => "واجه لاعبين من جميع أنحاء العالم أو تدرب ضد الذكاء الاصطناعي في مساحتنا المخصصة."),
        "Jouer aux Échecs Maintenant ♞" => array('en' => 'Play Chess Now ♞', 'ar' => 'العب الشطرنج الآن ♞'),

        // Headings, Staff & Contact
        "Notre Équipe - Classement FIDE" => array('en' => 'Our Team - FIDE Ratings', 'ar' => 'فريقنا - تصنيف FIDE الدولي'),
        "Nos Events" => array('en' => 'Our Events', 'ar' => 'أنشطتنا وفعالياتنا'),
        "Notre Actualité" => array('en' => 'Latest News', 'ar' => 'آخر أخبارنا'),
        "Voir tout le staff" => array('en' => 'View all staff', 'ar' => 'عرض كل الطاقم'),
        "Une question, une inscription ou un partenariat ? Nous sommes là pour vous." => array('en' => "Got a question, want to register, or discuss a partnership? We are here for you.", 'ar' => "هل لديك سؤال، ترغب في التسجيل أو عقد شراكة؟ نحن هنا من أجلك."),

        // Partnership Section
        "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires." => array('en' => "Associate your brand with Agadir's standard of excellence. Najah Souss Chess — 2025 Throne Cup Champions — offers national and international visibility to its partners.", 'ar' => "اربط علامتك التجارية بالتميز في أكادير. يقدم نادي نجاح سوس - أبطال كأس العرش 2025 - رؤية وإشعاعاً وطنياً ودولياً لشركائه."),
        
        // Tags
        "À venir" => array('en' => 'Upcoming', 'ar' => 'قادم'),
        "Terminé" => array('en' => 'Past', 'ar' => 'منتهي'),
        "Lire l'article" => array('en' => 'Read Article', 'ar' => 'اقرأ المقال'),

        
        // 4. Partnership & Contact
        "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires." => array('ar' => "اربط علامتك التجارية بالتميز في أكادير. يقدم نادي نجاح سوس - أبطال كأس العرش 2025 - رؤية وإشعاعاً وطنياً ودولياً لشركائه.", 'en' => "Associate your brand with Agadir's standard of excellence. Najah Souss Chess — 2025 Throne Cup Champions — offers national and international visibility to its partners."),
        "Une question, une inscription ou un partenariat ? Nous sommes là pour vous." => array('ar' => "هل لديك سؤال، ترغب في التسجيل أو عقد شراكة؟ نحن هنا من أجلك.", 'en' => "Got a question, want to register, or discuss a partnership? We are here for you."),
        "Prêt à relever le défi !" => array('ar' => "هل أنت مستعد لرفع التحدي؟", 'en' => "Ready for the challenge!"),
        "Galerie Najah Souss" => array('ar' => "معرض نجاح سوس", 'en' => "Najah Souss Gallery"),
        "Rejoignez Najah Souss Echecs" => array('ar' => "انضم إلى نادي نجاح سوس", 'en' => "Join Najah Souss Chess"),
        "Devenir Partenaire" => array('ar' => "كن شريكاً", 'en' => "Become a Partner"),
        "des Champions" => array('ar' => "للأبطال", 'en' => "of the Champions"),
        "Participez à une séance d'essai gratuite" => array('ar' => "شارك في جلسة تجريبية مجانية", 'en' => "Join a free trial session"),
        "Une équipe d'experts au service de l'excellence des échecs." => array('ar' => "فريق من الخبراء في خدمة التميز في الشطرنج.", 'en' => "A team of experts dedicated to chess excellence."),
        "Album Photo" => array('ar' => "ألبوم الصور", 'en' => "Photo Album"),
        "Image" => array('ar' => "صورة", 'en' => "Image"),
        "Agrandir l'image" => array('ar' => "تكبير الصورة", 'en' => "Enlarge image"),
        "Aperçu de l'image" => array('ar' => "معاينة الصورة", 'en' => "Image preview"),
        "Fermer" => array('ar' => "إغلاق", 'en' => "Close"),
        "Aperçu" => array('ar' => "معاينة", 'en' => "Preview"),
        "Précédent" => array('ar' => "السابق", 'en' => "Previous"),
        "Suivant" => array('ar' => "التالي", 'en' => "Next"),
        "Aucune image dans cet album pour le moment." => array('ar' => "لا توجد صور في هذا الألبوم حالياً.", 'en' => "No images in this album yet."),
        "Retour aux albums" => array('ar' => "العودة إلى الألبومات", 'en' => "Back to Albums"),
            );
        
        $normalized_dict = array();
        foreach ($dict as $key => $trans) {
            $normalized_dict[$normalize($key)] = $trans;
        }
        
        $mappings = array(

        'Les échecs comme outil pédagog' => array('ar' => 'الشطرنج كأداة تربوية لتطوير التركيز، المنطق، والإبداع.', 'en' => 'Chess as an educational tool to develop focus, logic, and creativity.'),
        'Participation aux tournois rég' => array('ar' => 'المشاركة في البطولات الجهوية، الوطنية، والدولية المعتمدة من طرف FIDE/FRME.', 'en' => 'Participation in FIDE/FRME rated regional, national, and international tournaments.'),
        'Respect mutuel, éthique irrépr' => array('ar' => 'الاحترام المتبادل، الأخلاق العالية، والروح الرياضية في صميم كل مباراة.', 'en' => 'Mutual respect, impeccable ethics, and sportsmanship at the heart of every game.'),
        'Coupe du Trône 2025, 8 médaill' => array('ar' => 'كأس العرش 2025: 8 ميداليات ذهبية و4 اختيارات للمنتخب الوطني، تميز نجاح سوس للشطرنج.', 'en' => '2025 Throne Cup, 8 gold medals, and 4 national team selections — the standard of excellence at Najah Souss Chess.'),
        'Les visages de notre excellenc' => array('ar' => 'وجوه تميزنا — أولئك الذين يحملون ألوان نادي نجاح سوس للشطرنج.', 'en' => 'The faces of our excellence — those who proudly wear the colors of Najah Souss Chess.'),
        'Programmes pédagogiques pour t' => array('ar' => 'برامج تعليمية لجميع المستويات، تحت إشراف مدربين معتمدين ولاعبين مصنفين دولياً (FIDE).', 'en' => 'Educational programs for all levels, led by certified coaches and FIDE-rated players.'),
        'Choisissez la formule qui vous' => array('ar' => 'اختر الصيغة التي تناسبك وتقدم بالوتيرة التي تريحك مع مدربينا المعتمدين من FIDE.', 'en' => 'Choose the program that suits you and progress at your own pace with our FIDE coaches.'),
        'Tournois, cérémonies, entraîne' => array('ar' => 'بطولات، حفلات، تداريب وانتصارات — حياة النادي بالصور.', 'en' => 'Tournaments, ceremonies, training, and victories — club life in pictures.'),
        'Des tournois pour tous les niv' => array('ar' => 'بطولات لجميع المستويات، من المنافسات الرسمية إلى الفعاليات الثقافية.', 'en' => 'Tournaments for all levels, from official competitions to cultural events.'),
        'La presse nationale et locale ' => array('ar' => 'الصحافة الوطنية والمحلية تغطي نجاحات نادي نجاح سوس للشطرنج.', 'en' => 'National and local press covering the success of Najah Souss Chess.'),
        'Les dernières nouvelles, événe' => array('ar' => 'آخر المستجدات، الأنشطة والإعلانات الخاصة بنادي نجاح سوس للشطرنج.', 'en' => 'The latest news, events, and announcements from Najah Souss Chess.'),
        'Une équipe d\'experts au servic' => array('ar' => 'فريق من الخبراء في خدمة التميز في الشطرنج.', 'en' => 'A team of experts dedicated to chess excellence.'),
        'Portez fièrement les couleurs ' => array('ar' => 'ارتدِ بفخر ألوان نادي نجاح سوس للشطرنج مع تشكيلتنا الحصرية.', 'en' => 'Wear the Najah Souss Chess colors with pride through our exclusive collection.'),
        'Remplissez le formulaire ci-co' => array('ar' => 'املأ النموذج', 'en' => 'Fill out the form'),
        'Un responsable vous contactera' => array('ar' => 'سيتواصل معك أحد المسؤولين خلال 48 ساعة', 'en' => 'A representative will contact you within 48h'),
        'Participez à une séance d\'essai' => array('ar' => 'شارك في حصة تجريبية مجانية', 'en' => 'Attend a free trial session'),
        'Rejoignez officiellement le cl' => array('ar' => 'انضم رسمياً إلى النادي!', 'en' => 'Officially join the club!'),
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
                <th class="px-4 py-4 text-start"><?php echo ansae_t('CATÉGORIE'); ?></th>
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
