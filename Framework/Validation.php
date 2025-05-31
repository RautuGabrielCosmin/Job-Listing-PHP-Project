<?php

namespace Framework;

class Validation
{
    /**
     * Validate a string
     * 
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function string($value, $min = 1, $max = INF)
    {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);
            return $length >= $min && $length <= $max;
        }
        return false;
    } //end of string($value, $min = 1, $max = INF)

    /**
     * Validate email address
     * 
     * @param string $value
     * @return mixed it will return false if it is NOT a valid email and the email if it is valid
     */
    public static function email($value)
    {
        $value = trim($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    } //end of email($value)

    /**
     * Match a value against another
     * @param string $value1
     * @param string $value2
     * @return bool
     */
    public static function match($value1, $value2)
    {
        $value1 = trim($value1);
        $value2 = trim($value2);

        return ($value1 === $value2);
    } //end of match($value1, $value2)
}
