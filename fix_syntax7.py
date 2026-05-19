import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix sprintf errors
content = re.sub(r"sprintf\(__\('([^']+)',\s*'([^']+)',\s*([^)]+)\)\s*;", r"sprintf(__('\1', '\2'), \3);", content)

# Or maybe my regex didn't match because it was `sprintf(__('...', 'najah-souss', $p_name);` instead of `... 'najah-souss'), $p_name);`
content = re.sub(r"sprintf\(__\('([^']+)',\s*'([^']+)',\s*(\$p[0-9]_name)\);", r"sprintf(__('\1', '\2'), \3);", content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
