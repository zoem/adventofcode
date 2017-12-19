<?php

$input = file_get_contents('day19.txt');
$lines = explode(PHP_EOL, rtrim($input));

$grid = [];
foreach ($lines as $line) {
    $grid[] = str_split(rtrim($line));
}

// find start, should be the | character in the top row
$start = array_search('|', $grid[0]);
$c = [$start, 0];
// path starts in downward direction, so x=0, y=1
$d = [0, 1];

// all possible directions
$directions = [
    [0, 1],
    [1, 0],
    [-1, 0],
    [0, -1],
];

$log = [];
$i = 0;
do {
    $i++;
    
    // next coordinate
    $c = [
        $c[0] + $d[0],
        $c[1] + $d[1],
    ];
    
    $char = $grid[$c[1]][$c[0]] ?? null;
    
    if (ctype_upper($char)) {
        // encountered an alphabetic char, log it
        $log[] = $char;
    } elseif ($char == '+') {
        // '+' indicates a change of direction
        foreach ($directions as $direction) {
            // the new direction is always perpendicular to the current direction, so skip parallel directions
            if (abs($d[0] * $direction[0]) > 0 || abs($d[1] * $direction[1]) > 0) {
                continue;
            }
            
            $next = [
                $c[0] + $direction[0],
                $c[1] + $direction[1],
            ];

            $nextChar = trim($grid[$next[1]][$next[0]] ?? null);

            if (!empty(trim($nextChar))) {
                $d = $direction;
                break;
            }
        }
    }
} while (!empty(trim($char)));

printf('ans#19.1: %s'.PHP_EOL, implode($log));
printf('ans#19.2: %d'.PHP_EOL, $i);