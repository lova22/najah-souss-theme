import os, re

fp = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\functions.php'
with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

missing_dict = """
        "Prêt à relever le défi !" => array('ar' => "هل أنت مستعد لرفع التحدي؟", 'en' => "Ready for the challenge!"),
        "Galerie Najah Souss" => array('ar' => "معرض نجاح سوس", 'en' => "Najah Souss Gallery"),
        "Rejoignez Najah Souss Echecs" => array('ar' => "انضم إلى نادي نجاح سوس", 'en' => "Join Najah Souss Chess"),
        "Devenir Partenaire" => array('ar' => "كن شريكاً", 'en' => "Become a Partner"),
        "des Champions" => array('ar' => "للأبطال", 'en' => "of the Champions"),
        "Participez à une séance d'essai gratuite" => array('ar' => "شارك في جلسة تجريبية مجانية", 'en' => "Join a free trial session"),
        "Une équipe d'experts au service de l'excellence des échecs." => array('ar' => "فريق من الخبراء في خدمة التميز في الشطرنج.", 'en' => "A team of experts dedicated to chess excellence."),
"""

match = re.search(r'(\$dict\s*=\s*array\s*\()(.*?)(\s*\);\s*\$normalized_dict\s*=\s*array\(\);)', content, re.DOTALL)
if match:
    new_dict_content = match.group(2)
    if not new_dict_content.strip().endswith(','):
        new_dict_content += ",\n"
    new_dict_content += missing_dict
    new_content = content[:match.start()] + match.group(1) + new_dict_content + match.group(3) + content[match.end():]
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Added missing translations.")
else:
    print("Failed")
