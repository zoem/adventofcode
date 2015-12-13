<?php 

$input = file_get_contents('day03.txt');

$moves = str_split($input);

function deliver($moves, $alternate = false) {
    $p = 1;
    $grid = ['0|0' => $p];
    $pos = array(0,0,0,0);
    
    foreach ($moves as $i => $move) {
        $x = 0;
        $y = 1;
        if ($alternate && $i % 2) {
            $x = 2;
            $y = 3;
        }
        
        switch ($move) {
            case '^': $pos[$y]++; break;
            case 'v': $pos[$y]--; break;
            case '>': $pos[$x]++; break;
            case '<': $pos[$x]--; break;
        }

        $key = "{$pos[$x]}|{$pos[$y]}";
        
        if (!isset($grid[$key])) {
            $grid[$key] = true;
            $p++;
        }
    }

    return $p;
}

printf('ans#3.1: %u'.PHP_EOL, deliver($moves));
printf('ans#3.2: %u'.PHP_EOL, deliver($moves, true));