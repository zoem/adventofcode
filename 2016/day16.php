<?php

$input = trim(file_get_contents('day16.txt'));

function getChecksum($input, $length) {
    $bits = $input;

    while (strlen($bits) < $length) {
        $bits .= '0' . strtr(strrev($bits), [1,0]);
    }

    $bits = substr($bits, 0, $length);
    
    do {
        $checksum = '';
        for ($i=1; $i<strlen($bits); $i+=2) {
            $checksum .= $bits[$i-1] == $bits[$i] ? 1 : 0;
        }
        $bits = $checksum;
    }
    while(strlen($checksum) % 2 == 0);
    
    return $checksum;
}

printf('ans#16.1: %s'.PHP_EOL, getChecksum($input, 272));
printf('ans#16.2: %s'.PHP_EOL, getChecksum($input, 35651584));