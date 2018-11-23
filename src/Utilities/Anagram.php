<?php
namespace App\Utilities;


class Anagram
{
    public static function areAnagram($string1, $string2)
    {
        // first check if both strings have the same length
        if (strlen($string1) !== strlen($string2)) {
            return "false";
        }
        
        // remove case for both words
        $string1 = strtolower($string1);
        $string2 = strtolower($string2);

        // use count_chars to count the number of occurences for every char
        return (count_chars($string1, 1) == count_chars($string2, 1)) ? "true" : "false";
    }
}
