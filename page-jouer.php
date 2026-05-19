<?php
/* Template Name: Page Jouer */
get_header();
?>

<main class="pt-32 pb-24 px-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- HERO HEADER -->
        <header class="text-center mb-16">
            <p class="eyebrow mb-4"><?php _e('Espace Interactif', 'najah-souss'); ?></p>
            <h1 class="text-4xl md:text-6xl font-bold font-display gradient-gold mb-6"><?php _e('Salle de Jeu en Ligne ♞', 'najah-souss'); ?></h1>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto"><?php _e('Affrontez l\'IA, résolvez les tactiques du jour ou rejoignez la communauté numérique de l\'ANSAE.', 'najah-souss'); ?></p>
            <div class="divider-gold w-24 mx-auto mt-8" aria-hidden="true"></div>
        </header>

        <!-- DASHBOARD GRID -->
        <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto px-4 mt-12 items-stretch">
            
            <!-- LEFT COLUMN: Daily Puzzle -->
            <div class="border border-gold/30 bg-neutral-900 rounded-2xl p-6 shadow-2xl flex flex-col h-full">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-2xl" aria-hidden="true">🧩</span>
                    <h2 class="text-xl font-display font-bold text-gold"><?php _e('Casse-tête du Jour', 'najah-souss'); ?></h2>
                </div>
                <div class="relative flex-1 rounded-xl overflow-hidden bg-black/20">
                    <iframe id="chess-today-puzzle" src="https://www.chess.com/today-puzzle?theme=wood" class="w-full h-[450px] md:h-full min-h-[450px] rounded-xl border border-neutral-700 shadow-xl" scrolling="no" frameborder="0"></iframe>
                </div>
                <p class="mt-4 text-xs text-muted-foreground text-center italic"><?php _e('Mis à jour quotidiennement par Chess.com', 'najah-souss'); ?></p>
            </div>

            <!-- RIGHT COLUMN: Actions Hub -->
            <div class="border border-gold/30 bg-neutral-900 rounded-2xl p-6 shadow-2xl flex flex-col">
                <div class="flex items-center gap-3 mb-8">
                    <span class="text-2xl" aria-hidden="true">🎮</span>
                    <h2 class="text-xl font-display font-bold text-gold"><?php _e('Modes de Jeu & Entraînement', 'najah-souss'); ?></h2>
                </div>
                
                <div class="flex-1 flex flex-col gap-5 justify-center">
                    <!-- CTA 1 -->
                    <div class="group p-5 rounded-xl bg-black/20 border border-border hover:border-gold/30 transition-all duration-300">
                        <h3 class="text-sm uppercase tracking-widest text-muted-foreground mb-3 font-semibold"><?php _e('Compétition Numérique', 'najah-souss'); ?></h3>
                        <a href="https://www.chess.com/play/online" target="_blank" rel="noopener noreferrer" class="block w-full text-center border border-gold text-gold py-3 px-6 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 hover:bg-gold/5 hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]">
                            <?php _e('Ouvrir Chess.com en Plein Écran ↗', 'najah-souss'); ?>
                        </a>
                    </div>

                    <!-- CTA 2 -->
                    <div class="group p-5 rounded-xl bg-black/20 border border-border hover:border-gold/30 transition-all duration-300">
                        <h3 class="text-sm uppercase tracking-widest text-muted-foreground mb-3 font-semibold"><?php _e('Entraînement Tactique', 'najah-souss'); ?></h3>
                        <a href="https://www.chess.com/play/computer" target="_blank" rel="noopener noreferrer" class="block w-full text-center border border-gold text-gold py-3 px-6 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 hover:bg-gold/5 hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]">
                            <?php _e('Jouer contre l\'IA (Stockfish)', 'najah-souss'); ?>
                        </a>
                    </div>

                    <!-- CTA 3 -->
                    <div class="group p-5 rounded-xl bg-black/20 border border-border hover:border-gold/30 transition-all duration-300">
                        <h3 class="text-sm uppercase tracking-widest text-muted-foreground mb-3 font-semibold"><?php _e('Communauté Club', 'najah-souss'); ?></h3>
                        <a href="https://www.chess.com/clubs/details/najah-souss" target="_blank" rel="noopener noreferrer" class="block w-full text-center border border-gold text-gold py-3 px-6 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 hover:bg-gold/5 hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]">
                            <?php _e('Rejoindre l\'Équipe ANSAE ♞', 'najah-souss'); ?>
                        </a>
                    </div>
                </div>

                <p class="mt-8 text-[10px] text-center text-muted-foreground tracking-widest uppercase">
                    <?php _e('Utilisez vos identifiants existants pour synchroniser vos progrès', 'najah-souss'); ?>
                </p>
            </div>
        </div>

        <!-- FOOTER RETURN LINK -->
        <div class="mt-20 text-center">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-2 text-muted-foreground hover:text-gold transition-all duration-300 text-xs font-royal tracking-[0.3em] uppercase group">
                <span class="transform group-hover:-translate-x-2 transition-transform">←</span>
                <?php _e('RETOUR AU SITE OFFICIEL', 'najah-souss'); ?>
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
