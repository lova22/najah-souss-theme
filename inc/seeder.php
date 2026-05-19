<?php
/**
 * Najah Souss Echecs — Comprehensive Demo Data Seeder v7
 * Populates: Events (3), Posts/News (3), Press (4), Gallery (3)
 * Runs once on `init` and marks itself complete via a DB option flag.
 */

// ── Helper: set Polylang language ─────────────────────────────────────────────
if ( ! function_exists('najah_set_lang') ) {
    function najah_set_lang( $post_id, $lang ) {
        if ( function_exists('pll_set_post_language') ) {
            pll_set_post_language( $post_id, $lang );
        }
    }
}

// ── Helper: link translations across languages ────────────────────────────────
if ( ! function_exists('najah_link_tr') ) {
    function najah_link_tr( array $ids ) {
        if ( function_exists('pll_save_post_translations') ) {
            pll_save_post_translations( $ids );
        }
    }
}

// ── Helper: safe ACF update ───────────────────────────────────────────────────
if ( ! function_exists('najah_acf') ) {
    function najah_acf( $key, $value, $post_id ) {
        if ( function_exists('update_field') ) {
            update_field( $key, $value, $post_id );
        } else {
            update_post_meta( $post_id, $key, $value );
        }
    }
}

// ─────────────────────────────────────────────────────────────────────────────
function najah_souss_run_seeder_v7() {

    if ( get_option('najah_souss_seeder_run_v7') ) {
        return;
    }

    $langs = array('fr', 'ar', 'en');

    // ══════════════════════════════════════════════════════════════════════════
    // 1. EVENTS  — 3 items × 3 languages
    // ══════════════════════════════════════════════════════════════════════════
    $events = array(
        array(
            'fr'   => array(
                'title'   => 'Tournoi International d\'Agadir 2025',
                'excerpt' => 'Le plus grand rassemblement d\'échecs au sud du Maroc.'
            ),
            'ar'   => array(
                'title'   => 'الدوري الدولي للشطرنج بأكادير 2025',
                'excerpt' => 'أكبر تجمع شطرنجي في جنوب المغرب.'
            ),
            'en'   => array(
                'title'   => 'Agadir International Chess Tournament 2025',
                'excerpt' => 'The largest chess gathering in southern Morocco.'
            ),
            'meta' => array(
                'event_date'     => '15 - 20 Août 2025',
                'event_location' => 'Hôtel Royal, Agadir',
                'event_icon'     => '🏆',
                'event_badge'    => 'À venir',
                'event_category' => 'Tournoi FIDE',
            ),
        ),
        array(
            'fr'   => array(
                'title'   => 'Stage de Préparation — Équipe Nationale',
                'excerpt' => 'Intensif avec nos 4 sélectionnés nationaux.'
            ),
            'ar'   => array(
                'title'   => 'معسكر الإعداد — الفريق الوطني',
                'excerpt' => 'تدريب مكثف لأبطالنا الأربعة الوطنيين.'
            ),
            'en'   => array(
                'title'   => 'Preparation Camp — National Team',
                'excerpt' => 'Intensive camp with our 4 national representatives.'
            ),
            'meta' => array(
                'event_date'     => '05 Juillet 2025',
                'event_location' => 'Académie ANSAE, Agadir',
                'event_icon'     => '♟',
                'event_badge'    => 'À venir',
                'event_category' => 'Stage',
            ),
        ),
        array(
            'fr'   => array(
                'title'   => 'Coupe du Trône 2025 — Phases Finales',
                'excerpt' => 'Défense de notre titre national contre l\'élite du Maroc.'
            ),
            'ar'   => array(
                'title'   => 'كأس العرش 2025 — المراحل النهائية',
                'excerpt' => 'الدفاع عن لقبنا الوطني أمام نخبة أندية المغرب.'
            ),
            'en'   => array(
                'title'   => 'Throne Cup 2025 — Final Rounds',
                'excerpt' => 'Defending our national title against Morocco\'s elite clubs.'
            ),
            'meta' => array(
                'event_date'     => 'Novembre 2025',
                'event_location' => 'Salle Omnisports, Rabat',
                'event_icon'     => '👑',
                'event_badge'    => 'Bientôt',
                'event_category' => 'Compétition',
            ),
        ),
    );

    foreach ( $events as $event ) {
        $ids = array();
        foreach ( $langs as $lang ) {
            $d   = $event[ $lang ];
            $pid = wp_insert_post( array(
                'post_title'   => $d['title'],
                'post_excerpt' => $d['excerpt'],
                'post_content' => $d['excerpt'],
                'post_type'    => 'event',
                'post_status'  => 'publish',
            ) );
            if ( ! $pid || is_wp_error( $pid ) ) continue;
            najah_set_lang( $pid, $lang );
            foreach ( $event['meta'] as $k => $v ) {
                najah_acf( $k, $v, $pid );
            }
            $ids[ $lang ] = $pid;
        }
        najah_link_tr( $ids );
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 2. LATEST NEWS  — 3 standard WP posts × 3 languages
    // ══════════════════════════════════════════════════════════════════════════
    $news = array(
        array(
            'fr' => array(
                'title'   => 'Najah Souss remporte la Coupe du Trône 2025',
                'excerpt' => 'Une victoire historique qui consolide notre domination nationale.',
                'content' => '<p>Le club Najah Souss Echecs a remporté la Coupe du Trône 2025, un titre historique qui témoigne du travail acharné de toute l\'équipe. Cette victoire couronne des mois de préparation et place Agadir sur la carte nationale de l\'excellence en échecs.</p>',
            ),
            'ar' => array(
                'title'   => 'نجاح سوس يُتوّج بكأس العرش 2025',
                'excerpt' => 'انتصار تاريخي يُرسّخ هيمنتنا على الساحة الوطنية.',
                'content' => '<p>أحرز نادي نجاح سوس للشطرنج لقب كأس العرش 2025، في انتصار تاريخي يعكس الجهود الجبارة التي بذلها الفريق بأكمله. هذا الفوز يضع أكادير على خريطة التميز الشطرنجي الوطني.</p>',
            ),
            'en' => array(
                'title'   => 'Najah Souss Wins the 2025 Throne Cup',
                'excerpt' => 'A historic victory that cements our national dominance.',
                'content' => '<p>Najah Souss Chess Club won the 2025 Throne Cup, a historic title that reflects the hard work of the entire team. This victory places Agadir firmly on the national chess excellence map.</p>',
            ),
        ),
        array(
            'fr' => array(
                'title'   => '8 Médailles d\'Or pour nos joueurs aux Championnats Nationaux',
                'excerpt' => 'Nos athlètes brillent sur la scène nationale en décrochant un record de médailles.',
                'content' => '<p>Lors des championnats nationaux de jeunes 2025, les joueurs de Najah Souss ont décroché 8 médailles d\'or, battant ainsi le record du club. Inès Boufous (U8 F) et Fahd Amrani (U18 M) figurent parmi les lauréats.</p>',
            ),
            'ar' => array(
                'title'   => '8 ميداليات ذهبية لرياضيينا في البطولة الوطنية',
                'excerpt' => 'أبطالنا يتألقون على الصعيد الوطني بتحقيق رقم قياسي من الميداليات.',
                'content' => '<p>خلال بطولة الشباب الوطنية 2025، أحرز لاعبو نجاح سوس 8 ميداليات ذهبية، متجاوزين الرقم القياسي للنادي. إيناس بوفوس (أقل من 8 إناث) وفهد عمراني (أقل من 18 ذكور) في مقدمة المتوجين.</p>',
            ),
            'en' => array(
                'title'   => '8 Gold Medals for Our Players at the National Championships',
                'excerpt' => 'Our athletes shine nationally, breaking the club\'s medal record.',
                'content' => '<p>At the 2025 National Youth Championships, Najah Souss players won 8 gold medals, breaking the club record. Ines Boufous (U8 Girls) and Fahd Amrani (U18 Boys) were among the title winners.</p>',
            ),
        ),
        array(
            'fr' => array(
                'title'   => 'Ouverture des Inscriptions — Programme Académie 2025/26',
                'excerpt' => 'Les inscriptions au programme de formation sont désormais ouvertes pour tous les niveaux.',
                'content' => '<p>Najah Souss Echecs ouvre les inscriptions pour son programme académique 2025/26. Des cours adaptés à tous les niveaux — débutant, intermédiaire et avancé — sont disponibles. Rejoignez-nous et progressez avec des entraîneurs certifiés FIDE.</p>',
            ),
            'ar' => array(
                'title'   => 'فتح باب التسجيل — برنامج الأكاديمية 2025/26',
                'excerpt' => 'التسجيل في برنامج التكوين متاح الآن لجميع المستويات.',
                'content' => '<p>يفتح نادي نجاح سوس للشطرنج باب التسجيل لبرنامجه الأكاديمي 2025/26. دروس ملائمة لجميع المستويات — مبتدئ، متوسط، ومتقدم — متاحة الآن. انضم إلينا وطور مستواك مع مدربين معتمدين من الاتحاد الدولي.</p>',
            ),
            'en' => array(
                'title'   => 'Registration Open — Academy Programme 2025/26',
                'excerpt' => 'Enrollments for the training programme are now open for all levels.',
                'content' => '<p>Najah Souss Chess Club is now accepting registrations for its 2025/26 academic programme. Courses adapted to all levels — beginner, intermediate, and advanced — are available. Join us and improve with FIDE-certified coaches.</p>',
            ),
        ),
    );

    $news_cat_id = 0;
    $cat = get_category_by_slug('club-news');
    if ( ! $cat ) {
        $inserted = wp_insert_term('Club News', 'category', array('slug' => 'club-news'));
        if ( ! is_wp_error($inserted) ) {
            $news_cat_id = $inserted['term_id'];
        }
    } else {
        $news_cat_id = $cat->term_id;
    }

    foreach ( $news as $item ) {
        $ids = array();
        foreach ( $langs as $lang ) {
            $d   = $item[ $lang ];
            $pid = wp_insert_post( array(
                'post_title'    => $d['title'],
                'post_excerpt'  => $d['excerpt'],
                'post_content'  => $d['content'],
                'post_type'     => 'post',
                'post_status'   => 'publish',
                'post_category' => $news_cat_id ? array( $news_cat_id ) : array(),
            ) );
            if ( ! $pid || is_wp_error( $pid ) ) continue;
            najah_set_lang( $pid, $lang );
            $ids[ $lang ] = $pid;
        }
        najah_link_tr( $ids );
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 3. PRESS ARTICLES — 4 items × 3 languages
    // ══════════════════════════════════════════════════════════════════════════
    $presse = array(
        array(
            'fr' => array(
                'title'   => 'Najah Souss Echecs domine la Coupe du Trône 2025',
                'excerpt' => 'Le journal Le Matin couvre la victoire historique du club d\'Agadir.',
            ),
            'ar' => array(
                'title'   => 'نجاح سوس للشطرنج يهيمن على كأس العرش 2025',
                'excerpt' => 'جريدة "لو ماتان" ترصد الانتصار التاريخي لنادي أكادير.',
            ),
            'en' => array(
                'title'   => 'Najah Souss Chess dominates the 2025 Throne Cup',
                'excerpt' => 'Le Matin covers the historic victory of the Agadir club.',
            ),
            'meta' => array(
                'presse_source' => 'Le Matin',
                'external_link' => 'https://lematin.ma',
                'presse_tag'    => 'Article',
            ),
        ),
        array(
            'fr' => array(
                'title'   => 'Les Jeunes Prodiges du Souss sur 2M',
                'excerpt' => 'La chaîne nationale 2M consacre un reportage à la pépinière de champions d\'Agadir.',
            ),
            'ar' => array(
                'title'   => 'نبوغ الشباب في سوس على قناة 2M',
                'excerpt' => 'تخصص القناة الوطنية 2M تقريراً حول حضانة الأبطال في أكادير.',
            ),
            'en' => array(
                'title'   => 'The Young Prodigies of Souss on 2M TV',
                'excerpt' => 'National channel 2M dedicates a report to Agadir\'s champion nursery.',
            ),
            'meta' => array(
                'presse_source' => '2M',
                'external_link' => 'https://2m.ma',
                'presse_tag'    => 'Reportage',
            ),
        ),
        array(
            'fr' => array(
                'title'   => 'Record : 8 Médailles d\'Or pour Agadir aux Nationaux',
                'excerpt' => 'Hespress salue les performances exceptionnelles du club Najah Souss.',
            ),
            'ar' => array(
                'title'   => 'رقم قياسي: 8 ميداليات ذهبية لأكادير في الوطنيات',
                'excerpt' => 'موقع هسبريس يُشيد بالأداء الاستثنائي لنادي نجاح سوس.',
            ),
            'en' => array(
                'title'   => 'Record: 8 Gold Medals for Agadir at the Nationals',
                'excerpt' => 'Hespress praises the exceptional performances of Najah Souss.',
            ),
            'meta' => array(
                'presse_source' => 'Hespress',
                'external_link' => 'https://hespress.com',
                'presse_tag'    => 'News',
            ),
        ),
        array(
            'fr' => array(
                'title'   => 'Interview avec le DT Karim Bennis — Vision 2030',
                'excerpt' => 'Radio Plus Agadir explore la stratégie de développement du club sur la prochaine décennie.',
            ),
            'ar' => array(
                'title'   => 'لقاء مع المدير التقني كريم بنيس — رؤية 2030',
                'excerpt' => 'راديو بلوس أكادير يستكشف استراتيجية تطوير النادي على العقد القادم.',
            ),
            'en' => array(
                'title'   => 'Interview with TD Karim Bennis — Vision 2030',
                'excerpt' => 'Radio Plus Agadir explores the club\'s development strategy for the next decade.',
            ),
            'meta' => array(
                'presse_source' => 'Radio Plus Agadir',
                'external_link' => 'https://radioplus.ma',
                'presse_tag'    => 'Interview',
            ),
        ),
    );

    foreach ( $presse as $article ) {
        $ids = array();
        foreach ( $langs as $lang ) {
            $d   = $article[ $lang ];
            $pid = wp_insert_post( array(
                'post_title'   => $d['title'],
                'post_excerpt' => $d['excerpt'],
                'post_content' => $d['excerpt'],
                'post_type'    => 'presse',
                'post_status'  => 'publish',
            ) );
            if ( ! $pid || is_wp_error( $pid ) ) continue;
            najah_set_lang( $pid, $lang );
            foreach ( $article['meta'] as $k => $v ) {
                najah_acf( $k, $v, $pid );
            }
            $ids[ $lang ] = $pid;
        }
        najah_link_tr( $ids );
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 4. GALLERY ALBUMS — 3 items × 3 languages
    //    (Thumbnails set to placeholder Unsplash chess images)
    // ══════════════════════════════════════════════════════════════════════════
    $gallery_placeholder = 'https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80';

    $galleries = array(
        array(
            'fr' => 'Coupe du Trône 2025 — Cérémonie de Remise des Prix',
            'ar' => 'كأس العرش 2025 — حفل توزيع الجوائز',
            'en' => 'Throne Cup 2025 — Award Ceremony',
            'meta' => array(
                'album_description_fr' => 'Photos de la cérémonie officielle de remise des prix.',
                'album_description_ar' => 'صور من حفل توزيع الجوائز الرسمي.',
                'thumbnail_url'        => $gallery_placeholder,
            ),
        ),
        array(
            'fr' => 'Championnat National Jeunes U14 & U18',
            'ar' => 'البطولة الوطنية للشباب أقل من 14 و18 سنة',
            'en' => 'National Youth Championship U14 & U18',
            'meta' => array(
                'album_description_fr' => 'Moments inoubliables des championnats nationaux de jeunes.',
                'album_description_ar' => 'لحظات لا تُنسى من البطولات الوطنية للشباب.',
                'thumbnail_url'        => $gallery_placeholder,
            ),
        ),
        array(
            'fr' => 'Tournoi Interne d\'Hiver 2024',
            'ar' => 'دوري الشتاء الداخلي 2024',
            'en' => 'Internal Winter Tournament 2024',
            'meta' => array(
                'album_description_fr' => 'Notre tournoi interne annuel — ambiance chaleureuse et combats acharnés.',
                'album_description_ar' => 'بطولتنا الداخلية السنوية — أجواء دافئة ومنافسات شرسة.',
                'thumbnail_url'        => $gallery_placeholder,
            ),
        ),
    );

    foreach ( $galleries as $album ) {
        $ids = array();
        foreach ( $langs as $lang ) {
            $pid = wp_insert_post( array(
                'post_title'  => $album[ $lang ],
                'post_type'   => 'gallery',
                'post_status' => 'publish',
            ) );
            if ( ! $pid || is_wp_error( $pid ) ) continue;
            najah_set_lang( $pid, $lang );
            foreach ( $album['meta'] as $k => $v ) {
                najah_acf( $k, $v, $pid );
            }
            $ids[ $lang ] = $pid;
        }
        najah_link_tr( $ids );
    }

    // ── Mark seeder v7 as done ─────────────────────────────────────────────
    update_option( 'najah_souss_seeder_run_v7', true );
    error_log('[NAJAH SEEDER v7] Demo data seeded successfully at ' . current_time('mysql'));
}

add_action( 'init', 'najah_souss_run_seeder_v7', 20 );

// ─────────────────────────────────────────────────────────────────────────────
// Seeder v8: Products
// ─────────────────────────────────────────────────────────────────────────────
function najah_souss_run_seeder_v8() {
    if ( get_option('najah_souss_seeder_run_v8') ) {
        return;
    }

    $langs = array('fr', 'ar', 'en');

    $products = array(
        array(
            'fr' => array(
                'title' => 'T-shirt Officiel ANSAE',
                'excerpt' => 'Coton premium avec logo brodé haute qualité.',
            ),
            'ar' => array(
                'title' => 'تي شيرت نجاح سوس الرسمي',
                'excerpt' => 'قطن ممتاز مع شعار مطرز عالي الجودة.',
            ),
            'en' => array(
                'title' => 'Official ANSAE T-shirt',
                'excerpt' => 'Premium cotton with high-quality embroidered logo.',
            ),
            'meta' => array(
                'product_price' => '150 MAD',
            ),
        ),
        array(
            'fr' => array(
                'title' => 'Casquette ANSAE',
                'excerpt' => 'Style urbain aux couleurs du club, réglable.',
            ),
            'ar' => array(
                'title' => 'قبعة نجاح سوس',
                'excerpt' => 'أسلوب حضري بألوان النادي، قابلة للتعديل.',
            ),
            'en' => array(
                'title' => 'ANSAE Cap',
                'excerpt' => 'Urban style in club colors, adjustable.',
            ),
            'meta' => array(
                'product_price' => '80 MAD',
            ),
        ),
        array(
            'fr' => array(
                'title' => 'Mug de Compétition',
                'excerpt' => 'Le compagnon idéal pour vos analyses nocturnes.',
            ),
            'ar' => array(
                'title' => 'كوب المنافسة',
                'excerpt' => 'الرفيق المثالي لتحليلاتك الليلية.',
            ),
            'en' => array(
                'title' => 'Competition Mug',
                'excerpt' => 'The perfect companion for your late-night analysis.',
            ),
            'meta' => array(
                'product_price' => '50 MAD',
            ),
        ),
    );

    foreach ( $products as $product ) {
        $ids = array();
        foreach ( $langs as $lang ) {
            $d = $product[ $lang ];
            $pid = wp_insert_post( array(
                'post_title' => $d['title'],
                'post_excerpt' => $d['excerpt'],
                'post_content' => $d['excerpt'],
                'post_type' => 'product',
                'post_status' => 'publish',
            ) );
            if ( ! $pid || is_wp_error( $pid ) ) continue;
            najah_set_lang( $pid, $lang );
            foreach ( $product['meta'] as $k => $v ) {
                najah_acf( $k, $v, $pid );
            }
            $ids[ $lang ] = $pid;
        }
        najah_link_tr( $ids );
    }

    update_option( 'najah_souss_seeder_run_v8', true );
    error_log('[NAJAH SEEDER v8] Products seeded successfully at ' . current_time('mysql'));
}

add_action( 'init', 'najah_souss_run_seeder_v8', 21 );

