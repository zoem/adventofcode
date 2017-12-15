<?php

$input = trim(file_get_contents('day14.txt'));

// copied from 2017 day 10
function process(array $lengths, $rounds = 1) {
    $list = range(0, 255);
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

// copied from 2017 day 10
function knot_hash($input, $toBin = false) {
    $lenghts = [];
    foreach (str_split($input) as $char) {
        $lenghts[] = ord($char);
    }
    $lengths = array_merge($lenghts, [17, 31, 73, 47, 23]);

    $r = process($lengths, 64);
    $chunks = array_chunk($r, 16);

    $return = '';
    foreach ($chunks as $chunk) {
        $xor = $chunk[0];

        for ($i=1; $i<count($chunk); $i++) {
            $xor ^= $chunk[$i];
        }
        
        $return .= $toBin 
            ? str_pad(decbin($xor), 8, '0', STR_PAD_LEFT)
            : $hash .= str_pad(dechex($xor), 2, '0', STR_PAD_LEFT);
    }
    
    return $return;
}

function explore(array $grid) {
    $region = 1;
    $seen = [];
    foreach ($grid as $i => $row) {
        foreach ($row as $j => $v) {
            // skip free square
            if (!$v) {
                continue;
            }
            
            $key = "$i|$j";
            
            // skip used squares that have already been seen
            if (isset($seen[$key])) {
                continue;
            }
            
            // add current coordinate to the seen map and give it the current region value
            $seen[$key] = $region;
            
            // add the current coordinate to the queue
            $queue = [[$i,$j]];
            
            do {
                // pop last coordinate from queue
                $c = array_pop($queue);
                // look left/right and up/down to see if there are any adjacent used squares
                foreach ([[0,1], [1,0], [-1,0], [0, -1]] as $delta) {
                    $ii  = $c[0] + $delta[0];
                    $jj  = $c[1] + $delta[1];
                    $key = "$ii|$jj";
                    
                    // skip unused adjacent square and non-existing/seen coordinates
                    if (!isset($grid[$ii][$jj]) || !$grid[$ii][$jj] || isset($seen[$key])) {
                        continue;
                    }
                    
                    // add adjacent square to the queue
                    $queue[] = [$ii, $jj];
                    $seen[$key] = $region;
                }

            } while ($queue);
            
            // current region has been explored, increase the region number
            $region++;
        }
    }
    
    return $seen;
}

$map = [];
$sum = 0;
for ($i=0; $i<=127; $i++) {
    $binhash = knot_hash("$input-$i", true);
    $map[$i] = str_split($binhash);
    $sum += count(array_filter($map[$i]));
}

printf('ans#10.1: %s'.PHP_EOL, $sum);
printf('ans#10.1: %s'.PHP_EOL, max(explore($map)));