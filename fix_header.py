import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\header.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix `theme('...';`
content = re.sub(r"theme\('([^']+)'\s*;", r"theme('\1');", content)

# Fix `ansae_t('...';`
content = re.sub(r"ansae_t\('([^']+)'\s*;", r"ansae_t('\1');", content)

# Fix `esc_attr_e('...', '...';`
content = re.sub(r"esc_attr_e\('([^']+)',\s*'([^']+)'\s*;", r"esc_attr_e('\1', '\2');", content)

# Fix `function_exists('pll_the_languages' ) {` -> `function_exists('pll_the_languages') ) {`
content = re.sub(r"function_exists\('pll_the_languages'\s*\)\s*\{", r"function_exists('pll_the_languages') ) {", content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
