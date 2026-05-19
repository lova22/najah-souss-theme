<?php
require_once 'functions.php';
// Mock polylang
if (!function_exists('pll_current_language')) {
    function pll_current_language() { return 'en'; }
}
echo 'TEST 1: ' . ansae_t('Formez-vous avec des Experts') . "\n";
echo 'TEST 2: ' . ansae_t('À PROPOS') . "\n";
echo 'TEST 3: ' . ansae_t("D'EXCELLENCE") . "\n";
echo 'TEST 4: ' . ansae_t('Prêt à relever le défi ?') . "\n";
echo 'TEST 5: ' . ansae_t('Notre Équipe - Classement FIDE') . "\n";
echo 'TEST 6: ' . ansae_t('Galerie Najah Souss') . "\n";
