<?php

class Test2
{
    public function is_power($number, $base)
    {
        if (!is_numeric($number) || !is_numeric($base)) {
            return false;
        }

        if ($number == 1) {
            return true;
        }

        if ($base == $number) {
            return true;
        }

        if ($base == 0 || abs($base) == 1) {
            return $number == $base;
        }

        if ($number == 0) {
            return false;
        }


        $power = log(abs($number)) / log(abs($base));

        if ($power == (int)$power) {
            if ($power % 2 == 0) {
                if ($number > 0) {
                    return true;
                }
            } else {
                if ($number < 0 && $base < 0 || $number > 0 && $base > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    public function format_number($str)
    {
        return preg_replace('/[^\d.,]/', '', $str);
    }

    public function sum_digits($int)
    {
        return array_sum(str_split(abs($int)));
    }

}

$obj = new Test2();

var_dump($obj->is_power(16, 4));
var_dump($obj->is_power(12, 3));
var_dump($obj->is_power(1, 100));
var_dump($obj->is_power(-8, -2));
var_dump($obj->is_power(8, -2));
var_dump($obj->is_power(-8, 2));
var_dump($obj->is_power(-4, 2));
var_dump($obj->is_power(4, -2));
var_dump($obj->is_power(-4, -2));
var_dump($obj->is_power(1, 1));
var_dump($obj->is_power(-1, 1));
var_dump($obj->is_power(1, -1));
var_dump($obj->is_power(-1, -1));
var_dump($obj->is_power(6.25, -2.5));

echo $obj->format_number("$4,000.15A") . PHP_EOL;

echo $obj->sum_digits(12345) . PHP_EOL;
echo $obj->sum_digits(-12345) . PHP_EOL;