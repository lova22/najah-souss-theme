<?php
/**
 * Archive template for Champions (FIDE Leaderboard)
 */
get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Back Navigation -->
        <div class="mb-10">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 text-gold hover:text-white transition-colors duration-300 font-semibold tracking-wide">
                <span aria-hidden="true" class="rtl:rotate-180">←</span>
                <?php echo ansae_t('Retour à l\'accueil'); ?>
            </a>
        </div>

        <header class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-bold mb-4"><?php echo wp_kses_post( ansae_t('Notre Équipe - Classement FIDE') ); ?></h1>
            <div class="divider-gold w-20 mx-auto" aria-hidden="true"></div>
        </header>

        <div class="overflow-x-auto rounded-xl border border-gold/20 shadow-2xl">
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
                    <?php
                    $leaderboard = new WP_Query(array(
                        'post_type' => 'champion',
                        'posts_per_page' => -1,
                        'meta_key' => 'rating_standard',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'suppress_filters' => false
                    ));

                    if ($leaderboard->have_posts()) :
                        $counter = 0;
                        while ($leaderboard->have_posts()) : $leaderboard->the_post();
                            $counter++;
                            $row_bg = ($counter % 2 === 0) ? 'bg-neutral-900/50' : 'bg-black';
                            $f_id = get_field('fide_id');
                            $r_std = get_field('rating_standard');
                            $r_rap = get_field('rating_rapid');
                            $r_bli = get_field('rating_blitz');
                    ?>
                        <tr class="<?php echo $row_bg; ?> hover:bg-gold/5 transition-colors group">
                            <td class="px-4 py-4 font-semibold text-white group-hover:text-gold transition-colors">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </td>
                            <td class="px-4 py-4 text-muted-foreground text-xs font-mono">
                                <?php if ($f_id): ?>
                                    <a href="https://ratings.fide.com/profile/<?php echo esc_attr($f_id); ?>" target="_blank" class="text-gold hover:underline font-mono">
                                        <?php echo esc_html($f_id); ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-muted-foreground text-xs">
                                <?php echo esc_html(ansae_t(get_field('categorie') ?: '-')); ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-block px-2 py-1 rounded bg-gold/10 text-gold font-bold font-mono text-sm">
                                    <?php echo esc_html($r_std ?: '-'); ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center text-muted-foreground font-mono text-sm">
                                <?php echo esc_html($r_rap ?: '-'); ?>
                            </td>
                            <td class="px-4 py-4 text-center text-muted-foreground font-mono text-sm">
                                <?php echo esc_html($r_bli ?: '-'); ?>
                            </td>
                        </tr>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-muted-foreground"><?php echo ansae_t('Aucun classement disponible.'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php get_footer(); ?>
