<?php

$input = file_get_contents('day23.txt');
$lines = explode(PHP_EOL, trim($input));

$list = [];
foreach ($lines as $line) {
    $list[] = explode(' ', trim($line));
}

$vars = array_fill_keys(range('a', 'h'), 0);

$get = function ($key) use (&$vars) {
    return is_numeric($key) ? $key : $vars[$key];
};

$count = count($list);
$i = $part1 = 0;
do {
    $arg1 = $list[$i][1] ?? null;
    $arg2 = $list[$i][2] ?? null; 

    switch($list[$i][0]) {
        case 'set': $vars[$arg1]  = $get($arg2); break;
        case 'sub': $vars[$arg1] -= $get($arg2); break;
        case 'mul': $vars[$arg1] *= $get($arg2); $part1++; break;
        case 'jnz': $i += ($get($arg1) != 0) ? $get($arg2) : 1; continue 2; 
    }
    
    $i++;
} while ($i > 0 && $i < $count);

printf('ans#23.1: %d'.PHP_EOL, $part1);

// part 2

$h = 0;
for ($b = 106500; $b <= 123500; $b += 17) {
    for ($e = 2; pow($e, 2) <= $b; $e++) {
        if ($b % $e == 0) {
            $h++;
            break;
        }
    }
}

printf('ans#23.2: %d'.PHP_EOL, $h);