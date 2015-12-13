<?php

$input = file_get_contents('day10.txt');

$chars = str_split($input);
$chars[] = '0';
$ans = [];

for ($j=1;$j<=50;$j++) {
    $chars = str_split($input);
    $chars[] = ' ';
    $str = '';
    $count = 0;
    $prev = $chars[0];
    foreach ($chars as $i => $char) {
        if ($prev === $char) {
            $count++;
        }
        elseif ($prev !== $char) {
            $str .= $count . $prev;
            $prev = $char;
            $count = 1;
        }
    }
    
    $input = $str;
    
    if ($j == 40 || $j == 50) {
        $ans[] = strlen($str);
    }
}

printf('ans#10.1: %u'.PHP_EOL, $ans[0]);
printf('ans#10.2: %u'.PHP_EOL, $ans[1]);