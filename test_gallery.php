<?php
require_once('../../../wp-load.php');
$q = new WP_Query(array('post_type' => 'gallery', 'posts_per_page' => 1));
if ($q->have_posts()) {
    $q->the_post();
    echo "CONTENT:\n";
    echo get_the_content();
    echo "\n\nMETA:\n";
    print_r(get_post_meta(get_the_ID()));
} else {
    echo "No gallery posts";
}
