<?php
/*
 * Archive template for Champions – Wall of Champions (grid layout)
 */
get_header();
?>
<main class="py-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Back Navigation -->
        <div class="mb-10">
            <a href="<?php echo esc_url(function_exists('pll_home_url') ? pll_home_url() : home_url('/')); ?>" class="inline-flex items-center gap-2 text-gold hover:text-white transition-colors duration-300 font-semibold tracking-wide">
                <span aria-hidden="true" class="rtl:rotate-180">←</span>
                <?php echo ansae_t('Retour à l\'accueil'); ?>
            </a>
        </div>

        <header class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-bold mb-4"><?php echo wp_kses_post( ansae_t('Notre Équipe - Classement FIDE') ); ?></h1>
            <div class="divider-gold w-20 mx-auto" aria-hidden="true"></div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="group rounded-xl overflow-hidden border border-gold/20 bg-neutral-900/30 hover:bg-neutral-900/50 transition-colors shadow-lg">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php the_post_thumbnail('large', ['class' => 'w-full h-48 object-cover']); ?>
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
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="text-center text-muted-foreground"><?php echo ansae_t('Aucun champion disponible pour le moment.'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
