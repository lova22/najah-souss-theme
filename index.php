<?php get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <?php if ( have_posts() ) : ?>
            <header class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-display">
                    <?php 
                        if ( is_home() && ! is_front_page() ) {
                            single_post_title();
                        } else {
                            echo 'Actualités';
                        }
                    ?>
                </h1>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>

            <div class="grid md:grid-cols-3 gap-6">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('surface-card rounded-2xl overflow-hidden flex flex-col group hover:border-gold/50 transition gold-glow'); ?>>
                        <div class="relative aspect-video overflow-hidden">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                            <?php else : ?>
                                <div class="w-full h-full bg-surface"></div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent" aria-hidden="true"></div>
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                echo '<span class="absolute top-3 left-3 text-[10px] uppercase tracking-[0.25em] px-3 py-1 rounded-full bg-background/80 text-gold border border-gold/40">' . esc_html( $categories[0]->name ) . '</span>';
                            }
                            ?>
                        </div>
                        <div class="p-7 flex flex-col flex-1">
                            <h3 class="font-display font-semibold text-xl mb-3 leading-snug"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="text-sm text-muted-foreground mb-6 flex-1"><?php the_excerpt(); ?></div>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-md border border-gold/40 text-gold text-sm font-semibold tracking-wide hover:bg-gold hover:text-primary-foreground hover:gold-shadow transition">
                                Lire la suite <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="mt-12 flex justify-between text-gold font-semibold">
                <?php previous_posts_link( '← Récents' ); ?>
                <?php next_posts_link( 'Anciens →' ); ?>
            </div>

        <?php else : ?>
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-display">Rien n'a été trouvé</h1>
                <p class="text-muted-foreground">Désolé, mais aucun contenu ne correspond à votre recherche.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
