<?php

$input = file_get_contents('day04.txt');

$phrases = explode(PHP_EOL, $input);

$dupes = $anagrams = 0;

foreach ($phrases as $phrase) {
    $words = explode(' ', trim($phrase));
    $unique = array_unique($words);
    
    // check for non-unique words
    if (count($words) != count($unique)) {
        $dupes++;
        $anagrams++;
        continue;
    }
    
    // sort all word characters alphabetically
    $sorted = array_map(function($word) {
        $chars = str_split($word);
        sort($chars);
        return implode($chars);
    }, $unique);
    
    // check for non-unique words
    if (count($unique) != count(array_unique($sorted))) {
        $anagrams++;
    }
}

printf('ans#4.1: %d'.PHP_EOL, count($phrases) - $dupes);
printf('ans#4.2: %d'.PHP_EOL, count($phrases) - $anagrams);