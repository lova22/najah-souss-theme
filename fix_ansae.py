import os, sys, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    new_content = re.sub(r'\(+ansae_t\(([\'\"].*?[\'\"])\)\)+', r'ansae_t(\1)', content)
    
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
print('Cleaned up parentheses.')
