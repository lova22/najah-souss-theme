import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix broken function calls globally
# any have_rows('...s' : or have_rows('...s' ) :
content = re.sub(r"have_rows\('([^']+s)'\s*:", r"have_rows('\1') :", content)
content = re.sub(r"have_rows\('([^']+s)'\s*\)", r"have_rows('\1')", content)

# Check for any other broken functions that end with s'
# get_field('...s'
content = re.sub(r"get_field\('([^']+s)'\s*\)", r"get_field('\1')", content)
content = re.sub(r"get_field\('([^']+s)'\s*\??:", r"get_field('\1') ?:", content)
# It was `get_field('about_badges')` originally? No, badges is have_rows.
# Let's fix missing `)` before ` :` for have_rows.
content = re.sub(r"have_rows\('([^']+)'\s*:", r"have_rows('\1') :", content)

# Let's fix missing `)` before `;` for the_sub_field
content = re.sub(r"the_sub_field\('([^']+)'\s*;", r"the_sub_field('\1');", content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
