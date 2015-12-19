<?php

// process input
$input = file_get_contents('day18.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$grid = [];

foreach ($lines as $i => $line) {
    $chars = str_split($line);
    $grid[$i+1] = [];
    
    foreach ($chars as $j => $char) {
        $grid[$i+1][$j+1] = ($char === '#');
    }
}

$grid1 = updateGrid($grid, 100);
printf('ans#18.1: %u'.PHP_EOL, countLights($grid1));

// 4 corners stuck @ on
$stuck = [
    [1, 1],
    [1, count($grid)],
    [count($grid), 1],
    [count($grid), count($grid)]
];

foreach ($stuck as $xy) {
    $grid[$xy[0]][$xy[1]] = true;
}

$grid2 = updateGrid($grid, 100, $stuck);
printf('ans#18.2: %u'.PHP_EOL, countLights($grid2));

function updateGrid($grid, $num, $stuck = [], $show = false) {
    if ($num < 1) {
        return $grid;
    }
    
    $newGrid = $grid;
    $xnum = count($grid);
    $ynum = count(reset($grid));

    foreach ($grid as $i => $row) {
        foreach ($row as $j => $on) {

            if (in_array([$i,$j], $stuck)) {
                continue;
            }
            
            $minX = max(1, $i-1);
            $maxX = min($xnum, $i+1);
            $minY = max(1, $j-1);
            $maxY = min($ynum, $j+1);
        
            $neighbours = 0;
            
            for ($m = $minX; $m <= $maxX; $m++) {
                for ($n = $minY; $n <= $maxY; $n++) {
                    if ($m == $i && $n == $j) {
                        continue;
                    }
                    
                    $neighbours += $grid[$m][$n] ? 1 : 0;
                }
            }
            
            if ($on) {
                $newGrid[$i][$j] = ($neighbours == 2 || $neighbours == 3);
            }
            else {
                $newGrid[$i][$j] = ($neighbours == 3);
            }
        }
    }
    
    if ($show) { 
        printGrid($newGrid);
    }
    
    return updateGrid($newGrid, $num-1, $stuck, $show);
}

function countLights($grid) {
    $c = 0;
    foreach ($grid as $i => $row) {
        $c += array_sum($row);
    }
    
    return $c;
}

function printGrid($grid) {
    foreach ($grid as $row) {
        foreach ($row as $value) {
            echo $value ? '#' : '.';
        }
        echo PHP_EOL;
    }

    echo PHP_EOL . PHP_EOL;
}