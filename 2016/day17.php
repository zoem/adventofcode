<?php

$input = trim(file_get_contents('day17.txt'));

function findPath($input, $mode = 1) {
    $open    = array_fill_keys(range('b', 'f'), true);
    $states  = [[0, 0, 'path' => '']];
    $longest = null;
    $j = 1;
    
    while (count($states)) {
        $newStates = [];
        
        foreach ($states as $state) {
            
            $hash = substr(md5($input . $state['path']), 0, 4);
            
            for ($i=0; $i<4; $i++) {
                if (isset($open[$hash[$i]])) {
                    $newState = $state;
                    switch ($i) {
                        case 0: $newState[1]--; $newState['path'] .= 'U'; break; // up
                        case 1: $newState[1]++; $newState['path'] .= 'D'; break; // down
                        case 2: $newState[0]--; $newState['path'] .= 'L'; break; // left
                        case 3: $newState[0]++; $newState['path'] .= 'R'; break; // right
                    }
                    
                    // check bounds
                    if (min($newState) < 0 || max($newState) > 3) {
                        continue;
                    }
                    
                    if ($newState[0] == 3 && $newState[1] == 3) {
                        $answer = $newState + ['steps' => $j];
                        
                        if ($mode == 1) {
                            // Part 1: return shortest
                            return $answer;
                        } else {
                            // Part 2: obtain longest
                            $longest = $answer;
                            continue;
                        }
                    }
                    
                    $newStates[] = $newState;
                }
            }
        }

        $states = $newStates;
        $j++;
    }
    
    return $longest;
}

printf('ans#17.1: %s'.PHP_EOL, findPath($input, 1)['path']);
printf('ans#17.2: %s'.PHP_EOL, findPath($input, 2)['steps']);