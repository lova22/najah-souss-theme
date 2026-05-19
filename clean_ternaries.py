# -*- coding: utf-8 -*-
import os, sys, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'

files = ['front-page.php', 'header.php', 'footer.php']

dictionary = {}

def process_file(filename):
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # Pattern 1: <?php echo (function_exists('pll_current_language')&&pll_current_language()=='ar') ? 'ARABIC' : (((function_exists('pll_current_language')&&pll_current_language()=='en')) ? 'ENGLISH' : 'FRENCH'); ?>
    # Pattern 2: (function_exists('pll_current_language')&&pll_current_language()=='ar') ? 'ARABIC' : ((function_exists('pll_current_language')&&pll_current_language()=='en') ? 'ENGLISH' : 'FRENCH')
    # Let's find anything that looks like:
    # (function_exists('pll_current_language')&&pll_current_language()=='ar') ? 'ARABIC' : [OPTIONAL_EN] 'FRENCH'
    
    # We will do a generic regex that captures AR, EN and FR.
    # The AR part: \(function_exists\('pll_current_language'\)\s*&&\s*pll_current_language\(\)==\'ar\'\)\s*\?\s*['"](.*?)['"]\s*:\s*
    # The EN part (optional): \(\(function_exists\('pll_current_language'\)\s*&&\s*pll_current_language\(\)==\'en\'\)\)\s*\?\s*['"](.*?)['"]\s*:\s*
    # The FR part: ['"](.*?)['"]
    
    # Let's use a simpler approach. Find all `(function_exists('pll_current_language')`
    
    # Regex to find the whole ternary blocks
    # It might be nested.
    # Let's match carefully:
    pattern = r"\(function_exists\('pll_current_language'\)&&pll_current_language\(\)=='ar'\)\s*\?\s*(['\"])(.*?)\1\s*:\s*(?:\(\(function_exists\('pll_current_language'\)&&pll_current_language\(\)=='en'\)\)\s*\?\s*(['\"])(.*?)\3\s*:\s*)?(['\"])(.*?)\5"
    
    def replacer(match):
        q_ar, ar_text, q_en, en_text, q_fr, fr_text = match.groups()
        
        # Clean up escapes
        ar_text = ar_text.replace("\\'", "'").replace('\\"', '"')
        if en_text:
            en_text = en_text.replace("\\'", "'").replace('\\"', '"')
        fr_text = fr_text.replace("\\'", "'").replace('\\"', '"')
        
        dictionary[fr_text] = {
            'ar': ar_text,
            'en': en_text if en_text else fr_text
        }
        
        # Replace the whole matched ternary expression with ansae_t('fr_text')
        # We need to escape single quotes in fr_text for the PHP function call
        fr_esc = fr_text.replace("'", "\\'")
        return f"ansae_t('{fr_esc}')"

    new_content = re.sub(pattern, replacer, content)
    
    # Pattern 2: the ones that I added in fix_missed_en.py:
    # ((function_exists('pll_current_language')&&pll_current_language()=='en') ? 'EN' : 'FR')
    pattern_en_only = r"\(\(function_exists\('pll_current_language'\)&&pll_current_language\(\)=='en'\)\)\s*\?\s*(['\"])(.*?)\1\s*:\s*(['\"])(.*?)\3"
    
    def replacer_en_only(match):
        q_en, en_text, q_fr, fr_text = match.groups()
        
        en_text = en_text.replace("\\'", "'").replace('\\"', '"')
        fr_text = fr_text.replace("\\'", "'").replace('\\"', '"')
        
        if fr_text not in dictionary:
            dictionary[fr_text] = {
                'ar': fr_text,
                'en': en_text
            }
        else:
            dictionary[fr_text]['en'] = en_text
            
        fr_esc = fr_text.replace("'", "\\'")
        return f"ansae_t('{fr_esc}')"
        
    new_content = re.sub(pattern_en_only, replacer_en_only, new_content)
    
    # Also handle the `<?php echo ansae_t(...)` inside `__('...', 'najah-souss')` that we might have missed? No, I fixed those.
    # What about `<?php echo (function_exists(...)) ? ... : ...; ?>`?
    # Our regex caught the inside, so it will become:
    # `<?php echo ansae_t('...'); ?>`
    
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
for f in files:
    process_file(f)
    
# Generate the functions.php helper
import json
dict_php = ""
for fr, trans in dictionary.items():
    fr_esc = fr.replace("'", "\\'")
    ar_esc = trans['ar'].replace("'", "\\'")
    en_esc = trans['en'].replace("'", "\\'")
    dict_php += f"        '{fr_esc}' => [\n"
    dict_php += f"            'ar' => '{ar_esc}',\n"
    dict_php += f"            'en' => '{en_esc}'\n"
    dict_php += f"        ],\n"

print("DICTIONARY:")
print(dict_php)
