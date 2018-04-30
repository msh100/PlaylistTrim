<?php

$includeList = explode("\n", trim(file_get_contents(__DIR__ . '/include.list')));

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="trim.xml"');

include_once(__DIR__ . '/config.php');
$data = trim(file_get_contents($EPGURL));
$data = $movies = new SimpleXMLElement($data);

// Trim channels
foreach ($data->channel as $channel) {    
    if (in_array($channel->{'display-name'}, $includeList)) {
        $output[] = sprintf('<channel id="%s"><display-name>%s</display-name></channel>',
                        $channel['id'],
                        htmlspecialchars($channel->{'display-name'}));
        $includeList[] = (string) $channel['id']; // Add ID to list
    }
}

// Trim EPG
foreach ($data->programme as $programme) {
    if (in_array($programme['channel'], $includeList)) {
        $output[] = sprintf('<programme start="%s" stop="%s" channel="%s"><title>%s</title><desc>%s</desc></programme>',
                        $programme['start'],
                        $programme['stop'],
                        $programme['channel'],
                        htmlspecialchars($programme->title),
                        htmlspecialchars($programme->desc));
    }
}

$prefix = sprintf('<?xml version="1.0" encoding="utf-8"?><!DOCTYPE tv SYSTEM "xmltv.dtd"><tv generator-info-name="%s" generator-info-url="%s">',
                     $data['generator-info-name'],
                     $data['generator-info-url']);
$suffix = '</tv>';
echo $prefix . implode('', $output) . $suffix;
