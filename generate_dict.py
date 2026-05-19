import os, re

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']
matches = set()

for filename in files:
    fp = os.path.join(THEME, filename)
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()
    m = re.findall(r"ansae_t\(['\"](.*?)['\"]\)", content)
    matches.update(m)

# Let's read the translation dictionaries we can guess
# The user wants a dictionary of French -> ['ar': ..., 'en': ...]
# I'll output a PHP function that falls back to FR if not found.
# But what about the actual AR and EN values? We know most of them.

php_code = """
function ansae_t($french_text) {
    if (!function_exists('pll_current_language')) {
        return $french_text;
    }
    
    $lang = pll_current_language();
    
    // Default fallback to French
    if ($lang == 'fr') {
        return $french_text;
    }

    $dict = array(
        'Agadir · Maroc — Depuis 1987' => array('ar' => 'أكادير · المغرب — منذ 1987', 'en' => 'Agadir · Morocco — Since 1987'),
        'NAJAH SOUSS <span class="gradient-gold">ECHECS</span>' => array('ar' => 'نادي نجاح سوس للشطرنج', 'en' => 'Najah Souss <span class="gradient-gold">Chess Club</span>'),
        'Vainqueurs de la Coupe du Trône 2025' => array('ar' => 'أبطال كأس العرش 2025', 'en' => '2025 Throne Cup Champions'),
        'Champions du Maroc 2025' => array('ar' => 'أبطال المغرب 2025', 'en' => 'Morocco Champions 2025'),
        'L\\'excellence des échecs à Agadir.' => array('ar' => 'التميز في الشطرنج بأكادير.', 'en' => 'Chess Excellence in Agadir.'),
        'Formation, Compétition et Culture.' => array('ar' => 'تكوين، منافسة وثقافة.', 'en' => 'Training, Competition, and Culture.'),
        'Rejoindre le Club' => array('ar' => 'انضم إلى النادي', 'en' => 'Join the Club'),
        'Voir le Palmarès' => array('ar' => 'شاهد سجل الإنجازات', 'en' => 'View our Achievements'),
        'Coupe du Trône 2025' => array('ar' => 'كأس العرش 2025', 'en' => '2025 Throne Cup'),
        'Médailles d\\'Or 2025-26' => array('ar' => 'ميداليات ذهبية 2025-26', 'en' => 'Gold Medals 2025-26'),
        'Affilié' => array('ar' => 'عضو', 'en' => 'Affiliated'),
        'Année de fondation' => array('ar' => 'سنة التأسيس', 'en' => 'Founded'),
        'À propos' => array('ar' => 'من نحن', 'en' => 'About Us'),
        'À PROPOS' => array('ar' => 'من نحن', 'en' => 'ABOUT US'),
        'D\\'EXCELLENCE' => array('ar' => 'والتميز', 'en' => 'OF EXCELLENCE'),
        '« Aucun coup ne doit être joué sans but »' => array('ar' => '« لا يجب لعب أي نقلة بدون هدف »', 'en' => '« No move should be played without a purpose »'),
        'Affilié FRME' => array('ar' => 'عضو في الجامعة الملكية', 'en' => 'FRME Affiliated'),
        'Reconnu FIDE' => array('ar' => 'معترف به من FIDE', 'en' => 'FIDE Recognized'),
        'Agadir, Maroc' => array('ar' => 'أكادير، المغرب', 'en' => 'Agadir, Morocco'),
        'Depuis 1987' => array('ar' => 'منذ 1987', 'en' => 'Since 1987'),
        'Éducation' => array('ar' => 'التعليم', 'en' => 'Education'),
        'Compétition' => array('ar' => 'المنافسة', 'en' => 'Competition'),
        'Fair-play' => array('ar' => 'الروح الرياضية', 'en' => 'Sportsmanship'),
        'Notre Palmarès' => array('ar' => 'سجل الإنجازات', 'en' => 'Our Achievements'),
        'TITRE MAJEUR' => array('ar' => 'اللقب الأبرز', 'en' => 'MAJOR TITLE'),
        'Débutant' => array('ar' => 'مبتدئ', 'en' => 'Beginner'),
        'Les Pions' => array('ar' => 'البيادق', 'en' => 'The Pawns'),
        'Intermédiaire' => array('ar' => 'متوسط', 'en' => 'Intermediate'),
        'Les Fous & Tours' => array('ar' => 'الأفيال والقلاع', 'en' => 'Bishops & Rooks'),
        'Avancé' => array('ar' => 'متقدم', 'en' => 'Advanced'),
        'Les Dames & Rois' => array('ar' => 'الوزراء والملوك', 'en' => 'Queens & Kings'),
        'Demander un cours d\\'essai' => array('ar' => 'طلب حصة تجريبية', 'en' => 'Request a trial class'),
        'Galerie' => array('ar' => 'معرض', 'en' => 'Gallery'),
        'Voir toute la galerie' => array('ar' => 'عرض المعرض بالكامل', 'en' => 'View full gallery'),
        'Événements' => array('ar' => 'فعاليات', 'en' => 'Events'),
        'Voir tous les événements' => array('ar' => 'عرض جميع الفعاليات', 'en' => 'View all events'),
        'Médias & Presse' => array('ar' => 'الصحافة والإعلام', 'en' => 'Media & Press'),
        'Voir toute la presse' => array('ar' => 'عرض كل التغطيات', 'en' => 'View all press coverage'),
        'Notre Actualité' => array('ar' => 'أخبارنا', 'en' => 'Latest News'),
        'Voir toutes les actualités' => array('ar' => 'عرض جميع الأخبار', 'en' => 'View all news'),
        'Direction & Encadrement' => array('ar' => 'الإدارة والتأطير', 'en' => 'Management & Coaching'),
        'Staff Technique' => array('ar' => 'الطاقم التقني', 'en' => 'Technical'),
        '& Administratif' => array('ar' => 'والإداري', 'en' => '& Administrative Staff'),
        'Supportez votre club' => array('ar' => 'ادعم ناديك', 'en' => 'Support your club'),
        'Boutique' => array('ar' => 'المتجر', 'en' => 'Official'),
        'Officielle' => array('ar' => 'الرسمي', 'en' => 'Store'),
        'NOUVEAU' => array('ar' => 'جديد', 'en' => 'NEW'),
        'Devenir Partenaire' => array('ar' => 'كن شريكاً', 'en' => 'Become a Partner'),
        'des Champions' => array('ar' => 'للأبطال', 'en' => 'of the Champions'),
        'Visibilité Nationale' => array('ar' => 'رؤية وطنية', 'en' => 'National Visibility'),
        'Tournois FRME / FIDE' => array('ar' => 'بطولات FRME / FIDE', 'en' => 'FRME / FIDE Tournaments'),
        'Réseaux Sociaux' => array('ar' => 'شبكات التواصل', 'en' => 'Social Media'),
        'Communauté engagée' => array('ar' => 'مجتمع نشط', 'en' => 'Engaged Community'),
        'Impact Social' => array('ar' => 'تأثير اجتماعي', 'en' => 'Social Impact'),
        'Éducation jeunesse' => array('ar' => 'تعليم الشباب', 'en' => 'Youth Education'),
        'TÉLÉCHARGER LE DOSSIER SPONSORING' => array('ar' => 'تحميل ملف الرعاية', 'en' => 'DOWNLOAD SPONSORSHIP DECK'),
        'Contact' => array('ar' => 'اتصل بنا', 'en' => 'Contact'),
        'NOUS CONTACTER' => array('ar' => 'اتصل بنا', 'en' => 'CONTACT US'),
        'Adresse' => array('ar' => 'العنوان', 'en' => 'Address'),
        'Email' => array('ar' => 'البريد الإلكتروني', 'en' => 'Email'),
        'Téléphone' => array('ar' => 'الهاتف', 'en' => 'Phone'),
        'Comment s\\'inscrire ?' => array('ar' => 'كيف تسجل؟', 'en' => 'How to register?'),
        'Nom complet *' => array('ar' => 'الاسم الكامل *', 'en' => 'Full Name *'),
        'Email *' => array('ar' => 'البريد الإلكتروني *', 'en' => 'Email *'),
        'Message *' => array('ar' => 'الرسالة *', 'en' => 'Message *'),
        'Envoyer ma demande ♞' => array('ar' => 'إرسال طلبي ♞', 'en' => 'Send Request ♞'),
        'Palmarès' => array('ar' => 'قاعة المشاهير', 'en' => 'Hall of Fame'),
        'Presse' => array('ar' => 'الصحافة', 'en' => 'Press'),
        'Académie' => array('ar' => 'الأكاديمية', 'en' => 'Academy'),
        'Accueil' => array('ar' => 'الرئيسية', 'en' => 'Home'),
        'Le Club' => array('ar' => 'النادي', 'en' => 'The Club'),
        'S\\'inscrire' => array('ar' => 'التسجيل', 'en' => 'Register'),
        'Tous droits réservés.' => array('ar' => 'جميع الحقوق محفوظة.', 'en' => 'All rights reserved.'),
        'Réseaux sociaux' => array('ar' => 'شبكات التواصل الاجتماعي', 'en' => 'Social Networks'),
        'Champions du Maroc 2025 · Depuis 1987' => array('ar' => 'أبطال المغرب 2025 · منذ 1987', 'en' => 'Morocco Champions 2025 · Since 1987'),
        'Echecs · Depuis 1987' => array('ar' => 'الشطرنج · منذ 1987', 'en' => 'Chess · Since 1987'),
        'Partenariat' => array('ar' => 'الشراكات', 'en' => 'Partnerships'),
    );

    if (isset($dict[$french_text]) && isset($dict[$french_text][$lang])) {
        return $dict[$french_text][$lang];
    }
    
    // Hard fallback mapping for some prefixes
    $mappings = array(
        'Les échecs comme outil pédagog' => array('ar' => 'الشطرنج كأداة تربوية لتطوير التركيز، المنطق، والإبداع.', 'en' => 'Chess as an educational tool to develop focus, logic, and creativity.'),
        'Participation aux tournois rég' => array('ar' => 'المشاركة في البطولات الجهوية، الوطنية، والدولية المعتمدة من طرف FIDE/FRME.', 'en' => 'Participation in FIDE/FRME rated regional, national, and international tournaments.'),
        'Respect mutuel, éthique irrépr' => array('ar' => 'الاحترام المتبادل، الأخلاق العالية، والروح الرياضية في صميم كل مباراة.', 'en' => 'Mutual respect, impeccable ethics, and sportsmanship at the heart of every game.'),
        'Coupe du Trône 2025, 8 médaill' => array('ar' => 'كأس العرش 2025: 8 ميداليات ذهبية و4 اختيارات للمنتخب الوطني، تميز نجاح سوس للشطرنج.', 'en' => '2025 Throne Cup, 8 gold medals, and 4 national team selections — the standard of excellence at Najah Souss Chess.'),
        'Les visages de notre excellenc' => array('ar' => 'وجوه تميزنا — أولئك الذين يحملون ألوان نادي نجاح سوس للشطرنج.', 'en' => 'The faces of our excellence — those who proudly wear the colors of Najah Souss Chess.'),
        'Programmes pédagogiques pour t' => array('ar' => 'برامج تعليمية لجميع المستويات، تحت إشراف مدربين معتمدين ولاعبين مصنفين دولياً (FIDE).', 'en' => 'Educational programs for all levels, led by certified coaches and FIDE-rated players.'),
        'Choisissez la formule qui vous' => array('ar' => 'اختر الصيغة التي تناسبك وتقدم بالوتيرة التي تريحك مع مدربينا المعتمدين من FIDE.', 'en' => 'Choose the program that suits you and progress at your own pace with our FIDE coaches.'),
        'Tournois, cérémonies, entraîne' => array('ar' => 'بطولات، حفلات، تداريب وانتصارات — حياة النادي بالصور.', 'en' => 'Tournaments, ceremonies, training, and victories — club life in pictures.'),
        'Des tournois pour tous les niv' => array('ar' => 'بطولات لجميع المستويات، من المنافسات الرسمية إلى الفعاليات الثقافية.', 'en' => 'Tournaments for all levels, from official competitions to cultural events.'),
        'La presse nationale et locale ' => array('ar' => 'الصحافة الوطنية والمحلية تغطي نجاحات نادي نجاح سوس للشطرنج.', 'en' => 'National and local press covering the success of Najah Souss Chess.'),
        'Les dernières nouvelles, événe' => array('ar' => 'آخر المستجدات، الأنشطة والإعلانات الخاصة بنادي نجاح سوس للشطرنج.', 'en' => 'The latest news, events, and announcements from Najah Souss Chess.'),
        'Une équipe d\\'experts au servic' => array('ar' => 'فريق من الخبراء في خدمة التميز في الشطرنج.', 'en' => 'A team of experts dedicated to chess excellence.'),
        'Portez fièrement les couleurs ' => array('ar' => 'ارتدِ بفخر ألوان نادي نجاح سوس للشطرنج مع تشكيلتنا الحصرية.', 'en' => 'Wear the Najah Souss Chess colors with pride through our exclusive collection.'),
        'Remplissez le formulaire ci-co' => array('ar' => 'املأ النموذج', 'en' => 'Fill out the form'),
        'Un responsable vous contactera' => array('ar' => 'سيتواصل معك أحد المسؤولين خلال 48 ساعة', 'en' => 'A representative will contact you within 48h'),
        'Participez à une séance d\\'essai' => array('ar' => 'شارك في حصة تجريبية مجانية', 'en' => 'Attend a free trial session'),
        'Rejoignez officiellement le cl' => array('ar' => 'انضم رسمياً إلى النادي!', 'en' => 'Officially join the club!'),
    );

    foreach ($mappings as $prefix => $translations) {
        if (strpos($french_text, $prefix) === 0) {
            return $translations[$lang];
        }
    }

    return $french_text;
}
"""

fp_functions = os.path.join(THEME, 'functions.php')
with open(fp_functions, 'a', encoding='utf-8') as f:
    f.write("\n" + php_code)

print("Generated ansae_t in functions.php")
