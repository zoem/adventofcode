<?php

$input = file_get_contents('day01.txt');

// Part 1 & 2
$directions = array_filter(explode(',', $input));

$coordinates = ['x' => 0,  'y' => 0];
$compass = ['N','E','S','W'];
$visited = ['0|0' => true];
$twice = false;

foreach ($directions as $direction) {
    preg_match('~(?<direction>[a-z]+)(?<blocks>[0-9]+)~i', $direction, $match);
    
    // simulate a circular array
    if ($match['direction'] == 'R') {
        next($compass) ?: reset($compass);
    } else {
        prev($compass) ?: end($compass);
    }
    
    $prevCoordinates = $coordinates;
    
    switch (current($compass)) {
        case 'N': $axis = 'y'; $sign =  1; break;
        case 'S': $axis = 'y'; $sign = -1; break;
        case 'E': $axis = 'x'; $sign =  1; break;
        case 'W': $axis = 'x'; $sign = -1; break;
    }
    
    // update coordinates
    $coordinates[$axis] += $match['blocks'] * $sign;
    
    // Part 2 specific
    if ($twice) {
        continue;
    }
    
    $axis2 = $axis == 'y' ? 'x' : 'y';

    foreach (range($prevCoordinates[$axis] + $sign, $coordinates[$axis]) as $i) {
        $j = $prevCoordinates[$axis2];
        $current = [$axis => $i, $axis2 => $j];
        ksort($current); // make sure it's ordered as (x,y)
        $key = implode('|', $current);
        
        if (isset($visited[$key])) {
            $twice = $key;
            break;
        }
        
        $visited[$key] = true;
    }
}

printf('ans#1.1: %d'.PHP_EOL, array_sum(array_map('abs', $coordinates)));
printf('ans#1.2: %d'.PHP_EOL, array_sum(array_map('abs', explode('|', $twice))));