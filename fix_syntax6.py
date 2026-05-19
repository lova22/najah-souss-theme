import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix `ansae_t('...';` that contains escaped quotes or whatever.
# Let's just find `ansae_t('...';`
content = re.sub(r"(ansae_t\('.*?'|ansae_t\(\".*?\")\s*;", r"\1);", content)

# Check for esc_html( ansae_t(...) ) where the closing bracket of esc_html is missing
# Actually, let's just make sure we don't have `;` immediately after `'` without a `)`
# Example: `('abc';` -> `('abc');`
# Be careful not to replace valid PHP.
# Let's find any function call that starts with `(` and ends with `'` then `;`.
# `(.*?['"])\s*;` -> `\1);` IF there's a missing parenthesis.
# Let's just fix the specific one we know about for now and any `ansae_t` ones.
content = re.sub(r"(ansae_t\((?:[^)(]+|\([^)(]*\))*\))[^)]*?;", lambda m: m.group(0) if m.group(0).endswith(");") else m.group(1) + ";", content)
# Wait, this is getting complicated. Let's just use a simple regex for `ansae_t( '...' ;`
content = re.sub(r"(ansae_t\('.*?'\))\s*;", r"\1;", content) 
# No, we want to ADD the `)` if it's missing.
# If it is `ansae_t('Répertoires d\'ouvertures';`
# It matches `ansae_t` then `(` then anything until `;`
# If it doesn't end with `)`, add `)`.
lines = content.split('\n')
for i, line in enumerate(lines):
    if "ansae_t(" in line and "ansae_t(" + ".*" + ";" in line:
        # just quick hack for front-page
        lines[i] = re.sub(r"ansae_t\((.*?['\"])\s*;", r"ansae_t(\1);", line)

content = '\n'.join(lines)

# Fix _e with two args that might have escaped quotes
content = re.sub(r"_e\((.*?['\"])\s*,\s*(.*?['\"])\s*;", r"_e(\1, \2);", content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
