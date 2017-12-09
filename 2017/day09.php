<?php

$input = trim(file_get_contents('day09.txt'));

$count = 0;
// remove canceled characters
$stream = preg_replace('~!.~', '', $input);

// remove garbage
$stream = preg_replace_callback('~<[^>]+>~', 'replace', $stream);

function replace($m) {
    $GLOBALS['count'] += strlen($m[0]) - 2; // don't count '<' and '>'
    return '';
}

$level = $score = 0;
for ($i=0; $i<strlen($stream); $i++) {
    if ($stream[$i] == '{') {
        $level++;
        $score += $level;
    } elseif ($stream[$i] == '}') {
        $level--;
    }
}

printf('ans#9.1: %d'.PHP_EOL, $score);
printf('ans#9.2: %d'.PHP_EOL, $count);