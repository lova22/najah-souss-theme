import os

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    content = content.replace("ÀÀ", "À")
    content = content.replace("ÉÉ", "É")
    content = content.replace("éé", "é")
    content = content.replace("àà", "à")
    content = content.replace("Eéchecs", "Échecs")
    
    # Also fix the split strings
    content = content.replace(
        r"<?php echo ansae_t('Notre Équipe -'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Classement FIDE'); ?></span>",
        r"<?php echo wp_kses_post( ansae_t('Notre équipe - <span class=\"gradient-gold\">Classement FIDE</span>') ); ?>"
    )
    content = content.replace(
        r"<?php echo ansae_t('Notre'); ?> <span class=\"gradient-gold\"><?php echo ansae_t('Actualité'); ?></span>",
        r"<?php echo wp_kses_post( ansae_t('Notre <span class=\"gradient-gold\">Actualité</span>') ); ?>"
    )

    with open(fp, 'w', encoding='utf-8') as f:
        f.write(content)

print("Fixed double accents and remaining splits.")
