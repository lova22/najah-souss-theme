import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix `if ( have_rows('...') :` and `while ( have_rows('...') :`
content = re.sub(r"if \(\s*have_rows\('([^']+)'\)\s*:", r"if ( have_rows('\1') ) :", content)
content = re.sub(r"while \(\s*have_rows\('([^']+)'\)\s*:", r"while ( have_rows('\1') ) :", content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
