<?php
header('Content-Type: text/xml');

$xml = new SimpleXMLElement("<urlset/>");
$xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");

foreach (glob("*.html") as $file) {
    $node = $xml->addChild("url");
    $node->addChild("loc", "https://rauchfuss.io/{$file}");
    $node->addChild("lastmod", date ("Y-m-d", filemtime($file)));
}

print $xml->asXML();