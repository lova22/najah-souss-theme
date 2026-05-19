# -*- coding: utf-8 -*-
import os, sys, subprocess
sys.stdout = open(sys.stdout.fileno(), 'w', encoding='utf-8', closefd=False)

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
PHP   = r'C:\xampp\php\php.exe'
LC    = "function_exists('pll_current_language')&&pll_current_language()=='ar'"

fp = os.path.join(THEME, 'front-page.php')
hp = os.path.join(THEME, 'header.php')

fc = open(fp, 'r', encoding='utf-8').read()
hc = open(hp, 'r', encoding='utf-8').read()

# Show exact context of both strings
for needle in ['Des tournois pour tous', 'NAJAH SOUSS']:
    for fname, content in [('front-page.php', fc), ('header.php', hc)]:
        idx = content.find(needle)
        if idx >= 0:
            print(f'\n[{needle}] in {fname}:\n  {content[max(0,idx-60):idx+200]!r}')

# ── Fix 1: "Des tournois pour tous les niveaux..." in front-page.php ─────────
old_tournois = "Des tournois pour tous les niveaux, des compétitions officielles aux événements culturels."
ar_tournois  = "بطولات لجميع المستويات، من المنافسات الرسمية إلى الفعاليات الثقافية."

if old_tournois in fc:
    fc = fc.replace(
        '>' + old_tournois + '<',
        '><?php echo (' + LC + ') ? "' + ar_tournois + '" : "' + old_tournois + '"; ?><',
        1
    )
    print(f'\n[OK] Des tournois fixed in front-page.php')
else:
    # May be inside a get_field fallback
    old_acf = "?: '" + old_tournois + "'"
    if old_acf in fc:
        fc = fc.replace(old_acf, "?: ((" + LC + ') ? "' + ar_tournois + '" : "' + old_tournois + '")', 1)
        print(f'\n[OK] Des tournois (ACF fallback) fixed in front-page.php')
    else:
        print(f'\n[!!] NOT FOUND: Des tournois')

# ── Fix 2: "NAJAH SOUSS ECHECS" logo text in header.php ─────────────────────
# The logo shows "NAJAH SOUSS" on one line and "ECHECS" in a sub-div
# Find exact form
old_logo_line = '>NAJAH SOUSS<'
ar_logo       = 'نجاح سوس اكادير للشطرنج'
fr_logo_1     = 'NAJAH SOUSS'

if old_logo_line in hc:
    hc = hc.replace(
        '>NAJAH SOUSS<',
        '><?php echo (' + LC + ') ? "نجاح سوس اكادير" : "NAJAH SOUSS"; ?><',
        1
    )
    print('[OK] NAJAH SOUSS logo line fixed')

# Also fix the hero h1 fallback in front-page.php
# "NAJAH SOUSS <span>ECHECS</span>" — already handled but let's check the ternary result
idx_h1 = fc.find('نادي نجاح سوس للشطرنج')
if idx_h1 >= 0:
    # Already translated — now update the AR value to the new requested text
    fc = fc.replace(
        "'نادي نجاح سوس للشطرنج'",
        "'نجاح سوس اكادير للشطرنج'",
        1  # only the hero h1 instance
    )
    print('[OK] Hero h1 AR value updated to نجاح سوس اكادير للشطرنج')

# Save
with open(fp, 'w', encoding='utf-8') as f:
    f.write(fc)
with open(hp, 'w', encoding='utf-8') as f:
    f.write(hc)

# Lint
for path in [fp, hp]:
    r = subprocess.run([PHP, '-l', path], capture_output=True, encoding='utf-8', errors='replace')
    print(f'{os.path.basename(path)}: {(r.stdout+r.stderr).strip()}')
