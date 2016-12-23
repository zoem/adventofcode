<?php

$input = file_get_contents('day22.txt');
$lines = array_filter(array_map('trim', explode(PHP_EOL, $input)));

$nodes = [];
$empty = null;
$maxX  = 0;
$dummy = range(1, 10);

foreach ($lines as $line) {
    if (preg_match('#x(?<x>\d+)-y(?<y>\d+)\s+(?<s>\d+)T\s+(?<u>\d+)T\s+(?<a>\d+)T\s+(?<p>\d+)%#', $line, $match)) {
        $match = array_diff_key($match, $dummy);
        $nodes[] = $match;
        
        $maxX = max($match['x'], $maxX);
        
        if ($match['u'] == 0) {
            $empty = $match;
        }
    }
}

$sum = 0;
foreach ($nodes as $a) {
    foreach ($nodes as $b) {
        if ($a['x'] == $b['x'] && $a['y'] == $b['y']) {
            continue;
        }
        
        if ($a['a'] >= $b['u'] && $b['u'] > 0) {
            $sum++;
        }
    }
}

$grid = [];
foreach ($nodes as $node) {
    $v = '.';
    if ($node['a'] < 10) {
        $v = '#';
    } elseif ($node['u'] == 0) {
        $v = 'E';
    }
    $grid[$node['y']][$node['x']] = $v;
}

foreach ($grid as $row) {
    echo implode('', $row).PHP_EOL;
}

printf('ans#22.1: %s'.PHP_EOL, $sum);
printf('ans#22.2: %s'.PHP_EOL, $empty['x'] + $empty['y'] + $maxX + ($maxX - 1) * 5);