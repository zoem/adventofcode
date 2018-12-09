<?php

$input = file_get_contents('day04.txt');
$lines = explode(PHP_EOL, trim($input));

sort($lines);

/** @var \DateTimeImmutable $prevDate */
$id = null;
$guards = [];
$prevDate = null;

foreach ($lines as $line) {
    $date = new \DateTimeImmutable(substr($line, strpos($line, '[') + 1, strpos($line, ']') - 1));

    if (preg_match('~#(?<id>\d+)~', $line, $match) && isset($match['id'])) {
        $id = $match['id'];
    } elseif (strpos($line, 'falls asleep') !== false) {
        $prevDate = $date;
    } elseif (strpos($line, 'wakes up') !== false) {
        $currentDate = $prevDate;
        while ($currentDate < $date) {
            @$guards[$id][$currentDate->format('i')]++;
            $currentDate = $currentDate->modify('+1 min');
        }
    }
}

$maxSum  = $maxFreq = null;
$part1Id = $part2Id = null;

foreach ($guards as $id => $guard) {
    $sum = array_sum($guard);
    if ($maxSum === null || $sum > $maxSum) {
        $maxSum = $sum;
        $part1Id = $id;
    }

    $freq = $guard ? max($guard) : 0;
    if ($maxFreq === null || $freq > $maxFreq) {
        $maxFreq = $freq;
        $part2Id = $id;
    }
}


printf('ans#3.1: %d'.PHP_EOL, $part1Id * array_search(max($guards[$part1Id]), $guards[$part1Id]));
printf('ans#3.2: %d'.PHP_EOL, $part2Id * array_search(max($guards[$part2Id]), $guards[$part2Id]));