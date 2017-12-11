<?php

$input = trim(file_get_contents('day11.txt'));

$steps = array_filter(explode(',', $input));

$v = $h = $max = 0;
foreach ($steps as $step) {
    switch ($step) {
        case 'n':  $v++; break;
        case 's':  $v--; break;
        case 'ne': $v+=0.5;$h+=0.5; break;
        case 'nw': $v+=0.5;$h-=0.5; break;
        case 'sw': $v-=0.5;$h-=0.5; break;
        case 'se': $v-=0.5;$h+=0.5; break;
    }
    
    if (abs($v) + abs($h) > $max) {
        $max = abs($v) + abs($h);
    }
}

printf('ans#11.1: %d'.PHP_EOL, abs($v) + abs($h));
printf('ans#11.2: %d'.PHP_EOL, $max);