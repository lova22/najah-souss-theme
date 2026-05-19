import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix `_e('...', '...';` or any function with 2 string arguments
content = re.sub(r"\('([^']+)',\s*'([^']+)'\s*;", r"('\1', '\2');", content)

# Check for `get_field('...', '...';` or similar
content = re.sub(r"\('([^']+)',\s*([0-9a-zA-Z_\$]+)\s*;", r"('\1', \2);", content)

# Check for `_e('...', '...'\s*\?>` - wait, in PHP it's usually `_e('...', '...');`
content = re.sub(r"\('([^']+)',\s*'([^']+)'\s*\?>", r"('\1', '\2') ?>", content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
