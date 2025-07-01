<?php
// Css manipulation
class Css {

    // Replace CSS vars
    static function replaceVars($str) {
        $vrs = new stdClass();

        // var definitions
        $str = Tagfun::r($str, ':root {', '}',
        function($c) use (&$vrs) {
        $c = Tagfun::r($c, '--',';',
        function($c) use (&$vrs) {
        $c = explode(":", $c);
        @$c[1] = trim($c[1]);
        $vrs->{$c[0]} = $c[1];
        return ''; }); return ''; });

        // var usage
        $str = Tagfun::r($str, 'var(--', ')',
        function($c) use ($vrs) {
        @$v = $vrs->$c; return $v; });

        // return
        return $str;
    }
}
