<?php
/**
 * home.php — Blog Posts Index Template
 * Used when a page is set as "Posts page" in WP Reading Settings.
 */
get_header();
?>

<main id="actualites-archive" class="py-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Page Header -->
        <header class="text-center mb-16">
            <p class="eyebrow mb-4"><?php echo ansae_t('Notre Actualité'); ?></p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-3">
                <?php echo ansae_t('Notre Actualité'); ?>
            </h1>
            <p class="text-muted-foreground max-w-2xl mx-auto">
                <?php echo ansae_t('Les dernières nouvelles, événements et annonces de Najah Souss Echecs.'); ?>
            </p>
            <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
        </header>

        <!-- Posts Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <article class="group surface-card rounded-2xl overflow-hidden flex flex-col hover:border-gold/50 gold-glow transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">

                    <!-- Featured Image -->
                    <div class="relative aspect-video overflow-hidden">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <img
                                src="<?php the_post_thumbnail_url('large'); ?>"
                                alt="<?php the_title_attribute(); ?>"
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
                            />
                        <?php else : ?>
                            <img
                                src="https://images.unsplash.com/photo-1580541832626-2a7131ee809f?auto=format&fit=crop&w=800&q=80"
                                alt="<?php echo esc_attr( ansae_t('Actualité Echecs') ); ?>"
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
                            />
                        <?php endif; ?>

                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent" aria-hidden="true"></div>

                        <!-- Category badge -->
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            echo '<span class="absolute top-3 left-3 text-[10px] uppercase tracking-[0.25em] px-3 py-1 rounded-full bg-background/80 text-gold border border-gold/40">'
                                . esc_html( $categories[0]->name )
                                . '</span>';
                        }
                        ?>
                    </div>

                    <!-- Card Body -->
                    <div class="p-7 flex flex-col flex-1 text-start">
                        <div class="text-[10px] tracking-widest uppercase text-gold/60 mb-3">
                            <?php echo get_the_date(); ?>
                        </div>
                        <h2 class="font-display font-semibold text-xl mb-3 leading-snug">
                            <a href="<?php the_permalink(); ?>" class="hover:text-gold transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <div class="text-sm text-muted-foreground mb-6 flex-1">
                            <?php the_excerpt(); ?>
                        </div>
                        <a
                            href="<?php the_permalink(); ?>"
                            class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-md border border-gold/40 text-gold text-sm font-semibold tracking-wide hover:bg-gold hover:text-primary-foreground hover:gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                        >
                            <?php echo ansae_t('Lire la suite'); ?> <span aria-hidden="true">→</span>
                        </a>
                    </div>

                </article>

            <?php endwhile; else : ?>

                <p class="col-span-full text-center text-muted-foreground py-16">
                    <?php echo ansae_t('Aucune actualité disponible pour le moment.'); ?>
                </p>

            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ( $wp_query->max_num_pages > 1 ) : ?>
            <nav class="mt-16 flex justify-center gap-3" aria-label="<?php echo esc_attr( ansae_t('Pagination') ); ?>">
                <?php
                echo paginate_links( array(
                    'prev_text' => '← ' . ansae_t('Précédent'),
                    'next_text' => ansae_t('Suivant') . ' →',
                    'type'      => 'list',
                    'before_page_number' => '<span class="inline-block px-4 py-2 rounded-md border border-gold/40 text-gold hover:bg-gold/10 transition">',
                    'after_page_number'  => '</span>',
                ) );
                ?>
            </nav>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
