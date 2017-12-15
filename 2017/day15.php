<?php

$input = trim(file_get_contents('day15.txt'));
$lines = explode(PHP_EOL, $input);

$gens = [];
foreach ($lines as $line) {
    preg_match('~\d+~', $line, $match);
    $gens[] = $match[0];
}

$divs = [16807, 48271];

$count = 0;
$values = $gens;
for ($i=0; $i<40E6; $i++) {
    $pair = [];

    foreach ($values as $k => $v) {
        $values[$k] = ($v * $divs[$k]) % 2147483647;
        $pair[substr(decbin($values[$k]), -16)] = true;
    }
    
    if (count($pair) == 1) {
        $count++;
    }
}

printf('ans#15.1: %d'.PHP_EOL, $count);

// part 2

$mods = [4, 8];
$count = 0;
$values = $gens;
for ($i=0; $i<5E6; $i++) {
    $pair = [];
    
    foreach ($values as $k => $v) {
        do {
            $values[$k] = ($values[$k] * $divs[$k]) % 2147483647;
        } while ($values[$k] % $mods[$k] != 0);

        $pair[substr(decbin($values[$k]), -16)] = true;
    }
    
    if (count($pair) == 1) {
        $count++;
    }
}

printf('ans#15.2: %d'.PHP_EOL, $count);