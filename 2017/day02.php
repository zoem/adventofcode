<?php

$input = file_get_contents('day02.txt');

$splitter = function ($value) {
    return preg_split('~\s+~', trim($value));
};

$input = array_map($splitter, explode(PHP_EOL, $input));

// part 1
$sum = array_reduce($input, function ($sum, $row) {
    return $sum += max($row) - min($row);
}, 0);

// part 2
$sum2 = array_reduce($input, function ($sum, $row) {
    $start = 1;
    foreach ($row as $number) {
        for ($i=$start; $i<count($row); $i++) {
            $a = max($number, $row[$i]);
            $b = min($number, $row[$i]);
            
            $sum += $a % $b == 0 ? $a / $b : 0;
        }
        $start++;
    }
    
    return $sum;
}, 0);

printf('ans#2.1: %d'.PHP_EOL, $sum);
printf('ans#2.2: %d'.PHP_EOL, $sum2);