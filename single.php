<?php
/**
 * Single template for Standard Posts / News Articles (Al Jazeera Style)
 */
get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Universal Back Navigation & Top Bar -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gold/10">
            <a href="<?php echo esc_url(function_exists('pll_home_url') ? pll_home_url() : home_url('/')); ?>" class="inline-flex items-center gap-2 text-gold hover:text-white transition-colors duration-300 font-semibold tracking-wide">
                <span aria-hidden="true" class="rtl:rotate-180">←</span>
                <?php echo ansae_t('Retour à l\'accueil'); ?>
            </a>
            
            <!-- Typography & Font Resizer -->
            <div class="flex items-center gap-2 bg-surface border border-gold/20 rounded-full px-4 py-1.5 shadow-sm">
                <span class="text-xs text-muted-foreground me-2"><?php echo ansae_t('Taille du texte'); ?></span>
                <button id="btn-font-dec" class="text-white hover:text-gold transition-colors font-bold px-2 py-1" aria-label="Decrease font size">A-</button>
                <div class="w-[1px] h-4 bg-gold/20 mx-1"></div>
                <button id="btn-font-inc" class="text-white hover:text-gold transition-colors font-bold px-2 py-1 text-lg leading-none" aria-label="Increase font size">A+</button>
            </div>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>">
                
                <header class="mb-10">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<span class="inline-block px-3 py-1 mb-4 rounded text-xs tracking-widest uppercase font-bold bg-gold/10 text-gold">' . esc_html($categories[0]->name) . '</span>';
                    }
                    ?>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white font-tajawal leading-tight mb-6">
                        <?php the_title(); ?>
                    </h1>
                    <div class="flex items-center gap-4 text-sm text-muted-foreground font-medium">
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <span class="w-1.5 h-1.5 rounded-full bg-gold/50"></span>
                        <span><?php echo ansae_t('Najah Souss'); ?></span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-12 rounded-2xl overflow-hidden shadow-2xl relative gold-glow">
                        <img 
                            src="<?php the_post_thumbnail_url('full'); ?>" 
                            alt="<?php the_title_attribute(); ?>" 
                            class="object-cover w-full h-[50vh] md:h-[65vh]"
                        />
                    </div>
                <?php endif; ?>

                <!-- Dynamic Typography Content Container -->
                <div id="article-content" class="text-lg leading-relaxed text-neutral-300 prose prose-invert prose-headings:font-tajawal prose-headings:text-white prose-a:text-gold max-w-none transition-all duration-300">
                    <?php the_content(); ?>
                </div>

            </article>
        <?php endwhile; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('article-content');
    const btnInc = document.getElementById('btn-font-inc');
    const btnDec = document.getElementById('btn-font-dec');
    
    // Scale sequence
    const sizes = ['text-base', 'text-lg', 'text-xl', 'text-2xl'];
    let currentIndex = 1; // Default is 'text-lg'

    function updateFontSize() {
        sizes.forEach(size => content.classList.remove(size));
        content.classList.add(sizes[currentIndex]);
    }

    btnInc.addEventListener('click', () => {
        if (currentIndex < sizes.length - 1) {
            currentIndex++;
            updateFontSize();
        }
    });

    btnDec.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateFontSize();
        }
    });
});
</script>

<?php get_footer(); ?>
