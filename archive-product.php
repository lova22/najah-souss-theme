<?php get_header(); ?>

<main class="pt-32 pb-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-16">
            <p class="eyebrow mb-4"><?php echo ansae_t('Supportez votre club'); ?></p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Boutique Officielle'); ?></h1>
            <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Portez fièrement les couleurs de Najah Souss Echecs avec notre collection exclusive.'); ?></p>
            <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
            <article class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-gold/50 transition duration-300 flex flex-col group">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-64 object-cover" />
                <?php else : ?>
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-64 object-cover" />
                <?php endif; ?>
                <div class="p-5 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1"><?php the_title(); ?></h3>
                        <div class="text-gray-400 text-sm mb-4 line-clamp-2"><?php the_excerpt(); ?></div>
                    </div>
                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-gold font-bold text-xl"><?php echo esc_html(get_field('product_price')); ?></span>
                        <?php 
                        $wa_msg = sprintf(__('Bonjour, je souhaite commander : %s', 'najah-souss'), get_the_title());
                        ?>
                        <a href="https://wa.me/212600000000?text=<?php echo urlencode($wa_msg); ?>" target="_blank" class="border border-gold text-gold hover:bg-gold hover:text-black font-semibold text-sm px-4 py-2 rounded-lg transition"><?php echo ansae_t('Acheter'); ?></a>
                    </div>
                </div>
            </article>
            <?php
                endwhile;
            else :
            ?>
                <p class="col-span-full text-center text-muted-foreground">Aucun produit trouvé.</p>
            <?php endif; ?>
        </div>

        <div class="mt-16 text-center">
            <?php 
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '&larr; ' . ansae_t('Précédent'),
                'next_text' => ansae_t('Suivant') . ' &rarr;',
            )); 
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
