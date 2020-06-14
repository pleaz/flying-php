<?php

    require "vendor/autoload.php";
    use PHPHtmlParser\Dom;

    $post = $_POST;
    $encoded = '';
    if(isset($post)) {
        foreach($_POST as $name => $value) {
            $encoded .= urlencode($name).'='.urlencode($value).'&';
        }
        $encoded = substr($encoded, 0, strlen($encoded)-1);
    }
    $url = 'https://www.alicante-airport.net/arrivals.php';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $encoded ? $url.'?'.$encoded : $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $tuData = curl_exec($ch);
    curl_close($ch);


    $dom = new Dom;
    $dom->setOptions([
        'removeScripts' => true,
        'removeStyles' => true,
    ]);
    $dom->load($tuData);
    $contents = $dom->find('.flights');
    $contents = str_replace('/arrivals.php', '/flights-to-alicante-airport.html', $contents);
    $contents = str_replace('[+]', '', $contents);
    $a = $dom->find('a');
    foreach ($a as $h) {
        $href = $h->getAttribute('href');
        $ar = ['?tp=0&day=tomorrow', '?tp=6&day=tomorrow', '?tp=12&day=tomorrow', '?tp=18&day=tomorrow', '?day=yesterday', '?day=tomorrow', '/flights-to-alicante-airport.html', '?tp=0&day=yesterday', '?tp=6&day=yesterday', '?tp=12&day=yesterday', '?tp=18&day=yesterday', '?tp=0', '?tp=6', '?tp=12', '?tp=18'];
        if(!in_array($href, $ar)) $contents = str_replace($h->getAttribute('href'), 'javascript: void(0)', $contents);
    }

    echo $contents;
