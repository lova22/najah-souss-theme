# -*- coding: utf-8 -*-
import os, sys

THEME = r'C:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme'
files = ['front-page.php', 'header.php', 'footer.php']

replacements = {
    "Agadir  Maroc - Depuis 1987": "Agadir · Maroc — Depuis 1987",
    "Comptition": "Compétition",
    "tre jou": "être joué",
    "checs ": "échecs à",
    "checs": "échecs",
    "? Rejoindre": "♞ Rejoindre",
    "Palmars": "Palmarès",
    "Trne": "Trône",
    "mdailles": "médailles",
    "l\\' )or": "l\\'or",
    "l\\' )excellence": "l\\'excellence",
    "slections": "sélections",
    " propos": "À propos",
    " PROPOS": "À PROPOS",
    " Aucun coup": "« Aucun coup",
    "but. ": "but. »",
    "Catgorie Ouverte ": "Catégorie Ouverte à",
    "Aot": "Août",
    " venir": "À venir",
    "visibilit": "visibilité",
    "TLCHARGER": "TÉLÉCHARGER",
    " Agadir": "à Agadir",
    " l\\'excellence": "à l\\'excellence",
    "Acadmie": "Académie",
    "vnements": "Événements",
    "S\\')inscrire": "S\\'inscrire",
    "s\\')inscrire": "s\\'inscrire",
    "d\\')essai": "d\\'essai",
    "sance": "séance",
    "l pour vous": "là pour vous",
    "ddi": "dédié",
    "entranez-vous": "entraînez-vous",
    "Prt ": "Prêt à",
    "dfi": "défi",
    "quipe": "Équipe",
    "Actualit": "Actualité",
    "crmonies": "cérémonies",
    "dernires": "dernières",
    "t": "été",
    " l'": "à l'",
    " ses": "à ses",
    " une": "à une"
}

for filename in files:
    fp = os.path.join(THEME, filename)
    # The file might have been written in cp1252 or utf-8 but with wrong characters.
    # We will read it as utf-8, replace the exact strings, and write it back.
    with open(fp, 'r', encoding='utf-8', errors='replace') as f:
        content = f.read()

    for bad, good in replacements.items():
        content = content.replace(bad, good)
        
    # Also fix any remaining `l\' )excellence`
    content = content.replace(r"l\' )excellence", r"l\'excellence")
    content = content.replace(r"l' )excellence", r"l\'excellence")
    content = content.replace(r"d\' )essai", r"d\'essai")
    content = content.replace(r"s\' )inscrire", r"s\'inscrire")
    content = content.replace(r"S\' )inscrire", r"S\'inscrire")
    
    with open(fp, 'w', encoding='utf-8') as f:
        f.write(content)

print("Restored accents and fixed typos.")
