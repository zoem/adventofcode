<?php

$input = file_get_contents('day17.txt');
$lines = explode("\r\n", $input);
$lines = array_filter($lines);

$containers = [];

foreach ($lines as $line) {
    $containers[] = intval($line);
}

$solver = new Solver();
$ans = $solver->solve($containers, 150);

printf('ans#17.1: %u'.PHP_EOL, $ans[1]);
printf('ans#17.2: %u'.PHP_EOL, $ans[2]);

class Solver
{
    public $used = [];
    
    public $found = 0;
    
    public $foundMin = 0;
    
    public $min;
    
    public $max;

    public function solve($values, $max)
    {
        $this->used = [];
        $this->min = count($values);
        $this->max = $max;
        
        while ($values) {
            end($values);
            $key = key($values);
            $node = new Node($key, array_pop($values));
            $this->doSolve($values, $node, 150);
        }
        
        return [
            1 => $this->found,
            2 => $this->foundMin
        ];
    }
    
    protected function doSolve($values, $parent)
    {
        $ids = $parent->ids();
        sort($ids);
        
        $idsKey = implode('|', $ids);

        if (isset($this->used[$idsKey])) {
            return;
        }
        
        $this->used[$idsKey] = true;
        
        if ($parent->sum() == $this->max) {
            $this->found++;
            
            $count = count($ids);
            if ($count < $this->min) {
                $this->foundMin = 1;
                $this->min = $count;
            }
            elseif ($count == $this->min) {
                $this->foundMin++;
            }
            
            return;
        }
        
        foreach ($values as $i => $value) {
            if ($parent->sum() + $value > $this->max) {
                unset($values[$i]);
            }
        }

        foreach ($values as $i => $value) {
            $newValues = $values;
            unset($newValues[$i]);
            $node = new Node($i, $value, $parent);

            $this->doSolve($newValues, $node, $this->max);
        }
    }
}

class Node
{
    public $parent;
    public $value;
    public $id;
    
    public function __construct($id, $value, $parent = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->parent = $parent;
    }
    
    public function sum()
    {
        $sum = $this->value;
        
        if ($this->parent) {
            $sum += $this->parent->sum();
        }
        
        return $sum;
    }
    
    public function ids()
    {
        if ($this->parent) {
            $r = $this->parent->ids();
            $r[] = $this->id;
            return $r;
        }
        
        return [$this->id];
    }
}