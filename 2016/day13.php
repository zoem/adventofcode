<?php

$input = trim(file_get_contents('day13.txt'));

$maze = function($x, $y) use ($input) {
    $value = $x*$x + 3*$x + 2*$x*$y + $y + $y*$y + $input;
    return substr_count(decbin($value), 1) % 2 !== 0;
};

function findMin(Closure $maze, array $start, $end) {   
    $visited[implode(';', $start)] = true;
    $i = $t = 1;
    $states = [$start];
    
    while (count($states) > 0 && (is_array($end) || $i <= $end)) {
        $newStates = [];
        
        foreach ($states as $state) {
            foreach ([[1,0],[0,1],[-1,0],[0,-1]] as $move) {
                $new     = $state;
                $new[0] += $move[0];
                $new[1] += $move[1];
                $key     = implode(';', $new);
                
                if ($new[0] < 0 || $new[1] < 0) {
                    continue; // out of bounds
                } elseif ($maze($new[0], $new[1])) {
                    continue; // wall
                } elseif (isset($visited[$key])) {
                    continue; // visited
                } else {
                    $newStates[] = $new;
                    $visited[$key] = true;
                    $t++;
                    
                    if (is_array($end) && $new[0] == $end[0] && $new[1] == $end[1]) {
                        break 3; // stop when $end is a coordinate and it matches $new
                    }
                }
            }
        }

        $states = $newStates;
        $i++;
    }
    
    if (is_array($end)) {
        return $i; // return min steps
    } else {
        return count($visited); // return unique visited locations
    }
}

printf('ans#13.1: %d'.PHP_EOL, findMin($maze, [1,1], [31,39]));
printf('ans#13.2: %d'.PHP_EOL, findMin($maze, [1,1], 50));