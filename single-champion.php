<?php
/**
 * Single template for Champions
 */
get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumbs -->
        <div class="mb-10">
            <?php if (function_exists('ansae_breadcrumbs')) ansae_breadcrumbs(); ?>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left Column: Profile Image -->
                    <div class="col-span-1">
                        <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl border-2 border-gold/40 relative group gold-glow">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Avatar" class="w-full h-full object-cover" />
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        </div>
                    </div>

                    <!-- Main Column: Metadata & Details -->
                    <div class="col-span-1 md:col-span-2 flex flex-col justify-center text-start">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white font-tajawal mb-2"><?php the_title(); ?></h1>
                        <p class="text-gold text-lg md:text-xl font-medium tracking-wide uppercase mb-6"><?php echo esc_html(get_field('champion_title')); ?></p>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('FIDE ID'); ?></div>
                                <div class="font-mono text-gold font-bold"><?php echo esc_html(get_field('fide_id') ?: '-'); ?></div>
                            </div>
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('STANDARD'); ?></div>
                                <div class="font-mono text-white font-bold"><?php echo esc_html(get_field('rating_standard') ?: '-'); ?></div>
                            </div>
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('RAPID'); ?></div>
                                <div class="font-mono text-white font-bold"><?php echo esc_html(get_field('rating_rapid') ?: '-'); ?></div>
                            </div>
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('BLITZ'); ?></div>
                                <div class="font-mono text-white font-bold"><?php echo esc_html(get_field('rating_blitz') ?: '-'); ?></div>
                            </div>
                        </div>

                        <!-- Bio / Achievements -->
                        <div class="prose prose-lg prose-invert max-w-none text-muted-foreground leading-relaxed mb-10">
                            <?php the_content(); ?>
                        </div>

                        <!-- Official Profile Link -->
                        <?php $fide_id = get_field('fide_id'); ?>
                        <?php if ($fide_id): ?>
                            <div class="mt-auto self-start">
                                <a href="https://ratings.fide.com/profile/<?php echo esc_attr($fide_id); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 px-8 py-4 rounded-md bg-gradient-gold text-primary-foreground font-semibold tracking-wide gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                    <?php echo ansae_t('Profil FIDE Officiel'); ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
