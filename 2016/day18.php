<?php

$input = trim(file_get_contents('day18.txt'));

function countTiles($input, $rows) {
    $input = '1' . strtr($input, ['^' => 0, '.' => 1]) . '1';

    $sum = substr_count($input, 1) - 2;
    for ($j=1; $j<$rows; $j++) {
        $row = '1';
        for ($i=1; $i<strlen($input)-1; $i++) {
            $x = 2 * $input[$i-1] + $input[$i+1];
            $row .= $x == 1 || $x == 2 ? 0 : 1;
        }
        $row .= '1';
        $input = $row;
        $sum += substr_count($input, 1) - 2;
    }
    return $sum;
}

printf('ans#18.1: %s'.PHP_EOL, countTiles($input, 40));
printf('ans#18.2: %s'.PHP_EOL, countTiles($input, 4E5));