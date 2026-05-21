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
                        
                        <?php
                        $fide_id = get_post_meta(get_the_ID(), '_champion_fide_id', true);
                        if (empty($fide_id) && function_exists('get_field')) {
                            $fide_id = get_field('fide_id');
                        }
                        
                        $standard = function_exists('get_field') ? get_field('rating_standard') : '';
                        $rapid = function_exists('get_field') ? get_field('rating_rapid') : '';
                        $blitz = function_exists('get_field') ? get_field('rating_blitz') : '';
                        
                        $api_data = function_exists('ansae_fetch_fide_data') ? ansae_fetch_fide_data($fide_id) : false;
                        $chart_data = [];
                        $birth_year = '-';
                        $federation = '-';
                        $world_rank = '-';
                        $national_rank = '-';
                        $continental_rank = '-';
                        
                        if ($api_data) {
                            if (isset($api_data['std_rating'])) $standard = $api_data['std_rating'];
                            if (isset($api_data['rapid_rating'])) $rapid = $api_data['rapid_rating'];
                            if (isset($api_data['blitz_rating'])) $blitz = $api_data['blitz_rating'];
                            
                            if (isset($api_data['birth_year'])) $birth_year = $api_data['birth_year'];
                            if (isset($api_data['federation'])) $federation = $api_data['federation'];
                            if (isset($api_data['world_rank_active'])) $world_rank = $api_data['world_rank_active'];
                            if (isset($api_data['national_rank_active'])) $national_rank = $api_data['national_rank_active'];
                            if (isset($api_data['continental_rank_active'])) $continental_rank = $api_data['continental_rank_active'];
                            
                            // Grab history array
                            if (isset($api_data['history']) && is_array($api_data['history'])) {
                                $chart_data = array_reverse($api_data['history']); // Reverse if API returns newest first, Chart.js usually wants oldest left to newest right
                            }
                        }
                        
                        $standard = $standard ?: 'N/A';
                        $rapid = $rapid ?: 'N/A';
                        $blitz = $blitz ?: 'N/A';
                        ?>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('FIDE ID'); ?></div>
                                <div class="font-mono text-gold font-bold"><?php echo esc_html($fide_id ?: '-'); ?></div>
                            </div>
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg relative group">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('STANDARD'); ?></div>
                                <div class="font-mono text-white font-bold"><?php echo esc_html($standard); ?></div>
                                <?php if($api_data) echo '<div class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.8)]" title="Live API Data"></div>'; ?>
                            </div>
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg relative group">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('RAPID'); ?></div>
                                <div class="font-mono text-white font-bold"><?php echo esc_html($rapid); ?></div>
                            </div>
                            <div class="bg-surface border border-gold/20 p-4 rounded-xl text-center shadow-lg relative group">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('BLITZ'); ?></div>
                                <div class="font-mono text-white font-bold"><?php echo esc_html($blitz); ?></div>
                            </div>
                        </div>

                        <!-- FIDE Ratings Chart -->
                        <?php if (!empty($chart_data)) : ?>
                        <div class="mb-10 bg-surface/50 border border-gold/10 rounded-2xl p-6 shadow-xl">
                            <h3 class="text-sm font-bold text-white tracking-widest uppercase mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                <?php echo ansae_t('Évolution Elo'); ?>
                            </h3>
                            <div class="relative w-full h-64">
                                <canvas id="eloHistoryChart"></canvas>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Premium Info Grid -->
                        <?php if ($api_data && $fide_id) : ?>
                        <div class="mb-10 bg-surface/50 border border-gold/10 rounded-2xl p-6 shadow-xl">
                            <h3 class="text-sm font-bold text-white tracking-widest uppercase mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo ansae_t('Statistiques Officielles FIDE'); ?>
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div>
                                    <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('Année de naissance'); ?></div>
                                    <div class="text-white font-medium text-lg"><?php echo esc_html($birth_year); ?></div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('Fédération'); ?></div>
                                    <div class="text-white font-medium text-lg"><?php echo esc_html($federation); ?></div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('Rang National Actif'); ?></div>
                                    <div class="text-gold font-bold text-lg">#<?php echo esc_html($national_rank); ?></div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-muted-foreground uppercase tracking-widest mb-1"><?php echo ansae_t('Rang Mondial Actif'); ?></div>
                                    <div class="text-gold font-bold text-lg">#<?php echo esc_html($world_rank); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

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

<?php if (!empty($chart_data)) : ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('eloHistoryChart');
    if (!ctx) return;
    
    const chartData = <?php echo wp_json_encode($chart_data); ?>;
    console.log("FIDE API Chart Data:", chartData);

    if (!chartData || !Array.isArray(chartData) || chartData.length === 0) {
        console.warn('FIDE Chart: No valid history data found or empty array.');
        return;
    }
    
    // Extract dates and standard ratings robustly
    const labels = chartData.map(item => item.date || item.period || 'N/A');
    const dataPoints = chartData.map(item => {
        let val = parseInt(item.classical_rating || item.std_rating || item.standard);
        return isNaN(val) ? null : val;
    });
    
    // Filter out completely null datasets so the chart doesn't crash
    const validDataPoints = dataPoints.filter(d => d !== null);
    if (validDataPoints.length === 0) {
        console.warn('FIDE Chart: Could not parse any valid ratings from data.');
        return;
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Standard Elo',
                data: dataPoints,
                borderColor: '#d4af37', // Gold
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#d4af37',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#d4af37',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#d4af37',
                    borderColor: 'rgba(212, 175, 55, 0.3)',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: 'rgba(255,255,255,0.5)', maxTicksLimit: 6 }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                    ticks: { color: 'rgba(255,255,255,0.5)' },
                    suggestedMin: Math.min(...validDataPoints) - 50,
                    suggestedMax: Math.max(...validDataPoints) + 50
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<?php get_footer(); ?>
