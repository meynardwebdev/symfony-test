<?php
namespace App\Utilities;


class Palindrome
{
    public static function isPalindrome($phrase)
    {
        // remove all spaces
        $string = str_replace(' ', '', $phrase);
        
        // remove special characters and numbers
        $string = preg_replace('/[^A-Za-z]/', '', $string);
        
        // remove case
        $string = strtolower($string);
        
        // get the string reverse using strrev
        $reverse = strrev($string);
        
        return ($reverse == $string) ? "true" : "false";
    }
}
