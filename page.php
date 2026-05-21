<?php
get_header();
?>
<main class="py-24 px-6 min-h-screen">
  <div class="max-w-7xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="mb-10">
        <?php if (function_exists('ansae_breadcrumbs')) ansae_breadcrumbs(); ?>
    </div>

    <?php
    if ( have_posts() ) :
      while ( have_posts() ) : the_post(); ?>
        <article class="prose prose-invert max-w-none">
          <?php the_content(); ?>
        </article>
      <?php endwhile;
    else :
      echo '<p class="text-center text-muted-foreground">' . esc_html(ansae_t('Aucun contenu disponible.')) . '</p>';
    endif;
    ?>
  </div>
</main>
<?php get_footer(); ?>
