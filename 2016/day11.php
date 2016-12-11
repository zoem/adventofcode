<?php
$input = file_get_contents('day11.txt');

$lines = array_filter(array_map('trim', explode(PHP_EOL, $input)));

$floors = [];

$state = ['E' => 3];
$map = [];
$letters = range('A', 'Z');

foreach ($lines as $floor => $line) {
    $num = preg_match_all("#(?<iso>[a-z]+)(?:-compatible)?\s(?<type>generator|microchip)#i", $line, $match);
    
    for ($i=0; $i<$num; $i++) {
        $type = $match['type'][$i] == 'generator' ? 'G' : 'M';
        $iso  = $match['iso'][$i];
        
        if (!isset($map[$iso])) {
            $map[$iso] = array_shift($letters);
        }

        // floor 1 => 3, floor 4 => 3
        // this way the sum of the state vector will be 0 when the puzzle is solved
        $key = $map[$iso] . $type;
        $state[$key] = 3 - $floor;
    }
}

function findMinimum(array $state) {
    $states = [$state];

    $i = 1;
    $seen = [];

    while ($i < 100) {
        $newStates = [];
         $s = microtime(1);
        
        foreach ($states as $state) {
            
            $floorToItems = [];
            foreach ($state as $item => $floor) {
                if ($item != 'E') {
                    $floorToItems[$floor][] = $item;
                }
            }
            $combinations = [];
            $items = $floorToItems[$state['E']];

            // determine combinations
            foreach ([1,2] as $size) {
                foreach (combinations($size, $items) as $combo) {
                    if (count($combo) == 2 && $combo[0][0] != $combo[1][0] && $combo[0][1] != $combo[1][1]) {
                        // dont move a chip/generation combination when the isotopes differ
                        continue;
                    }
                    elseif (count($combo) == 2 && $combo[0][1] == $combo[1][1]) {
                        // dont move two items of the same type
                        continue;
                    }
                    else {
                        $combinations[] = $combo;
                    }
                }
            }
            
            foreach ($combinations as $combo) {
                // move elevator up (-1) or down (+1)
                foreach ([-1,1] as $incr) {
                    // dont move any matching M+G down
                    if ($incr == 1 && count($combo) == 2 && $combo[0][0] == $combo[1][0]) {
                        continue;
                    }

                    $newState = $state;
                    $newState['E'] += $incr;

                    foreach ($combo as $item) {
                        $newState[$item] += $incr;
                    }

                    // only proceed when valid state
                    if (isValidState($newState)) {
                        $key = generateSeenKey($newState);
                        if (isset($seen[$i][$key]) || isset($seen[$i-1][$key])) {
                            continue;
                        }
                        
                        $seen[$i][$key] = true;
                                 
                        // dont move items to the floor below when its empty
                        if ($incr == 1 && !isset($floorToItems[$newState['E']])) {
                            continue;
                        }
                        
                        if (array_sum($newState) == 0) {
                            return $i; // THE ANSWER
                        }
                        
                        if ($incr == -1) {
                            // when going upstairs move 2 items at once instead of 1-by-1
                            foreach ($combo as $item) {
                                $singleItemState = $newState;
                                $singleItemState[$item] -= $incr;
                                $singleItemStateKey = json_encode($singleItemState);
                                if (isset($newStates[$singleItemStateKey])) {
                                    unset($newStates[$singleItemStateKey]);
                                }
                            }
                        } elseif ($incr == 1) {
                            // when going downstairs move only 1 item instead of 2
                            $do = true;
                            foreach ($combo as $item) {
                                $singleItemState = $newState;
                                $singleItemState[$item] -= $incr;
                                $singleItemStateKey = json_encode($singleItemState);
                                $do = $do && isset($newStates[$singleItemStateKey]);
                            }
                            
                            if ($do) {
                                continue 2;
                            }
                        }
                        
                        // add state 
                        $newStateKey = json_encode($newState);
                        $newStates[] = $newState;
                    }
                }
            }
        }

        $states = $newStates;
        
        unset($seen[$i]);
        
        $i++;
    }
}

function generateSeenKey(array $state) {
    static $cache = [];
    
    $cacheKey = json_encode($state);
    
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }
    
    $key = [];
    foreach ($state as $item => $floor) {
        if ($item != 'E') {
            $key[] = $floor . $item;
        }
    }
    
    sort($key);
    
    $letters = range('A','Z');
    $map = [];
    foreach ($key as $i => $str) {
        // give each isotope a letter from the alphabet
        if (!isset($map[$str[1]])) {
            $map[$str[1]] = array_shift($letters);
        }
        
        // replace first letter
        $letter = $map[$str[1]];
        $key[$i][1] = $letter;
    }
    
    $cache[$cacheKey] = implode(';',$key);
    
    return $cache[$cacheKey];
}

/** @see http://stackoverflow.com/questions/25610919/all-combinations-without-repetitions-with-specific-cardinality?answertab=votes#tab-top */
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

function isValidState(array $state) {
    static $cache = [];
    
    // not valid when out of bounds
    if (in_array(-1, $state)) return false;
    if (in_array( 4, $state)) return false;
    
    $cacheKey = json_encode($state);
    
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }
    
    // remove elevator from state
    unset($state['E']);
    
    $map = $filtered = [];
    
    foreach ($state as $item => $floor) {
        $type = $item[1] == 'G' ? 'M' : 'G';
        
        $otherItem = $item[0] . $type;
        if ($state[$otherItem] != $state[$item]) {
            // only add items which are not paired
            $filtered[$item] = $floor;
            $map[$floor][$item] = true;
        }
    }
    
    foreach ($filtered as $item => $floor) {
        foreach ($map[$floor] as $item2 => $v) {
            if ($item[0] != $item2[0] && $item[1] != $item2[1]) {
                // invalid when the remaining items is an incompatible chip/generator combination
                $cache[$cacheKey] = false;
                return false;
            }
        }
    }
    
    $cache[$cacheKey] = true;
    return true;
}

$min = findMinimum($state);
printf('ans#11.1: %u'.PHP_EOL, $min);
printf('ans#11.2: %u'.PHP_EOL, $min + 2 * 12);