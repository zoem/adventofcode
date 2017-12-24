<?php

$input = file_get_contents('day24.txt');
$lines = explode(PHP_EOL, trim($input));

$parts = [];
foreach ($lines as $i => $line) {
    $parts[$i] = $part = explode('/', trim($line));
}

function generate(array $parts, $end) : array {
    $list = [];
    foreach ($parts as $i => $part) {
        // check if the part connects to the current bridge end
        if (in_array($end, $part)) {
            list ($a, $b) = $part;
            // add the current bridge
            $list[] = $base = [
                'sum' => $a + $b,
                'length' => 1,
            ];
            // copy and remove the selected part
            $parts2 = $parts;
            unset($parts2[$i]);
            foreach (generate($parts2, $a == $end ? $b : $a) as $k => $r) {
                $list[] = [
                    'sum'    => $base['sum']    + $r['sum'],
                    'length' => $base['length'] + $r['length'],
                ];
            }
        }
    }
    
    return $list;
}

$results = generate($parts, 0);

$part1 = $part2 = ['sum' => 0, 'length' => 0];
foreach ($results as $result) { 
    if ($result['sum'] > $part1['sum']) {
        $part1 = $result;
    }
    
    if ($result['length'] > $part2['length'] || ($result['length'] == $part2['length'] && $result['sum'] > $part2['sum'])) {
        $part2 = $result;
    }
}

printf('ans#24.1: %d'.PHP_EOL, $part1['sum']);
printf('ans#24.2: %d'.PHP_EOL, $part2['sum']);