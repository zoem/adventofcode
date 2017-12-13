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