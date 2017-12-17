<?php

$input = (int) file_get_contents('day17.txt');

$p = 0;
$list = [0];
for ($i=1; $i<=2017; $i++) {
    $p = ($p + $input) % $i + 1;
    array_splice($list, $p, 0, [$i]);
}

printf('ans#17.1: %d'.PHP_EOL, $list[array_search(2017, $list)+1]);

$p = $v = 0;
for ($i=1; $i<=50E6; $i++) {
    $p = ($p + $input) % $i + 1;
    
    // remember value at index #1
    if ($p == 1) {
        $v = $i;
    }
}

printf('ans#17.2: %d'.PHP_EOL, $v);