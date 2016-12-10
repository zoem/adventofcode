<?php
$input = file_get_contents('day10.txt');

$lines = array_filter(array_map('trim', explode(PHP_EOL, $input)));
$instructions = array_map(function ($v) { return explode(' ', $v); }, $lines);

$bots = $output = [];

$chip61And17Bot = 0;
do {
    foreach ($instructions as $j => $parts) {
        if ($parts[0] == 'value') {
            // input => bot
            $bot  = $parts[5];
            $chip = $parts[1];
            $bots[$bot][$chip] = true;
            
            // remove instruction
            unset($instructions[$j]);
        } elseif ($parts[0] == 'bot') {
            // bot => bot and/or bot => output
            $bot = $parts[1];
            if (isset($bots[$bot]) && count($bots[$bot]) == 2) {
                // Part 1: find the bot which handles both chip 61 and 17
                if (isset($bots[$bot][61]) && isset($bots[$bot][17])) {
                    $chip61And17Bot = $bot;
                }
                
                // handle low and high chip
                foreach ([[3,5,6], [8,10,11]] as $idx) {
                    $type     = $parts[$idx[0]];
                    $target   = $parts[$idx[1]];
                    $targetId = $parts[$idx[2]];
                    $chips    = array_keys($bots[$bot]);
                    $chip     = $type == 'low' ? min($chips) : max($chips);
                    
                    if ($target == 'bot') {
                        // bot => bot
                        $bots[$targetId][$chip] = true;
                    } elseif ($target == 'output') {
                        // bot => output (required for Part 2)
                        $output[$targetId] = $chip;
                    }
                    
                    // remove chip from bot
                    unset($bots[$bot][$chip]);
                }
                
                // remove instruction
                unset($instructions[$j]);
            }
        }
    }
} while (count($instructions));

printf('ans#10.1: %u'.PHP_EOL, $chip61And17Bot);
printf('ans#10.2: %u'.PHP_EOL, $output[0] * $output[1] * $output[2]);