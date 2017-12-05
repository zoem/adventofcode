<?php

$input = file_get_contents('day05.txt');

$offsets = array_map('intval', explode(PHP_EOL, $input));

// part 1
$steps = $i = 0;
$jumps = $offsets;
while (isset($jumps[$i])) {
    ++$jumps[$i];
    $i += $jumps[$i] - 1;
    $steps++;
}

printf('ans#5.1: %d'.PHP_EOL, $steps);

// part 2
$steps = $i = 0;
$jumps = $offsets;
while (isset($jumps[$i])) {
    $jump = $jumps[$i];
    $jumps[$i] >= 3 ? --$jumps[$i] : ++$jumps[$i];
    
    $i += $jump;
    $steps++;
}

printf('ans#5.2: %d'.PHP_EOL, $steps);