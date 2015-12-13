<?php 

$input = file_get_contents('day05.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$vowels = ['a','e','i','o','u'];

// P1

$not = array('ab','cd','pq', 'xy');
$nice = 0;

foreach ($lines as $str) {
    $chars = str_split($str);
    
    $vowelCount = 0;
    $doubles = 0;
    foreach ($chars as $pos => $char) {
        
        if ($pos > 0 && in_array(($chars[$pos-1] . $char), $not)) {
            continue 2;
        }
        
        if (in_array($char, $vowels)) {
            $vowelCount++;
        }
        
        if ($pos > 0 && $char == $chars[$pos-1]) {
            $doubles++;
        }
    }
    
    if ($doubles > 0 && $vowelCount >= 3) {
        $nice++;
    }
}

printf('ans#5.1: %u'.PHP_EOL, $nice);

// P2

$nice = 0;

foreach ($lines as $str) {
    $chars = str_split($str);
    
    $pairs = 0;
    $repeats = 0;

    for ($pos=0; $pos<count($chars) - 1; $pos++) { 

        $pair1 = $chars[$pos+0] . $chars[$pos+1];
        
        for ($pos2=$pos+2; $pos2<count($chars) - 1; $pos2++) { 
            $pair2 = $chars[$pos2+0] . $chars[$pos2+1];
            if ($pair1 == $pair2) {
                $pairs++;
                break 2;
            }
        }
    }
    
    for ($pos=0; $pos<count($chars) - 2; $pos++) { 
        if ($chars[$pos] == $chars[$pos+2]) {
            $repeats++;
            break;
        }
    }

    if ($pairs > 0 && $repeats > 0) {
        $nice++;
    }
}

printf('ans#5.2: %u'.PHP_EOL, $nice);