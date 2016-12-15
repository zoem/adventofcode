<?php

$input = file_get_contents('day15.txt');
$lines = array_filter(explode(PHP_EOL, $input));
$discs = [];

foreach ($lines as $line) {
    preg_match_all('#(\d+)#', $line, $match);
    $discs[] = [
        'positions' => $match[0][1],  
        'position'  => $match[0][3]
    ];
}

function solve(array $discs) {
    $t = 0;
    while (true) {
        $t++;
        $sum = 0;
        
        foreach ($discs as $i => $disc) {
            $pos = ($t + 1 + $i + $disc['position']) % $disc['positions'];
            
            // skip current time when position is not 0 
            if ($pos > 0) {
                continue 2;
            }
            
            $sum += $pos;
        }
        
        // answer is obtained when sum of moduli is zero
        if ($sum === 0) {
            return $t;
        }
    }
}

printf('ans#15.1: %u'.PHP_EOL, solve($discs));

$discs[] = [
    'positions' => 11,  
    'position'  => 0
];

printf('ans#15.2: %u'.PHP_EOL, solve($discs));