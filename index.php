<?php

error_reporting(E_ALL & ~E_WARNING);

use App\Models\Procedure;
use App\Services\Parser;
use App\Services\View;

require_once __DIR__ . '/autoload.php';

$xpath = \App\Services\Parser::getXpath('https://etp.eltox.ru/registry/procedure');

$numberPattern = '//div[contains(@class,"procedure-list")]/div[contains(@class,"registerBox procedure-list-item")]/table/tbody/tr/td[contains(@class, "descriptTenderTd")]/dl/dt/a';
$oosNumberPattern = '//div[contains(@class,"procedure-list")]/div[contains(@class,"registerBox procedure-list-item")]/table/tbody/tr/td[contains(@class, "descriptTenderTd")]/dl/dt/span';
$linkProcedurePattern = '//div[contains(@class,"procedure-list")]/div[contains(@class,"registerBox procedure-list-item")]/table/tbody/tr/td[contains(@class, "descriptTenderTd")]/dl/dt/a';

$number = $xpath->query($numberPattern);
$oosNumber = $xpath->query($oosNumberPattern);
$linkProcedure = $xpath->query($linkProcedurePattern);

$data = [];
for ($i = 0; $i < count($number); $i++) {
    $procedure = new Procedure();

    preg_match('/(\d+)/', $number[$i]->nodeValue, $matchesNumber);
    $procedure->setNumber($matchesNumber[0]);

    preg_match('/(\d+)/', $oosNumber[$i]->nodeValue, $matchesOosNumber);
    $procedure->setOosNumber($matchesOosNumber[0]);

    $pageProcedure = 'https://etp.eltox.ru' . $linkProcedure[$i]->attributes[0]->nodeValue;
    $procedure->setLinkProcedure($pageProcedure);

    $xpathProcedure = Parser::getXpath($pageProcedure);
    $email = $xpathProcedure->query('//div[contains(@id,"tab-basic")]/table');
    $procedure->setEmail($email[0]->childNodes[25]->childNodes[3]->nodeValue);

    $documents = $xpathProcedure->query(
        '/html/body/div[3]/div/div/div/div/div[2]/div/script[13]'
    );
    preg_match_all('~download_route\s*:\s*\'(.+)\'~', $documents[0]->nodeValue, $arrayDownloadRoute);
    $downloadRoute = $arrayDownloadRoute[1][0];
    preg_match_all('~"path":"(.+)"~U', $documents[0]->nodeValue, $arrayPaths);
    $paths = $arrayPaths[1];
    preg_match_all('~"name":"(.+)"~U', $documents[0]->nodeValue, $arrayNames);
    $names = $arrayNames[1];
    $namesConvert = [];
    foreach ($names as $name) {
        $strName = $name;
        $strName = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $strName);
        $namesConvert[] = $strName;
    }
    preg_match_all('~"alias":"(.+)"~U', $documents[0]->nodeValue, $arrayAliases);
    $aliases = $arrayAliases[1];
    $aliasesConvert = [];
    foreach ($aliases as $alias) {
        $strAlias = $alias;
        $strAlias = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $strAlias);
        $aliasesConvert[] = $strAlias;
    }
    $documentsInProcedure = [];
    foreach ($aliasesConvert as $key => $aliasOfDoc) {
        $documentsInProcedure[$key]['alias'] = $aliasOfDoc;
        $documentsInProcedure[$key]['path'] = $downloadRoute . '/' . $paths[$key] . '/' . $namesConvert[$key];
    }
    $procedure->setDocuments($documentsInProcedure);

    $data[] = $procedure;
    $procedure->save();
}

$view = new View(__DIR__ . '/Templates');
$view->renderHtml('procedures.php', ['data' => $data]);
