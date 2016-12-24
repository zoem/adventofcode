<?php

$input = file_get_contents('day24.txt');
$grid  = array_filter(array_map('trim', explode(PHP_EOL, $input)));

function getSteps(array $grid, array $a, array $b) {
    $states = [$a];
    $steps  = 0;
    $directions = [[0,1],[1,0],[0,-1],[-1,0]];
    
    while (true) {
        $steps++;
        $newStates = [];
        $seen = [];
        
        foreach ($states as $state) {
            foreach ($directions as $direction) {
                $x = $state[0] + $direction[0];
                $y = $state[1] + $direction[1];
                
                if (isset($seen[$x][$y])) {
                    continue; // already visited
                }
                
                if ($grid[$y][$x] == '#') {
                    continue; // wall or out of bounds
                }
                
                if ($x == $b[0] && $y == $b[1]) {
                    return $steps; // shortest path
                }
                
                $seen[$x][$y] = true;
                $newStates[]  = [$x, $y];
            }
        }
        
        $states = $newStates;
    }
}

function getShortestPath($start, array $steps, array $points, $return = false) {
    $distances = [];
    foreach (permutations($points) as $permutation) {
        $distance = 0;
        $prev = $start;
        
        // return to starting point?
        if ($return) {
            $permutation[] = $start;
        }
        
        foreach ($permutation as $point) {
            $sort = [$prev, $point];
            sort($sort);
            $distance += $steps[$sort[0]][$sort[1]];
            $prev = $point;
        }
        
        $distances[] = $distance;
    }

    return min($distances);
}

/** @see http://stackoverflow.com/a/25611822 */
function combinations($m, array $a) {
    if (!$m) {
        yield [];
        return;
    }
    if (!$a) {
        return;
    }
    $h = $a[0];
    $t = array_slice($a, 1);
    foreach (combinations($m - 1, $t) as $c) {
        yield array_merge([$h], $c);
    }
    foreach (combinations($m, $t) as $c) {
        yield $c;
    }
}

/** @see http://stackoverflow.com/a/27160465 */
function permutations(array $elements) {
    if (count($elements) <= 1) {
        yield $elements;
    } else {
        foreach (permutations(array_slice($elements, 1)) as $permutation) {
            foreach (range(0, count($elements) - 1) as $i) {
                yield array_merge(
                    array_slice($permutation, 0, $i),
                    [$elements[0]],
                    array_slice($permutation, $i)
                );
            }
        }
    }
}

// find all points of interest in the input grid
$points = [];
foreach ($grid as $y => $line) {
    if (preg_match_all('(\d+)', $line, $match)) {
        foreach ($match[0] as $num) {
            $x = strpos($line, $num);
            if ($x !== false) {
                $points[$num] = [$x, $y];
            }
        }
    }
}

sort($points);

// find the shortest paths between every combination of two points
$steps = [];
foreach (combinations(2, array_keys($points)) as $p) {
    $steps[$p[0]][$p[1]] = getSteps($grid, $points[$p[0]], $points[$p[1]]);
}

// exclude 0 since it's the starting point
$values = array_values(array_diff(array_keys($points), [0]));

printf('ans#24.1: %s'.PHP_EOL, getShortestPath(0, $steps, $values, false));
printf('ans#24.2: %s'.PHP_EOL, getShortestPath(0, $steps, $values, true));