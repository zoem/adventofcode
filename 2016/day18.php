<?php

$input = trim(file_get_contents('day18.txt'));

function countTiles($input, $rows) {
    $input = '1' . strtr($input, ['^' => 0, '.' => 1]) . '1';
    $input = str_split($input);

    $sum = array_sum($input);
    $max = count($input)-1;
    for ($j=1; $j<$rows; $j++) {
        $row = $input;

        for ($i=1; $i<$max; $i++) {
            $row[$i] = $input[$i-1] != $input[$i+1] ? 0 : 1;
        }

        $input = $row;
        $sum += array_sum($input);
    }

    return $sum  - $rows * 2;
}

printf('ans#18.1: %s'.PHP_EOL, countTiles($input, 40));
printf('ans#18.2: %s'.PHP_EOL, countTiles($input, 4E5));