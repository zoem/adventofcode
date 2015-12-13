<?php

$input = file_get_contents('day09.txt');
$lines = explode("\n", $input);
$lines = array_filter($lines);

$map = [];

foreach ($lines as $line) {
    $parts = explode(' ', $line);
    
    $from = $parts[0];
    $to = $parts[2];
    $distance = $parts[4];
    
    $map[$from][$to] = $distance;
    $map[$to][$from] = $distance;
}

$routes = [];
$min = false;
$max = false;
$minRoute = false;
$maxRoute = false;

foreach (permutations(array_keys($map)) as $i => $route) {
    $distance = 0;
    foreach ($route as $j => $place) {
        if ($j==0) {
            continue;
        }

        $distance += $map[$route[$j-1]][$place];
    }
    
    if ($min === false || $distance < $min) {
        $minRoute = $route;
        $min = $distance;
    }
    
    if ($max === false || $distance > $max) {
        $maxRoute = $route;
        $max = $distance;
    }
}

printf('ans#9.1: %u'.PHP_EOL, $min);
printf('ans#9.2: %u'.PHP_EOL, $max);

function permutations(array $elements)
{
    if (count($elements) <= 1) {
        yield $elements;
    } else {
        foreach (permutations(array_slice($elements, 1)) as $permutation) {
            foreach (range(0, count($elements) - 1) as $i) {
                yield array_merge(
                    array_slice($permutation, 0, $i),
                    [$elements[0]],
                    array_slice($permutation, $i)
                );
            }
        }
    }
}