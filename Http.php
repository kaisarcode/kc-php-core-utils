<?php
class Http {

    // Allow origin
    static function allowOrigin($origin = '*') {
        @header("Access-Control-Allow-Origin: $origin");
        @header('Access-Control-Allow-Headers: Content-Type');
    }

    // Set response JSON
    static function setHeaderJson($charset = 'utf-8') {
        @header("Content-Type: application/json; charset=$charset");
    }

    // Set response HTML
    static function setHeaderHtml($charset = 'utf-8') {
        @header("Content-Type: text/html; charset=$charset");
    }

    // Set response XML
    static function setHeaderXml($charset = 'utf-8') {
        @header("Content-Type: text/xml; charset=$charset");
    }

    // Set response Text
    static function setHeaderText($charset = 'utf-8') {
        @header("Content-Type: text/plain; charset=$charset");
    }

    // Set response JavaScript
    static function setHeaderJs($charset = 'utf-8') {
        @header("Content-Type: text/javascript; charset=$charset");
    }

    // Set response CSS
    static function setHeaderCss($charset = 'utf-8') {
        @header("Content-Type: text/css; charset=$charset");
    }

    // Set response PNG
    static function setHeaderPng() {
        @header("Content-Type: image/png;");
    }

    static function setHeaderCache($ttl = 0) {
        $ttl == 0 ?
        @header("Cache-Control: no-cache"):
        @header("Cache-Control: max-age=$ttl");
    }

    // No robots
    static function noRobots() {
        @header('X-Robots-Tag: noindex, nofollow');
    }

    // 404 Not found
    static function set404() {
        @header('HTTP/1.1 404 Not Found', true, 404);
    }

    // 403 Forbidden
    static function set403() {
        @header('HTTP/1.1 403 Forbidden', true, 403);
    }

    // 401 Unauthorized
    static function set401() {
        @header('HTTP/1.1 401 Unauthorized', true, 401);
    }

    // 400 Bad Request
    static function set400() {
        @header('HTTP/1.1 400 Bad Request', true, 400);
    }

    // 200 OK
    static function set200() {
        @header('HTTP/1.1 200 OK', true, 200);
    }

    // Redirect
    static function redirect($url, $is301 = false) {
        if($is301) @header('HTTP/1.1 301 Moved Permanently');
        @header("Location: $url");
    }

    // Reload
    static function refresh() {
        @header("Refresh:0");
    }

    // Force HTTPS
    static function forceHttps() {
        if (array_key_exists
        ('HTTPS', $_SERVER) &&
        (empty($_SERVER['HTTPS']) ||
        $_SERVER['HTTPS']==="off")){
            $redir = "https://".
            $_SERVER['HTTP_HOST'].
            $_SERVER['REQUEST_URI'];
            header("Location:$redir");
            exit;
        }
    }

    // (Force) Download file
    static function downloadFile($url, $mime) {
        $nm = basename($url);
        $fz = filesize($url);
        header("Content-Type: $mime");
        header("Content-Disposition: attachment; filename=\"$nm\"");
        header("Content-Length: $fz");
        header("Location: $url");
    }

    // Clear browser cache
    static function clearBrowserCache() {
        @header('Pragma: no-cache');
        @header('Cache: no-cache');
        @header('Expires: Mon, 01 Jan 1970 00:00:00 GMT');
        @header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        @header("Cache-Control: post-check=0, pre-check=0", false);
    }

    // Get base URI
    static function getBaseUri() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    // Get browser lang
    static function getBrowserLang($df = 'en') {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $al = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            return substr($al, 0, 2);
        } else {
            return substr($df, 0, 2);
        }
    }

    // Get HTTP method
    static function getHTTPMethod(): string {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    // Obtain request body
    static function getHTTPBody() {
        return file_get_contents("php://input");
    }

    // Obtain POST and GET vars
    static function getHTTPVars() {
        $out = array();
        foreach ($_POST as $k => $v) {
            $out[$k] = $v;
        }
        foreach ($_GET as $k => $v) {
            $out[$k] = $v;
        }
        return $out;
    }

    // Obtain single HTTP var
    static function getHTTPVar($k = '', $default = '') {
        if (!$k) return $default;
        if (isset($_GET[$k])) {
            return $_GET[$k];
        } else if (isset($_POST[$k])) {
            return $_POST[$k];
        }
        return $default;
    }

    // Single HTTP exists
    static function issetHTTPVar($k = '') {
        if (!$k) return false;
        return isset($_POST[$k]) || isset($_GET[$k]);
    }
}
