<?php

$input = file_get_contents('day07.txt');
$lines = explode(PHP_EOL, $input);

// process input
$map = $weights = [];
foreach ($lines as $line) {
    $parts  = array_filter(explode(' ', preg_replace('~[^a-z0-9]+~i', ' ', trim($line))));
    $name   = array_shift($parts);
    $weight = array_shift($parts);
    $weights[$name] = $weight;
    $map[$name]    = $parts;
}

// trace path: [level #1, level #2, level #3, ..]
function resolve(array $map, $start) {
    $tower = [$start];
    foreach ($map as $name => $dependencies) {
        if (in_array($start, $dependencies)) {
            $tower = array_merge(resolve($map, $name), $tower);
        }
    }
    
    return $tower;
}

// resolve path for each name
$list = $levels = [];
$bottom = null;
foreach ($map as $name => $dependencies) {
    $path = resolve(array_filter($map), $name);
    
    $levels[$name] = count($path);
    
    // path with single entry is the top level
    if ($levels[$name] == 1) {
        $bottom = $path[0];
        continue;
    }
    
    // calculate the weight of each parent => children relation
    // start with the deepest level
    $last = $path[count($path)-1];
    for ($i=count($path)-1; $i>0; $i--) {
        $child  = $path[$i];
        $parent = $path[$i-1];
        
        if (!isset($list[$parent][$child])) {
            $list[$parent][$child] = 0;
        }
        
        // add weight of last item to all of its parents
        $list[$parent][$child] += $weights[$last];
    }
}

$diff = [];
foreach ($list as $parent => $children) {
    $count = array_count_values($children);
    if (count($count) > 1) {
        // lowest occurence is the culprit (within this set of children)
        asort($count);
        $child = array_search(key($count), $children);
        // calculate required correction
        $diff[$levels[$parent]][$child] = max($children) - min($children);
    }
}

// deepest level is the actual culprit; obtain its level and name
$level = max(array_keys($diff));
$name  = key($diff[$level]);

printf('ans#7.1: %s'.PHP_EOL, $bottom);
printf('ans#7.2: %d'.PHP_EOL, $weights[$name] - $diff[$level][$name]);