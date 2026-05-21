<!DOCTYPE html>
<html lang="fr" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vous êtes hors ligne — Najah Souss Échecs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0a0a0a; color: #f5f5f5; font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; text-align: center; }
        .gold-text { color: #d4af37; }
        .gold-border { border: 1px solid rgba(212, 175, 55, 0.3); }
        .gold-button { background-color: #d4af37; color: #0a0a0a; padding: 12px 24px; border-radius: 8px; font-weight: bold; text-decoration: none; display: inline-block; transition: opacity 0.2s; }
        .gold-button:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="gold-border" style="padding: 3rem; border-radius: 1rem; max-width: 400px; background: rgba(255,255,255,0.03);">
        <div style="font-size: 4rem; margin-bottom: 1rem;">♞</div>
        <h1 class="gold-text" style="font-size: 1.5rem; margin-bottom: 0.5rem; font-weight: bold;">Vous êtes hors ligne</h1>
        <p style="color: #a0a0a0; margin-bottom: 2rem; line-height: 1.5;">Il semble que vous ayez perdu votre connexion Internet. Veuillez vérifier votre réseau.</p>
        <button onclick="window.location.reload()" class="gold-button">Réessayer</button>
    </div>
</body>
</html>
