<?php

$input = preg_match_all('~(\d)+~', file_get_contents('day25.txt'), $matches);

list($row, $col) = $matches[0];

$n = 20151125;

$max  = array_sum(range(0, $col + $row - 2));
$max += $col - 1;

for ($i=0; $i<$max; $i++) {
    $n = ($n * 252533) % 33554393;
}

printf('ans#25.1: %u'.PHP_EOL, $n);