<?php

$input = file_get_contents('day03.txt');

$splitter = function ($value) {
    return preg_split('~\s+~', trim($value));
};

$horizontal = array_map($splitter, array_filter(explode(PHP_EOL, $input)));

$test = function (array $triangle) {
    sort($triangle); 
    return ($triangle[0] + $triangle[1] > $triangle[2]);
};

// Part 1
printf('ans#3.1: %u'.PHP_EOL, array_sum(array_map($test, $horizontal)));

// Part 2
$vertical = array_chunk(array_merge(
    array_column($horizontal, 0),
    array_column($horizontal, 1),
    array_column($horizontal, 2)
), 3);


printf('ans#3.2: %u'.PHP_EOL, array_sum(array_map($test, $vertical)));