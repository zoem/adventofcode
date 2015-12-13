<?php

$input = file_get_contents('day07.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$eqs = []; 
foreach ($lines as $line) {
    $parts = explode(' ', $line);
    $values = [];
    $operator = null;
    $var = array_pop($parts);
    
    for ($i=0; $i<count($parts); $i++) {
        $operator = $parts[$i];
        switch ($operator) {
            case 'LSHIFT':
            case 'RSHIFT':
            case 'AND': 
            case 'OR':
                $values[] = $parts[$i-1];
                $values[] = $parts[$i+1];
                break 2;
            case 'NOT':
                $values[] = $parts[$i+1];
                break 2;
            case '->':
                $values[] = $parts[$i-1];
                break 2;
        }
    }
    
    if ($values) {
        $eqs[$var] = ['op' => $operator, 'values' => $values];
    }
}

function solve($eqs) {
    $solved = [];
    $solvedCount = -1;

    while (count($solved) > $solvedCount) {
        $solvedCount = count($solved);
        
        foreach ($eqs as $var => $eq) {
            if (isset($solved[$var])) {
                continue;
            }
            
            $solvable = true;
            $values = $eq['values'];
            
            foreach ($values as $value) {
                if (is_string($value) && !ctype_digit($value)) {
                    $solvable = false;
                    break;
                }
            }
            
            if ($solvable) {
                switch ($eq['op']) {
                    case 'LSHIFT': $solved[$var] = $values[0] << $values[1]; break;
                    case 'RSHIFT': $solved[$var] = $values[0] >> $values[1]; break;
                    case 'AND':    $solved[$var] = $values[0] &  $values[1]; break;
                    case 'OR':     $solved[$var] = $values[0] |  $values[1]; break;
                    case 'NOT':    $solved[$var] =  ~ $values[0]; break;
                    case '->':     $solved[$var] = $values[0]; break;
                }
                
                // substitute obtained value
                foreach ($eqs as $var2 => $eq2) {
                    if (isset($solved[$var2])) {
                        continue;
                    }
                    for ($j=0; $j<count($eqs[$var2]['values']); $j++) {
                        if ($eqs[$var2]['values'][$j] === $var) {
                            $eqs[$var2]['values'][$j] = $solved[$var];
                        }
                    }
                }
            }
        }   
    }
    
    return $solved;
}

$vars = solve($eqs);

printf('ans#7.1: %u'.PHP_EOL, $vars['a']);

$eqs['b']['values'] = [$vars['a']];

$vars = solve($eqs);

printf('ans#7.2: %u'.PHP_EOL, $vars['a']);