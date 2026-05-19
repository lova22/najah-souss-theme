import os

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
fp = os.path.join(THEME, 'front-page.php')

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix esc_url missing parenthesis
content = content.replace(r"get_field('hero_cta_1_link') ?: '#contact'; ?>", r"get_field('hero_cta_1_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('hero_cta_2_link') ?: '#palmares'; ?>", r"get_field('hero_cta_2_link') ?: '#palmares' ); ?>")
content = content.replace(r"get_field('palmares_link') ?: '#contact'; ?>", r"get_field('palmares_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('academie_cta_link') ?: '#contact'; ?>", r"get_field('academie_cta_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('galerie_cta_link') ?: '#contact'; ?>", r"get_field('galerie_cta_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('events_cta_link') ?: '#contact'; ?>", r"get_field('events_cta_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('presse_cta_link') ?: '#contact'; ?>", r"get_field('presse_cta_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('news_cta_link') ?: '#contact'; ?>", r"get_field('news_cta_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('staff_cta_link') ?: '#contact'; ?>", r"get_field('staff_cta_link') ?: '#contact' ); ?>")
content = content.replace(r"get_field('partenariat_cta_link') ?: get_template_directory_uri() . '/assets/Dossier_Sponsoring_Najah_Souss.pdf'; ?>", r"get_field('partenariat_cta_link') ?: get_template_directory_uri() . '/assets/Dossier_Sponsoring_Najah_Souss.pdf' ); ?>")

# Fix missing ansae_t text wrapper
content = content.replace(r"<?php echo esc_html( get_field('hero_cta_1_text')", r"<?php echo esc_html( ansae_t( get_field('hero_cta_1_text')")
content = content.replace(r"<?php echo esc_html( get_field('hero_cta_2_text')", r"<?php echo esc_html( ansae_t( get_field('hero_cta_2_text')")
content = content.replace(r"<?php echo esc_html( get_field('palmares_cta_text')", r"<?php echo esc_html( ansae_t( get_field('palmares_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('academie_cta_text')", r"<?php echo esc_html( ansae_t( get_field('academie_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('galerie_cta_text')", r"<?php echo esc_html( ansae_t( get_field('galerie_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('events_cta_text')", r"<?php echo esc_html( ansae_t( get_field('events_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('presse_cta_text')", r"<?php echo esc_html( ansae_t( get_field('presse_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('news_cta_text')", r"<?php echo esc_html( ansae_t( get_field('news_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('staff_cta_text')", r"<?php echo esc_html( ansae_t( get_field('staff_cta_text')")
content = content.replace(r"<?php echo esc_html( get_field('partenariat_cta_text')", r"<?php echo esc_html( ansae_t( get_field('partenariat_cta_text')")

with open(fp, 'w', encoding='utf-8') as f:
    f.write(content)
print("Fixed esc_url parentheses and ansae_t wrappers.")
