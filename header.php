<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo is_rtl() ? 'dir="rtl"' : 'dir="ltr"'; ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Outfit:wght@300;400;600;700&family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        .rtl { font-family: 'Tajawal', sans-serif !important; }
        .rtl .font-display, .rtl h1, .rtl h2, .rtl h3 { font-family: 'Tajawal', sans-serif !important; font-weight: 800; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              background: "oklch(0.11 0.012 270)",
              foreground: "oklch(0.97 0.012 85)",
              surface: "oklch(0.15 0.014 270)",
              "surface-2": "oklch(0.19 0.016 270)",
              card: "oklch(0.15 0.014 270)",
              "card-foreground": "oklch(0.97 0.012 85)",
              popover: "oklch(0.15 0.014 270)",
              "popover-foreground": "oklch(0.97 0.012 85)",
              primary: "oklch(0.83 0.13 82)",
              "primary-foreground": "oklch(0.11 0.012 270)",
              gold: "oklch(0.83 0.13 82)",
              "gold-soft": "oklch(0.93 0.07 88)",
              secondary: "oklch(0.23 0.014 270)",
              "secondary-foreground": "oklch(0.97 0.012 85)",
              muted: "oklch(0.20 0.012 270)",
              "muted-foreground": "oklch(0.74 0.018 85)",
              accent: "oklch(0.83 0.13 82)",
              "accent-foreground": "oklch(0.11 0.012 270)",
              destructive: "oklch(0.6 0.22 25)",
              "destructive-foreground": "oklch(0.97 0.012 85)",
              border: "oklch(0.83 0.13 82 / 18%)",
              input: "oklch(0.20 0.014 270)",
              ring: "oklch(0.83 0.13 82)",
            },
            fontFamily: {
              sans: ["Inter", "system-ui", "sans-serif"],
              display: ["Cormorant Garamond", "Playfair Display", "Georgia", "serif"],
              royal: ["Cinzel", "Cormorant Garamond", "Georgia", "serif"],
            }
          }
        }
      }
    </script>
    <style type="text/tailwindcss">
      @layer base {
        * { border-color: theme('colors.border'); }
        html { scroll-behavior: smooth; }
        body {
          background-color: theme('colors.background');
          color: theme('colors.foreground');
          font-family: theme('fontFamily.sans');
          background-image:
            radial-gradient(ellipse at top, oklch(0.83 0.13 82 / 10%), transparent 55%),
            radial-gradient(ellipse at bottom right, oklch(0.25 0.04 280 / 35%), transparent 60%),
            radial-gradient(ellipse at bottom, oklch(0.13 0.014 270), theme('colors.background'));
        }
        h1, h2, h3, h4 { font-family: theme('fontFamily.display'); letter-spacing: -0.015em; font-weight: 600; }
      }

      @layer utilities {
        .text-gold { color: theme('colors.gold'); }
        .bg-gold { background-color: theme('colors.gold'); }
        .border-gold { border-color: theme('colors.gold'); }
        .font-royal { font-family: theme('fontFamily.royal'); letter-spacing: 0.08em; }
        .gradient-gold {
          background: linear-gradient(135deg, oklch(0.95 0.06 88), oklch(0.83 0.13 82) 45%, oklch(0.72 0.14 70));
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
        }
        .bg-gradient-gold {
          background: linear-gradient(135deg, oklch(0.92 0.09 86), oklch(0.78 0.14 75));
        }
        .surface-card {
          background:
            linear-gradient(180deg, oklch(0.17 0.014 270 / 90%), oklch(0.13 0.014 270 / 90%));
          border: 1px solid oklch(0.83 0.13 82 / 18%);
          box-shadow:
            inset 0 1px 0 0 oklch(0.83 0.13 82 / 10%),
            0 1px 0 0 oklch(0 0 0 / 30%);
        }
        .gold-shadow {
          box-shadow:
            0 0 0 1px oklch(0.83 0.13 82 / 25%),
            0 10px 50px -10px oklch(0.83 0.13 82 / 40%);
        }
        .gold-glow {
          box-shadow: 0 0 60px -10px oklch(0.83 0.13 82 / 35%);
        }
        .divider-gold {
          background: linear-gradient(90deg, transparent, theme('colors.gold'), transparent);
          height: 1px;
        }
        .ornament::before, .ornament::after {
          content: "";
          display: inline-block;
          width: 2.5rem;
          height: 1px;
          background: linear-gradient(90deg, transparent, theme('colors.gold'));
          vertical-align: middle;
          margin: 0 0.75rem;
        }
        .ornament::after {
          background: linear-gradient(90deg, theme('colors.gold'), transparent);
        }
        .eyebrow {
          font-family: theme('fontFamily.royal');
          text-transform: uppercase;
          letter-spacing: 0.35em;
          font-size: 0.72rem;
          color: theme('colors.gold');
          font-weight: 500;
        }
      }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-screen bg-background text-foreground' ); ?>>
<?php wp_body_open(); ?>

<?php $home_url = is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>
<header id="site-header" class="fixed top-0 inset-x-0 z-50 backdrop-blur-md bg-background/70 border-b border-border">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <a href="<?php echo $home_url; ?>#accueil" class="flex items-center gap-3" aria-label="Najah Souss Échecs — Accueil">
            <div class="size-11 rounded-md bg-gradient-gold flex items-center justify-center text-primary-foreground font-bold text-xl gold-glow" aria-hidden="true">♞</div>
            <div class="leading-tight">
                <div class="font-royal font-semibold text-base tracking-[0.18em] text-gold"><?php echo ansae_t('NAJAH SOUSS'); ?></div>
                <div class="text-[10px] text-muted-foreground tracking-[0.3em] uppercase"><?php echo ansae_t('Échecs · Depuis 1987'); ?></div>
            </div>
        </a>
        
        <nav aria-label="<?php esc_attr_e('Navigation principale', 'najah-souss'); ?>" class="hidden lg:flex items-center gap-7 text-sm">
            <a href="<?php echo $home_url; ?>#accueil" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Accueil'); ?></a>
            <a href="<?php echo $home_url; ?>#club" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Le Club'); ?></a>
            <a href="<?php echo $home_url; ?>#palmares" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Palmarès'); ?></a>
            <a href="<?php echo $home_url; ?>#academie" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Académie'); ?></a>
            <a href="<?php echo $home_url; ?>#galerie" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Galerie'); ?></a>
            <a href="<?php echo $home_url; ?>#evenements" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Événements'); ?></a>
            <a href="<?php echo $home_url; ?>#presse" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Presse'); ?></a>
            <a href="<?php echo $home_url; ?>#partenariat" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Partenariat'); ?></a>
            <a href="<?php echo $home_url; ?>#contact" class="text-muted-foreground hover:text-gold transition-colors"><?php echo ansae_t('Contact'); ?></a>
        </nav>
        
        <div class="flex items-center gap-4 md:gap-6">
            <!-- Premium Language Switcher Dropdown (Desktop) -->
            <div class="relative group hidden lg:block">
                <?php 
                $current_lang = '';
                $languages = array();
                if ( function_exists('pll_the_languages') ) {
                    $languages = pll_the_languages(array('raw' => 1));
                    foreach ($languages as $lang) {
                        if ($lang['current_lang']) {
                            $current_lang = $lang['slug'];
                            break;
                        }
                    }
                }
                if ( empty($current_lang) ) $current_lang = 'FR';
                ?>
                <button class="flex items-center gap-2 text-[11px] font-bold tracking-widest text-gold hover:text-white transition-colors uppercase border border-gold/20 px-3 py-1.5 rounded-md bg-neutral-900/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20"/><path d="M2 12h20"/></svg>
                    <span><?php echo esc_html($current_lang); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:rotate-180"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div class="absolute inset-inline-end-0 mt-2 w-24 bg-neutral-900 border border-gold/20 rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden">
                    <?php if ( ! empty($languages) ) : ?>
                        <?php foreach ($languages as $lang) : if (!$lang['current_lang']) : ?>
                            <a href="<?php echo esc_url($lang['url']); ?>" class="block px-4 py-3 text-[10px] font-bold tracking-widest text-muted-foreground hover:bg-gold/10 hover:text-gold transition-colors border-b border-gold/5 last:border-0 uppercase">
                                <?php echo esc_html($lang['slug']); ?>
                            </a>
                        <?php endif; endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <a href="<?php echo $home_url; ?>#contact" class="px-5 py-2.5 rounded-md bg-gold text-primary-foreground font-semibold text-sm tracking-wide hover:opacity-90 transition gold-shadow uppercase hidden sm:inline-block">
                <?php echo ansae_t('S\'inscrire'); ?>
            </a>

            <!-- Mobile Burger Menu Button -->
            <button id="mobile-menu-btn" class="block lg:hidden text-gold hover:text-white transition-colors p-2" aria-label="Toggle Menu">
                <svg id="burger-icon" class="w-7 h-7 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg id="close-icon" class="w-7 h-7 hidden transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="lg:hidden absolute top-20 inset-x-0 bg-neutral-950/95 backdrop-blur-xl border-b border-gold/20 transition-all duration-300 overflow-hidden" style="max-height: 0; opacity: 0; visibility: hidden;">
        <nav class="flex flex-col p-6 space-y-4 text-center">
            <a href="<?php echo $home_url; ?>#accueil" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Accueil'); ?></a>
            <a href="<?php echo $home_url; ?>#club" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Le Club'); ?></a>
            <a href="<?php echo $home_url; ?>#palmares" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Palmarès'); ?></a>
            <a href="<?php echo $home_url; ?>#academie" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Académie'); ?></a>
            <a href="<?php echo $home_url; ?>#galerie" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Galerie'); ?></a>
            <a href="<?php echo $home_url; ?>#evenements" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Événements'); ?></a>
            <a href="<?php echo $home_url; ?>#presse" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Presse'); ?></a>
            <a href="<?php echo $home_url; ?>#partenariat" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Partenariat'); ?></a>
            <a href="<?php echo $home_url; ?>#contact" class="mobile-link text-lg font-bold text-white hover:text-gold transition-colors py-2 border-b border-white/5"><?php echo ansae_t('Contact'); ?></a>
            
            <div class="pt-4 flex justify-center gap-4">
                <?php if ( ! empty($languages) ) : foreach ($languages as $lang) : ?>
                    <a href="<?php echo esc_url($lang['url']); ?>" class="px-4 py-2 text-xs font-bold tracking-widest uppercase border <?php echo $lang['current_lang'] ? 'border-gold text-gold bg-gold/10' : 'border-gold/20 text-muted-foreground hover:text-white'; ?> rounded-md transition-colors">
                        <?php echo esc_html($lang['slug']); ?>
                    </a>
                <?php endforeach; endif; ?>
            </div>
            <a href="<?php echo $home_url; ?>#contact" class="mobile-link mt-4 px-5 py-3 rounded-md bg-gold text-primary-foreground font-bold tracking-wide uppercase shadow-lg inline-block sm:hidden">
                <?php echo ansae_t('S\'inscrire'); ?>
            </a>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const burgerIcon = document.getElementById('burger-icon');
        const closeIcon = document.getElementById('close-icon');
        const mobileLinks = document.querySelectorAll('.mobile-link');
        let isOpen = false;

        function toggleMenu() {
            isOpen = !isOpen;
            if (isOpen) {
                menu.style.maxHeight = '100vh';
                menu.style.opacity = '1';
                menu.style.visibility = 'visible';
                burgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                menu.style.maxHeight = '0';
                menu.style.opacity = '0';
                setTimeout(() => { if(!isOpen) menu.style.visibility = 'hidden'; }, 300);
                burgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }

        btn.addEventListener('click', toggleMenu);
        mobileLinks.forEach(link => link.addEventListener('click', () => { if(isOpen) toggleMenu(); }));
    });
</script>
