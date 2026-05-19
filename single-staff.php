<?php
/**
 * Single template for Staff
 */
get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <!-- Universal Back Navigation -->
        <div class="mb-10">
            <a href="<?php echo esc_url(function_exists('pll_home_url') ? pll_home_url() : home_url('/')); ?>" class="inline-flex items-center gap-2 text-gold hover:text-white transition-colors duration-300 font-semibold tracking-wide">
                <span aria-hidden="true" class="rtl:rotate-180">←</span>
                <?php echo ansae_t('Retour à l\'accueil'); ?>
            </a>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('surface-card rounded-3xl p-6 md:p-12 shadow-2xl border border-gold/10'); ?>>
                <div class="flex flex-col md:flex-row gap-12 items-center md:items-start">
                    
                    <!-- Sidebar: Profile Image -->
                    <div class="w-full md:w-1/3 shrink-0 text-center md:text-start">
                        <div class="aspect-square rounded-full overflow-hidden border-4 border-gold/30 shadow-[0_0_30px_rgba(212,175,55,0.15)] mb-6 mx-auto max-w-[250px] md:max-w-full md:mx-0">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Staff Avatar" class="w-full h-full object-cover grayscale" />
                            <?php endif; ?>
                        </div>
                        
                        <!-- Social Integration -->
                        <div class="flex flex-col gap-3 max-w-[250px] mx-auto md:mx-0">
                            <?php if (get_field('staff_facebook')) : ?>
                                <a href="<?php echo esc_url(get_field('staff_facebook')); ?>" target="_blank" class="flex items-center justify-center md:justify-start gap-3 px-4 py-3 rounded-lg bg-[#1877F2]/10 text-[#1877F2] hover:bg-[#1877F2]/20 transition-colors font-medium">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                                    <span>Facebook Profile</span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (get_field('staff_email')) : ?>
                                <a href="mailto:<?php echo esc_attr(get_field('staff_email')); ?>" class="flex items-center justify-center md:justify-start gap-3 px-4 py-3 rounded-lg bg-gold/10 text-gold hover:bg-gold/20 transition-colors font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Email Contact</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="w-full md:w-2/3 flex flex-col justify-center text-center md:text-start">
                        <div class="inline-block px-3 py-1 mb-4 rounded-full bg-gold/10 text-gold text-xs font-bold tracking-widest uppercase border border-gold/30 self-center md:self-start">
                            <?php echo esc_html(get_field('staff_role') ?: 'Staff'); ?>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white font-tajawal mb-6"><?php the_title(); ?></h1>
                        
                        <div class="prose prose-lg prose-invert text-muted-foreground leading-relaxed">
                            <?php the_content(); ?>
                        </div>
                    </div>

                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
