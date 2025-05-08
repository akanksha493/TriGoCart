<?php

$fullUrl = $_GET['current_url'];

// Parse the URL into an array
$parsedUrl = parse_url($fullUrl);

$baseUrl = $parsedUrl["path"];

$url = $baseUrl."?";
if(isset($_GET["search"])){
    $url = $url."search=".$_GET["search"];
}
if(isset($parsedUrl['query'])){
    $queryString = $parsedUrl['query'];
    parse_str($queryString, $params);
    if(($params['sortby'])){
        $url = $url."&sortby=".$params['sortby'];
    }
}
echo $url;
header("Location: $url");





?>