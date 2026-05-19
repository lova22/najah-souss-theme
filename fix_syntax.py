import os
import re

filepath = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix broken function calls
content = re.sub(r"have_rows\('hero_stats'\s*", "have_rows('hero_stats')", content)
content = re.sub(r"the_sub_field\('stat_label'\s*;", "the_sub_field('stat_label');", content)

# Actually, the replacement was:
# 's\)' -> 's\'' -> so 'hero_stats\)' became 'hero_stats\''
# wait, 'hero_stats\'' is literally `hero_stats'`? But the code shows:
# `have_rows('hero_stats' ) : ?>`
# Wait, no. The string in PHP was `have_rows('hero_stats')`.
# The regex was r"s'\)". This matches `s')`.
# The string in PHP was `have_rows('hero_stats')`. It ends with `s')`.
# So it was replaced with `s'`.
# Thus it became `have_rows('hero_stats'`
# Let's just fix it properly.

content = content.replace("have_rows('hero_stats' ", "have_rows('hero_stats') ")
content = content.replace("have_rows('hero_stats' :", "have_rows('hero_stats') :")
content = content.replace("the_sub_field('stat_label';", "the_sub_field('stat_label');")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
