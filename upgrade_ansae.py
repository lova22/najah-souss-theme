import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'

files = ['front-page.php', 'header.php', 'footer.php']

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()

    # Fix the corrupted apostrophes from previous regex
    content = content.replace(r"l')excellence", r"l\'excellence")
    content = content.replace(r"d')or", r"d\'or")
    content = content.replace(r"s')inscrire", r"s\'inscrire")
    content = content.replace(r"d')essai", r"d\'essai")
    
    # Capitalized versions
    content = content.replace(r"L')excellence", r"L\'excellence")
    content = content.replace(r"S')inscrire", r"S\'inscrire")
    content = content.replace(r"Comment s')inscrire", r"Comment s\'inscrire")
    
    # If there's any weird JINSCRIRE
    content = re.sub(r"(?i)S['\\]+JINSCRIRE", r"S\'INSCRIRE", content)
    content = content.replace("S'JINSCRIRE", "S\\'INSCRIRE")

    with open(fp, 'w', encoding='utf-8') as f:
        f.write(content)

# Now upgrade ansae_t in functions.php
fp_func = os.path.join(THEME, 'functions.php')
with open(fp_func, 'r', encoding='utf-8') as f:
    functions = f.read()

# Find the start of function ansae_t
match = re.search(r'function ansae_t\(\$french_text\).*', functions, re.DOTALL)
if match:
    # We will replace the whole function
    original_func = match.group(0)
    
    # Extract dict and mappings
    dict_match = re.search(r'\$dict\s*=\s*array\s*\((.*?)\);\s*if \(isset\(\$dict', original_func, re.DOTALL)
    mappings_match = re.search(r'\$mappings\s*=\s*array\s*\((.*?)\);\s*foreach \(\$mappings', original_func, re.DOTALL)
    
    if dict_match and mappings_match:
        dict_body = dict_match.group(1)
        mappings_body = mappings_match.group(1)
        
        new_func = """function ansae_t($french_text) {
    if (!function_exists('pll_current_language')) {
        return $french_text;
    }
    
    $lang = pll_current_language();
    
    if ($lang == 'fr') {
        return $french_text;
    }

    $normalize = function($str) {
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
        $str = str_replace(array("'", "’", "\\'", "\\’"), "'", $str);
        $str = preg_replace('/\s+/', ' ', $str);
        return mb_strtolower(trim($str), 'UTF-8');
    };
    
    $norm_key = $normalize($french_text);

    static $normalized_dict = null;
    static $normalized_mappings = null;

    if ($normalized_dict === null) {
        $dict = array(
""" + dict_body + """        );
        
        $normalized_dict = array();
        foreach ($dict as $key => $trans) {
            $normalized_dict[$normalize($key)] = $trans;
        }
        
        $mappings = array(
""" + mappings_body + """        );
        
        $normalized_mappings = array();
        foreach ($mappings as $prefix => $trans) {
            $normalized_mappings[$normalize($prefix)] = $trans;
        }
    }

    if (isset($normalized_dict[$norm_key]) && isset($normalized_dict[$norm_key][$lang])) {
        return $normalized_dict[$norm_key][$lang];
    }
    
    foreach ($normalized_mappings as $prefix => $translations) {
        if (strpos($norm_key, $prefix) === 0) {
            return $translations[$lang];
        }
    }

    return $french_text;
}
"""
        new_functions = functions[:match.start()] + new_func
        with open(fp_func, 'w', encoding='utf-8') as f:
            f.write(new_functions)
        print("functions.php successfully upgraded.")
    else:
        print("Could not extract dict or mappings")
else:
    print("Could not find ansae_t")
