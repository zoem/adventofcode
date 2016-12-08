<?php

$input = file_get_contents('day08.txt');

$lines  = array_filter(array_map('trim', explode(PHP_EOL, $input)));
$pixels = array_fill(0, 6, array_fill(0, 50, ' '));

foreach ($lines as $j => $line) {
    $parts = explode(' ', $line);
    
    switch ($parts[0]) {
        case 'rect':
            // draw rectangle
            list($w, $h) = explode('x', $parts[1]);
            for ($i=0; $i<$h; $i++) {
                $pixels[$i] = array_fill(0, $w, '#') + $pixels[$i];
            }
            break;
            
        case 'rotate': 
            $index = explode('=', $parts[2])[1];
            
            switch ($parts[1]) {
                case 'row': 
                    // shift row
                    $pixels[$index] = array_shift_values($pixels[$index], $parts[4]);
                    break;
                    
                case 'column':
                    // shift column
                    $column = array_shift_values(array_column($pixels, $index), $parts[4]);
                    foreach ($pixels as $id => $row) {
                        $pixels[$id][$index] = $column[$id];
                    }
                    break;
            }
            break;
    }
}

function array_shift_values(array $array, $amount) {
    return array_merge(array_splice($array, -$amount), $array);
}

$count = array_reduce($pixels, function ($carry, $item) {
    return $carry + (array_count_values($item) + ['#' => 0])['#'];
}, 0);

printf('ans#8.1: %u'.PHP_EOL, $count);

// Part 2
print('ans#8.2:'.PHP_EOL);
foreach ($pixels as $i => $line) {
    echo implode('', $line) . PHP_EOL;
}