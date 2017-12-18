<?php

$input = file_get_contents('day18.txt');
$lines = explode(PHP_EOL, trim($input));

$list = [];
foreach ($lines as $line) {
    $list[] = explode(' ', trim($line));
}

// part 1

$vars = array_fill_keys(range('a', 'z'), 0);

$get = function ($key) use (&$vars) {
    return is_numeric($key) ? $key : $vars[$key];
};

$count = count($list);
$i = $freq = 0;
do {
    $arg1 = $list[$i][1] ?? null;
    $arg2 = $list[$i][2] ?? null; 
    
    switch($list[$i][0]) {
        case 'set': $vars[$arg1]  = $get($arg2); break;
        case 'add': $vars[$arg1] += $get($arg2); break;
        case 'mul': $vars[$arg1] *= $get($arg2); break;
        case 'mod': $vars[$arg1] %= $get($arg2); break;
        case 'snd': $freq = $get($arg1); break;
        case 'jgz': $i += $vars[$arg1] > 0 ? $get($arg2) : 1; continue 2; 
        case 'rcv': 
            if ($get($arg1) > 0) { 
                break 2; 
            } else {
                break;
            }
    }
    
    $i++;
} while ($i > 0 && $i < $count);


printf('ans#18.1: %d'.PHP_EOL, $freq);

// part 2

$get = function ($p, $key) use (&$vars) {
    return is_numeric($key) ? $key : $vars[$p][$key];
};

$lock = [];
$queue = array_fill(0, 2, []);
$sent = $ii = array_fill(0, 2, 0);
$vars = array_fill(0, 2, array_fill_keys(range('a', 'z'), 0));
$vars[0]['p'] = 0;
$vars[1]['p'] = 1;

$foo = [];
do {
    for ($p=0; $p<2; $p++) {
        $i = $ii[$p];
        $arg1 = $list[$i][1] ?? null;
        $arg2 = $list[$i][2] ?? null; 

        switch($list[$i][0]) {
            case 'set': $vars[$p][$arg1]  = $get($p, $arg2); break;
            case 'add': $vars[$p][$arg1] += $get($p, $arg2); break;
            case 'mul': $vars[$p][$arg1] *= $get($p, $arg2); break;
            case 'mod': $vars[$p][$arg1] %= $get($p, $arg2); break;
            case 'snd': $sent[$p]++; $queue[$p == 0 ? 1 : 0][] = $get($p, $arg1); break;
            case 'jgz': $ii[$p] += $get($p, $arg1) > 0 ? $get($p, $arg2) - 1: 0; break;
            case 'rcv': 
                $input = array_shift($queue[$p]);
                
                if ($input === null) {
                    $lock[$p] = true;
                } else {
                    unset($lock[$p]);
                    $vars[$p][$arg1] = $input;
                }
                break;
        }
        
        // only update pointer when not locked
        $ii[$p] += isset($lock[$p]) ? 0 : 1;
    }
    
} while ($ii[0] > 0 && $ii[1] > 0 && $ii[0] < $count && $ii[1] < $count && count($lock) < 2);

printf('ans#18.2: %d'.PHP_EOL, $sent[1]);