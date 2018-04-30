<?php

$matchRegex = '/#EXTINF:-1 tvg-id="(.*)" tvg-name="(.*)" tvg-logo="(.*)" group-title="(.*)",(.*)/';

include_once(__DIR__ . '/config.php');
$m3uData = trim(file_get_contents($playlistURL));
$m3uData = str_replace("\r", "", $m3uData);
$m3uData = explode("\n", $m3uData);

// Build local array of channels
for ($i = 1; $i <= count($m3uData)-1; $i++) {
    preg_match_all($matchRegex, $m3uData[$i], $out);
    $channels[$out[2][0]] = array($m3uData[$i], $m3uData[$i+1]);
    $i++;
}

// Cross-reference with local list
$includeFile = __DIR__ . "/include.list";
$includeList = array();
if (file_exists($includeFile)) {
    $includeList = explode("\n", trim(file_get_contents($includeFile)));
}
