<?php

namespace App\Helpers;

class ArrayHelper {
    public static function getValue($array, $key, $default = null) {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}