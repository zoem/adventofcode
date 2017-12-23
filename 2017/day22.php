<?php

$input = file_get_contents('day22.txt');

$grid = [];
foreach (explode(PHP_EOL, trim($input)) as $line) {
    $grid[] = str_split(trim($line));
}

function run(array $grid, $max, $part2 = false) {
    // starting coordinate (center)
    $c = [
        ceil(count($grid) / 2) - 1,
        ceil(count($grid[0]) / 2) - 1,
    ];

    $turns = [
        [0, -1], // U
        [1, 0],  // R
        [0, 1],  // D
        [-1, 0], // L
    ];
    
    // orientation starts facing up
    $o = 0;
    $inf = 0;

    for ($i=0; $i<$max; $i++) {
        if (!isset($grid[$c[1]][$c[0]])) {
            $grid[$c[1]][$c[0]] = '.';
        }
        
        $node = &$grid[$c[1]][$c[0]];
        
        if ($part2) {
            switch ($node) {
                case '.':
                    $node = 'W';
                    $o = ($o + 4 - 1) % 4; // turn left
                    break;
                    
                case 'W':
                    $node = '#';
                    $inf++;
                    break;
                    
                case '#':
                    $node = 'F';
                    $o = ($o + 1) % 4;  // turn right
                    break;
                    
                case 'F':
                    $node = '.';
                    $o = ($o + 2) % 4; // reverse
                    break;
            }
        } else {
            switch ($node) {
                case '#':
                    $node = '.';
                    $o = ($o + 1) % 4; // turn right
                    break;
                
                case '.':
                    $node = '#';
                    $inf++;
                    $o = ($o + 4 - 1) % 4; // turn left
                    break;
            }
        }
        
        $c[0] += $turns[$o][0];
        $c[1] += $turns[$o][1];
    }
    
    return $inf;
}

printf('ans#22.1: %d'.PHP_EOL, run($grid, 10000, false));
printf('ans#22.2: %d'.PHP_EOL, run($grid, 10000000, true));