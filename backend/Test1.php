<?php

class Test1
{
    public static function last_word($sentence)
    {
        $sentence = trim($sentence);
        if (empty($sentence)) {
            return 0;
        }
        $words = explode(' ', $sentence);
        return strlen(end($words));
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
}

echo Test1::last_word('Hello World') . PHP_EOL;
echo Test1::extract_string('Hello [World]') . PHP_EOL;
