<?php

$input = file_get_contents('day02.txt');

$steps = array_map('str_split', array_map('trim', array_filter(explode(PHP_EOL, $input))));

function code(array $steps, array $pad, array $start) {
    $current = $start;
    $code = '';

    foreach ($steps as $moves) {
        foreach ($moves as $move) {
            $new = $current;
            switch ($move) {
                case 'U': $new['y']--; break;
                case 'D': $new['y']++; break;
                case 'R': $new['x']++; break;
                case 'L': $new['x']--; break;
            }
            
            if (isset($pad[$new['y']][$new['x']])) {
                $current = $new;
            }
        }

        $code .= $pad[$current['y']][$current['x']];
    }
    
    return $code;
}

// Part 1
$start = ['x' => 1, 'y' => 1]; // start at 5
$pad   = array_chunk(range(1,9), 3);
printf('ans#2.1: %d'.PHP_EOL, code($steps, $pad, $start));

//  Part 2
$start = ['x' => 0, 'y' => 2]; // start at 5
$pad   = [
    [2 => 1],
    [1 => 2, 3, 4],
    [0 => 5, 6, 7, 8, 9],
    [1 => 'A', 'B', 'C'],
    [2 => 'D'],
];
printf('ans#2.2: %s'.PHP_EOL, code($steps, $pad, $start));