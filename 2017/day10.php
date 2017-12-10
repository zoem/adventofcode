<?php

$input = trim(file_get_contents('day10.txt'));
$lengths = array_map('intval', explode(',', $input));
$list = range(0, 255);

function process(array $list, array $lengths, $rounds = 1) {
    $skip = $offset = 0;
    for ($j=0; $j<$rounds; $j++) {
        foreach ($lengths as $length) {
            $max = (int) ceil($length / 2);
            for ($i=0; $i<$max; $i++) {
                $idx1 = ($offset + $i) % count($list);
                $idx2 = ($offset + $length - $i - 1) % count($list);
                $v1 = $list[$idx1];
                $v2 = $list[$idx2];
                $list[$idx1] = $v2;
                $list[$idx2] = $v1;
            }
            
            $offset += $length + $skip;
            $skip ++;
        }
    }
    
    return $list;
}

$r = process($list, $lengths, 1);

printf('ans#10.1: %d'.PHP_EOL, $r[0] * $r[1]);

// part 2

$lenghts2 = [];
foreach (str_split($input) as $char) {
    $lenghts2[] = ord($char);
}
$lengths2 = array_merge($lenghts2, [17, 31, 73, 47, 23]);

$r = process($list, $lengths2, 64);
$chunks = array_chunk($r, 16);

$hash = '';
foreach ($chunks as $chunk) {
    $xor = $chunk[0];
    for ($i=1; $i<count($chunk); $i++) {
        $xor ^= $chunk[$i];
    }
    
    $hash .= str_pad(dechex($xor), 2, '0', STR_PAD_LEFT);
}

printf('ans#10.2: %s'.PHP_EOL, $hash);