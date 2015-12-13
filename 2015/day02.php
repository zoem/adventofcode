<?php 

$input = file_get_contents('day02.txt');

$lines = explode("\r\n", $input);
$lines = array_filter($lines);
$area = 0;
$ribbon = 0;

foreach ($lines as $line) {
    $dims = explode('x', $line);
    
    for ($i=0; $i<count($dims); $i++) {
        for ($j=0; $j<count($dims); $j++) {
            // skip same dimension
            if ($i == $j) {
                continue;
            }
            
            $area += $dims[$i] * $dims[$j];
        }
    }
    sort($dims);
    $area   += $dims[0] * $dims[1];
    $ribbon += array_product($dims) + $dims[0]*2 + $dims[1]*2;
}

printf('ans#2.1: %u'.PHP_EOL, $area);
printf('ans#2.2: %u'.PHP_EOL, $ribbon);