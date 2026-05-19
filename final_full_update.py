import os
import re

THEME_DIR = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme"

# --- 1. REPAIR TEMPLATE TYPOS & ENFORCE ANSAE_T ---
def fix_file_typos_and_keys(filepath):
    if not os.path.exists(filepath):
        return
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Repair regex typos
    typos = {
        r"l'\)excellence": "l'excellence",
        r"d'\)or": "d'or",
        r"s'\)inscrire": "s'inscrire",
        r"d'\)essai": "d'essai",
        r"S'JINSCRIRE": "S'INSCRIRE",
        r"ééchecs àà": "échecs à",
        r"ééchecs à": "échecs",
        r"êêtre": "être",
        r"««": "«",
        r"»»": "»",
        r"l'\)": "l'",
        r"d'\)": "d'",
        r"s'\)": "s'",
        r"qu'\)": "qu'",
    }
    for search, replace in typos.items():
        content = re.sub(search, replace, content)

    # Specific structural wraps requested by user in front-page.php
    if "front-page.php" in filepath:
        # "Formez-vous avec des Experts"
        content = re.sub(
            r"ansae_t\('Formez-vous avec des <span class=\"gradient-gold\">Experts</span>'\)",
            r"ansae_t('Formez-vous avec des Experts')",
            content
        )
        # "Notre Équipe - Classement FIDE"
        content = re.sub(
            r"ansae_t\('Notre Équipe - <span class=\"gradient-gold\">Classement FIDE</span>'\)",
            r"ansae_t('Notre Équipe - Classement FIDE')",
            content
        )
        # "Nos Events" (currently it might be split or Nos Événements)
        content = re.sub(
            r"ansae_t\('Nos'\);\s*\?>\s*<span class=\"gradient-gold\"><\?php\s*echo\s*ansae_t\('Événements'\);\s*\?></span>",
            r"ansae_t('Nos Events'); ?>",
            content
        )
        # Also fix any other split ones if found
        content = re.sub(
            r"ansae_t\('Nos'\);\s*\?>\s*<span class=\"gradient-gold\"><\?php\s*echo\s*ansae_t\('Events'\);\s*\?></span>",
            r"ansae_t('Nos Events'); ?>",
            content
        )

        # "Jouer aux Échecs Maintenant ♞"
        content = re.sub(
            r"ansae_t\('Jouer aux échecs Maintenant ♞'\)",
            r"ansae_t('Jouer aux Échecs Maintenant ♞')",
            content
        )
        content = re.sub(
            r"ansae_t\('Jouer aux Échecs àMaintenant ♞'\)",
            r"ansae_t('Jouer aux Échecs Maintenant ♞')",
            content
        )
        
        # Staff Refactor
        staff_search = r'<a href="<\?php the_permalink\(\); \?>" class="block surface-card rounded-2xl overflow-hidden hover:border-gold/40 group transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">([\s\S]*?)<div class="p-5 text-start">\s*<p class="eyebrow mb-2"><\?php echo esc_html\(get_field\(\'staff_role\'\)\); \?></p>\s*<h3 class="font-display font-bold text-lg mb-2"><\?php the_title\(\); \?></h3>\s*<div class="text-xs text-muted-foreground leading-relaxed"><\?php the_content\(\); \?></div>\s*</div>\s*</article>\s*</a>'
        staff_replace = r'''<article class="surface-card rounded-2xl overflow-hidden hover:border-gold/40 group transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl flex flex-col h-full w-full">
                        <a href="<?php the_permalink(); ?>" class="block">
                            \1
                        </a>
                        <div class="p-5 text-start flex flex-col flex-1">
                            <p class="eyebrow mb-2"><?php echo esc_html(get_field('staff_role')); ?></p>
                            <a href="<?php the_permalink(); ?>"><h3 class="font-display font-bold text-lg mb-2 hover:text-gold transition-colors"><?php the_title(); ?></h3></a>
                            <div class="text-xs text-muted-foreground leading-relaxed mb-4"><?php the_content(); ?></div>
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
                        </article>'''
        content = re.sub(staff_search, staff_replace, content)
        
        # Events Badges
        content = content.replace("echo esc_html(get_field('event_badge') ?: 'À venir');", "echo ansae_t(get_field('event_badge') ?: 'À venir');")

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

for filename in ['front-page.php', 'header.php', 'footer.php']:
    fix_file_typos_and_keys(os.path.join(THEME_DIR, filename))


# --- 2. INJECT THE PROFESSIONAL 3-LANGUAGE DICTIONARY (functions.php) ---
functions_path = os.path.join(THEME_DIR, 'functions.php')
with open(functions_path, 'r', encoding='utf-8') as f:
    funcs = f.read()

# We will just append the new translations safely into $dict array.
# The $dict is defined inside ansae_t() function, usually around line 343.
new_dict_entries = """
        // About Section
        "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987." => array('en' => "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987.", 'ar' => "نادي نجاح سوس للشطرنج هو نادي عريق مقره في أكادير، في قلب جهة سوس ماسة، تأسس عام 1987."),
        "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale." => array('en' => "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage.", 'ar' => "منضوٍ رسمياً تحت لواء الجامعة الملكية المغربية للشطرنج (FRME) ومعترف به من طرف الاتحاد الدولي للشطرنج (FIDE)، يمثل نادينا جهة سوس ماسة على الساحة الوطنية والدولية."),
        "« Aucun coup ne doit être joué sans but. »" => array('en' => "« No move should be played without a purpose. »", 'ar' => "« لا ينبغي لعب أي نقلة دون هدف »"),

        // Academy, Play & Challenge Sections
        "Académie & Formation" => array('en' => 'Academy & Training', 'ar' => 'الأكاديمية والتكوين'),
        "Formez-vous avec des Experts" => array('en' => 'Train with Experts', 'ar' => 'تدرب مع الخبراء'),
        "Encadrement Expert" => array('en' => 'Expert Coaching', 'ar' => 'تأطير من الخبراء'),
        "Cours individuels & collectifs disponibles" => array('en' => 'Individual & group lessons available', 'ar' => 'دروس فردية وجماعية متاحة'),
        "Prêt à relever le défi ?" => array('en' => 'Ready for the challenge?', 'ar' => 'هل أنت مستعد لرفع التحدي؟'),
        "Affrontez des joueurs du monde entier ou entraînez-vous contre l'IA sur notre espace dédié." => array('en' => "Face players from around the world or practice against AI in our dedicated arena.", 'ar' => "واجه لاعبين من جميع أنحاء العالم أو تدرب ضد الذكاء الاصطناعي في مساحتنا المخصصة."),
        "Jouer aux Échecs Maintenant ♞" => array('en' => 'Play Chess Now ♞', 'ar' => 'العب الشطرنج الآن ♞'),

        // Headings, Staff & Contact
        "Notre Équipe - Classement FIDE" => array('en' => 'Our Team - FIDE Ratings', 'ar' => 'فريقنا - تصنيف FIDE الدولي'),
        "Nos Events" => array('en' => 'Our Events', 'ar' => 'أنشطتنا وفعالياتنا'),
        "Notre Actualité" => array('en' => 'Latest News', 'ar' => 'آخر أخبارنا'),
        "Voir tout le staff" => array('en' => 'View all staff', 'ar' => 'عرض كل الطاقم'),
        "Une question, une inscription ou un partenariat ? Nous sommes là pour vous." => array('en' => "Got a question, want to register, or discuss a partnership? We are here for you.", 'ar' => "هل لديك سؤال، ترغب في التسجيل أو عقد شراكة؟ نحن هنا من أجلك."),

        // Partnership Section
        "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires." => array('en' => "Associate your brand with Agadir's standard of excellence. Najah Souss Chess — 2025 Throne Cup Champions — offers national and international visibility to its partners.", 'ar' => "اربط علامتك التجارية بالتميز في أكادير. يقدم نادي نجاح سوس - أبطال كأس العرش 2025 - رؤية وإشعاعاً وطنياً ودولياً لشركائه."),
        
        // Tags
        "À venir" => array('en' => 'Upcoming', 'ar' => 'قادم'),
        "Terminé" => array('en' => 'Past', 'ar' => 'منتهي'),
        "Lire l'article" => array('en' => 'Read Article', 'ar' => 'اقرأ المقال'),
"""

# Insert right before the end of $dict array
funcs = re.sub(
    r"(\s*// 4\. Partnership & Contact.*?\n\s*\);\s*\n\s*\$normalized_dict = array\(\);)",
    lambda m: new_dict_entries + m.group(1),
    funcs,
    flags=re.DOTALL
)

with open(functions_path, 'w', encoding='utf-8') as f:
    f.write(funcs)

print("Updates completed successfully.")
