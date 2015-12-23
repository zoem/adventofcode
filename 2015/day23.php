<?php

// process input
$input = file_get_contents('day23.txt');
$lines = explode(PHP_EOL, $input);
$lines = array_filter($lines);

$instructions = [];

foreach ($lines as $i => $line) {
    $instructions[] = explode(' ', str_replace(',', '', trim($line)));
}

function process($registers, $instructions, $debug = false) {
    $i = 0;
    
    while ($i < count($instructions)) {
        $d = $instructions[$i];
        
        if ($debug) echo $i .': ['. implode(',', $registers) .'] '. implode(' ', $d) . PHP_EOL;
        
        switch ($d[0]) {
            case 'hlf': $registers[$d[1]] /= 2; break;
            case 'tpl': $registers[$d[1]] *= 3; break;
            case 'inc': $registers[$d[1]]++;    break;
            case 'jmp': $i += $d[1]; continue 2;
            case 'jie': 
                if ($registers[$d[1]] % 2 == 0) {
                    $i += $d[2]; continue 2;
                }
                break;
            case 'jio': 
                if ($registers[$d[1]] == 1) {
                    $i += $d[2]; continue 2;
                }
                break;
        }
        
        $i++;
    }
    
    return $registers;
}
printf('ans#23.1: %u'.PHP_EOL, process(['a' => 0, 'b' => 0], $instructions)['b']);
printf('ans#23.2: %u'.PHP_EOL, process(['a' => 1, 'b' => 0], $instructions)['b']);