<?php

$input = intval(file_get_contents('day20.txt'));

function findHousePart1($presents) {
    $sum = 0;
    $min = ceil($presents/10);
    $h = intval(floor($min / 5)); // guess starting point (ratio sum/h is somewhere between 4 and 5)
    while ($sum < $min) {
        $sum = 0;
        $h++;
        $max = sqrt($h);

        for ($e=1;$e<=$max;$e++) {
            if ($h % $e == 0) {
                $sum += $e + $h / $e * ($e == $max ? 0 : 1);
            }
        }
    }
    
    return $h;
}

printf('ans#20.1: %u'.PHP_EOL, findHousePart1($input));

function findHousePart2($presents, $maxPerElve = 50) {
    $sum = 0;
    $min = ceil($presents/11);
    $h = intval(floor($min / 5));
    while ($sum < $min) {
        $sum = 0;
        $h++;
        $max = sqrt($h);
        for ($e=1;$e<=$max;$e++) {
            if ($h % $e == 0) {
                if ($e <= $maxPerElve && $e != $max) {
                    $sum += $h / $e;
                }
                if ($h / $e <= $maxPerElve) {
                    $sum += $e;
                }
            }
        }
    }
    
    return $h;
}

printf('ans#20.2: %u'.PHP_EOL, findHousePart2($input, 50));