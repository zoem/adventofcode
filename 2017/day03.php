<?php

$input = file_get_contents('day03.txt');

/* part 1 */

// find closest odd square n^2
$n = ceil(sqrt($input)) | 1;

// steps required to reach the square in which the number is located
$y = $n >> 1;

// calc max square value
$max = pow($n, 2);

// steps required to reach the actual value in the square
$x = abs($y - ($max - $input) % ($n - 1));

printf('ans#3.1: %d'.PHP_EOL, $x + $y);

/* part 2 */

// initial values
$grid = [];
$x = $y = $dy = 0;
$grid[0][0] = $dx = $i = 1;
$deltas = [[-1,-1], [ 1, 1], [ 0, 1], [ 0,-1], [ 1,-1], [ 1, 0], [ -1, 0], [-1,1]];

do {
    $i++;
    // find closest odd square n^2
    $n = ceil(sqrt($i)) | 1;
    // determine max square value
    $max = pow($n, 2);
    
    // move along grid
    $x += $dx;
    $y += $dy;
    
    // sum all existing adjacent values 
    $sum = 0;
    foreach ($deltas as $delta) {
        $y1 = $y + $delta[0];
        $x1 = $x + $delta[1];
        $sum += $grid[$y1][$x1] ?? 0;
    }
    
    $grid[$y][$x] = $sum;
    
    if (($i - 1) % ($n - 1) == 0 && $i != $max) {
        // reached a corner
        $direction = ($max - $i) / ($n - 1);
    } elseif ($i - 1 == pow($n-2, 2)) {
        // start new square
        $direction = 0;
    } else {
        // dont change direction
        $direction = -1;
    }
  
    // determine dx and dy
    switch ($direction) {
        case 3: $dx = -1; $dy = 0;  break; // left
        case 2: $dx = 0;  $dy = -1; break; // down
        case 1: $dx = 1;  $dy = 0;  break; // right
        case 0: $dx = 0;  $dy = 1;  break; // up
    }
}
while ($sum < $input);

printf('ans#3.2: %d'.PHP_EOL, $sum);