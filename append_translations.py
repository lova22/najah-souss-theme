# -*- coding: utf-8 -*-
import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
fp = os.path.join(THEME, 'functions.php')

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

new_entries = """
        // 1. About Section
        "Najah Souss Echecs est un club d'échecs prestigieux basé à Agadir, au cœur de la région Souss-Massa, fondé en 1987." => array('ar' => "نادي نجاح سوس للشطرنج هو نادي عريق مقره في أكادير، في قلب جهة سوس ماسة، تأسس عام 1987.", 'en' => "Najah Souss Chess is a prestigious chess club based in Agadir, in the heart of the Souss-Massa region, founded in 1987."),
        "Officiellement affilié à la Fédération Royale Marocaine des Échecs (FRME) et reconnu par la FIDE, notre club représente la région Souss-Massa sur la scène nationale et internationale." => array('ar' => "منضوٍ رسمياً تحت لواء الجامعة الملكية المغربية للشطرنج (FRME) ومعترف به من طرف الاتحاد الدولي للشطرنج (FIDE)، يمثل نادينا جهة سوس ماسة على الساحة الوطنية والدولية.", 'en' => "Officially affiliated with the Royal Moroccan Chess Federation (FRME) and recognized by FIDE, our club proudly represents the Souss-Massa region on both the national and international stage."),
        
        // 2. Academy & Play Sections
        "Académie & Formation" => array('ar' => "الأكاديمية والتكوين", 'en' => "Academy & Training"),
        "Formez-vous avec des Experts" => array('ar' => "تدرب مع الخبراء", 'en' => "Train with Experts"),
        "Encadrement Expert" => array('ar' => "تأطير من الخبراء", 'en' => "Expert Coaching"),
        "Cours individuels & collectifs disponibles" => array('ar' => "دروس فردية وجماعية متاحة", 'en' => "Individual & group lessons available"),
        "Prêt à relever le défi ?" => array('ar' => "هل أنت مستعد لرفع التحدي؟", 'en' => "Ready for the challenge?"),
        "Affrontez des joueurs du monde entier ou entraînez-vous contre l'IA sur notre espace dédié." => array('ar' => "واجه لاعبين من جميع أنحاء العالم أو تدرب ضد الذكاء الاصطناعي في مساحتنا المخصصة.", 'en' => "Face players from around the world or practice against AI in our dedicated arena."),
        "Jouer aux Échecs Maintenant ♞" => array('ar' => "العب الشطرنج الآن ♞", 'en' => "Play Chess Now ♞"),
        
        // 3. Various Headings & Buttons
        "Notre Équipe - Classement FIDE" => array('ar' => "فريقنا - تصنيف FIDE الدولي", 'en' => "Our Team - FIDE Ratings"),
        "Nos Events" => array('ar' => "أنشطتنا وفعالياتنا", 'en' => "Our Events"),
        "Notre Actualité" => array('ar' => "آخر أخبارنا", 'en' => "Latest News"),
        "Voir tout le staff" => array('ar' => "عرض كل الطاقم", 'en' => "View all staff"),
        
        // 4. Partnership & Contact
        "Associez votre marque à l'excellence d'Agadir. Najah Souss Echecs — Vainqueurs de la Coupe du Trône 2025 — offre une visibilité nationale et internationale à ses partenaires." => array('ar' => "اربط علامتك التجارية بالتميز في أكادير. يقدم نادي نجاح سوس - أبطال كأس العرش 2025 - رؤية وإشعاعاً وطنياً ودولياً لشركائه.", 'en' => "Associate your brand with Agadir's standard of excellence. Najah Souss Chess — 2025 Throne Cup Champions — offers national and international visibility to its partners."),
        "Une question, une inscription ou un partenariat ? Nous sommes là pour vous." => array('ar' => "هل لديك سؤال، ترغب في التسجيل أو عقد شراكة؟ نحن هنا من أجلك.", 'en' => "Got a question, want to register, or discuss a partnership? We are here for you."),
"""

# Find the end of the $dict array definition inside ansae_t function
# Look for: $dict = array( ... );
match = re.search(r'(\$dict\s*=\s*array\s*\()(.*?)(\s*\);\s*\$normalized_dict\s*=\s*array\(\);)', content, re.DOTALL)

if match:
    # Append the new entries just before the closing parenthesis of the array
    new_dict_content = match.group(2)
    # Ensure there's a trailing comma before appending new stuff if not empty
    if not new_dict_content.strip().endswith(','):
        new_dict_content += ",\n"
    new_dict_content += new_entries

    new_content = content[:match.start()] + match.group(1) + new_dict_content + match.group(3) + content[match.end():]

    with open(fp, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Successfully appended translations to functions.php")
else:
    print("Could not find the $dict array definition in functions.php")
