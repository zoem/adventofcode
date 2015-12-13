<?php 

$input = file_get_contents('day08.txt');
$lines = explode("\n", $input);
$lines = array_filter($lines);

$litLength = 0;
$memLength = 0;
$encLength = 0;

foreach ($lines as $line) {
    $litLength += strlen($line);
    $stripped = stripcslashes($line);
    $stripped = substr($stripped, 1, -1);
    $memLength += strlen($stripped);
    
    $line = '"' . addslashes($line) . '"';
    $encLength += strlen($line);
}

printf('ans#8.1: %u'.PHP_EOL, ($litLength - $memLength));
printf('ans#8.2: %u'.PHP_EOL, ($encLength - $litLength));