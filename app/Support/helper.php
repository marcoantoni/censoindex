<?php

if (!function_exists('to_lowercase')){
    function to_lowercase($string) {
        return mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string));
    }
}
