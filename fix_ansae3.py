import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replace `(function_exists(...) ? '...' : ansae_t('...'))` with `ansae_t('...')`
    # We want to consume any enclosing parentheses that wrap the whole ternary.
    # But wait! If it's inside `esc_html( ... ?: ... )`, the outer parentheses belong to `esc_html`!
    # If the ternary is `((function_exists(...) ? ... : ansae_t('...')))`, we should replace it with `ansae_t('...')`
    # Let's match carefully: `\(*function_exists\('pll_current_language'\).*?\?\s*['"].*?['"]\s*:\s*(ansae_t\((['"]).*?\2\))\)*`
    # We will replace it with `\1` and then let PHP linter tell us if there's any missing/extra parentheses, which we can fix by adding/removing.
    
    # Actually, a better regex that handles optional nested parentheses:
    # `\(+function_exists\('pll_current_language'\).*?\?\s*['"].*?['"]\s*:\s*(ansae_t\((['"]).*?\2\))\)+`
    pattern = r"\(+function_exists\('pll_current_language'\).*?\?\s*['\"].*?['\"]\s*:\s*(ansae_t\((['\"]).*?\2\))\)+"
    
    # This might consume the `)` of `esc_html()`. We will see.
    def replacer(m):
        return m.group(1)
        
    new_content = re.sub(pattern, replacer, content)
    
    # Also handle `(function_exists...` (no leading '(')
    pattern2 = r"function_exists\('pll_current_language'\).*?\?\s*['\"].*?['\"]\s*:\s*(ansae_t\((['\"]).*?\2\))\)*"
    new_content = re.sub(pattern2, replacer, new_content)
    
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
print('Cleaned up remaining ternaries.')
