<?php

$input = file_get_contents('day01.txt');

// P1 
$floor = substr_count($input, '(') - substr_count($input, ')');

printf('ans#1.1: %d'.PHP_EOL, $floor);

// P2
$chars = str_split($input);

$floor = 0;
foreach ($chars as $pos => $char) {
    $floor += $char == '(' ? 1 : -1;
    
    if ($floor < 0) {
        printf('ans#1.2: %d'.PHP_EOL, $pos+1);
        break;
    }
}