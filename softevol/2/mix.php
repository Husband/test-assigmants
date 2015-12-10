<?php

function mix($string)
{
    $mixed = preg_replace_callback("/\{([^\{]*)\}/U", function ($match) {
        $variants = explode('|', $match[1]);
        return $variants[array_rand($variants)];
    }, $string, 1);
    if ($mixed === $string) {
        return $mixed;
    } else {
        return mix($mixed);
    }
}

echo mix('{Пожалуйста|Просто} сделайте так, чтобы это {удивительное|крутое|простое} тестовое предложение {изменялось {быстро|мгновенно} случайным образом|менялось каждый раз}.');