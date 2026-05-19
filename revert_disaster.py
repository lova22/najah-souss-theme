import os

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # Revert the disastrous "t" -> "été" replacement
    content = content.replace("été", "t")

    # Fix line 21 and similar lines properly
    # The broken pattern is:
    # `ansae_t( get_field('hero_cta_1_link') ?: '#contact' ); ?>`
    # We want:
    # `get_field('hero_cta_1_link') ?: '#contact'; ?>`
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

    with open(fp, 'w', encoding='utf-8') as f:
        f.write(content)

print("Reverted disastrous replacement and fixed links.")
