<?php

$input = file_get_contents('day06.txt');
preg_match_all('!\d+!', $input, $matches);

$banks = $matches[0];
$count = count($banks);

$cycles1 = $cycles2 = $hash = 0;
$seen = [];
$match = null;
while ($hash !== $match) {
    $cycles1 += isset($seen[$hash]) ? 0 : 1;
    $cycles2++;
    
    if (isset($seen[$hash]) && $match === null) {
        $match = $hash;
    }
    
    $seen[$hash] = true;
    
    $max = max($banks);
    $idx = array_search($max, $banks);
    
    $banks[$idx] = 0;
    for ($i=1; $i<=$max; $i++) {
        $banks[($idx + $i) % $count]++;
    }

    $hash = implode('|', $banks);
} 

printf('ans#6.1: %d'.PHP_EOL, $cycles1);
printf('ans#6.1: %d'.PHP_EOL, $cycles2 - $cycles1);