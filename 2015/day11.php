<?php


$input = file_get_contents('day11.txt');

$ans1 = nextPass($input);
printf('ans#11.1: %s'.PHP_EOL, $ans1);
$ans2 = nextPass($ans1);
printf('ans#11.2: %s'.PHP_EOL, $ans2);

function nextPass($input) {
    while (true) {
        $input++;
        
        $chars = str_split($input);
        if (array_intersect(['i','o','l'], $chars)) {
            continue;
        }
        
        $seq = false;
        $pairs = 0;
        $skip = 0;
        for ($j=1; $j<count($chars); $j++) {

            if ($skip !== $j && $chars[$j] === $chars[$j-1]) {
                $pairs++;
                $skip = $j+1;
            }
            
            if (!$seq && $j >= 2) {
                $one   = $chars[$j-2];
                $two   = $chars[$j-1];
                $three = $chars[$j]; 
                
                if (ord($one)+2 === ord($three) && ord($two)+1 === ord($three)) {
                    $seq = true;
                }
            }
        }
        
        if ($pairs < 2 || !$seq) {
            continue;
        }

        break;
    }
    
    return $input;
}