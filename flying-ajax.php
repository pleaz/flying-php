<?php

/**
 * @param $text
 * @param string $tags
 * @param bool $invert
 * @return string|string[]|null
 */
function strip_tags_content($text, $tags = '', $invert = FALSE) {

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if(is_array($tags) AND count($tags) > 0) {
            if($invert == FALSE) {
                return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            }
            else {
                return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
            }
        }
        elseif($invert == FALSE) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }

    $data = array(
        'language' => 'English',
        'startAction' => 'AirportFlightStatus',
        'imageColor' => 'orange',
        'airportQueryType' => 1
    );

    $post = $_POST;
    if(isset($post['language'])) $data = $post;
    $url = 'https://www.flightstats.com/go/weblet?guid=34b64945a69b9cac:144d791f:124cb97347e:ebf&weblet=status&action=AirportFlightStatus&airportCode=RMU';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $tuData = curl_exec($ch);
    curl_close($ch);

    $tuData = str_replace(
        ['<br/>','<meta name="robots" content="noindex"/>','ga(\'send\', \'event\', \'Form\', \'Change\', \'QueryType\', 0); ','ga(\'send\', \'event\', \'Form\', \'Change\', \'QueryType\', 1); ','<link rel="stylesheet" type="text/css" href="http://themhigroup.com" />','More RMU Arrivals','RMU Weather','RMU Airport Delays', 'More RMU Departures','<span class="codeshareIndicator">^</span>','^ = Codeshare Flight'], '', $tuData);
    $tuData = preg_replace('#<a.*?>(.*?)</a>#is', '\1', $tuData);
    $tuData = str_replace($url, 'flying-ajax.php', $tuData);
    $tuData = str_replace('JavaScript:submit();', 'flyPost();', $tuData);
    $tuData = str_replace('flightStatusByRouteForm', 'fly-dep', $tuData);
$tuData = str_replace('flyPost();"> Departures', 'JavaScript:submit();">', $tuData);
$tuData = str_replace('flyPost();"> Arrivals', 'JavaScript:submit();">', $tuData);
    echo strip_tags_content($tuData, '<script>', TRUE);
