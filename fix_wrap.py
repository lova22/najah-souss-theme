import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # The broken pattern looks like:
    # `ansae_t( get_field(A) ?: B )`
    # Where B spans across PHP tags.
    # We can match `ansae_t\(\s*get_field\((.*?)\)\s*\?:\s*(.*?)\s*\)` where B contains `?>`.
    # Let's write a replacer that splits B.
    
    def replacer(match):
        A = match.group(1)
        B = match.group(2)
        
        if '?>' in B:
            # We must break it up into the proper parts.
            # B actually contains ` '#contact' ); ?>" ... <?php echo esc_html( get_field('hero_cta_1_text') ?: '♞ Rejoindre le Club' `
            # So the original was: `get_field(A) ?: B` ... wait, the original was:
            # `get_field(A) ?: B1 ); ?>" ... get_field(C) ?: ansae_t(B2)`
            
            # Let's just fix it manually using string replacements because there are very few of them.
            return match.group(0)
        return match.group(0)

# Actually, I will just manually fix the exact lines since I know them.
# The broken lines in front-page.php are:
# `ansae_t( get_field('hero_cta_1_link') ?: '#contact' ); ?>`
# `ansae_t( get_field('hero_cta_2_link') ?: '#palmares' ); ?>`
# `ansae_t( get_field('palmares_link') ?: '#contact' ); ?>`
# Wait, I don't know all of them. I'll just use a regex.

with open(os.path.join(THEME, 'front-page.php'), 'r', encoding='utf-8') as f:
    front = f.read()

# Fix the broken replacements
# What did `wrap_ansae.py` do?
# It changed `get_field('hero_cta_1_link') ?: '#contact' ); ?>" class="...">... <?php echo esc_html( get_field('hero_cta_1_text') ?: ansae_t('♞ Rejoindre le Club')`
# To: `ansae_t( get_field('hero_cta_1_link') ?: '#contact' ); ?>" class="...">... <?php echo esc_html( get_field('hero_cta_1_text') ?: '♞ Rejoindre le Club' )`

# We want: `get_field('hero_cta_1_link') ?: '#contact' ); ?>" class="...">... <?php echo esc_html( ansae_t( get_field('hero_cta_1_text') ?: '♞ Rejoindre le Club' )`

def fix_broken(match):
    # match is `ansae_t( get_field(` ... `)`
    # We will search for `ansae_t( get_field(A) ?: B` where B contains `?>`
    pass

# A simpler way:
# Just find `ansae_t( get_field(` and if there is a `?>` before the closing `)`, we know it's broken.
# Since regex is hard, let's just do a simple replacement script that fixes the exact known patterns:
# Pattern: `ansae_t( get_field(X) ?: Y ); ?>` -> `get_field(X) ?: Y ); ?>`
# Pattern: `get_field(X) ?: Y )` where it is inside `esc_html(` -> `ansae_t( get_field(X) ?: Y )`

# Let's revert ALL `ansae_t( get_field` that contain `?>` back to original, then re-apply correctly.
# But I lost the original.
# Let's just manually fix the HTML by replacing the broken parts.
