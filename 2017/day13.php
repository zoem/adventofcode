<?php

$input = trim(file_get_contents('day13.txt'));
$lines = explode(PHP_EOL, $input);

$layers = [];
foreach ($lines as $line) {
    list ($layer, $range) =  array_map('intval', explode(':', trim($line)));
    $layers[$layer] = $range;
}

function solve(array $layers, $delay, $breakOnCaught) {
    $severity = 0;
    
    foreach ($layers as $depth => $range) {
        /* 
         * Example of a layer with range 4:
         *   position: 1 2 3 4 3 2 1 2 3 4 3 2 1
         *   pattern:  1 2 3 4 5 6 1 2 3 4 5 6 1 (2 * range - 2)
         * After each 6 steps the scanner is at the top again
         * So when the depth (+ delay) coincides with the repeating pattern of 6 you are caught
         */
        $mod = ($depth + $delay) % (2 * $range - 2);
        
        if ($mod == 0 && $breakOnCaught) {
            return false;
        } elseif ($mod == 0) {
            $severity += $depth * $range;
        }
    }
    
    return $severity;
}

// part 1

printf('ans#13.1: %d'.PHP_EOL, solve($layers, 0, false));

// part 2

for ($delay=0;; $delay++) {
    if (solve($layers, $delay, true) === 0) {
        printf('ans#13.2: %d'.PHP_EOL, $delay);
        break;
    }
}