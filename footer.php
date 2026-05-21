<footer id="site-footer" class="border-t border-border py-12 px-6">
    <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8 items-center">
        <div class="flex items-center gap-3">
            <div class="size-11 rounded-md bg-gold flex items-center justify-center text-primary-foreground font-bold text-xl" aria-hidden="true">♞</div>
            <div>
                <div class="font-display font-bold">Najah Souss Échecs</div>
                <div class="text-xs text-muted-foreground tracking-widest uppercase"><?php echo ansae_t('Champions du Maroc 2025 · Depuis 1987') ?></div>
            </div>
        </div>
        <p class="text-sm text-muted-foreground text-center font-display italic">« <?php echo ansae_t('Aucun coup ne doit être joué sans but'); ?> »</p>
        <nav aria-label="<?php echo esc_attr(ansae_t('Réseaux sociaux')); ?>" class="flex md:justify-end gap-6 text-sm text-muted-foreground">
            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors flex items-center gap-2">
                <span>Facebook</span>
            </a>
            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors flex items-center gap-2">
                <span>Instagram</span>
            </a>
            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors flex items-center gap-2">
                <span>YouTube</span>
            </a>
        </nav>
    </div>
    <p class="max-w-7xl mx-auto mt-8 text-center text-xs text-muted-foreground">© <?php echo date('Y'); ?> Najah Souss Échecs. <?php echo ansae_t('Tous droits réservés.') ?></p>
</footer>

<?php wp_footer(); ?>

<button id="backToTop" aria-label="Retour en haut" class="fixed bottom-6 right-6 z-[99] bg-black/60 hover:bg-gold text-white hover:text-black w-12 h-12 rounded-full flex items-center justify-center opacity-0 invisible transition-all duration-300 shadow-lg backdrop-blur-sm transform translate-y-4 cursor-pointer">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTopButton.classList.remove('opacity-0', 'invisible', 'translate-y-4');
                backToTopButton.classList.add('opacity-100', 'visible', 'translate-y-0');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible', 'translate-y-4');
                backToTopButton.classList.remove('opacity-100', 'visible', 'translate-y-0');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    // PWA Service Worker Registration
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => console.log('SW registered: ', registration.scope))
                .catch(err => console.log('SW registration failed: ', err));
        });
    }
</script>
</body>
</html>
