<?php
// Register Custom Post Types
function najah_souss_register_cpts() {
    // Champions
    register_post_type('champion', array(
        'labels' => array(
            'name' => 'Champions',
            'singular_name' => 'Champion',
            'add_new' => 'Ajouter un champion',
            'add_new_item' => 'Ajouter un nouveau champion',
            'edit_item' => 'Modifier le champion',
            'all_items' => 'Tous les champions',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-awards',
        'supports' => array('title', 'editor', 'thumbnail'),
    ));

    // Galerie
    register_post_type('gallery', array(
        'labels' => array(
            'name' => 'Galeries',
            'singular_name' => 'Galerie',
            'add_new' => 'Ajouter une image',
            'add_new_item' => 'Ajouter une nouvelle image',
            'edit_item' => 'Modifier l\'image',
            'all_items' => 'Toute la galerie',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array('title', 'editor', 'thumbnail'),
    ));

    // Événements
    register_post_type('event', array(
        'labels' => array(
            'name' => 'Événements',
            'singular_name' => 'Événement',
            'add_new' => 'Ajouter un événement',
            'add_new_item' => 'Ajouter un nouvel événement',
            'edit_item' => 'Modifier l\'événement',
            'all_items' => 'Tous les événements',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    ));

    // Presse
    register_post_type('presse', array(
        'labels' => array(
            'name' => 'Presse',
            'singular_name' => 'Article de presse',
            'add_new' => 'Ajouter un article',
            'add_new_item' => 'Ajouter un nouvel article',
            'edit_item' => 'Modifier l\'article',
            'all_items' => 'Toute la presse',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    ));

    // Staff
    register_post_type('staff', array(
        'labels' => array(
            'name' => 'Staff techniques',
            'singular_name' => 'Membre du staff',
            'add_new' => 'Ajouter un membre',
            'add_new_item' => 'Ajouter un nouveau membre',
            'edit_item' => 'Modifier le membre',
            'all_items' => 'Tout le staff',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail'),
    ));

    // Produit
    register_post_type('product', array(
        'labels' => array(
            'name' => 'Produits',
            'singular_name' => 'Produit',
            'add_new' => 'Ajouter un produit',
            'add_new_item' => 'Ajouter un nouveau produit',
            'edit_item' => 'Modifier le produit',
            'all_items' => 'Tous les produits',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-cart',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    ));
}
add_action('init', 'najah_souss_register_cpts');

// Register ACF Fields
if( function_exists('acf_add_local_field_group') ):

    // Front Page Fields
    acf_add_local_field_group(array(
        'key' => 'group_front_page',
        'title' => 'Paramètres de la page d\'accueil',
        'fields' => array(
            // TAB: HERO
            array('key' => 'field_tab_hero', 'label' => 'Hero', 'name' => '', 'type' => 'tab'),
            array('key' => 'field_hero_top_badge', 'label' => 'Badge Supérieur', 'name' => 'hero_top_badge', 'type' => 'text'),
            array('key' => 'field_hero_eyebrow', 'label' => 'Surtitre (Eyebrow)', 'name' => 'hero_eyebrow', 'type' => 'text'),
            array('key' => 'field_hero_title', 'label' => 'Titre Principal', 'name' => 'hero_title', 'type' => 'text'),
            array('key' => 'field_hero_motto', 'label' => 'Devise (Motto)', 'name' => 'hero_motto', 'type' => 'text'),
            array('key' => 'field_hero_heading', 'label' => 'Sous-titre Principal', 'name' => 'hero_heading', 'type' => 'text'),
            array('key' => 'field_hero_description', 'label' => 'Description', 'name' => 'hero_description', 'type' => 'textarea'),
            array('key' => 'field_hero_sub_description', 'label' => 'Sous-description', 'name' => 'hero_sub_description', 'type' => 'textarea'),
            array('key' => 'field_hero_cta_1_text', 'label' => 'Texte Bouton 1', 'name' => 'hero_cta_1_text', 'type' => 'text'),
            array('key' => 'field_hero_cta_1_link', 'label' => 'Lien Bouton 1', 'name' => 'hero_cta_1_link', 'type' => 'url'),
            array('key' => 'field_hero_cta_2_text', 'label' => 'Texte Bouton 2', 'name' => 'hero_cta_2_text', 'type' => 'text'),
            array('key' => 'field_hero_cta_2_link', 'label' => 'Lien Bouton 2', 'name' => 'hero_cta_2_link', 'type' => 'url'),
            array(
                'key' => 'field_hero_stats',
                'label' => 'Statistiques',
                'name' => 'hero_stats',
                'type' => 'repeater',
                'sub_fields' => array(
                    array('key' => 'field_stat_value', 'label' => 'Valeur', 'name' => 'stat_value', 'type' => 'text'),
                    array('key' => 'field_stat_label', 'label' => 'Label', 'name' => 'stat_label', 'type' => 'text'),
                )
            ),

            // TAB: ABOUT
            array('key' => 'field_tab_about', 'label' => 'À propos', 'name' => '', 'type' => 'tab'),
            array('key' => 'field_about_eyebrow', 'label' => 'Surtitre', 'name' => 'about_eyebrow', 'type' => 'text'),
            array('key' => 'field_about_title_1', 'label' => 'Titre Ligne 1', 'name' => 'about_title_1', 'type' => 'text'),
            array('key' => 'field_about_title_2', 'label' => 'Titre Ligne 2', 'name' => 'about_title_2', 'type' => 'text'),
            array('key' => 'field_about_description', 'label' => 'Description', 'name' => 'about_description', 'type' => 'wysiwyg'),
            array('key' => 'field_about_motto', 'label' => 'Citation / Devise', 'name' => 'about_motto', 'type' => 'text'),
            array(
                'key' => 'field_about_badges',
                'label' => 'Badges',
                'name' => 'about_badges',
                'type' => 'repeater',
                'sub_fields' => array(
                    array('key' => 'field_badge_text', 'label' => 'Texte du badge', 'name' => 'badge_text', 'type' => 'text'),
                )
            ),
            array(
                'key' => 'field_about_pillars',
                'label' => 'Piliers',
                'name' => 'about_pillars',
                'type' => 'repeater',
                'sub_fields' => array(
                    array('key' => 'field_pillar_icon', 'label' => 'Icône (Emoji/Texte)', 'name' => 'pillar_icon', 'type' => 'text'),
                    array('key' => 'field_pillar_title', 'label' => 'Titre', 'name' => 'pillar_title', 'type' => 'text'),
                    array('key' => 'field_pillar_description', 'label' => 'Description', 'name' => 'pillar_description', 'type' => 'textarea'),
                )
            ),

            // TAB: PALMARES
            array('key' => 'field_tab_palmares', 'label' => 'Palmarès', 'name' => '', 'type' => 'tab'),
            array('key' => 'field_palmares_eyebrow', 'label' => 'Surtitre', 'name' => 'palmares_eyebrow', 'type' => 'text'),
            array('key' => 'field_palmares_title', 'label' => 'Titre', 'name' => 'palmares_title', 'type' => 'text'),
            array('key' => 'field_palmares_subtitle', 'label' => 'Sous-titre', 'name' => 'palmares_subtitle', 'type' => 'textarea'),
            array('key' => 'field_palmares_major_title', 'label' => 'Titre Majeur', 'name' => 'palmares_major_title', 'type' => 'text'),
            array('key' => 'field_palmares_major_desc', 'label' => 'Description Majeure', 'name' => 'palmares_major_desc', 'type' => 'textarea'),
            array(
                'key' => 'field_palmares_list',
                'label' => 'Liste des palmarès',
                'name' => 'palmares_list',
                'type' => 'repeater',
                'sub_fields' => array(
                    array('key' => 'field_palmares_icon', 'label' => 'Icône', 'name' => 'icon', 'type' => 'text'),
                    array('key' => 'field_palmares_item_title', 'label' => 'Titre', 'name' => 'title', 'type' => 'text'),
                    array('key' => 'field_palmares_item_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea'),
                )
            ),

            // TAB: PARTENARIAT
            array('key' => 'field_tab_partenariat', 'label' => 'Partenariat', 'name' => '', 'type' => 'tab'),
            array('key' => 'field_partenariat_badge', 'label' => 'Badge', 'name' => 'partenariat_badge', 'type' => 'text'),
            array('key' => 'field_partenariat_title_1', 'label' => 'Titre Ligne 1', 'name' => 'partenariat_title_1', 'type' => 'text'),
            array('key' => 'field_partenariat_title_2', 'label' => 'Titre Ligne 2', 'name' => 'partenariat_title_2', 'type' => 'text'),
            array('key' => 'field_partenariat_description', 'label' => 'Description', 'name' => 'partenariat_description', 'type' => 'wysiwyg'),
            array(
                'key' => 'field_partenariat_perks',
                'label' => 'Avantages (Perks)',
                'name' => 'partenariat_perks',
                'type' => 'repeater',
                'sub_fields' => array(
                    array('key' => 'field_perk_icon', 'label' => 'Icône', 'name' => 'perk_icon', 'type' => 'text'),
                    array('key' => 'field_perk_title', 'label' => 'Titre', 'name' => 'perk_title', 'type' => 'text'),
                    array('key' => 'field_perk_description', 'label' => 'Description', 'name' => 'perk_description', 'type' => 'textarea'),
                )
            ),
            array('key' => 'field_partenariat_cta_link', 'label' => 'Lien du Bouton', 'name' => 'partenariat_cta_link', 'type' => 'url'),
            array('key' => 'field_partenariat_cta_text', 'label' => 'Texte du Bouton', 'name' => 'partenariat_cta_text', 'type' => 'text'),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php'),
            ),
            array(
                array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'),
            ),
        ),
    ));

    // Champion Fields
    acf_add_local_field_group(array(
        'key' => 'group_champion',
        'title' => 'Détails du Champion',
        'fields' => array(
            array('key' => 'field_champion_title', 'label' => 'Titre / Récompense', 'name' => 'champion_title', 'type' => 'text'),
            array('key' => 'field_champion_categorie', 'label' => 'Catégorie', 'name' => 'categorie', 'type' => 'text'),
            array('key' => 'field_champion_fide_id', 'label' => 'FIDE ID', 'name' => 'fide_id', 'type' => 'text'),
            array('key' => 'field_champion_rating_standard', 'label' => 'Rating Standard', 'name' => 'rating_standard', 'type' => 'number'),
            array('key' => 'field_champion_rating_rapid', 'label' => 'Rating Rapid', 'name' => 'rating_rapid', 'type' => 'number'),
            array('key' => 'field_champion_rating_blitz', 'label' => 'Rating Blitz', 'name' => 'rating_blitz', 'type' => 'number'),
        ),
        'location' => array(
            array(array('param' => 'post_type', 'operator' => '==', 'value' => 'champion')),
        ),
    ));

    // Event Fields
    acf_add_local_field_group(array(
        'key' => 'group_event',
        'title' => 'Détails de l\'Événement',
        'fields' => array(
            array('key' => 'field_event_icon', 'label' => 'Icône', 'name' => 'event_icon', 'type' => 'text'),
            array('key' => 'field_event_badge', 'label' => 'Badge (Ex: À venir)', 'name' => 'event_badge', 'type' => 'text'),
            array('key' => 'field_event_category', 'label' => 'Catégorie', 'name' => 'event_category', 'type' => 'text'),
            array('key' => 'field_event_date', 'label' => 'Date', 'name' => 'event_date', 'type' => 'text'),
            array('key' => 'field_event_location', 'label' => 'Lieu', 'name' => 'event_location', 'type' => 'text'),
        ),
        'location' => array(
            array(array('param' => 'post_type', 'operator' => '==', 'value' => 'event')),
        ),
    ));

    // Presse Fields
    acf_add_local_field_group(array(
        'key' => 'group_presse',
        'title' => 'Détails de Presse',
        'fields' => array(
            array('key' => 'field_presse_tag', 'label' => 'Tag (Ex: Article, Vidéo)', 'name' => 'presse_tag', 'type' => 'text'),
            array('key' => 'field_presse_source', 'label' => 'Source / Journal', 'name' => 'presse_source', 'type' => 'text'),
            array('key' => 'field_presse_link', 'label' => 'Lien de l\'article', 'name' => 'presse_link', 'type' => 'url'),
            array('key' => 'field_external_link', 'label' => 'Lien Externe', 'name' => 'external_link', 'type' => 'url'),
        ),
        'location' => array(
            array(array('param' => 'post_type', 'operator' => '==', 'value' => 'presse')),
        ),
    ));

    // Staff Fields
    acf_add_local_field_group(array(
        'key' => 'group_staff',
        'title' => 'Détails du Staff',
        'fields' => array(
            array('key' => 'field_staff_role', 'label' => 'Rôle / Fonction', 'name' => 'staff_role', 'type' => 'text'),
            array('key' => 'field_staff_facebook', 'label' => 'Lien Facebook', 'name' => 'staff_facebook', 'type' => 'url'),
            array('key' => 'field_staff_email', 'label' => 'Email', 'name' => 'staff_email', 'type' => 'email'),
        ),
        'location' => array(
            array(array('param' => 'post_type', 'operator' => '==', 'value' => 'staff')),
        ),
    ));

    // Product Fields
    acf_add_local_field_group(array(
        'key' => 'group_product',
        'title' => 'Détails du Produit',
        'fields' => array(
            array('key' => 'field_product_price', 'label' => 'Prix', 'name' => 'product_price', 'type' => 'text'),
        ),
        'location' => array(
            array(array('param' => 'post_type', 'operator' => '==', 'value' => 'product')),
        ),
    ));

endif;
