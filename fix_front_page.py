import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
fp = os.path.join(THEME, 'front-page.php')

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix broken link wrappers: `ansae_t( get_field('something_link') ?: '#hash' ); ?>` -> `get_field('something_link') ?: '#hash'; ?>`
content = content.replace(r"ansae_t( get_field('hero_cta_1_link') ?: '#contact' ); ?>", r"get_field('hero_cta_1_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('hero_cta_2_link') ?: '#palmares' ); ?>", r"get_field('hero_cta_2_link') ?: '#palmares'; ?>")
content = content.replace(r"ansae_t( get_field('palmares_link') ?: '#contact' ); ?>", r"get_field('palmares_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('academie_cta_link') ?: '#contact' ); ?>", r"get_field('academie_cta_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('galerie_cta_link') ?: '#contact' ); ?>", r"get_field('galerie_cta_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('events_cta_link') ?: '#contact' ); ?>", r"get_field('events_cta_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('presse_cta_link') ?: '#contact' ); ?>", r"get_field('presse_cta_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('news_cta_link') ?: '#contact' ); ?>", r"get_field('news_cta_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('staff_cta_link') ?: '#contact' ); ?>", r"get_field('staff_cta_link') ?: '#contact'; ?>")
content = content.replace(r"ansae_t( get_field('partenariat_cta_link') ?: get_template_directory_uri() . '/assets/Dossier_Sponsoring_Najah_Souss.pdf' ); ?>", r"get_field('partenariat_cta_link') ?: get_template_directory_uri() . '/assets/Dossier_Sponsoring_Najah_Souss.pdf'; ?>")

# Remove extra `)` at the end of text wrappers
content = content.replace(r"? '♞ Rejoindre le Club' ) ); ?>", r"? '♞ Rejoindre le Club' ) ); ?>".replace(" ) );", " ) );")) # Actually, `esc_html( ansae_t( get_field(...) ?: '...' ) )` -> `ansae_t` uses `)` and `esc_html` uses `)`. So `) ); ?>` is correct!
# Wait, look at line 21 from earlier:
# `<?php echo esc_html( get_field('hero_cta_1_text') ?: '♞ Rejoindre le Club' ) ); ?>`
# The `ansae_t(` is missing! Because my bad regex injected it at the beginning of the `esc_url`!
# Let's completely revert the `hero_cta_1` lines and re-do them correctly.
