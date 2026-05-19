import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
fp = os.path.join(THEME, 'front-page.php')

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix split headers in front-page.php
# "Formez-vous avec des Experts"
content = content.replace(
    r"<?php echo ansae_t('Formez-vous avec des'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Experts'); ?></span>",
    r"<?php echo wp_kses_post( ansae_t('Formez-vous avec des <span class=\"gradient-gold\">Experts</span>') ); ?>"
)

# "Notre équipe - Classement FIDE"
content = content.replace(
    r"<?php echo ansae_t('Notre équipe -'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Classement FIDE'); ?></span>",
    r"<?php echo wp_kses_post( ansae_t('Notre équipe - <span class=\"gradient-gold\">Classement FIDE</span>') ); ?>"
)
content = content.replace(
    r"<?php echo ansae_t('Notre quipe -'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Classement FIDE'); ?></span>",
    r"<?php echo wp_kses_post( ansae_t('Notre équipe - <span class=\"gradient-gold\">Classement FIDE</span>') ); ?>"
)

# "Notre Actualité"
content = content.replace(
    r"<?php echo ansae_t('Notre'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Actualité'); ?></span>",
    r"<?php echo wp_kses_post( ansae_t('Notre <span class=\"gradient-gold\">Actualité</span>') ); ?>"
)
content = content.replace(
    r"<?php echo ansae_t('Notre'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Actualit'); ?></span>",
    r"<?php echo wp_kses_post( ansae_t('Notre <span class=\"gradient-gold\">Actualité</span>') ); ?>"
)

with open(fp, 'w', encoding='utf-8') as f:
    f.write(content)

# Now update functions.php dictionary keys
fp_func = os.path.join(THEME, 'functions.php')
with open(fp_func, 'r', encoding='utf-8') as f:
    func_content = f.read()

func_content = func_content.replace(
    r'"Formez-vous avec des Experts" => array(\'ar\' => "تدرب مع الخبراء", \'en\' => "Train with Experts"),',
    r'"Formez-vous avec des <span class=\"gradient-gold\">Experts</span>" => array(\'ar\' => "تدرب مع <span class=\"gradient-gold\">الخبراء</span>", \'en\' => "Train with <span class=\"gradient-gold\">Experts</span>"),'
)
func_content = func_content.replace(
    r'"Notre Équipe - Classement FIDE" => array(\'ar\' => "فريقنا - تصنيف FIDE الدولي", \'en\' => "Our Team - FIDE Ratings"),',
    r'"Notre équipe - <span class=\"gradient-gold\">Classement FIDE</span>" => array(\'ar\' => "فريقنا - <span class=\"gradient-gold\">تصنيف FIDE الدولي</span>", \'en\' => "Our Team - <span class=\"gradient-gold\">FIDE Ratings</span>"),'
)
func_content = func_content.replace(
    r'"Notre Actualité" => array(\'ar\' => "آخر أخبارنا", \'en\' => "Latest News"),',
    r'"Notre <span class=\"gradient-gold\">Actualité</span>" => array(\'ar\' => "آخر <span class=\"gradient-gold\">أخبارنا</span>", \'en\' => "Latest <span class=\"gradient-gold\">News</span>"),'
)

with open(fp_func, 'w', encoding='utf-8') as f:
    f.write(func_content)

print("Fixed split ansae_t calls.")
