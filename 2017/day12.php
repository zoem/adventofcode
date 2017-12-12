<?php

$input = trim(file_get_contents('day12.txt'));
$lines = explode(PHP_EOL, $input);

// create a map
// use array keys instead of values, since it is much faster
$programs = [];
foreach ($lines as $line) {
    preg_match_all('~\d+~', $line, $matches);
    $key = array_shift($matches[0]);
    $programs[$key] = array_fill_keys($matches[0], true);
}

// start with program 0
$start = 0;
$remaining = $programs;
while (count($remaining)) {
    $groups[$start] = $programs[$start];
    $count = 0;
    // resolve pipes until the group does not change anymore
    while (count($groups[$start]) != $count) {
        $count = count($groups[$start]);
        foreach ($programs as $num => $deps) {
            // add to the current group when they are related
            if (array_intersect_key($deps, $groups[$start])) {
                $groups[$start][$num] = $num;
                $groups[$start] += $deps;
            }
        }
    }
    // remove the programs of the current group from the remaining list of programs
    $remaining = array_diff_key($remaining, $groups[$start]);
    // choose a program to start the next iteration with
    $start = key($remaining);
}

printf('ans#12.1: %d'.PHP_EOL, count($groups[0]));
printf('ans#12.2: %d'.PHP_EOL, count($groups));