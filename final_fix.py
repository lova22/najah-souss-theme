import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'

fp_front = os.path.join(THEME, 'front-page.php')
with open(fp_front, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix `((ansae_t` or `(?:ansae_t` etc to just `ansae_t`
content = re.sub(r'\(\s*\(\s*ansae_t', 'ansae_t', content)
content = re.sub(r'\(\s*ansae_t', 'ansae_t', content)

# Fix missing closing parentheses around esc_html(get_field(...) ?: ansae_t(...))
# Actually, if I just replace `ansae_t('...') );` with `ansae_t('...') );`
# Wait, let's fix `?: ansae_t('...'))` to `?: ansae_t('...'))`
content = re.sub(r'esc_html\(\s*get_field\((.*?)\)\s*\?:\s*ansae_t\((.*?)\)\s*;\s*\?>', r'esc_html( get_field(\1) ?: ansae_t(\2) ); ?>', content)

# Also fix `ansae_t('D\')EXCELLENCE')` to `ansae_t('D\'EXCELLENCE')`
content = content.replace(r"ansae_t('D\')EXCELLENCE')", r"ansae_t('D\'EXCELLENCE')")

# Remove any extra trailing parentheses inside PHP blocks that cause errors.
# We'll just run a regex to clean `ansae_t('...') )` to `ansae_t('...')` if it's not inside `esc_html`.
content = content.replace(r"ansae_t('Coupe du Trône 2025'));", r"ansae_t('Coupe du Trône 2025');")
content = content.replace(r"ansae_t('Médailles d\')Or 2025-26');", r"ansae_t('Médailles d\'Or 2025-26');")
content = content.replace(r"ansae_t('Affilié'));", r"ansae_t('Affilié');")
content = content.replace(r"ansae_t('Année de fondation'));", r"ansae_t('Année de fondation');")
content = content.replace(r"ansae_t('Nom complet *'));", r"ansae_t('Nom complet *');")
content = content.replace(r"ansae_t('Email *'));", r"ansae_t('Email *');")
content = content.replace(r"ansae_t('Message *'));", r"ansae_t('Message *');")
content = content.replace(r"ansae_t('Envoyer ma demande ♞'));", r"ansae_t('Envoyer ma demande ♞');")

with open(fp_front, 'w', encoding='utf-8') as f:
    f.write(content)

fp_footer = os.path.join(THEME, 'footer.php')
with open(fp_footer, 'r', encoding='utf-8') as f:
    footer = f.read()
    
footer = footer.replace("esc_attrecho ansae_t", "echo esc_attr(ansae_t")
footer = footer.replace("ansae_t('Réseaux sociaux'); ?>", "ansae_t('Réseaux sociaux')); ?>")

with open(fp_footer, 'w', encoding='utf-8') as f:
    f.write(footer)

print("Fixed syntax errors.")
