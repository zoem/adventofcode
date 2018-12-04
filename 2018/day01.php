<?php

$input = file_get_contents('day01.txt');
$lines = explode(PHP_EOL, trim($input));

$seen = [];
$sum = $i = 0;

do {
    if ($i > 0) {
        $seen[$sum] = true;
    }

    $sum += (int) $lines[$i];
    $i = ($i + 1) % count($lines);

} while (!isset($seen[$sum]));

printf('ans#1.1: %d'.PHP_EOL, array_sum(array_map('intval', $lines)));
printf('ans#1.2: %d'.PHP_EOL, $sum);