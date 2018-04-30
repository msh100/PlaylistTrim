<?php

include_once(__DIR__ . '/common.php');

$output = "#EXTM3U\r\n";
foreach ($includeList as $include) {
    if (isset($channels[$include])) {
        $output .= $channels[$include][0] . "\r\n";
        $output .= $channels[$include][1] . "\r\n";
    }
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="trim.m3u"');
echo $output;
