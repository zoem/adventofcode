<?php

$input = trim(file_get_contents('day14.txt'));

function get64thKey($salt, $stretches = 0) {
    $i = 1;
    $triplets = $found = [];
    
    // loop until the 64 lowest(!) keys have been found
    while (count($found) < 64 || (count($found) >= 63 && min(array_keys($triplets)) < $found[63])) {
        
        $hash = md5($salt . $i);
        
        // key stretching
        for ($j=0; $j<$stretches; $j++) {
            $hash = md5($hash);
        }
        
        // evaluate triplets from earlier iterations
        foreach ($triplets as $iter => $triplet) {
            if ($i - $iter <= 1000) {
                if (preg_match(sprintf('#[^%1$s]?[%1$s]{5}[^%1$s]?#', $triplet), $hash)) {
                    // matching quintuple found
                    $found[] = $iter;
                    unset($triplets[$iter]);
                }
            } else {
                // remove triplet when quintuple not found within 1000 iterations
                unset($triplets[$iter]);
            }
        }
        
        // find possible triplet in current hash
        if (preg_match('#(?<set>([a-z0-9])\\2{2,}?)#', $hash, $match)) {
            $triplets[$i] = $match['set'][0];
            printf('#%u: %s (%s)' . PHP_EOL, $i, $match['set'], $hash);
        }
        
        $i++;
    }
    
    // reorder, since the keys are not necessarily listed from lowest to highest
    sort($found);
    
    return $found[63];
}

printf('ans#14.1: %d'.PHP_EOL, get64thKey($input));
printf('ans#14.2: %d'.PHP_EOL, get64thKey($input, 2016));