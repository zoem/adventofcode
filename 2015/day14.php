<?php

$input = file_get_contents('day14.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

// P1

$reindeers = [];
$distances = [];
$time = 2503;
foreach ($lines as $line) {
    $words = explode(' ', $line);
    
    $name = $words[0];
    
    $speed = $words[3];
    $flytime = $words[6];
    $rest = $words[13];
    
    $reindeers[$name] = [
        'speed' => $speed,
        'flytime' => $flytime,
        'rest' => $rest
    ];

    $distances[$name] = distance($speed, $flytime, $rest, $time);
}

printf('ans#14.1: %u'.PHP_EOL, max($distances));

// P2

$points = array_fill_keys(array_keys($reindeers), 0);
for ($t=1; $t<=$time; $t++) {
    $max = 0;
    $names = [];
    foreach ($reindeers as $name => $reindeer) {
        $distance = distance($reindeer['speed'], $reindeer['flytime'], $reindeer['rest'], $t);
        
        if ($distance > $max) {
            $names = [$name];
            $max = $distance;
        }
        elseif ($distance == $max) {
            $names[] = $name;
        }
    }
    
    foreach ($names as $name) {
        $points[$name]++;
    }
}

printf('ans#14.2: %u'.PHP_EOL, max($points));

function distance($speed, $flytime, $rest, $time) {
    $cycle = $flytime + $rest;
    return min($flytime, $time % $cycle) * $speed + floor($time / $cycle) * $speed * $flytime;
}