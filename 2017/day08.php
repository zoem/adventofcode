<?php

$input = file_get_contents('day08.txt');
$lines = array_map('trim', explode(PHP_EOL, $input));

$a = [];
$max = 0;
foreach ($lines as $line) {
    $parts = explode(' ', $line);
    $s = [];
    $s[] = sprintf('isset($a["%s"]) ?: $a["%s"]=0', $parts[0], $parts[0]);
    $s[] = sprintf('isset($a["%s"]) ?: $a["%s"]=0', $parts[4], $parts[4]);
    $s[] = sprintf('!($a["%s"] %s %s) ?: $a["%s"] %s %s',
        $parts[4],
        $parts[5],
        $parts[6],
        $parts[0],
        $parts[1] == 'inc' ? '+=' : '-=',
        $parts[2]
    );

    eval(implode(';', $s) . ';');
    $max = max(max($a), $max);
}

printf('ans#8.1: %s'.PHP_EOL, max($a));
printf('ans#8.2: %d'.PHP_EOL, $max);