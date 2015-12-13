<?php

$input = file_get_contents('day04.txt');

function md5WithZeroPrefix($prefix, $zeros) {
    $s = microtime(1);
    $i = 0;
    $zerostr = str_repeat('0', $zeros);
    
    while (true) {
        $md5 = md5(sprintf('%s%u', $prefix, $i));
        $str = substr($md5, 0, $zeros);
        //var_dump($str . ' | ' . $i);
        
        if ($str === $zerostr) {
            //var_dump('FOUND', $md5, $i, 'time:' . (microtime(1) - $s));
            break;
        }
        $i++;
    }
    
    return $i;
}

printf('ans#4.1: %u'.PHP_EOL, md5WithZeroPrefix($input, 5));
printf('ans#4.2: %u'.PHP_EOL, md5WithZeroPrefix($input, 6));