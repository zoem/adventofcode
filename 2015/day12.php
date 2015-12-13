<?php

$input = file_get_contents('day12.txt');

$data = json_decode($input, false);


function run(array $data, $ignore = null) {
    $sum = 0;
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $sum += run($value, $ignore);
        }
        elseif (is_object($value)) {
            $vars = get_object_vars($value);
            
            if ($ignore === null || !in_array($ignore, $vars, true)) {
                $sum += run($vars, $ignore);
            }
        }
        elseif (is_int($value)) {
            $sum += $value;
        }
    }
    return $sum;
}

printf('ans#11.1: %s'.PHP_EOL, run($data));
printf('ans#11.1: %s'.PHP_EOL, run($data, 'red'));