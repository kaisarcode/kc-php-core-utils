<?php
// Manipulate strings
class Str {

    // Simple minify
    static function min($str = '') {
        $str = preg_replace("/\s+/"," ",$str);
        return $str;
    }

    // Remove comments
    static function rmc($str = '') {
        $str = preg_replace("/<!--[\s\S]*?-->/", "", $str);
        $str = preg_replace("/\/\*[\s\S]*?\*\/|(?<!:)\/\/.*$/", "", $str);
        return $str;
    }

    // Truncate string
    static function truncate($str, $len = 30, $ellipsis = '...') {
        if (strlen($str) > $len) {
            $str = substr($str, 0, $len) . $ellipsis;
        }
        return $str;
    }

    // Sanitize string
    static function sanitize($str = '') {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    // Random
    function random($len, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
        $str = '';
        $charlen = strlen($chars);
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars[rand(0, $charlen - 1)];
        }
        return $str;
    }

}
