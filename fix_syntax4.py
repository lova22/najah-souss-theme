import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix `ansae_t('...';` or `any_function('...';` missing the closing parenthesis.
# Essentially, if a string ends with a quote, and is immediately followed by `;` or ` ;` instead of `)`, add the `)`.
# Pattern: \('([^']+)'\s*;
content = re.sub(r"\('([^']+)'\s*;", r"('\1');", content)

# Check for missing `)` before `)` (like `esc_html( ansae_t('...') ;`)
# Wait, `esc_html( ansae_t('...' ) )` -> `ansae_t('...' ` -> missing `)`
# Let's just fix any `('string'` that is not followed by `,` or `)` or `.` or `:`
content = re.sub(r"\('([^']+)'\s*\)", r"('\1')", content)  # clean up spaces
content = re.sub(r"\('([^']+)'\s+(?!\)|,|:|\.)", r"('\1') ", content) 

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
