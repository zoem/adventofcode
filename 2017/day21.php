<?php

$input = file_get_contents('day21.txt');
$lines = explode(PHP_EOL, trim($input));

$rules = [];
foreach ($lines as $line) {
    list ($from, $to) = explode(' => ', trim($line));
    $rules[$from] = $to;
}

$img = [
str_split('.#.'),
str_split('..#'),
str_split('###'),
];

function pattern(array $grid, $sep = '/') : string {
    return implode($sep, array_map('implode', $grid));
}

function variants(array $grid) : array {
    $v = [
        $grid,
        flip($grid)
    ];
    
    for ($i=1; $i<4; $i++) {
        $v[] = $r = rotate($grid, $i);
        $v[] = flip($r);
    }
    
    return $v;
}

function flip(array $grid, $horizontal = 0) : array {
    if ($horizontal) {
        return array_map('array_reverse', $grid);
    } else {
        return array_reverse($grid);
    }
}

function rotate(array $grid, $k = 1) : array {
    for ($j=0; $j<$k; $j++ ) {
        $r = [];
        for ($i=0; $i<count($grid); $i++) {
            $r[$i] = array_reverse(array_column($grid, $i));
        }
        
        $grid = $r;
    }
    return $grid;
}

function blocks(array $grid) : array {
    if (count($grid[0]) % 2 === 0) {
        $size = 2;
    } elseif (count($grid[0]) % 3 === 0) {
        $size = 3;
    } else {
        exit('wrong size');
    }
    
    if (count($grid) == $size) {
        return [$grid];
    }
    
    $n = (int) count($grid) / $size;
    $blocks = [];
    
    for ($i=0; $i<$n; $i++) {
        for($j=0; $j<$n; $j++) {
            $block = [];
            for ($k=0; $k<$size; $k++) {
                $block[] = array_slice($grid[$i * $size + $k], $j * $size, $size);
            }
            
            $blocks[] = ($block);
        }
    }
    
    return $blocks;
}

function enhance(array $img, array $rules, int $iter) : array {
    $seen = [];
    for ($i=0; $i<$iter; $i++) {
        $new = [];
        $blocks = blocks($img);
        foreach ($blocks as $n => $block) {
            $key = pattern($block);
            
            if (isset($seen[$key])) {
                $rows = $seen[$key];
            } else {
                foreach (variants($block) as $variant) {
                    $rule = false;
                    $pattern = pattern($variant);
                    if (isset($rules[$pattern])) {
                        $rule = $rules[$pattern];
                        break;
                    }
                }
                
                if (!$rule) {
                    exit('pattern not found');
                }
                
                $seen[$key] = $rows = explode('/', $rule);
            }
            
            $offset = (int) floor($n / sqrt(count($blocks))) * count($rows);
            foreach ($rows as $j => $row) {
                $new[$offset + $j] = isset($new[$offset + $j]) ? $new[$offset + $j] . $row : $row;
            }
        }
        
        $img = array_map('str_split', $new);
    }
    
    return $img;
}

function pixels(array $img) : int {
    $sum = 0;
    foreach ($img as $row) {
        $sum += array_count_values($row)['#'] ?? 0;
    }
    return $sum;
}

printf('ans#21.1: %d'.PHP_EOL, pixels(enhance($img, $rules, 5)));
printf('ans#21.2: %d'.PHP_EOL, pixels(enhance($img, $rules, 18)));