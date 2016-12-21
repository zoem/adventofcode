<?php

$input = file_get_contents('day21.txt');
$lines = array_filter(array_map('trim', explode(PHP_EOL, $input)));

function scramble($password, array $lines) {
    foreach ($lines as $line) {
        $parts = explode(' ', $line);
        $instruction = $parts[0] .' '. $parts[1];
        
        switch ($instruction) {
            case 'swap position': 
                $a = $password[$parts[2]];
                $b = $password[$parts[5]];
                $password[$parts[2]] = $b;
                $password[$parts[5]] = $a;
                break;
            case 'swap letter':  
                $password = strtr($password, [
                    $parts[2] => $parts[5],
                    $parts[5] => $parts[2],
                ]);
                break;
            case 'rotate right':
                $steps = $parts[2] % strlen($password);
                $password = substr($password, -$steps) . substr($password, 0, strlen($password) - $steps);
                break;
            case 'rotate left': 
                $steps = $parts[2] % strlen($password);
                $password = substr($password, $steps) . substr($password, 0, $steps);
                break;
            case 'rotate based': 
                $pos = strpos($password, $parts[6]);
                $steps = ($pos + 1 + ($pos >= 4 ? 1 : 0)) % (strlen($password));
                if ($steps > 0) {
                    $password = substr($password, -$steps) . substr($password, 0, strlen($password) - $steps);
                }
                break;
            case 'reverse positions':
                $sub = substr($password, $parts[2], $parts[4] - $parts[2] + 1);
                $rev = strrev($sub);
                for ($i=$parts[2]; $i<=$parts[4]; $i++) {
                    $password[$i] = $rev[$i - $parts[2]];
                }
                break;
            case 'move position': 
                $chars = str_split($password);
                $new = [$parts[5] => $chars[$parts[2]]];
                unset($chars[$parts[2]]);
                
                $i = 0;
                foreach ($chars as $char) {
                    $i += isset($new[$i]) ? 1 : 0;
                    $new[$i] = $char;
                    $i++;
                }
                ksort($new);
                $password = implode('', $new);
                break;
        }
    }
    
    return $password;
}

printf('ans#21.1: %s'.PHP_EOL, scramble('abcdefgh', $lines));

/** @see http://stackoverflow.com/a/27160465 */
function permutations(array $elements)
{
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

$range = range('a','h');
$password = '';
foreach (permutations($range) as $chars) {
    $password = implode('', $chars);
    if (scramble($password, $lines) == 'fbgdceah') {
        break;
    }
}

printf('ans#21.2: %s'.PHP_EOL, $password);