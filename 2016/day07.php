<?php

$input = file_get_contents('day07.txt');

$lines = array_filter(array_map('trim', explode(PHP_EOL, $input)));

$regex = "\w*((\w)((?!\\2)\w)\\3\\2)\w*";

$count = 0;
foreach ($lines as $line) {
    // skip when there is any ABBA between square brackets
    if (preg_match_all("#\[{$regex}\]#", $line, $matches)) {
        continue;
    }
    // skip when there is no ABBA outside square brackets
    if (!preg_match_all("#[^\[]?{$regex}[^\]]?#", $line, $matches)) {
        continue;
    }
    
    $count++;
}

printf('ans#7.1: %u'.PHP_EOL, $count);

$count = 0;
foreach ($lines as $line) {
    // find all ABA's (index 0) and BAB's (index 1)
    $matches = array_fill(0, 2, []);
    $type = 0;
    for ($i=0;$i<strlen($line)-2;$i++) {
        // check if inside or outside square backets
        if ($line[$i+2] == '[') {
            $type = 1; $i += 2; continue;
        } elseif ($line[$i+2] == ']') {
            $type = 0; $i += 2; continue;
        }
        
        // valid ABA?
        if ($line[$i] == $line[$i+2] && $line[$i+1] != $line[$i]) {
            $matches[$type][] = substr($line, $i, 3);
        }
    }
    
    // reverse all ABA's 
    $matches[2] = array_map(function ($v) {
        return $v[1] . $v[0] . $v[1];
    }, $matches[1]);
    
    // SSL when any ABA and BAB match
    if (array_intersect($matches[0], $matches[2])) {
        $count++;
    }
}

printf('ans#7.2: %u'.PHP_EOL, $count);























