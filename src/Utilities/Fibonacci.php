<?php
namespace App\Utilities;


class Fibonacci
{
    public static function FibonacciSeries($series_length)
    {
        $fibonacci_series = [];
        $first_number = 1;
        
        for($i = 0; $i < $series_length; $i++) {
            if ($i < 2) {
                $fibonacci_series[$i] = $first_number;
            } else {
                $fibonacci_series[$i] = $fibonacci_series[$i-2] + $fibonacci_series[$i-1];
            }
        }
        
        return implode(", ", $fibonacci_series);
    }
}
