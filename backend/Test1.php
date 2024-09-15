<?php

class Test1
{
    public static function last_word($sentence)
    {
        $sentence = trim($sentence);
        if (empty($sentence)) {
            return 0;
        }

        preg_match('/\b[\w-]+\b(?=[^\w-]*$)/', $sentence, $matches);
        return !empty($matches[0]) ? strlen($matches[0]) : 0;
    }

    public static function extract_string($str)
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }

        preg_match('/\[(.*?)\]/', $str, $matches);
        return $matches[1] ?? '';
    }

    public static function extract_all_string($str)
    {
        $str = trim($str);
        if (empty($str)) {
            return [];
        }

        preg_match_all('/\[(.*?)\]/', $str, $matches);
        return $matches[1] ?? [];
    }
}

echo Test1::last_word('Hello World!!!') . PHP_EOL;
echo Test1::extract_string('Hello [World]') . PHP_EOL;
print_r(Test1::extract_all_string('[Hello] [World]'));