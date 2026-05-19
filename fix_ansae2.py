import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # We need to find all `ansae_t('...')` or `ansae_t("...")` taking into account escaped quotes inside.
    # And we also want to eat any trailing `)` up to the `;` or `? >`.
    
    # We can match `ansae_t\((['"])(?:(?!\1|\\).|\\.)*\1` which matches `ansae_t('...')` safely
    
    # Let's fix the trailing parentheses first.
    # We want to match: `ansae_t\((['"])(?:(?!\1|\\).|\\.)*\1\)\)+`
    def replacer(m):
        # m.group(0) is the full match including trailing parentheses
        # We just want `ansae_t('...')`
        inner = m.group(0)
        # remove trailing ')' until the first valid ')'
        # Actually, it's easier to just match the valid `ansae_t(...)` part.
        valid_part = re.match(r"ansae_t\((['\"])(?:(?!\1|\\).|\\.)*\1\)", inner).group(0)
        return valid_part
        
    pattern = r"ansae_t\((['\"])(?:(?!\1|\\).|\\.)*\1\)\)*"
    new_content = re.sub(pattern, replacer, content)
    
    # Let's also fix the ones that got chopped like `ansae_t('Médailles d\')Or ...`
    # They look like: `ansae_t('Médailles d\')Or 2025-26');`
    # In my previous script I did `ansae_t(\1)` so I turned `(((ansae_t('Médailles d\')))` into `ansae_t('Médailles d\')Or 2025-26');` - wait, the previous regex was `\(+ansae_t\((['\"].*?['\"])\)\)+`.
    # `.*?` matched `Médailles d\'` as `Médailles d\`. Then `)` is unmatched!
    # Let's just fix it manually.
    new_content = new_content.replace(r"ansae_t('Médailles d\')Or 2025-26');", r"ansae_t('Médailles d\'Or 2025-26');")
    
    new_content = new_content.replace(r"ansae_t('Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs.');", r"ansae_t('Les visages de notre excellence — ceux qui portent les couleurs de Najah Souss Echecs.');")
    
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
print('Fixed trailing parentheses and quotes.')
