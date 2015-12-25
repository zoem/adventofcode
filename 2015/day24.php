<?php

$input = file_get_contents('day24.txt');
$lines = explode(PHP_EOL, $input);
$numbers = array_map('intval', array_filter($lines));

function findNumbers($numbers, $target, $set = [], &$minCount = PHP_INT_MAX, &$minProduct = PHP_INT_MAX) {
    $count  = count($set);
    $result = false;
    
    if ($count < $minCount) {
        while ($value = array_pop($numbers)) {
            if ($value < $target) {
                $newSet   = $set;
                $newSet[] = $value;
                
                // reduce $target by $value and do another run
                $newResult = findNumbers($numbers, $target - $value, $newSet, $minCount, $minProduct);
                
                if ($newResult) {
                    $result = $newResult;
                }
            }
            elseif ($value == $target) {
                $set[]   = $value;
                $count   = $count + 1;
                $product = array_product($set);
                
                // set current set as best when it has fewer numbers or has a lower product
                if ($count < $minCount || $count == $minCount && $product < $minProduct) {
                    $minCount   = $count;
                    $minProduct = $product;
                    return $set;
                }
            }
        }
    }
    
    return $result;
}

printf('ans#24.1: %u'.PHP_EOL, array_product(findNumbers($numbers, array_sum($numbers)/3)));
printf('ans#24.2: %u'.PHP_EOL, array_product(findNumbers($numbers, array_sum($numbers)/4)));