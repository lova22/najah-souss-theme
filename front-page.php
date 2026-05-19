<?php get_header(); ?>

<main>
    <!-- HERO -->
    <section id="accueil" aria-label="Hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-chess.jpg" alt="Échiquier Najah Souss Échecs" class="absolute inset-0 w-full h-full object-cover opacity-50" width="1920" height="1080" />
        <div class="absolute inset-0 bg-gradient-to-b from-background/60 via-background/40 to-background" aria-hidden="true"></div>
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center py-24">
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full border border-gold/40 bg-background/60 backdrop-blur mb-10 gold-glow">
                <span class="text-gold" aria-hidden="true">✦</span>
                <span class="text-xs tracking-[0.35em] uppercase text-gold font-royal"><?php echo esc_html( ansae_t( get_field('hero_top_badge') ?: 'Vainqueurs de la Coupe du Trône 2025' ) ); ?></span>
                <span class="text-gold" aria-hidden="true">✦</span>
            </div>
            <p class="eyebrow mb-6"><?php echo esc_html( ansae_t( get_field('hero_eyebrow') ?: 'Agadir · Maroc — Depuis 1987' ) ); ?></p>
            <h1 class="font-royal text-4xl md:text-6xl font-semibold tracking-[0.06em] mb-3"><?php echo wp_kses_post( ansae_t( get_field('hero_title') ?: 'NAJAH SOUSS <span class="gradient-gold">ECHECS</span>' ) ); ?></h1>
            <p class="font-display italic text-lg md:text-2xl text-gold-soft/90 mb-8 ornament inline-block"><?php echo esc_html( ansae_t( get_field('hero_motto') ?: 'Aucun coup ne doit être joué sans but' ) ); ?></p>
            <h2 class="text-3xl md:text-5xl font-display gradient-gold mb-8 italic"><?php echo esc_html( ansae_t( get_field('hero_heading') ?: 'Champions du Maroc 2025' ) ); ?></h2>
            <p class="text-lg md:text-xl text-muted-foreground mb-2"><?php echo esc_html( ansae_t( get_field('hero_description') ?: 'L\'excellence des échecs à Agadir.' ) ); ?></p>
            <p class="text-lg md:text-xl italic text-muted-foreground mb-10"><?php echo esc_html( ansae_t( get_field('hero_sub_description') ?: 'Formation, Compétition et Culture.' ) ); ?></p>
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <a href="<?php echo esc_url( get_field('hero_cta_1_link') ?: '#contact' ); ?>" class="px-8 py-4 rounded-md bg-gradient-gold text-primary-foreground font-semibold gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]"><?php echo esc_html( ansae_t( get_field('hero_cta_1_text') ?: '♞ Rejoindre le Club' ) ); ?></a>
                <a href="<?php echo esc_url( get_field('hero_cta_2_link') ?: '#palmares' ); ?>" class="px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold hover:bg-gold/10 transition-all duration-300 transform hover:scale-105 hover:shadow-lg"><?php echo esc_html( ansae_t( get_field('hero_cta_2_text') ?: 'Voir le Palmarès' ) ); ?></a>
            </div>
            <ul class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto list-none">
                <?php if ( have_rows('hero_stats')) : ?>
                    <?php while ( have_rows('hero_stats')) : the_row(); ?>
                        <li class="text-center">
                            <div class="text-3xl md:text-4xl text-gold font-display font-bold mb-1"><?php the_sub_field('stat_value'); ?></div>
                            <div class="text-xs uppercase tracking-widest text-muted-foreground"><?php the_sub_field('stat_label'); ?></div>
                        </li>
                    <?php endwhile; ?>
                <?php else : ?>
                    <li class="text-center">
                        <div class="text-3xl md:text-4xl text-gold font-display font-bold mb-1">🏆</div>
                        <div class="text-xs uppercase tracking-widest text-muted-foreground"><?php echo ansae_t('Coupe du Trône 2025'); ?></div>
                    </li>
                    <li class="text-center">
                        <div class="text-3xl md:text-4xl text-gold font-display font-bold mb-1">8×</div>
                        <div class="text-xs uppercase tracking-widest text-muted-foreground"><?php echo ansae_t('Médailles d\'Or 2025-26'); ?></div>
                    </li>
                    <li class="text-center">
                        <div class="text-3xl md:text-4xl text-gold font-display font-bold mb-1">FIDE</div>
                        <div class="text-xs uppercase tracking-widest text-muted-foreground"><?php echo ansae_t('Affilié'); ?></div>
                    </li>
                    <li class="text-center">
                        <div class="text-3xl md:text-4xl text-gold font-display font-bold mb-1">1987</div>
                        <div class="text-xs uppercase tracking-widest text-muted-foreground"><?php echo ansae_t('Année de fondation'); ?></div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="club" class="py-24 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo esc_html( ansae_t( get_field('about_title_1') ?: 'À PROPOS' ) ); ?></h2>
                <div class="space-y-5 text-muted-foreground leading-relaxed">
                    <?php if ( get_field('about_description') ) : ?>
                        <?php echo wp_kses_post( get_field('about_description') ); ?>
                    <?php else : ?>
                        <p><?php echo wp_kses_post( ansae_t("Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987.") ); ?></p>
                        <p><?php echo wp_kses_post( ansae_t("Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale.") ); ?></p>
                    <?php endif; ?>
                    <p class="font-display italic text-gold-soft/90 text-lg pt-2"><?php echo esc_html( ansae_t( get_field('about_motto') ?: '« Aucun coup ne doit être joué sans but. »' ) ); ?></p>
                </div>
                <ul class="flex flex-wrap gap-2 mt-8 list-none">
                    <?php if ( have_rows('about_badges') ) : ?>
                        <?php while ( have_rows('about_badges') ) : the_row(); ?>
                            <li class="px-3 py-1.5 rounded-full bg-surface border border-gold/30 text-xs text-gold"><?php the_sub_field('badge_text'); ?></li>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <li class="px-3 py-1.5 rounded-full bg-surface border border-gold/30 text-xs text-gold"><?php echo ansae_t('Affilié FRME'); ?></li>
                        <li class="px-3 py-1.5 rounded-full bg-surface border border-gold/30 text-xs text-gold"><?php echo ansae_t('Reconnu FIDE'); ?></li>
                        <li class="px-3 py-1.5 rounded-full bg-surface border border-gold/30 text-xs text-gold"><?php echo ansae_t('Agadir, Maroc'); ?></li>
                        <li class="px-3 py-1.5 rounded-full bg-surface border border-gold/30 text-xs text-gold"><?php echo ansae_t('Depuis 1987'); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="space-y-4">
                <?php if ( have_rows('about_pillars') ) : ?>
                    <?php while ( have_rows('about_pillars') ) : the_row(); ?>
                        <article class="surface-card rounded-xl p-6 flex gap-5 hover:border-gold/40 transition">
                            <div class="size-12 shrink-0 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-2xl" aria-hidden="true"><?php the_sub_field('pillar_icon'); ?></div>
                            <div>
                                <h3 class="font-display font-bold text-lg text-gold mb-1"><?php the_sub_field('pillar_title'); ?></h3>
                                <p class="text-sm text-muted-foreground"><?php the_sub_field('pillar_description'); ?></p>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <article class="surface-card rounded-xl p-6 flex gap-5 hover:border-gold/40 transition">
                        <div class="size-12 shrink-0 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-2xl" aria-hidden="true">📚</div>
                        <div>
                            <h3 class="font-display font-bold text-lg text-gold mb-1"><?php echo ansae_t('Éducation'); ?></h3>
                            <p class="text-sm text-muted-foreground"><?php echo ansae_t('Les échecs comme outil pédagogique pour développer la concentration, la logique et la créativité.'); ?></p>
                        </div>
                    </article>
                    <article class="surface-card rounded-xl p-6 flex gap-5 hover:border-gold/40 transition">
                        <div class="size-12 shrink-0 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-2xl" aria-hidden="true">🏆</div>
                        <div>
                            <h3 class="font-display font-bold text-lg text-gold mb-1"><?php echo ansae_t('Compétition'); ?></h3>
                            <p class="text-sm text-muted-foreground"><?php echo ansae_t('Participation aux tournois régionaux, nationaux et internationaux homologués FIDE/FRME.'); ?></p>
                        </div>
                    </article>
                    <article class="surface-card rounded-xl p-6 flex gap-5 hover:border-gold/40 transition">
                        <div class="size-12 shrink-0 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-2xl" aria-hidden="true">🤝</div>
                        <div>
                            <h3 class="font-display font-bold text-lg text-gold mb-1"><?php echo ansae_t('Fair-play'); ?></h3>
                            <p class="text-sm text-muted-foreground"><?php echo ansae_t('Respect mutuel, éthique irréprochable et esprit sportif au cœur de chaque partie.'); ?></p>
                        </div>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- PALMARES -->
    <section id="palmares" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo esc_html( ansae_t( get_field('palmares_eyebrow') ?: 'Hall of Fame' ) ); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo wp_kses_post( ansae_t( get_field('palmares_title') ?: 'Notre Palmarès' ) ); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html( ansae_t( get_field('palmares_subtitle') ?: 'Coupe du Trône 2025, 8 médailles d\' )or et 4 sélections nationales —à l\'excellence de Najah Souss Échecs.') ); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="surface-card rounded-2xl p-8 mb-6 text-center gold-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-gold/5 via-gold/15 to-gold/5" aria-hidden="true"></div>
                <div class="relative">
                    <div class="inline-block px-4 py-1 rounded-full bg-gold text-primary-foreground text-xs font-bold tracking-widest mb-4"><?php echo ansae_t('TITRE MAJEUR'); ?></div>
                    <div class="text-5xl mb-3" aria-hidden="true">🏆</div>
                    <h3 class="text-3xl md:text-4xl font-bold gradient-gold mb-2"><?php echo esc_html( get_field('palmares_major_title') ?: ((function_exists('pll_current_language') && pll_current_language()=='ar') ? 'كأس العرش 2025' : 'Coupe du Trône 2025') ); ?></h3>
                    <p class="text-muted-foreground"><?php echo esc_html( get_field('palmares_major_desc') ?: 'Champion National — Catégorie Ouverte · Jerrada, Août 2025' ); ?></p>
                </div>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <?php if ( have_rows('palmares_list') ) : ?>
                    <?php while ( have_rows('palmares_list') ) : the_row(); ?>
                        <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                            <div class="text-3xl mb-3" aria-hidden="true"><?php the_sub_field('icon'); ?></div>
                            <h3 class="font-display font-bold text-base mb-1"><?php the_sub_field('title'); ?></h3>
                            <p class="text-xs text-muted-foreground tracking-wide"><?php the_sub_field('description'); ?></p>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <!-- Fallback if ACF is not populated -->
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🥇</div>
                        <h3 class="font-display font-bold text-base mb-1">Inès Boufous — U8 Féminines</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Championne du Maroc 2025 & 2026</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🥇</div>
                        <h3 class="font-display font-bold text-base mb-1">Wissale Cherbini — U20 Féminines</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Championne du Maroc 2025 & 2026</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🥇</div>
                        <h3 class="font-display font-bold text-base mb-1">Houda Cherbini — U18 Féminines</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Championne du Maroc 2025</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🥇</div>
                        <h3 class="font-display font-bold text-base mb-1">Houssine Fana — U14 Masculins</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Champion du Maroc 2025 · Sélection Nationale</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🥇</div>
                        <h3 class="font-display font-bold text-base mb-1">Fahd Amrani — U18 Masculins</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Champion du Maroc 2026</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🥇</div>
                        <h3 class="font-display font-bold text-base mb-1">Maryam Herraq — U14 Féminines</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Championne du Maroc 2026</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🌍</div>
                        <h3 class="font-display font-bold text-base mb-1">Équipe Nationale 2026</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">4 sélections — U14 M / U14 F / U16 F / U18 M</p>
                    </article>
                    <article class="surface-card rounded-xl p-6 text-center hover:border-gold/40 transition">
                        <div class="text-3xl mb-3" aria-hidden="true">🏅</div>
                        <h3 class="font-display font-bold text-base mb-1">8 Médailles d'Or</h3>
                        <p class="text-xs text-muted-foreground tracking-wide">Championnats nationaux — 2 ans consécutifs</p>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CHAMPIONS -->
    <section id="champions" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Mur des Champions'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <?php
                $champions = new WP_Query(array(
                    'post_type' => 'champion',
                    'posts_per_page' => 4,
                    'suppress_filters' => false
                ));
                if ($champions->have_posts()) :
                    while ($champions->have_posts()) : $champions->the_post();
                ?>
                    <a href="<?php the_permalink(); ?>" class="block group rounded-xl overflow-hidden surface-card flex flex-col transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                        <article class="flex flex-col h-full w-full">
                            <div class="relative w-full aspect-square overflow-hidden bg-black">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Échecs" loading="lazy" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php endif; ?>
                            </div>
                            <div class="bg-white rounded-b-xl p-5 shadow-md text-gray-950 flex-1 flex flex-col justify-center text-start">
                                <h3 class="font-display font-bold text-xl mb-1"><?php the_title(); ?></h3>
                                <p class="text-gold text-sm font-medium tracking-wide uppercase"><?php echo esc_html(get_field('champion_title')); ?></p>
                            </div>
                        </article>
                    </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucun champion trouvé.</p>
                <?php endif; ?>
            </div>
            
            <div class="mt-14 text-center">
                <a href="<?php echo get_post_type_archive_link('champion'); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir tous les champions'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- CLASSEMENT FIDE -->
    <section id="classement" class="py-12 px-6 bg-neutral-950/50">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo wp_kses_post( ansae_t('Notre Équipe - Classement FIDE') ); ?></h2>
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
                            'posts_per_page' => 8,
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
                                    <?php the_title(); ?>
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
                                <td colspan="4" class="px-6 py-8 text-center text-muted-foreground"><?php echo ansae_t('Aucun classement disponible.'); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-10 text-center">
                <a href="<?php echo esc_url(get_post_type_archive_link('champion')); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir tous les champions'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- ACADEMIE -->
    <section id="academie" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Académie & Formation'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo wp_kses_post( ansae_t('Formez-vous avec des Experts') ); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Programmes pédagogiques pour tous les niveaux, encadrés par des entraîneurs certifiés et des joueurs classés FIDE.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <article class="surface-card rounded-2xl p-8 relative">
                    <div class="text-5xl text-gold mb-4" aria-hidden="true">♟</div>
                    <p class="eyebrow mb-2"><?php echo ansae_t('Débutant'); ?></p>
                    <h3 class="font-display font-bold text-2xl mb-4"><?php echo ansae_t('Les Pions'); ?></h3>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Règles & mouvements'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Tactiques simples'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Mat en 1-2 coups'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Parties guidées'); ?></li>
                    </ul>
                </article>
                <article class="surface-card rounded-2xl p-8 relative border-gold/60 gold-shadow scale-[1.02]">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-gold text-primary-foreground text-[11px] font-bold tracking-widest"><?php echo ansae_t('POPULAIRE'); ?></span>
                    <div class="text-5xl text-gold mb-4" aria-hidden="true">♝</div>
                    <p class="eyebrow mb-2"><?php echo ansae_t('Intermédiaire'); ?></p>
                    <h3 class="font-display font-bold text-2xl mb-4"><?php echo ansae_t('Les Fous & Tours'); ?></h3>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Principes d\'ouverture'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Combinaisons tactiques'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Fins de partie'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Analyse de parties'); ?></li>
                    </ul>
                </article>
                <article class="surface-card rounded-2xl p-8 relative">
                    <div class="text-5xl text-gold mb-4" aria-hidden="true">♛</div>
                    <p class="eyebrow mb-2"><?php echo ansae_t('Avancé'); ?></p>
                    <h3 class="font-display font-bold text-2xl mb-4"><?php echo ansae_t('Les Dames & Rois'); ?></h3>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Stratégie positionnelle'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Répertoires d\'ouvertures'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Préparation tournois'); ?></li>
                        <li class="flex gap-2"><span class="text-gold" aria-hidden="true">✦</span><?php echo ansae_t('Analyse informatique'); ?></li>
                    </ul>
                </article>
            </div>
            <div class="surface-card rounded-2xl overflow-hidden grid md:grid-cols-2 items-center">
                <img src="<?php echo get_template_directory_uri(); ?>/src/assets/academy.jpg" alt="Cours d'échecsNajah Souss Échecs" loading="lazy" class="h-full w-full object-cover aspect-video" />
                <div class="p-10">
                    <p class="eyebrow mb-3"><?php echo ansae_t('Encadrement Expert'); ?></p>
                    <h3 class="font-display font-bold text-2xl mb-3"><?php echo ansae_t('Cours individuels & collectifs disponibles'); ?></h3>
                    <p class="text-muted-foreground mb-6"><?php echo ansae_t('Choisissez la formule qui vous convient et progressez à votre rythme avec nos coachs FIDE.'); ?></p>
                    <a href="https://wa.me/212600000000" target="_blank" class="inline-block px-6 py-3 rounded-md bg-gold text-primary-foreground font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]"><?php echo ansae_t('Demander un cours d\'essai'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- PLAY CHESS CTA -->
    <section id="jouer-en-ligne" class="py-20 px-6">
        <div class="max-w-5xl mx-auto surface-card rounded-3xl p-10 md:p-16 text-center relative overflow-hidden border border-gold/30">
            <div class="absolute inset-0 bg-gradient-to-r from-gold/5 via-transparent to-gold/5" aria-hidden="true"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Prêt à relever le défi ?'); ?></h2>
                <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-10 leading-relaxed">
                    <?php echo ansae_t('Affrontez des joueurs du monde entier ou entraînez-vous contre l\'IA sur notre espace dédié.'); ?>
                </p>
                <a href="<?php echo esc_url( home_url( '/jouer/' ) ); ?>" class="inline-block px-10 py-5 rounded-md bg-gradient-gold text-primary-foreground font-bold tracking-wide gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]">
                    <?php echo ansae_t('Jouer aux Échecs Maintenant ♞'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- GALERIE -->
    <section id="galerie" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Galerie'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Galerie Najah Souss'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Tournois, cérémonies, entraînements et victoires — la vie du club en images.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php
                $gallery = new WP_Query(array(
                    'post_type' => 'gallery',
                    'posts_per_page' => 3,
                    'suppress_filters' => false
                ));
                if ($gallery->have_posts()) :
                    while ($gallery->have_posts()) : $gallery->the_post();
                ?>
                    <a href="<?php the_permalink(); ?>" class="block group relative rounded-xl overflow-hidden surface-card aspect-[4/3] transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                        <figure class="w-full h-full">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1580541832626-2a7131ee809f?auto=format&fit=crop&w=800&q=80" alt="Ééchecs àGalerie" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/10 to-transparent opacity-0 group-hover:opacity-100 transition" aria-hidden="true"></div>
                        <figcaption class="absolute bottom-0 inset-x-0 p-4 text-sm font-medium text-gold opacity-0 group-hover:opacity-100 transition"><?php the_title(); ?></figcaption>
                        </figure>
                    </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucune image trouvée.</p>
                <?php endif; ?>
            </div>

            <div class="mt-14 text-center">
                <a href="<?php echo get_post_type_archive_link('gallery'); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir toute la galerie'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- EVENEMENTS -->
    <section id="evenements" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Calendrier 2025'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Calendrier des Événements'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Des tournois pour tous les niveaux, des compétitions officielles aux événements culturels.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid md:grid-cols-3 gap-6">
                <?php
                $events = new WP_Query(array(
                    'post_type' => 'event',
                    'posts_per_page' => 3,
                    'suppress_filters' => false
                ));
                if ($events->have_posts()) :
                    while ($events->have_posts()) : $events->the_post();
                ?>
                    <a href="<?php the_permalink(); ?>" class="block group surface-card rounded-2xl p-7 flex flex-col hover:border-gold/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                        <article class="flex flex-col flex-1 h-full">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl" aria-hidden="true"><?php echo esc_html(get_field('event_icon') ?: '🏆'); ?></span>
                            <span class="text-[10px] tracking-widest uppercase px-2 py-1 rounded-full bg-gold/10 text-gold border border-gold/30"><?php echo ansae_t(get_field('event_badge') ?: 'À venir'); ?></span>
                        </div>
                        <p class="eyebrow mb-2"><?php echo esc_html(ansae_t(get_field('event_category'))); ?></p>
                        <h3 class="font-display font-bold text-xl mb-3"><?php the_title(); ?></h3>
                        <div class="text-sm text-muted-foreground mb-5 flex-1"><?php the_excerpt(); ?></div>
                        <footer class="border-t border-border pt-4 text-xs text-muted-foreground space-y-1">
                            <div>📅 <?php echo esc_html(get_field('event_date')); ?></div>
                            <div>📍 <?php echo esc_html(get_field('event_location')); ?></div>
                        </footer>
                        </article>
                    </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucun événement trouvé.</p>
                <?php endif; ?>
            </div>

            <div class="mt-14 text-center">
                <a href="<?php echo get_post_type_archive_link('event'); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir tous les événements'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- PRESSE -->
    <section id="presse" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ((function_exists('pll_current_language')&&pll_current_language()=='en') ? 'Media & Press' : 'Médias & Presse'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Ils parlent de nous'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('La presse nationale et locale couvre les succès de Najah Souss Échecs.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid md:grid-cols-3 gap-6">
                <?php
                $presse = new WP_Query(array(
                    'post_type' => 'presse',
                    'posts_per_page' => 3,
                    'suppress_filters' => false
                ));
                if ($presse->have_posts()) :
                    while ($presse->have_posts()) : $presse->the_post();
                ?>
                    <a href="<?php echo get_field('external_link') ? esc_url(get_field('external_link')) : '#'; ?>" target="_blank" rel="noopener noreferrer" class="block group surface-card rounded-2xl overflow-hidden flex flex-col hover:border-gold/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                        <article class="flex flex-col flex-1">
                        <div class="relative aspect-video overflow-hidden">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Ééchecs àPresse" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php endif; ?>
                            <span class="absolute top-3 left-3 text-[10px] uppercase tracking-widest px-2 py-1 rounded bg-background/80 text-gold border border-gold/30"><?php echo esc_html(get_field('presse_tag') ?: 'Article'); ?></span>
                        </div>
                        <div class="p-6 flex flex-col flex-1 text-start">
                            <p class="text-xs text-gold mb-2 font-semibold"><?php echo esc_html(get_field('presse_source')); ?></p>
                            <h3 class="font-display font-bold text-lg mb-3"><?php the_title(); ?></h3>
                            <div class="text-sm text-muted-foreground mb-4 flex-1"><?php the_excerpt(); ?></div>
                            <span class="text-gold text-sm font-semibold border-b border-gold/40 self-start"><?php echo ansae_t('Lire l\'article'); ?> →</span>
                        </div>
                        </article>
                    </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucun article de presse trouvé.</p>
                <?php endif; ?>
            </div>

            <div class="mt-14 text-center">
                <a href="<?php echo get_post_type_archive_link('presse'); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir toute la presse'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- ACTUALITES -->
    <section id="actualites" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Notre Actualité'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo wp_kses_post( ansae_t('Notre Actualité') ); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Les dernières nouvelles, événements et annonces de Najah Souss Échecs.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid md:grid-cols-3 gap-6">
                <?php
                $actualites = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'suppress_filters' => false
                ));
                if ($actualites->have_posts()) :
                    while ($actualites->have_posts()) : $actualites->the_post();
                ?>
                    <article class="group surface-card rounded-2xl overflow-hidden flex flex-col hover:border-gold/50 gold-glow transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                        <div class="relative aspect-video overflow-hidden">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1580541832626-2a7131ee809f?auto=format&fit=crop&w=800&q=80" alt="Actualité Ééchecs" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent" aria-hidden="true"></div>
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                echo '<span class="absolute top-3 left-3 text-[10px] uppercase tracking-[0.25em] px-3 py-1 rounded-full bg-background/80 text-gold border border-gold/40">' . esc_html( $categories[0]->name ) . '</span>';
                            }
                            ?>
                        </div>
                        <div class="p-7 flex flex-col flex-1 text-start">
                            <div class="text-[10px] tracking-widest uppercase text-gold/60 mb-3"><?php echo get_the_date(); ?></div>
                            <h3 class="font-display font-semibold text-xl mb-3 leading-snug"><?php the_title(); ?></h3>
                            <div class="text-sm text-muted-foreground mb-6 flex-1"><?php the_excerpt(); ?></div>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-md border border-gold/40 text-gold text-sm font-semibold tracking-wide hover:bg-gold hover:text-primary-foreground hover:gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <?php echo ansae_t('Lire la suite'); ?> <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucune actualité trouvée.</p>
                <?php endif; ?>
            </div>

            <div class="mt-14 text-center">
                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir toutes les actualités'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- STAFF -->
    <section id="staff" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Direction & Encadrement'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Staff Technique & Administratif'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Une équipe d\'experts au service de l\'excellence des échecs.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                <?php
                $staff = new WP_Query(array(
                    'post_type' => 'staff',
                    'posts_per_page' => 4,
                    'suppress_filters' => false
                ));
                if ($staff->have_posts()) :
                    while ($staff->have_posts()) : $staff->the_post();
                ?>
                    <article class="surface-card rounded-2xl overflow-hidden hover:border-gold/40 group transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl flex flex-col">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="aspect-square overflow-hidden bg-black">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1528819622765-d6bcf132f793?auto=format&fit=crop&w=800&q=80" alt="Staff" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" />
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="p-5 text-start flex flex-col flex-1">
                            <p class="eyebrow mb-2"><?php echo esc_html(get_field('staff_role')); ?></p>
                            <a href="<?php the_permalink(); ?>"><h3 class="font-display font-bold text-lg mb-2 hover:text-gold transition-colors"><?php the_title(); ?></h3></a>
                            <div class="flex gap-4 mt-auto pt-4 border-t border-gold/10">
                                <?php if(get_field('staff_facebook')): ?>
                                <a href="<?php echo esc_url(get_field('staff_facebook')); ?>" target="_blank" class="text-gold/60 hover:text-gold transition-colors" title="Facebook">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('staff_email')): ?>
                                <a href="mailto:<?php echo esc_attr(get_field('staff_email')); ?>" class="text-gold/60 hover:text-gold transition-colors" title="Email">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucun membre du staff trouvé.</p>
                <?php endif; ?>
            </div>

            <div class="mt-14 text-center">
                <a href="<?php echo get_post_type_archive_link('staff'); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir tout le staff'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- BOUTIQUE -->
    <section id="boutique" class="py-24 px-6 bg-neutral-950/30">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Supportez votre club'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Boutique Officielle'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Portez fièrement les couleurs de Najah Souss Echecs avec notre collection exclusive.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $products = new WP_Query(array(
                    'post_type' => 'product',
                    'posts_per_page' => 3,
                    'suppress_filters' => false
                ));
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
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
                    wp_reset_postdata();
                else :
                ?>
                    <p class="col-span-full text-center text-muted-foreground">Aucun produit trouvé.</p>
                <?php endif; ?>
            </div>
            
            <div class="mt-14 text-center">
                <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="inline-block px-8 py-4 rounded-md border border-gold/60 text-gold font-semibold tracking-wide transition-all duration-300 transform hover:scale-105 hover:bg-gold/10 hover:shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                    <?php echo ansae_t('Voir toute la boutique'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- PARTENARIAT -->
    <section id="partenariat" class="py-24 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-gold/40 bg-background/60 backdrop-blur mb-10 gold-glow">
                <span class="text-gold" aria-hidden="true">★</span>
                <span class="text-xs tracking-[0.35em] uppercase text-gold font-royal"><?php echo esc_html( ansae_t( get_field('partenariat_badge') ?: 'Partenariat' ) ); ?></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2 whitespace-nowrap overflow-hidden text-ellipsis"><?php echo esc_html( ansae_t('Devenir Partenaire des Champions') ); ?></h2>
            <p class="font-display text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto mb-14 leading-relaxed">
                <?php echo wp_kses_post( ansae_t('Associez votre marque à l\'excellence d\'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires.') ); ?>
            </p>
            <div class="grid md:grid-cols-3 gap-6 mb-14 max-w-5xl mx-auto">
                <?php if ( have_rows('partenariat_perks') ) : ?>
                    <?php while ( have_rows('partenariat_perks') ) : the_row(); ?>
                        <article class="surface-card rounded-xl p-8 hover:border-gold/40 transition">
                            <div class="text-4xl mb-4" aria-hidden="true"><?php the_sub_field('perk_icon'); ?></div>
                            <h3 class="font-display font-bold text-lg text-gold mb-1"><?php the_sub_field('perk_title'); ?></h3>
                            <p class="text-sm text-muted-foreground"><?php the_sub_field('perk_description'); ?></p>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <article class="surface-card rounded-xl p-8 hover:border-gold/40 transition">
                        <div class="text-4xl mb-4" aria-hidden="true">🏆</div>
                        <h3 class="font-display font-bold text-lg text-gold mb-1"><?php echo ansae_t('Visibilité Nationale'); ?></h3>
                        <p class="text-sm text-muted-foreground"><?php echo ansae_t('Tournois FRME / FIDE'); ?></p>
                    </article>
                    <article class="surface-card rounded-xl p-8 hover:border-gold/40 transition">
                        <div class="text-4xl mb-4" aria-hidden="true">📱</div>
                        <h3 class="font-display font-bold text-lg text-gold mb-1"><?php echo ansae_t('Réseaux Sociaux'); ?></h3>
                        <p class="text-sm text-muted-foreground"><?php echo ansae_t('Communauté engagée'); ?></p>
                    </article>
                    <article class="surface-card rounded-xl p-8 hover:border-gold/40 transition">
                        <div class="text-4xl mb-4" aria-hidden="true">🤝</div>
                        <h3 class="font-display font-bold text-lg text-gold mb-1"><?php echo ansae_t('Impact Social'); ?></h3>
                        <p class="text-sm text-muted-foreground"><?php echo ansae_t('Éducation jeunesse'); ?></p>
                    </article>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="YOUR_PDF_LINK_HERE" target="_blank" download class="inline-flex items-center gap-3 px-8 py-4 rounded-md bg-gradient-gold text-primary-foreground font-semibold tracking-wide gold-shadow transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-[0_0_15px_rgba(212,175,55,0.6)]">
                    <span aria-hidden="true">⬇</span> <?php echo esc_html( ansae_t( get_field('partenariat_cta_text') ?: 'TÉLÉCHARGER LE DOSSIER SPONSORING' ) ); ?>
                </a>
                <a href="#contact" class="inline-flex items-center px-8 py-4 rounded-md border border-foreground/80 text-foreground font-semibold tracking-wide hover:bg-foreground hover:text-background transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                    <?php echo ansae_t('NOUS CONTACTER'); ?>
                </a>
            </div>

            <!-- Partnership Logo Grid -->
            <div class="mt-20 pt-12 border-t border-gold/10 grid grid-cols-2 md:grid-cols-4 gap-12 items-center max-w-5xl mx-auto">
                <div class="flex justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300 transform hover:scale-110">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/FIDE_Logo.png" alt="FIDE" class="h-12 w-auto object-contain">
                </div>
                <div class="flex justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300 transform hover:scale-110">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Logo_FRME.png" alt="FRME" class="h-16 w-auto object-contain">
                </div>
                <div class="flex justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300 transform hover:scale-110">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Logo_MDJS.svg/1200px-Logo_MDJS.svg.png" alt="MDJS" class="h-10 w-auto object-contain">
                </div>
                <div class="flex justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300 transform hover:scale-110">
                    <img src="https://upload.wikimedia.org/wikipedia/fr/4/4c/Logotype_de_la_R%C3%A9gion_Souss-Massa.png" alt="Région Souss-Massa" class="h-14 w-auto object-contain">
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <header class="text-center mb-16">
                <p class="eyebrow mb-4"><?php echo ansae_t('Contact'); ?></p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-tajawal mb-2"><?php echo ansae_t('Rejoignez Najah Souss Echecs'); ?></h2>
                <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo ansae_t('Une question, une inscription ou un partenariat ? Nous sommes là pour vous.'); ?></p>
                <div class="divider-gold w-24 mx-auto mt-6" aria-hidden="true"></div>
            </header>
            
            <div class="grid md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div class="flex gap-4 surface-card rounded-xl p-5">
                        <div class="size-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-xl" aria-hidden="true">📍</div>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gold mb-1"><?php echo ansae_t('Adresse'); ?></p>
                            <p class="font-medium"><?php echo ansae_t('Local Najah Souss Échecs, Corniche d\')Agadir'); ?></p>
                        </div>
                    </div>
                    <div class="flex gap-4 surface-card rounded-xl p-5">
                        <div class="size-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-xl" aria-hidden="true">✉️</div>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gold mb-1"><?php echo ansae_t('Email'); ?></p>
                            <p class="font-medium">najahsousseéchecs@gmail.com</p>
                        </div>
                    </div>
                    <div class="flex gap-4 surface-card rounded-xl p-5">
                        <div class="size-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-xl" aria-hidden="true">📞</div>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gold mb-1"><?php echo ansae_t('Téléphone'); ?></p>
                            <p class="font-medium">+212 6XX XXX XXX</p>
                        </div>
                    </div>
                    <div class="surface-card rounded-xl p-6">
                        <h3 class="font-display font-bold text-lg mb-4">🏅 <?php echo ansae_t('Comment s\'inscrire ?'); ?></h3>
                        <ol class="space-y-3 text-sm text-muted-foreground">
                            <li class="flex gap-3"><span class="size-6 shrink-0 rounded-full bg-gold text-primary-foreground flex items-center justify-center text-xs font-bold">1</span><?php echo ansae_t('Remplissez le formulaire ci-contre'); ?></li>
                            <li class="flex gap-3"><span class="size-6 shrink-0 rounded-full bg-gold text-primary-foreground flex items-center justify-center text-xs font-bold">2</span><?php echo ansae_t('Un responsable vous contactera sous 48h'); ?></li>
                            <li class="flex gap-3"><span class="size-6 shrink-0 rounded-full bg-gold text-primary-foreground flex items-center justify-center text-xs font-bold">3</span><?php echo ansae_t('Participez à une séance d\'essai gratuite'); ?></li>
                            <li class="flex gap-3"><span class="size-6 shrink-0 rounded-full bg-gold text-primary-foreground flex items-center justify-center text-xs font-bold">4</span><?php echo ansae_t('Rejoignez officiellement le club !'); ?></li>
                        </ol>
                    </div>
                </div>
                <div class="surface-card rounded-2xl p-8 w-full [&_input[type=text]]:w-full [&_input[type=email]]:w-full [&_input]:bg-white/5 [&_input]:border [&_input]:border-white/10 [&_input]:rounded-xl [&_input]:px-4 [&_input]:py-3 [&_input]:text-white [&_input]:placeholder:text-gray-400 [&_input]:focus:border-gold [&_input]:focus:ring-1 [&_input]:focus:ring-gold [&_input]:outline-none [&_input]:transition [&_textarea]:w-full [&_textarea]:bg-white/5 [&_textarea]:border [&_textarea]:border-white/10 [&_textarea]:rounded-xl [&_textarea]:px-4 [&_textarea]:py-3 [&_textarea]:text-white [&_textarea]:placeholder:text-gray-400 [&_textarea]:focus:border-gold [&_textarea]:focus:ring-1 [&_textarea]:focus:ring-gold [&_textarea]:outline-none [&_textarea]:transition [&_input[type=submit]]:w-full [&_input[type=submit]]:bg-gold [&_input[type=submit]]:hover:bg-yellow-500 [&_input[type=submit]]:text-black [&_input[type=submit]]:font-bold [&_input[type=submit]]:py-3 [&_input[type=submit]]:rounded-xl [&_input[type=submit]]:cursor-pointer [&_input[type=submit]]:transition [&_label]:text-sm [&_label]:text-gray-300 [&_label]:mb-2 [&_label]:block [&_.wpcf7-not-valid-tip]:text-red-400 [&_.wpcf7-not-valid-tip]:text-sm [&_.wpcf7-not-valid-tip]:mt-1 [&_.wpcf7-response-output]:border-gold [&_.wpcf7-response-output]:text-white [&_.wpcf7-response-output]:rounded-xl [&_.wpcf7-response-output]:p-4 [&_.wpcf7-response-output]:mt-4">
                    <?php echo do_shortcode('[contact-form-7 id="f729269" title="Contact form 1"]'); ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
