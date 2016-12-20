<?php

$input = file_get_contents('day20.txt');

$lines = array_map(function ($line) {
        return explode('-', $line);
    },
    array_filter(array_map('trim', explode(PHP_EOL, $input)))
);

usort($lines, function ($a, $b) {
    return $a[0] <=> $b[0];
});

$min = 0;
$allowed = pow(2,32);
$block = $lines[0];

foreach ($lines as $line) {
    list ($start, $end) = $line;
    
    if ($block[0] <= $start && $start <= $block[1] && $end > $block[1]) {
        $block[1] = $end;
    } elseif ($start > $block[1]) {
        $allowed -= $block[1] - $block[0] + 1;
        $block = $line;
    }
    
    if ($start <= $min && $min <= $end) {
        $min = $end + 1;
    }
}

$allowed -= $block[1] - $block[0] + 1;

printf('ans#20.1: %u'.PHP_EOL, $min);
printf('ans#20.2: %u'.PHP_EOL, $allowed);