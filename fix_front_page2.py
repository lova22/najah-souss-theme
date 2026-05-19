import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
fp = os.path.join(THEME, 'front-page.php')

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

# I know EXACTLY what went wrong. The regex `get_field\((.*?)\)\s*\?:\s*ansae_t\((.*?)\)` matched across the `href` and the `>Text</a>`
# This caused: `ansae_t( get_field('link') ?: '#link' ); ?>" class="...">... <?php echo esc_html( get_field('text') ?: 'Fallback' )`
# I can find all such broken lines using regex:
# `ansae_t\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\);\s*\?>"(.*?)<\?php echo esc_html\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\)`
# And replace it with:
# `get_field(\1) ?: \2; ?>"\3<?php echo esc_html( ansae_t( get_field(\4) ?: \5 ) )`

def replacer(match):
    m1 = match.group(1) # link field
    m2 = match.group(2) # fallback link
    m3 = match.group(3) # class="..." etc
    m4 = match.group(4) # text field
    m5 = match.group(5) # fallback text (could end with an extra parenthesis because of the old replacement)
    
    # Strip any trailing parenthesis from m5 if it exists due to the previous regex mismatch
    m5 = m5.strip()
    if m5.endswith(')'):
        m5 = m5[:-1].strip()
        
    return f"get_field({m1}) ?: {m2}; ?>\"{m3}<?php echo esc_html( ansae_t( get_field({m4}) ?: {m5} ) )"

pattern = r'ansae_t\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\);\s*\?>"(.*?)<\?php echo esc_html\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\)'
content = re.sub(pattern, replacer, content)

# Now what about wp_kses_post ? Sometimes it's `wp_kses_post` instead of `esc_html`.
pattern_kses = r'ansae_t\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\);\s*\?>"(.*?)<\?php echo wp_kses_post\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\)'
def replacer_kses(match):
    m1 = match.group(1); m2 = match.group(2); m3 = match.group(3); m4 = match.group(4); m5 = match.group(5).strip()
    if m5.endswith(')'): m5 = m5[:-1].strip()
    return f"get_field({m1}) ?: {m2}; ?>\"{m3}<?php echo wp_kses_post( ansae_t( get_field({m4}) ?: {m5} ) )"
content = re.sub(pattern_kses, replacer_kses, content)

# Also fix the one that uses esc_html without get_field? No, the regex was exactly for get_field.

with open(fp, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed broken cross-tag regex replacements.")
