import os, re

fp = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\functions.php'
with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

new_normalize = """    $normalize = function($str) {
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
        $str = str_replace(array("'", "’", "\\'", "\\’"), "'", $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = mb_strtolower(trim($str), 'UTF-8');
        $unwanted_array = array(
            'š'=>'s', 'ž'=>'z', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
        );
        $str = strtr($str, $unwanted_array);
        // Remove weird characters and punctuation to make it purely alphanumeric text
        $str = preg_replace('/[^a-z0-9\s]/', '', $str);
        // Collapse spaces again
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
    };"""

content = re.sub(r'\$normalize\s*=\s*function\(\$str\)\s*\{.*?\};', lambda m: new_normalize, content, flags=re.DOTALL)

with open(fp, 'w', encoding='utf-8') as f:
    f.write(content)
print('Accent stripping added to normalize.')
