<?php

$input = trim(file_get_contents('day05.txt'));

$password1 = '';
$password2 = [];

for ($i=0; $i<1E8; $i++) {
    $hash = md5($input . $i);

    if (substr($hash, 0, 5) === '00000') {
        // add 6th char to password #1
        if (strlen($password1) < 8) {
            $password1 .= $hash[5];
        }
        
        // add 7th char to password #2 when the 6th char represents a feasible position
        if (count($password2) < 8 && ctype_digit($hash[5]) && $hash[5] < 8 && !isset($password2[$hash[5]])) {
            $password2[$hash[5]] = $hash[6];
        }
        
        // stop when both passwords have been found
        if (strlen($password1) == 8 && count($password2) == 8) {
            break;
        }
    }
}

// put spaces on empty positions
$password2 = $password2 + array_fill(0, 8, ' ');

// sort keys in ascending order
ksort($password2);

printf('ans#5.1: %s'.PHP_EOL, $password1);
printf('ans#5.2: %s'.PHP_EOL, implode('', $password2));