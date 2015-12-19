<?php

// process input
$input = file_get_contents('day19.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);
$medicine = array_pop($lines);

$map = [];

foreach ($lines as $i => $line) {
    $words = explode(' ', $line);
       
    $map[$words[0]][] = $words[2];
}

$numAtoms = preg_match_all('~([A-Z][a-z]?)~', $medicine, $atoms);
$numRn = substr_count($medicine, 'Rn');
$numAr = substr_count($medicine, 'Ar');
$numY  = substr_count($medicine, 'Y');

$atoms = $atoms[0];

$combinations = [];
foreach ($atoms as $i => $atom) {
    if (!isset($map[$atom])) {
        continue;
    }
    
    foreach ($map[$atom] as $replacement) {
        $atomsCopy = $atoms;
        $atomsCopy[$i] = $replacement;
        $molecule = implode($atomsCopy);
        
        $combinations[$molecule] = true;
    }
}

printf('ans#19.1: %u'.PHP_EOL, count($combinations));
printf('ans#19.2: %u'.PHP_EOL, $numAtoms - $numRn - $numAr - 2 * $numY - 1);