<?php

$input = file_get_contents('day16.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$known = [
'children'    => 3,
'cats'        => 7,
'samoyeds'    => 2,
'pomeranians' => 3,
'akitas'      => 0,
'vizslas'     => 0,
'goldfish'    => 5,
'trees'       => 3,
'cars'        => 2,
'perfumes'    => 1
];

$ppl = [];

foreach ($lines as $line) {
    $words = explode(' ', str_replace([',',':'], '', $line));

    for ($i=2; $i<count($words); $i=$i+2) {
        $ppl[$words[1]][$words[$i]] = $words[$i+1];
    }
}

$suspectP1 = 0;
$suspectP2 = 0;
foreach ($ppl as $id => $person) {
    $isSuspectP1 = true;
    $isSuspectP2 = true;
    foreach ($known as $key => $value) {
        $isSuspectP1 = $isSuspectP1 && (!isset($person[$key]) || $person[$key] == $value);
        
        if (in_array($key, ['cats', 'trees'])) {
            $isSuspectP2 = $isSuspectP2 && (!isset($person[$key]) || $person[$key] > $value);
        }
        elseif (in_array($key, ['pomeranians', 'goldfish'])) {
            $isSuspectP2 = $isSuspectP2 && (!isset($person[$key]) || $person[$key] < $value);
        }
        else {
            $isSuspectP2 = $isSuspectP2 && (!isset($person[$key]) || $person[$key] == $value);
        }
    }
    
    if ($isSuspectP1) {
        $suspectP1 = $id;
    }
    
    if ($isSuspectP2) {
        $suspectP2 = $id;
    }
}

printf('ans#16.1: %u'.PHP_EOL, $suspectP1);
printf('ans#16.2: %u'.PHP_EOL, $suspectP2);