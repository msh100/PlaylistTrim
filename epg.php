<?php

$includeList = explode("\n", trim(file_get_contents(__DIR__ . '/include.list')));

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="trim.xml"');

include_once(__DIR__ . '/config.php');
$data = trim(file_get_contents($EPGURL));
$data = $movies = new SimpleXMLElement($data);

$genreList = __DIR__ . '/genre.list';
if (file_exists($genreList)) {
    $genreList = trim(file_get_contents($genreList));
} else {
    $genreList = '';
}
$genreList = explode("\n", $genreList);
foreach ($genreList as $line) {
    $map = array_map('strrev', explode(':', strrev($line), 2));
    $outputmap[str_replace(' ', '_', $map[1])] = $map[0];
}
$genreList = $outputmap;

// Trim channels
foreach ($data->channel as $channel) {
    $genre = '';
    if (in_array($channel->{'display-name'}, $includeList)) {
        if (isset($genreList[str_replace(' ', '_', $channel->{'display-name'})])) {
            $genreList[(string) $channel['id']] = $genreList[str_replace(' ', '_', $channel->{'display-name'})];
        }
        $output[] = sprintf('<channel id="%s"><display-name>%s</display-name>%s</channel>',
                        $channel['id'],
                        htmlspecialchars($channel->{'display-name'}),
                        $genre);
        $includeList[] = (string) $channel['id']; // Add ID to list
    }
}

// Trim EPG
foreach ($data->programme as $programme) {
    $genre = '';
    $thisChannel = (string) $programme['channel'];
    if (in_array($thisChannel, $includeList)) {
        if (isset($genreList[$thisChannel])) {
            $genre = sprintf('<category lang="en">%s</category>', $genreList[$thisChannel]);
        }
        $output[] = sprintf('<programme start="%s" stop="%s" channel="%s"><title>%s</title><desc>%s</desc>%s</programme>',
                        $programme['start'],
                        $programme['stop'],
                        $thisChannel,
                        htmlspecialchars($programme->title),
                        htmlspecialchars($programme->desc),
                        $genre);
    }
}

$prefix = sprintf('<?xml version="1.0" encoding="utf-8"?><!DOCTYPE tv SYSTEM "xmltv.dtd"><tv generator-info-name="%s" generator-info-url="%s">',
                     $data['generator-info-name'],
                     $data['generator-info-url']);
$suffix = '</tv>';
echo $prefix . implode('', $output) . $suffix;
