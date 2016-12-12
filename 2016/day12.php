<?php

$input = file_get_contents('day12.txt');
$stack = array_filter(array_map('trim', explode(PHP_EOL, $input)));

function compute($stack, array $defaults = []) {
    $regs = $defaults + array_fill_keys(range('a', 'd'), 0);
    $i = 0;
    while ($i < count($stack)) {
        $parts = explode(' ', $stack[$i]);
        
        switch ($parts[0]) {
            case 'cpy': 
                $regs[$parts[2]] = isset($regs[$parts[1]]) ? $regs[$parts[1]] : $parts[1];
                break;
            case 'inc': $regs[$parts[1]]++; break;
            case 'dec': $regs[$parts[1]]--; break;
            case 'jnz': 
                $value = isset($regs[$parts[1]]) ? $regs[$parts[1]] : $parts[1];
                if ($value <> 0) {
                    $i += $parts[2];
                    continue 2; 
                }
                break;
        }
        
        $i++;
    }
    
    return $regs;
}

printf('ans#12.1: %u'.PHP_EOL, compute($stack)['a']);
printf('ans#12.2: %u'.PHP_EOL, compute($stack, ['c' => 1])['a']);