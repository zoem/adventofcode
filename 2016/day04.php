<?php

$input = file_get_contents('day04.txt');

$splitter = function ($value) {
    preg_match('~^(?<name>[a-z\-]+)-(?<sector>\d+)\[(?<checksum>.+)\]$~i', trim($value), $match);
    return array_filter($match, 'is_string', ARRAY_FILTER_USE_KEY);
};

$rooms = array_map($splitter, array_filter(explode(PHP_EOL, $input)));

$posToChar = range('a', 'z');
$charToPos = array_flip($posToChar);

$sectorSum = 0;
$northPoleObjects = 0;

foreach ($rooms as $room) {
    // count occurrences of all characters in the room name
    $chars  = str_split(str_replace('-', '', $room['name']));
    $counts = array_count_values($chars);

    // sort
    array_multisort(
        array_values($counts), SORT_DESC, // sort by most common letters
        array_keys($counts)  , SORT_ASC,  // then sort ties alphabetically
        $counts
    );
    
    // create checksum, which consists of the five most common letters
    $checksum = implode('', array_slice(array_keys($counts), 0, 5));
    
    // decoy room!
    if ($room['checksum'] !== $checksum) {
        continue;
    }
    
    $sectorSum += $room['sector'];
    
    if ($room['sector'] > 26) {
        $shift = $room['sector'] % 26;
    } else {
        $shift = $room['sector'];
    }
    
    $decrypted = '';
    
    foreach ($chars as $char) {
        $pos = $charToPos[$char] + $shift;
        $pos = $pos > 25 ? $pos % 26 : $pos;
        $decrypted .= $posToChar[$pos];
    }
    
    if ($decrypted == 'northpoleobjectstorage') {
        $northPoleObjects = $room['sector'];
    }
}

printf('ans#4.1: %u'.PHP_EOL, $sectorSum);
printf('ans#4.2: %u'.PHP_EOL, $northPoleObjects);