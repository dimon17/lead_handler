<?php

namespace Tools\Math;

class NumbersConverter
{
    public static function floor($float, $decimals = 2)
    {
        return floor($float * pow(10, $decimals)) / pow(10, $decimals);
    }
}