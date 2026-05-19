<?php
/**
 * Single template for Gallery / Album
 */
get_header(); ?>

<main class="py-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Top Navigation -->
        <div class="mb-10">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 text-gold hover:text-white transition-colors duration-300 font-semibold tracking-wide">
                <span aria-hidden="true" class="rtl:rotate-180">←</span>
                <?php echo ansae_t('Retour à l\'accueil'); ?>
            </a>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <header class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white font-tajawal mb-4"><?php the_title(); ?></h1>
                <div class="divider-gold w-24 mx-auto" aria-hidden="true"></div>
            </header>

            <?php
            // 1. Extract raw images from content
            $content = get_the_content();
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);
            $extracted_images = !empty($matches[1]) ? $matches[1] : array();
            
            // Clean content to remove raw images
            $clean_content = preg_replace('/<figure[^>]*>.*?<img[^>]+>.*?<\/figure>/is', '', $content);
            $clean_content = preg_replace('/<img[^>]+>/i', '', $clean_content);
            $clean_content = apply_filters('the_content', $clean_content);
            $clean_content = str_replace(']]>', ']]&gt;', $clean_content);
            ?>

            <div class="prose prose-lg prose-invert max-w-3xl mx-auto text-center mb-12">
                <?php echo $clean_content; ?>
            </div>

            <?php
            // Fetch Images Logic
            $images = array();
            
            // Inject editor images first
            if (!empty($extracted_images)) {
                $images = array_merge($images, $extracted_images);
            }
            
            // 1. Try ACF Gallery
            if (function_exists('get_field')) {
                $acf_images = get_field('gallery_images');
                if ($acf_images && is_array($acf_images)) {
                    foreach ($acf_images as $acf_img) {
                        $images[] = is_array($acf_img) ? $acf_img['url'] : wp_get_attachment_image_url($acf_img, 'full');
                    }
                }
            }

            // 2. Fallback to attached media
            if (empty($images)) {
                $attached = get_attached_media('image', get_the_ID());
                if ($attached && !empty($attached)) {
                    foreach ($attached as $att) {
                        $images[] = wp_get_attachment_image_url($att->ID, 'full');
                    }
                }
            }

            // 3. Fallback to Featured Image if completely empty
            if (empty($images) && has_post_thumbnail()) {
                $images[] = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }
            ?>

            <!-- The Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-8" id="gallery-grid">
                <?php foreach ($images as $index => $high_res_url): ?>
                    <?php if ($high_res_url): ?>
                        <img src="<?php echo esc_url($high_res_url); ?>" alt="Gallery Image" class="cursor-pointer object-cover aspect-square w-full rounded-xl hover:opacity-75 transition duration-300" onclick="openLightbox(<?php echo $index; ?>)">
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (empty($images)): ?>
                    <p class="text-center col-span-full text-muted-foreground"><?php echo ansae_t('Aucune image trouvée.'); ?></p>
                <?php endif; ?>
            </div>

            <!-- The Lightbox Modal -->
            <div id="lightbox" class="fixed inset-0 bg-black/95 z-[9999] hidden flex items-center justify-center">
                <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors p-2 z-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                
                <button onclick="prevImage()" class="absolute left-4 md:left-10 text-white/50 hover:text-white transition-colors p-4 rounded-full bg-black/20 hover:bg-black/50 z-50">
                    <svg class="w-8 h-8 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <button onclick="nextImage()" class="absolute right-4 md:right-10 text-white/50 hover:text-white transition-colors p-4 rounded-full bg-black/20 hover:bg-black/50 z-50">
                    <svg class="w-8 h-8 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <div class="w-full h-full p-4 flex items-center justify-center">
                    <img id="lightbox-img" src="" alt="Zoomed Image" class="max-h-[85vh] max-w-[90vw] object-contain shadow-2xl" />
                </div>
            </div>

            <!-- Vanilla JS Engine -->
            <script>
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const jsImages = <?php echo json_encode(array_values(array_filter($images))); ?>;
            let currentIndex = 0;

            function openLightbox(index) {
                if (!jsImages || jsImages.length === 0) return;
                currentIndex = index;
                lightboxImg.src = jsImages[currentIndex];
                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                lightbox.classList.add('hidden');
                lightboxImg.src = '';
                document.body.style.overflow = '';
            }

            function nextImage() {
                currentIndex = (currentIndex + 1) % jsImages.length;
                lightboxImg.src = jsImages[currentIndex];
            }

            function prevImage() {
                currentIndex = (currentIndex - 1 + jsImages.length) % jsImages.length;
                lightboxImg.src = jsImages[currentIndex];
            }

            document.addEventListener('keydown', (e) => {
                if (lightbox.classList.contains('hidden')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'ArrowLeft') prevImage();
            });
            </script>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
