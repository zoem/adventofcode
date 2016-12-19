<?php

$input = trim(file_get_contents('day19.txt'));

printf('ans#19.1: %s'.PHP_EOL, 2 * $input - (pow(2, floor(log($input, 2)) + 1) - 1));

$t = floor(log($input, 3));
$pow = pow(3, $t);
if ($pow == $input) {
    $elf = $input;
} elseif (2 * $pow - $input > 0) {
    $elf = $input - $pow;
} else {
    $elf = 2 * $input - 3 * $pow;
}
    
printf('ans#19.2: %s'.PHP_EOL, $elf);