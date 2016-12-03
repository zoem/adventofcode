<?php

$input = file_get_contents('day03.txt');

$splitter = function ($value) {
    return preg_split('~\s+~', trim($value));
};

$horizontal = array_map($splitter, array_filter(explode(PHP_EOL, $input)));

function triangles(array $triangles) : int {
    $count = 0;
    foreach ($triangles as $triangle) {
        sort($triangle);
        
        if ($triangle[0] + $triangle[1] > $triangle[2]) {
            $count++;
        }
    }
    
    return $count;
}

// Part 1
printf('ans#3.1: %u'.PHP_EOL, triangles($horizontal));

// Part 2
$vertical = array_chunk(array_merge(
    array_column($horizontal, 0),
    array_column($horizontal, 1),
    array_column($horizontal, 2)
), 3);

printf('ans#3.2: %u'.PHP_EOL, triangles($vertical));