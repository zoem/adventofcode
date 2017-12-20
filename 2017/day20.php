<?php

$input = file_get_contents('day20.txt');
$lines = explode(PHP_EOL, trim($input));

$points = $velocity = $acceleration = [];
foreach ($lines as $line) {
    preg_match_all('|(-?[0-9]+)|', $line, $matches);
    
    list (
        $points[], 
        $velocity[], 
        $acceleration[]
    ) = array_chunk($matches[0], 3);
}

// calculates the length of a vector
function length(array $v) {
    $x = 0;
    foreach ($v as $c) {
        $x += pow($c, 2);
    }
    return sqrt($x);
}


$minA = $minV = $minP = null;
foreach ($points as $i => $p) {
    $lenA = length($acceleration[$i]);
    $lenV = length($velocity[$i]);

    if ($minA === null || $lenA < $minA) {
        // lowest acceleration wins
        $minA = $lenA;
        $minV = $lenV;
        $minP = $i;
    } elseif (abs($lenA - $minA) < 1E-10 && $lenV < $minV) {
        // acceleration tie is broken by comparing the velocity
        // lowest velocity wins
        $minP = $i;
    }
}

printf('ans#20.1: %d'.PHP_EOL, $minP);

// part 2

$currentP = $points;
$currentV = $velocity;
$lastCollision = 0;
do {
    $keys = [];
    $lastCollision++;
    
    foreach ($currentP as $pi => $p) {
        foreach ($currentV[$pi] as $vi => $v) {
            $currentV[$pi][$vi] += $acceleration[$pi][$vi];
            $currentP[$pi][$vi] += $currentV[$pi][$vi];
        }
        
        $key = implode(',', $currentP[$pi]);
        if (isset($keys[$key])) {
            // collision
            $other = $keys[$key];
            unset($currentP[$pi], $currentP[$other]);
            // reset counter
            $lastCollision = 0;
        } else {
            $keys[$key] = $pi;
        }
        
    }
} while ($lastCollision < 100);

printf('ans#20.2: %d'.PHP_EOL, count($currentP));