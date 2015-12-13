<?php

$input = file_get_contents('day06.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$gridOn = [];
$gridB  = [];

// P1
$s = microtime(1);
foreach ($lines as $line) {
    preg_match('~^(?<type>[^\d]+)\s(?<x1>\d+),(?<y1>\d+)[^\d]+(?<x2>\d+),(?<y2>\d+)$~', $line, $m);
    
    $type = trim($m['type']);
    
    // on/off
    $on = true;
    $b = 0;
    
    switch ($type) {
        case 'turn on' : $on = true;  $b += 1; break;
        case 'turn off': $on = false; $b -= 1; break;
        case 'toggle'  : $on = null;  $b += 2; break;
    }
    
    for ($x=$m['x1']; $x<=$m['x2']; $x++) {
        for ($y=$m['y1']; $y<=$m['y2']; $y++) {
            // on/off
            if (is_bool($on)) {
                $gridOn[$x][$y] = $on;
            }
            else {
                $gridOn[$x][$y] = isset($gridOn[$x][$y]) ? !$gridOn[$x][$y] : true;
            }
            
            // brightness
            if (!isset($gridB[$x][$y])) {
                $gridB[$x][$y] = 0;
            }
            
            $gridB[$x][$y] = max($gridB[$x][$y] + $b, 0);
        }
    }
}
var_dump(microtime(1)-$s);
$on = 0;
$b  = 0;
for ($x=0; $x<1000; $x++) {
    for ($y=0; $y<1000; $y++) {
        if (isset($gridOn[$x][$y]) && $gridOn[$x][$y]) {
            $on++;
        }
        
        if (isset($gridB[$x][$y])) {
            $b += $gridB[$x][$y];
        }
    }
}

printf('ans#6.1: %u'.PHP_EOL, $on);
printf('ans#6.2: %u'.PHP_EOL, $b);