<?php

$input = file_get_contents('day21.txt');
preg_match_all('~(\d+)~', $input, $matches);

$boss = array_combine(['hp', 'damage', 'armor'], $matches[0]);
$player = [
    'hp' => 100,
    'cost' => 0,
    'damage' => 0,
    'armor' => 0
];

$shopdata['weapons'] = <<<STR
Dagger        8     4       0
Shortsword   10     5       0
Warhammer    25     6       0
Longsword    40     7       0
Greataxe     74     8       0
STR;

$shopdata['armor'] = <<<STR
Leather      13     0       1
Chainmail    31     0       2
Splintmail   53     0       3
Bandedmail   75     0       4
Platemail   102     0       5
STR;

$shopdata['rings'] = <<<STR
Damage +1    25     1       0
Damage +2    50     2       0
Damage +3   100     3       0
Defense +1   20     0       1
Defense +2   40     0       2
Defense +3   80     0       3
STR;

$items = [];
$stats = ['cost','damage','armor'];
$slots = ['weapons', 'armor', 'rings', 'rings'];
$emptySlot = 'None';

// map shop data to array
foreach ($shopdata as $type => $str) {
    $lines = explode(PHP_EOL, $str);
    
    // Add empty slot item, except for the weapons
    if ($type != 'weapons') {
        $items[$type][$emptySlot] = array_combine($stats, [0,0,0]);
    }
    
    foreach ($lines as $line) {
        $parts = preg_split('~\s{2,}~', $line);
        $name  = array_shift($parts);
        $items[$type][$name] = array_combine($stats, $parts);
    }
}

// set item options per slot
$list = [];
foreach ($slots as $slot) {
    $list[] = array_keys($items[$slot]);
}

$min = 1e6;
$max = 0;

foreach (combinations($list) as $helditems) {    
    if ($helditems[2] == $helditems[3] && $helditems[2] !== $emptySlot) {
        continue;
    }

    $newPlayer = $player;

    foreach ($helditems as $i => $item) {
        foreach ($stats as $stat) { 
            $newPlayer[$stat] += $items[$slots[$i]][$item][$stat];
        }
    }
    
    $damBoss   = max(0, $newPlayer['damage'] - $boss['armor']);
    $damPlayer = max(0, $boss['damage'] - $newPlayer['armor']);
    
    // in order to win the player must:
    // - always inflict damage on the boss 
    // - not receive any damage or survive at least as many turns as the boss
    $playerWins = $damBoss > 0 && ($damPlayer == 0 || $newPlayer['hp'] / $damPlayer >= $boss['hp'] / $damBoss);
    
    if ($playerWins && $newPlayer['cost'] < $min) {
        $min = $newPlayer['cost'];
    }
    
    if (!$playerWins && $newPlayer['cost'] > $max) {
        $max = $newPlayer['cost'];
    }
}

printf('ans#21.1: %u'.PHP_EOL, $min);
printf('ans#21.2: %u'.PHP_EOL, $max);

function combinations($arrays, $i = 0) {
    if (!isset($arrays[$i])) {
        return [];
    }
    if ($i == count($arrays) - 1) {
        return $arrays[$i];
    }
 
    $c = combinations($arrays, $i + 1);
 
    $result = [];
 
    foreach ($arrays[$i] as $v) {
        foreach ($c as $t) {
            $result[] = is_array($t) ? array_merge([$v], $t) : [$v, $t];
        }
    }
 
    return $result;
}