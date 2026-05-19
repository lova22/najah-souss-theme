<?php
/**
 * Single template for Events
 */
get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Universal Back Navigation -->
        <div class="mb-10">
            <a href="<?php echo esc_url(function_exists('pll_home_url') ? pll_home_url() : home_url('/')); ?>" class="inline-flex items-center gap-2 text-gold hover:text-white transition-colors duration-300 font-semibold tracking-wide">
                <span aria-hidden="true" class="rtl:rotate-180">←</span>
                <?php echo ansae_t('Retour à l\'accueil'); ?>
            </a>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('surface-card rounded-3xl overflow-hidden shadow-2xl border border-gold/10'); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="h-[40vh] w-full relative">
                        <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
                    </div>
                <?php endif; ?>

                <div class="p-8 md:p-12">
                    <header class="mb-10 text-center">
                        <div class="flex items-center justify-center gap-4 mb-6">
                            <?php 
                            $badge = get_field('event_badge'); 
                            $is_past = stripos($badge, 'past') !== false || stripos($badge, 'terminé') !== false;
                            $badge_class = $is_past ? 'bg-neutral-800 text-neutral-400 border-neutral-600' : 'bg-green-500/10 text-green-400 border-green-500/30';
                            ?>
                            <span class="text-xs tracking-widest uppercase px-3 py-1 rounded-full border font-bold <?php echo $badge_class; ?>">
                                <?php echo esc_html(ansae_t($badge ?: 'Événement')); ?>
                            </span>
                            <span class="text-xs tracking-widest uppercase px-3 py-1 rounded-full border border-gold/30 bg-gold/10 text-gold font-bold">
                                <?php echo esc_html(ansae_t(get_field('event_category'))); ?>
                            </span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white font-tajawal mb-6"><?php the_title(); ?></h1>
                    </header>

                    <div class="grid md:grid-cols-2 gap-8 mb-10 border-y border-gold/10 py-8">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-gold/10 rounded-xl text-gold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white mb-1"><?php echo ansae_t('Date & Heure'); ?></h3>
                                <p class="text-muted-foreground"><?php echo esc_html(get_field('event_date')); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-gold/10 rounded-xl text-gold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white mb-1"><?php echo ansae_t('Lieu'); ?></h3>
                                <p class="text-muted-foreground"><?php echo esc_html(get_field('event_location')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="prose prose-lg prose-invert max-w-none text-muted-foreground leading-relaxed">
                        <?php the_content(); ?>
                    </div>
                </div>

            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
