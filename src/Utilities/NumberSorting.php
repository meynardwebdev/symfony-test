<?php
namespace App\Utilities;


class NumberSorting
{
    public static function sort($numbers)
    {
        $ascending_sort = NumberSorting::ascending_sort($numbers);
        $descending_sort = NumberSorting::descending_sort($numbers);
        
        return [
            'ascending' => $ascending_sort,
            'descending' => $descending_sort
        ];
    }
    
    function ascending_sort($numbers)
    {
        // use usort to use a user-defined or custom comparison
        usort($numbers, function($a, $b) {
            if ($a == $b) {
                return 0;
            }
            
            return ($a < $b) ? -1 : 1;
        });
        
        return $numbers;
    }
    
    function descending_sort($numbers)
    {
        // use usort to use a user-defined or custom comparison
        usort($numbers, function($a, $b) {
            if ($a == $b) {
                return 0;
            }
            
            return ($a > $b) ? -1 : 1;
        });
        
        return $numbers;
    }
}
