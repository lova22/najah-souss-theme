import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # Pattern: get_field('field_name') ?: ansae_t('String')
    # Actually, ACF returns an empty string, not false, sometimes. But `?:` works if it's falsy.
    # Regex to match: `get_field\((.*?)\)\s*\?:\s*ansae_t\((.*?)\)`
    # We want to capture `\1` and `\2`
    
    def replacer(match):
        field_part = match.group(1)
        string_part = match.group(2)
        return f"ansae_t( get_field({field_part}) ?: {string_part} )"

    new_content = re.sub(r'get_field\((.*?)\)\s*\?:\s*ansae_t\((.*?)\)', replacer, content)
    
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)

print("Wrapped get_field in ansae_t successfully.")
