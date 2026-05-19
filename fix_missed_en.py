# -*- coding: utf-8 -*-
import os, sys, re, subprocess

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
PHP   = r'C:\xampp\php\php.exe'
fp = os.path.join(THEME, 'front-page.php')

LC_EN = "(function_exists('pll_current_language')&&pll_current_language()=='en')"
LC_AR = "(function_exists('pll_current_language')&&pll_current_language()=='ar')"

translations = {
    # Plain HTML text (not yet translated to Arabic, so we just add EN and fallback to FR)
    "Visibilité Nationale": "National Visibility",
    "Tournois FRME / FIDE": "FRME / FIDE Tournaments",
    "Réseaux Sociaux": "Social Media",
    "Communauté engagée": "Engaged Community",
    "Impact Social": "Social Impact",
    "Éducation jeunesse": "Youth Education",
    "TÉLÉCHARGER LE DOSSIER SPONSORING": "DOWNLOAD SPONSORSHIP DECK",
    "Médias & Presse": "Media & Press",
    "Envoyer ma demande ♞": "Send Request ♞",
}

with open(fp, 'r', encoding='utf-8') as f:
    content = f.read()

def en_cond(en_text, quote_char="'"):
    en_escaped = en_text.replace(quote_char, "\\" + quote_char)
    return f"({LC_EN} ? {quote_char}{en_escaped}{quote_char} : "

hits = 0
for fr, en in translations.items():
    if fr in content:
        # replace raw text with ternary
        # assuming it's inside HTML
        c_sq = en_cond(en, "'")
        fr_esc = fr.replace("'", "\\'")
        content = content.replace(
            fr,
            f"<?php echo {c_sq}'{fr_esc}'); ?>",
            1
        )
        hits += 1

# Other difficult ones that were previously translated:
# "NAJAH SOUSS ECHECS" -> We have: ((LC_AR) ? 'نادي نجاح سوس للشطرنج' : 'NAJAH SOUSS <span class="gradient-gold">ECHECS</span>')
# It should be: ((LC_AR) ? 'ARABIC' : ((LC_EN) ? 'Najah Souss <span class="gradient-gold">Chess Club</span>' : 'FRENCH'))
hero_fr = 'NAJAH SOUSS <span class="gradient-gold">ECHECS</span>'
ar_trans = 'نادي نجاح سوس للشطرنج'
en_trans = 'Najah Souss <span class="gradient-gold">Chess Club</span>'
old_hero = f"(({LC_AR}) ? '{ar_trans}' : '{hero_fr}')"
new_hero = f"(({LC_AR}) ? '{ar_trans}' : (({LC_EN}) ? '{en_trans}' : '{hero_fr}'))"
if old_hero in content:
    content = content.replace(old_hero, new_hero, 1)
    hits += 1

# "VAINQUEURS DE LA COUPE DU TRONE 2025" -> we had "TITRE MAJEUR" instead of VAINQUEURS?
# The user asked: "VAINQUEURS DE LA COUPE DU TRONE 2025" -> "2025 THRONE CUP CHAMPIONS"
# and "TITRE MAJEUR" -> "MAJOR TITLE"
old_badge = f"(({LC_AR}) ? '✦ اللقب الأبرز ✦' : '✦ TITRE MAJEUR ✦')"
new_badge = f"(({LC_AR}) ? '✦ اللقب الأبرز ✦' : (({LC_EN}) ? '✦ MAJOR TITLE ✦' : '✦ TITRE MAJEUR ✦'))"
if old_badge in content:
    content = content.replace(old_badge, new_badge, 1)
    hits += 1

# "ACADEMIE & FORMATION" -> It was translated as 'Académie & Formation'
old_acad = f"(({LC_AR}) ? 'الأكاديمية والتكوين' : 'Académie & Formation')"
new_acad = f"(({LC_AR}) ? 'الأكاديمية والتكوين' : (({LC_EN}) ? 'ACADEMY & TRAINING' : 'Académie & Formation'))"
if old_acad in content:
    content = content.replace(old_acad, new_acad, 1)
    hits += 1

# "Formez-vous avec des Experts" -> We translated "Formez-vous avec des" and "Experts" separately
old_formez = f"({LC_AR}) ? 'تدرب مع' : 'Formez-vous avec des'"
new_formez = f"({LC_AR}) ? 'تدرب مع' : (({LC_EN}) ? 'Train with' : 'Formez-vous avec des')"
if old_formez in content:
    content = content.replace(old_formez, new_formez, 1)
    hits += 1

old_experts = f"({LC_AR}) ? 'الخبراء' : 'Experts'"
new_experts = f"({LC_AR}) ? 'الخبراء' : (({LC_EN}) ? 'Experts' : 'Experts')"
if old_experts in content:
    content = content.replace(old_experts, new_experts, 1)
    hits += 1

# "Prêt à relever le défi" -> We did "Prêt à" and "relever le défi ?"
old_pret = f"({LC_AR}) ? 'هل أنت مستعد' : 'Prêt à'"
new_pret = f"({LC_AR}) ? 'هل أنت مستعد' : (({LC_EN}) ? 'Ready for' : 'Prêt à')"
if old_pret in content:
    content = content.replace(old_pret, new_pret, 1)
    hits += 1

old_defi = f"({LC_AR}) ? 'لرفع التحدي؟' : 'relever le défi ?'"
new_defi = f"({LC_AR}) ? 'لرفع التحدي؟' : (({LC_EN}) ? 'the challenge?' : 'relever le défi ?')"
if old_defi in content:
    content = content.replace(old_defi, new_defi, 1)
    hits += 1

# "Affrontez des joueurs du monde entier ou entrainez-vous contre l'IA sur notre espace dédie"
# Was translated as "Affrontez des joueurs du monde entier ou entraînez-vous contre l'IA sur notre espace dédié."
old_affr = f'({LC_AR}) ? "واجه لاعبين من جميع أنحاء العالم أو تدرب ضد الذكاء الاصطناعي في مساحتنا المخصصة." : "Affrontez des joueurs du monde entier ou entraînez-vous contre l\'IA sur notre espace dédié."'
new_affr = f'({LC_AR}) ? "واجه لاعبين من جميع أنحاء العالم أو تدرب ضد الذكاء الاصطناعي في مساحتنا المخصصة." : (({LC_EN}) ? "Face players from around the world or practice against AI in our dedicated arena." : "Affrontez des joueurs du monde entier ou entraînez-vous contre l\'IA sur notre espace dédié.")'
if old_affr in content:
    content = content.replace(old_affr, new_affr, 1)
    hits += 1

# "Jouer aux Echecs Maintenant"
old_jouer = f"({LC_AR}) ? 'العب الشطرنج الآن ♞' : 'Jouer aux Échecs Maintenant ♞'"
new_jouer = f"({LC_AR}) ? 'العب الشطرنج الآن ♞' : (({LC_EN}) ? 'Play Chess Now ♞' : 'Jouer aux Échecs Maintenant ♞')"
if old_jouer in content:
    content = content.replace(old_jouer, new_jouer, 1)
    hits += 1

# "Galerie Najah Souss" -> "Galerie <span class="gradient-gold">Najah Souss</span>"
old_gal = f"({LC_AR}) ? 'معرض' : 'Galerie'"
new_gal = f"({LC_AR}) ? 'معرض' : (({LC_EN}) ? 'Gallery' : 'Galerie')"
# Ensure it only replaces the one before <span class="gradient-gold">Najah Souss</span>
if f"({LC_AR}) ? 'معرض' : 'Galerie'; ?> <span class=\"gradient-gold\">Najah Souss" in content:
    content = content.replace(
        f"({LC_AR}) ? 'معرض' : 'Galerie'; ?> <span class=\"gradient-gold\">Najah Souss",
        f"({LC_AR}) ? 'معرض' : (({LC_EN}) ? 'Gallery' : 'Galerie'); ?> <span class=\"gradient-gold\">Najah Souss",
        1
    )
    hits += 1

# "CALENDRIER 2025" -> We had "Calendrier 2025"
old_cal = f"({LC_AR}) ? 'روزنامة 2025' : 'Calendrier 2025'"
new_cal = f"({LC_AR}) ? 'روزنامة 2025' : (({LC_EN}) ? '2025 CALENDAR' : 'Calendrier 2025')"
if old_cal in content:
    content = content.replace(old_cal, new_cal, 1)
    hits += 1

# "Ils parlent de nous" -> "تغطية الصحافة"
old_ils = f"({LC_AR}) ? 'تغطية الصحافة' : 'Ils parlent de nous'"
new_ils = f"({LC_AR}) ? 'تغطية الصحافة' : (({LC_EN}) ? 'In the Press' : 'Ils parlent de nous')"
if old_ils in content:
    content = content.replace(old_ils, new_ils, 1)
    hits += 1

# "Staff Technique & Administratif" -> We had "Staff Technique" and "& Administratif"
old_staff1 = f"({LC_AR}) ? 'الطاقم التقني' : 'Staff Technique'"
new_staff1 = f"({LC_AR}) ? 'الطاقم التقني' : (({LC_EN}) ? 'Technical' : 'Staff Technique')"
if old_staff1 in content:
    content = content.replace(old_staff1, new_staff1, 1)
    hits += 1

old_staff2 = f"({LC_AR}) ? 'والإداري' : '& Administratif'"
new_staff2 = f"({LC_AR}) ? 'والإداري' : (({LC_EN}) ? '& Administrative Staff' : '& Administratif')"
if old_staff2 in content:
    content = content.replace(old_staff2, new_staff2, 1)
    hits += 1

# "Boutique Officielle" -> We had "Boutique" and "Officielle"
old_bout1 = f"({LC_AR}) ? 'المتجر' : 'Boutique'"
new_bout1 = f"({LC_AR}) ? 'المتجر' : (({LC_EN}) ? 'Official' : 'Boutique')"
if old_bout1 in content:
    content = content.replace(old_bout1, new_bout1, 1)
    hits += 1

old_bout2 = f"({LC_AR}) ? 'الرسمي' : 'Officielle'"
new_bout2 = f"({LC_AR}) ? 'الرسمي' : (({LC_EN}) ? 'Store' : 'Officielle')"
if old_bout2 in content:
    content = content.replace(old_bout2, new_bout2, 1)
    hits += 1

# "Rejoignez Najah Souss Echecs" -> We had "Rejoignez" and "Najah Souss Echecs"
old_rej1 = f"({LC_AR}) ? 'انضم إلى' : 'Rejoignez'"
new_rej1 = f"({LC_AR}) ? 'انضم إلى' : (({LC_EN}) ? 'Join' : 'Rejoignez')"
if old_rej1 in content:
    content = content.replace(old_rej1, new_rej1, 1)
    hits += 1

old_rej2 = f"({LC_AR}) ? 'نادي نجاح سوس' : 'Najah Souss Echecs'"
new_rej2 = f"({LC_AR}) ? 'نادي نجاح سوس' : (({LC_EN}) ? 'Najah Souss Chess' : 'Najah Souss Echecs')"
if old_rej2 in content:
    content = content.replace(old_rej2, new_rej2, 1)
    hits += 1

# "ARTICLE" -> It might be in the news section "ARTICLE" (in uppercase)
# Let's search for "ARTICLE"
if ">ARTICLE<" in content:
    content = content.replace(">ARTICLE<", f"><?php echo {LC_EN} ? 'ARTICLE' : 'ARTICLE'); ?><", 1)
    hits += 1

with open(fp, 'w', encoding='utf-8') as f:
    f.write(content)

print(f"Applied {hits} replacements in front-page.php")

# Lint
r = subprocess.run([PHP, '-l', fp], capture_output=True, encoding='utf-8', errors='replace')
print(f'Lint: {(r.stdout + r.stderr).strip()}')
