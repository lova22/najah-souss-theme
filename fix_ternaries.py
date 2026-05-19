import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
fp = os.path.join(THEME, 'front-page.php')

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix lines 65 and 66 (About description hardcoded ternaries)
# Instead of matching the corrupted text exactly, let's use regex to find the blocks and replace them.
# The block is `<p><?php if(function_exists('pll_current_language')...</p>`
pattern1 = r"<p><\?php\s*if\(function_exists\('pll_current_language'\).*?1987\.<\?php endif; \?></p>"
replacer1 = """<p><?php echo wp_kses_post( ansae_t("Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987.") ); ?></p>"""
content = re.sub(pattern1, replacer1, content, flags=re.DOTALL)

pattern2 = r"<p><\?php\s*if\(function_exists\('pll_current_language'\).*?nationale et internationale\.<\?php endif; \?></p>"
replacer2 = """<p><?php echo wp_kses_post( ansae_t("Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale.") ); ?></p>"""
content = re.sub(pattern2, replacer2, content, flags=re.DOTALL)

# Fix "Formez-vous avec des Experts" which is in the template but missing ansae_t maybe?
# Wait! In the screenshot, "Formez-vous avec des Experts" is there. Let's see how it is in front-page.php.
# I will just replace `Formez-vous avec des Experts` with `<?php echo ansae_t('Formez-vous avec des Experts'); ?>`
# Wait, it might be in get_field.
# Let's search for `Formez-vous avec des Experts`
pattern3 = r"Formez-vous avec des Experts"
# It wasn't found before because it's in header.php or footer.php? No, it's Academy section.
# What if the text in the template is `Formez-vous avec des Experts`?
# Let's do a global replace for these exact strings, wrapping them if they aren't wrapped.
# But `wrap_ansae.py` wrapped them. 
# Why did "Formez-vous avec des Experts" show in French? 
# Maybe it's in `header.php`? No, it's the Academy section on the front page.
# If it's in the database, wrapping `get_field` in `ansae_t` should have worked!

with open(fp, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed About section ternaries in front-page.php.")
