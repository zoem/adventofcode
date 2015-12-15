<?php

$input = file_get_contents('day15.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$ingredients = [];

foreach ($lines as $line) {
    $words = explode(' ', str_replace([',',':'], '', $line));

    $ingredients[$words[0]] = [
        'capacity'   => $words[2],
        'durability' => $words[4],
        'flavor'     => $words[6],
        'texture'    => $words[8],
        'calories'   => $words[10],
    ];
}

function permutations($num, $max) {
    if ($num == 1) {
        return [[$max]];
    }
    
    $r = [];
    for ($i=0; $i<=$max; $i++) {
        foreach (permutations($num-1, $max-$i) as $j) {
            $r[] = array_merge($j, [$i]);
        }
    }
    
    return $r;
}

$properties = array_keys(reset($ingredients));

$max = 0;
$maxCal500 = 0;
foreach (permutations(count($ingredients), 100) as $recipe) { 
    $mix = [];

    foreach ($properties as $property) {
        $mix[$property] = 0;

        foreach (array_keys($ingredients) as $i => $name) {
            $mix[$property] += $recipe[$i] * $ingredients[$name][$property];
        }
        
        $mix[$property] = max(0, $mix[$property]);
    }
    
    $calories = $mix['calories'];
    unset($mix['calories']);

    $product = array_product($mix);
    
    if ($product > $max) {
        $max = $product;
    }
    
    if ($calories == 500 && $product > $maxCal500) {
        $maxCal500 = $product;
    }
}

printf('ans#15.1: %u'.PHP_EOL, $max);
printf('ans#15.2: %u'.PHP_EOL, $maxCal500);