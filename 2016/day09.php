<?php

$input  = file_get_contents('day09.txt');
$string = preg_replace("#[\n\r\s]+#", '', $input);

function getDecompressedLength($string, $recursive = false) {
    if (!$string) {
        return 0;
    }
    
    $pos = $length = 0;
    while ($pos < strlen($string)) {
        if ($string[$pos] == '(') {
            // find closing bracket, which encloses the marker
            $markerEnd = strpos($string, ')', $pos);
            if ($markerEnd === false) throw new \Exception('No matching ) found');

            // extract marker information: <length>x<repetitions>
            $marker = substr($string, $pos + 1, $markerEnd - $pos - 1);
            list($sequenceLength, $repetitions) = explode('x', $marker);

            if ($recursive) {
                // Part 2: decompress compressed sequences within the current sequence recursively
                $sequence = substr($string, $markerEnd + 1, $sequenceLength);
                $length  += $repetitions * getDecompressedLength($sequence, $recursive);
            } else {
                // Part 1: add length of the repeated sequence
                $length += $repetitions * $sequenceLength;
            }
            
            // set pointer to the first character after the current block
            $pos = $markerEnd + $sequenceLength + 1;
        } else {
            $pos++;
            $length++;
        }
    }
    
    return $length;
}

printf('ans#9.1: %u'.PHP_EOL, getDecompressedLength($string, false));
printf('ans#9.2: %u'.PHP_EOL, getDecompressedLength($string, true));