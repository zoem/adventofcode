<?php

$input = file_get_contents('day22.txt');
preg_match_all('~(\d+)~', $input, $matches);

$boss = array_combine(['hp', 'damage'], $matches[0]);
$player = [
    'hp' => 50,
    'mana' => 500,
    'armor' => 0,
    'spent' => 0,
];

$spells = [
    'Magic Missile' => ['type' => 'instant', 'cost' => 53 , 'damage' => 4, 'heal' => 0, 'turns' => 1, 'armor' => 0, 'mana' => 0], 
    'Drain'         => ['type' => 'instant', 'cost' => 73 , 'damage' => 2, 'heal' => 2, 'turns' => 1, 'armor' => 0, 'mana' => 0],
    'Shield'        => ['type' => 'effect' , 'cost' => 113, 'damage' => 0, 'heal' => 0, 'turns' => 6, 'armor' => 7, 'mana' => 0],
    'Poison'        => ['type' => 'effect' , 'cost' => 173, 'damage' => 3, 'heal' => 0, 'turns' => 6, 'armor' => 0, 'mana' => 0],
    'Recharge'      => ['type' => 'effect' , 'cost' => 229, 'damage' => 0, 'heal' => 0, 'turns' => 5, 'armor' => 0, 'mana' => 101],
];

printf('ans#22.1: %u'.PHP_EOL, battle($spells, $player, $boss, false));
printf('ans#22.2: %u'.PHP_EOL, battle($spells, $player, $boss, true));

// $runs is an arbitrarily chosen number; the higher its value the more accurate the answer will be
function battle($spells, $player, $boss, $hardmode = false, $runs = 1E4) {
    $debug = false;
    $minSpent = PHP_INT_MAX;
    $retries = 0;

    while ($retries < $runs) {
        
        $players = ['p' => $player, 'b' => $boss];
        $activeEffects = [];
        
        while ($players['p']['spent'] < $minSpent) {
            
            foreach (array_keys($players) as $who) {

                // hardmode: reduce hp by 1
                if ($hardmode && $who == 'p') {
                    $players['p']['hp']--;
                    
                    if ($players['p']['hp'] <= 0) {
                        break 2;
                    }
                }
                
                // reset player's damage and armor
                $players['p']['damage'] = 0;
                $players['p']['armor']  = 0;

                // apply all effects that are currently active
                foreach ($activeEffects as $name => $effect) {
                    $players['p']['hp']     += $effect['heal'];
                    $players['p']['armor']  += $effect['armor'];
                    $players['p']['mana']   += $effect['mana'];
                    $players['p']['damage'] += $effect['damage'];
                    
                    $activeEffects[$name]['turns']--;

                    // effect wears off, remove from list
                    if ($activeEffects[$name]['turns'] == 0) {
                        unset($activeEffects[$name]);
                    }
                }
                
                if ($who == 'p') {
                    // player's turn
                    $availableSpells = $spells;
                    
                    // remove all spells which are too expensive
                    foreach ($availableSpells as $name => $availableSpell) {
                        if (isset($activeEffects[$name]) || $availableSpell['cost'] > $players['p']['mana']) {
                            unset($availableSpells[$name]);
                        }
                    }
                    
                    // no spells means the player lost
                    if (!$availableSpells) {
                        break 2;
                    }
                    
                    // pick a random spell
                    $name = array_rand($availableSpells);
                    
                    $spell = $spells[$name];
   
                    // spell cost
                    $players['p']['mana']  -= $spell['cost'];
                    $players['p']['spent'] += $spell['cost'];

                    if ($spell['type'] == 'instant') {
                        // instant spell
                        $players['p']['hp']     += $spell['heal'];
                        $players['p']['mana']   += $spell['mana'];
                        $players['p']['damage'] += $spell['damage'];
                    }
                    else {
                        // spell is an effect, which will take effect starting next turn
                        $activeEffects[$name] = $spell;
                    }
                    
                    // player attack
                    $players['b']['hp'] -= $players['p']['damage'];
                    
                    if ($players['b']['hp'] <= 0) {
                        break 2;
                    }
                }
                else {
                    // boss' turn
                    
                    // apply damage from active effects
                    $players['b']['hp'] -= $players['p']['damage'];
                    
                    if ($players['b']['hp'] <= 0) {
                        break 2;
                    }
                    
                    // boss attack, which is at least 1
                    $damage = max(1, ($players['b']['damage'] - $players['p']['armor']));
                    $players['p']['hp'] -= $damage;
                    
                    if ($players['p']['hp'] <= 0) {
                        break 2;
                    }
                }
            }
        }
        
        // player won -> compare the currently spent mana against the previous minimum
        if ($players['p']['hp'] > 0 && $players['b']['hp'] <= 0 && $players['p']['spent'] < $minSpent) {
            $minSpent = $players['p']['spent'];
            $retries = 0;
        }
        
        $retries++;
    }
    
    return $minSpent;
}