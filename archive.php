<?php get_header(); ?>

<main class="pt-32 pb-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-16">
            <?php the_archive_title('<h1 class="text-4xl md:text-5xl font-bold font-display gradient-gold mb-4">', '</h1>'); ?>
            <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
        </header>

        <?php if ( have_posts() ) : ?>
            
            <?php
            // Determine grid class based on post type for better layout
            $grid_class = 'grid sm:grid-cols-2 lg:grid-cols-4 gap-5'; // default for champions, staff, gallery
            if ( is_post_type_archive('event') || is_post_type_archive('presse') || is_category() || is_home() || is_archive() ) {
                // If it's posts, events, or presse, use 3 columns
                if ( in_array(get_post_type(), ['event', 'presse', 'post']) ) {
                    $grid_class = 'grid md:grid-cols-3 gap-6';
                }
            }
            ?>
            <div class="<?php echo esc_attr($grid_class); ?>">
                <?php while ( have_posts() ) : the_post(); 
                    $post_type = get_post_type();
                    
                    if ($post_type === 'champion') {
                        // CHAMPION CARD
                        ?>
                        <a href="<?php the_permalink(); ?>" class="block group relative rounded-xl overflow-hidden surface-card aspect-[3/4] transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                            <article class="h-full w-full">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Échecs" loading="lazy" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent" aria-hidden="true"></div>
                            <div class="absolute bottom-0 inset-x-0 p-5">
                                <h3 class="font-display font-bold text-xl"><?php the_title(); ?></h3>
                                <p class="text-sm text-gold"><?php echo esc_html(get_field('champion_title')); ?></p>
                            </div>
                            </article>
                        </a>
                        <?php
                    } elseif ($post_type === 'gallery') {
                        // GALLERY CARD
                        ?>
                        <a href="<?php the_permalink(); ?>" class="block group relative rounded-xl overflow-hidden surface-card aspect-[4/3] transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                            <figure class="w-full h-full">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1580541832626-2a7131ee809f?auto=format&fit=crop&w=800&q=80" alt="Échecs Galerie" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/10 to-transparent opacity-0 group-hover:opacity-100 transition" aria-hidden="true"></div>
                            <figcaption class="absolute bottom-0 inset-x-0 p-4 text-sm font-medium text-gold opacity-0 group-hover:opacity-100 transition"><?php the_title(); ?></figcaption>
                            </figure>
                        </a>
                        <?php
                    } elseif ($post_type === 'event') {
                        // EVENT CARD
                        ?>
                        <a href="<?php the_permalink(); ?>" class="block group surface-card rounded-2xl p-7 flex flex-col hover:border-gold/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                            <article class="flex flex-col flex-1 h-full">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-3xl" aria-hidden="true"><?php echo esc_html(get_field('event_icon') ?: '🏆'); ?></span>
                                <span class="text-[10px] tracking-widest uppercase px-2 py-1 rounded-full bg-gold/10 text-gold border border-gold/30"><?php echo esc_html(get_field('event_badge') ?: 'À venir'); ?></span>
                            </div>
                            <p class="eyebrow mb-2"><?php echo esc_html(get_field('event_category')); ?></p>
                            <h3 class="font-display font-bold text-xl mb-3"><?php the_title(); ?></h3>
                            <div class="text-sm text-muted-foreground mb-5 flex-1"><?php the_excerpt(); ?></div>
                            <footer class="border-t border-border pt-4 text-xs text-muted-foreground space-y-1">
                                <div>📅 <?php echo esc_html(get_field('event_date')); ?></div>
                                <div>📍 <?php echo esc_html(get_field('event_location')); ?></div>
                            </footer>
                            </article>
                        </a>
                        <?php
                    } elseif ($post_type === 'presse') {
                        // PRESSE CARD
                        ?>
                        <a href="<?php echo get_field('external_link') ? esc_url(get_field('external_link')) : '#'; ?>" target="_blank" rel="noopener noreferrer" class="block group surface-card rounded-2xl overflow-hidden flex flex-col hover:border-gold/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                            <article class="flex flex-col flex-1">
                            <div class="relative aspect-video overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Échecs Presse" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php endif; ?>
                                <span class="absolute top-3 left-3 text-[10px] uppercase tracking-widest px-2 py-1 rounded bg-background/80 text-gold border border-gold/30"><?php echo esc_html(get_field('presse_tag') ?: 'Article'); ?></span>
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <p class="text-xs text-gold mb-2 font-semibold"><?php echo esc_html(get_field('presse_source')); ?></p>
                                <h3 class="font-display font-bold text-lg mb-3"><?php the_title(); ?></h3>
                                <div class="text-sm text-muted-foreground mb-4 flex-1"><?php the_excerpt(); ?></div>
                                <span class="text-gold text-sm font-semibold border-b border-gold/40 self-start">Lire l'article →</span>
                            </div>
                            </article>
                        </a>
                        <?php
                    } elseif ($post_type === 'staff') {
                        // STAFF CARD
                        ?>
                        <a href="<?php the_permalink(); ?>" class="block surface-card rounded-2xl overflow-hidden hover:border-gold/40 group transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                            <article class="flex flex-col h-full w-full">
                            <div class="aspect-square overflow-hidden rounded-t-2xl relative">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="<?php esc_attr_e('Staff Placeholder', 'najah-souss'); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php endif; ?>
                            </div>
                            <div class="p-5">
                                <p class="eyebrow mb-2"><?php echo esc_html(get_field('staff_role')); ?></p>
                                <h3 class="font-display font-bold text-lg mb-2"><?php the_title(); ?></h3>
                                <div class="text-xs text-muted-foreground leading-relaxed"><?php the_content(); ?></div>
                            </div>
                            </article>
                        </a>
                        <?php
                    } else {
                        // DEFAULT POST (ACTUALITES)
                        ?>
                        <article class="group surface-card rounded-2xl overflow-hidden flex flex-col hover:border-gold/50 gold-glow transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative aspect-video overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1580541832626-2a7131ee809f?auto=format&fit=crop&w=800&q=80" alt="Actualité Échecs" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
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
                                <h3 class="font-display font-semibold text-xl mb-3 leading-snug"><?php the_title(); ?></h3>
                                <div class="text-sm text-muted-foreground mb-6 flex-1"><?php the_excerpt(); ?></div>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-md border border-gold/40 text-gold text-sm font-semibold tracking-wide hover:bg-gold hover:text-primary-foreground hover:gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                    Lire la suite <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                        <?php
                    }
                ?>
                <?php endwhile; ?>
            </div>

            <div class="mt-16 text-center">
                <?php 
                the_posts_pagination(array(
                    'prev_text' => is_rtl() ? __('Suivant', 'najah-souss') . ' →' : '← ' . __('Précédent', 'najah-souss'),
                    'next_text' => is_rtl() ? '← ' . __('Précédent', 'najah-souss') : __('Suivant', 'najah-souss') . ' →',
                    'class' => 'inline-flex items-center justify-center gap-4 text-gold font-semibold tracking-wide'
                )); 
                ?>
            </div>

        <?php else : ?>
            <p class="text-center text-muted-foreground mt-12"><?php _e('Aucun contenu trouvé dans cette archive.', 'najah-souss'); ?></p>
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
