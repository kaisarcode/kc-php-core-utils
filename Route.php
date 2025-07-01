<?php
// Router
class Route {
    static function add($mth, $pth, $cb) {
        $mth = trim($mth);
        $mth = strtoupper($mth);
        $mthd = $_SERVER['REQUEST_METHOD'];
        if ($mth == $mthd || $mth == 'ALL') {
            $path = $_SERVER['REQUEST_URI'];
            $path = parse_url($path, PHP_URL_PATH);
            $patt = str_replace('/', '\\/', $pth);
            preg_match_all("/^$patt$/", $path, $mtch)
            && $cb($mtch);
        }
    }
    static function all($pth, $cb) {
        self::add('all', $pth, $cb);
    }
    static function get($pth, $cb) {
        self::add('get', $pth, $cb);
    }
    static function post($pth, $cb) {
        self::add('post', $pth, $cb);
    }
    static function put($pth, $cb) {
        self::add('put', $pth, $cb);
    }
    static function delete($pth, $cb) {
        self::add('delete', $pth, $cb);
    }
}
