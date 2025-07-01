<?php
// Replace tags by function output
class Tagfun {

    // Replace by callback
    static function r($str, $tg1, $tg2, $fun) {
        $t1 = preg_quote($tg1, '/');
        $t2 = preg_quote($tg2, '/');
        $rx = "/$t1([\\s\\S]*?)$t2/";
        $str = preg_replace_callback
        ($rx, function($m) use (&$fun)
        { return $fun($m[1]);}, $str);
        preg_match($rx, $str) && $str =
        self::r($str, $tg1, $tg2, $fun);
        return $str;
    }

    // Replace by object property
    static function o($str, $tg1, $tg2, $obj, $sep = ".") {
        return self::r($str, $tg1, $tg2,
        function($c) use ($obj, $sep){
            foreach (explode($sep, $c) as $p) {
                if (is_array($obj) && array_key_exists($p, $obj)) {
                    $obj = $obj[$p];
                } elseif (is_object($obj) && property_exists($obj, $p)) {
                    $obj = $obj->{$p};
                } else { return ""; }
            } return $obj;
        });
    }
}
