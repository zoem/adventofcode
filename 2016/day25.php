<?php

$input = file_get_contents('day25.txt');

preg_match_all('#cpy\s(\d+)\s[bc]#m', $input, $matches);

$target = $matches[1][0] * $matches[1][1];
$n = 0;
while ($n < $target) {
    if ($n % 2 == 0) {
        $n = $n * 2 + 1;
    } else {
        $n *= 2;
    }
}

printf('ans#25.1: %s'.PHP_EOL, $n - $target);