<?php

$input  = file_get_contents('day03.txt');
$lines  = explode(PHP_EOL, trim($input));
$grid   = [];
$claims = [];

foreach ($lines as $line) {
    preg_match_all('~\d+~', $line, $matches);

    $id = array_shift($matches[0]);
    $claims[$id] = array_combine(['cx', 'cy', 'w', 'h'], $matches[0]);
}

foreach ($claims as $id => $claim) {
    for ($y = $claim['cy']; $y < ($claim['cy'] + $claim['h']); $y++) {
        for ($x = $claim['cx']; $x < ($claim['cx'] + $claim['w']); $x++) {
            $grid[$y][$x] = isset($grid[$y][$x]) ? 'x' : $id;
        }
    }
}

$sums = [];
foreach ($grid as $row) {
    foreach (array_count_values($row) as $id => $count) {
        $sums[$id] = ($sums[$id] ?? 0) + $count;
    }
}

$part2 = null;
foreach ($sums as $id => $sum) {
    if ($sum == $claims[$id]['w'] * $claims[$id]['h']) {
        $part2 = $id;
    }
}


printf('ans#3.1: %d'.PHP_EOL, $sums['x']);
printf('ans#3.2: %d'.PHP_EOL, $part2);