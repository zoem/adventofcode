<?php

$input = trim(file_get_contents('day16.txt'));
$moves = explode(',', $input);

// rearrange move data
foreach ($moves as $i => $move) {
    switch ($move{0}) {
        case 's': $moves[$i] = [$move{0}, substr($move, 1)]; break;
        case 'x': 
        case 'p': $moves[$i] = [$move{0}, explode('/', substr($move, 1))]; break;
    }
}

$programs = range('a', 'p');
$programs = implode($programs);

// let's dance!
function dance($programs, array $moves, $iter) {
    $history = [];
    $len = strlen($programs);
    for ($i=0; $i<$iter; $i++) {
        // return early when a cycle has been detected
        if (isset($history[$programs])) {
            return array_search($iter % $i, $history);
        }
        
        $history[$programs] = count($history) ;
        
        foreach ($moves as $move) {
            switch ($move[0]) {
                case 's': 
                    $programs = substr($programs, 0-$move[1]) . substr($programs, 0, $len - $move[1]);
                    break;
                case 'x': 
                    $a = $programs{$move[1][0]};
                    $b = $programs{$move[1][1]};
                    $programs{$move[1][0]} = $b;
                    $programs{$move[1][1]} = $a;
                    break;
                case 'p': 
                    $programs = strtr($programs, [
                        $move[1][0] => $move[1][1],
                        $move[1][1] => $move[1][0],
                    ]);
                    break;
            }
        }
    }
    
    return $programs;
}

printf('ans#16.1: %s'.PHP_EOL, dance($programs, $moves, 1));
printf('ans#16.2: %s'.PHP_EOL, dance($programs, $moves, 1E9));