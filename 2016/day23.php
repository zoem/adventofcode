<?php

$input = file_get_contents('day23.txt');

$find = <<<F
cpy b c
inc a
dec c
jnz c -2
dec d
jnz d -5
F;

$replace = <<<R
mul a b d
cpy 0 c
cpy 0 c
cpy 0 c
cpy 0 c
cpy 0 d
R;

$input = str_replace($find, $replace, $input);
$lines = array_filter(array_map('trim', explode(PHP_EOL, $input)));
$stack = [];

foreach ($lines as $i => $line) {
    $stack[$i] = explode(' ', $line);
}

function compute(array $stack, array $defaults = []) {
    $reg = $defaults + array_fill_keys(range('a', 'd'), 0);
    $i = 0;
    while ($i < count($stack)) {
        $parts = $stack[$i];
        
        switch ($parts[0]) {
            case 'mul': 
                if (isset($reg[$parts[1]])) {
                    $a = isset($reg[$parts[2]]) ? $reg[$parts[2]] : $parts[2];
                    $b = isset($reg[$parts[3]]) ? $reg[$parts[3]] : $parts[3];
                    $reg[$parts[1]] = $a * $b;
                }
                break;
            case 'inc': if (isset($reg[$parts[1]])) $reg[$parts[1]]++; break;
            case 'dec': if (isset($reg[$parts[1]])) $reg[$parts[1]]--; break;
            case 'cpy':
                if (isset($reg[$parts[2]])) {
                    $reg[$parts[2]] = isset($reg[$parts[1]]) ? $reg[$parts[1]] : $parts[1];
                }
                break;
            case 'jnz': 
                $value = isset($reg[$parts[1]]) ? $reg[$parts[1]] : $parts[1];
                if ($value <> 0) {
                    $i += (isset($reg[$parts[2]]) ? $reg[$parts[2]] : $parts[2]);
                    continue 2; 
                }
                break;
                
            case 'tgl':
                $index = $i + (isset($reg[$parts[1]]) ? $reg[$parts[1]] : $parts[1]);
                if (isset($stack[$index])) {
                    switch (count($stack[$index])) {
                        case 2: $stack[$index][0] = $stack[$index][0] == 'inc' ? 'dec' : 'inc'; break;
                        case 3: $stack[$index][0] = $stack[$index][0] == 'jnz' ? 'cpy' : 'jnz'; break;
                    }
                }
                break;
        }
        $i++;
    }
    
    return $reg;
}

printf('ans#23.1: %s'.PHP_EOL, compute($stack, ['a' => 7])['a']);
printf('ans#23.2: %s'.PHP_EOL, compute($stack, ['a' => 12])['a']);