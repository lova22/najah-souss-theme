<?php
    $normalize = function($str) {
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
        $str = preg_replace('/[^a-z0-9\s]/', '', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
    };
    echo '1: ' . $normalize('Académie & Formation') . "\n";
    echo '2: ' . $normalize('Prêt À relever le défi !') . "\n";
    echo '3: ' . $normalize('Notre équipe - Classement FIDE') . "\n";
    echo '4: ' . $normalize('Notre Équipe - Classement FIDE') . "\n";
    echo '5: ' . $normalize('Formez-vous avec des Experts') . "\n";
