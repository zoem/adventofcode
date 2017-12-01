<?php

$input = file_get_contents('day01.txt');
$digits = str_split($input);

$sum = $sum2 = 0;
$count = count($digits);
for ($i=0; $i < $count; $i++) {
    // part 1
    $j = ($i + 1) % $count;
    if ($digits[$i] == $digits[$j]) {
        $sum += $digits[$i];
    }
    
    // part 2
    $k = ($i + $count / 2) % $count;
    if ($digits[$i] == $digits[$k]) {
        $sum2 += $digits[$i];
    }
}

printf('ans#1.1: %d'.PHP_EOL, $sum);
printf('ans#1.2: %d'.PHP_EOL, $sum2);