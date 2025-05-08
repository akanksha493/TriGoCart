<?php

$fullUrl = $_GET['current_url'];

// Parse the URL into an array
$parsedUrl = parse_url($fullUrl);

$baseUrl = $parsedUrl["path"];

$url = $baseUrl."?";
if(isset($_GET["sortby"])){
    $url = $url."sortby=".$_GET["sortby"];
}
if(isset($parsedUrl['query'])){
    $queryString = $parsedUrl['query'];
    parse_str($queryString, $params);
    if(!empty($params['search'])){
        $url = $url."&search=".$params['search'];
    }
}
echo $url;
header("Location: $url");





?>