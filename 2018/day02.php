<?php

$input = file_get_contents('day02.txt');
$lines = explode(PHP_EOL, trim($input));
$count = [2 => 0, 3 => 0];
$part2 = '';

foreach ($lines as $i => $line) {
    foreach (array_intersect(array_keys($count), count_chars($line, 1)) as $value) {
        $count[$value]++;
    }

    // match all next lines
    for ($j = $i+1; $j < count($lines); $j++) {
        // length must be equal
        if (strlen($line) !== strlen($lines[$j])) {
            continue;
        }

        $diff = 0;
        $str  = '';
        for ($k = 0; $k < strlen($line); $k++) {
            if ($line{$k} === $lines[$j]{$k}) {
                // append matching char
                $str .= $line{$k};
            } else {
                // count different chars
                $diff++;
            }
        }

        if ($diff == 1) {
            $part2 = $str;
            break;
        }
    }
}

printf('ans#2.1: %d'.PHP_EOL, array_product($count));
printf('ans#2.2: %s'.PHP_EOL, $part2);