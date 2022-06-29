<?php

namespace App\Services;

class Parser
{
    public static function getXpath($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $page = curl_exec($curl);
        curl_close($curl);

        $doc = new \DOMDocument();
        $doc->loadHTML($page);

        return new \DOMXpath($doc);
    }

}
