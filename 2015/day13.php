<?php 

$input = file_get_contents('day13.txt');

$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$map = rulesToMap($lines);

printf('ans#13.1: %u'.PHP_EOL, happiness($map));

$me = 'Me';
foreach ($map as $person => $value) {
    $map[$person][$me] = 0;
    $map[$me][$person] = 0;
}

printf('ans#13.2: %s'.PHP_EOL, happiness($map));

function rulesToMap($lines) {
    $map = [];

    foreach ($lines as $line) {
        $words = explode(' ', $line);

        $person    = $words[0];
        $neighbour = substr(end($words), 0, -1);
        $sign      = $words[2] == 'gain' ? 1 : -1;
        $happiness = $words[3] * $sign;
        
        $map[$person][$neighbour] = $happiness;
    }
    
    return $map;
}

function happiness($map) {
    $solved = [];
    $ppl = array_keys($map);

    $start = $ppl[0];
    $scores = [];
    foreach (permutations(array_slice($ppl, 1)) as $set) {
        
        // skip counter-clockwise seating
        $key = implode('|', array_reverse($set)) . '|' . $start;
        if (isset($scores[$key])) {
            continue;
        }
        
        $set[] = $start;
        $left = $start;
        $key = implode('|', $set);

        $scores[$key] = 0;

        foreach ($set as $right) {
            $scores[$key] += $map[$left][$right];
            $scores[$key] += $map[$right][$left];
            $left = $right;
        }
    }
    
    //var_dump(count($scores));
    
    return max($scores);
}

function permutations(array $elements) {
    if (count($elements) <= 1) {
        yield $elements;
    } else {
        foreach (permutations(array_slice($elements, 1)) as $permutation) {
            foreach (range(0, count($elements) - 1) as $i) {
                yield array_merge(
                    array_slice($permutation, 0, $i),
                    [$elements[0]],
                    array_slice($permutation, $i)
                );
            }
        }
    }
}