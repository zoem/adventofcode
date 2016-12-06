<?php

$input = file_get_contents('day06.txt');

$lines = array_map('str_split', array_filter(array_map('trim', explode(PHP_EOL, $input))));

$answer1 = $answer2 = '';

for ($i=0; $i<count(current($lines)); $i++) {
    $col = array_column($lines, $i);
    $counts = array_count_values($col);
    
    // Part 1
    arsort($counts);
    $answer1 .= key($counts);
    
    // Part 2
    asort($counts);
    $answer2 .= key($counts);
}

printf('ans#6.1: %s'.PHP_EOL, $answer1);
printf('ans#6.2: %s'.PHP_EOL, $answer2);