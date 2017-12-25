<?php

$input = file_get_contents('day25.txt');
$lines = array_filter(array_map(function ($line) {
    return trim($line, "\r\n :.-");
}, explode(PHP_EOL, trim($input))));

$start  = substr(array_shift($lines), -1);
$steps  = explode(' ', array_shift($lines))[5];
$chunks = array_chunk($lines, 9);

$m = [];
foreach ($chunks as $chunk) {
    $state = substr($chunk[0], -1);

    foreach ([0, 4] as $offset) {
        $value = substr($chunk[1 + $offset], -1);
        $m[$state][$value] = [
            'write' => substr($chunk[2 + $offset], -1),
            'move'  => strpos($chunk[3 + $offset], 'left') !== false ? -1 : 1,
            'state' => substr($chunk[4 + $offset], -1),
        ];
    }
}

$pos   = 0;
$tape  = [$pos => 0];
$state = $start;

for ($i=0; $i<$steps; $i++) {
    $value      = $tape[$pos] ?? 0;
    $tape[$pos] = $m[$state][$value]['write'];
    $pos       += $m[$state][$value]['move'];
    $state      = $m[$state][$value]['state'];
}

printf('ans#25.1: %d'.PHP_EOL, array_sum($tape));