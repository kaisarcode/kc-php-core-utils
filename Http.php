<?php
class Http {

    /**
     * Allow cross-origin requests
     *
     * @param string $origin
     */
    static function allowOrigin(string $origin = '*'): void {
        header("Access-Control-Allow-Origin: $origin");
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * Set response header to JSON
     *
     * @param string $charset
     */
    static function setHeaderJson(string $charset = 'utf-8') {
        header("Content-Type: application/json; charset=$charset");
    }

    /**
     * Set response header to HTML
     *
     * @param string $charset
     */
    static function setHeaderHtml(string $charset = 'utf-8') {
        header("Content-Type: text/html; charset=$charset");
    }

    /**
     * Set response header to XML
     *
     * @param string $charset
     */
    static function setHeaderXml(string $charset = 'utf-8') {
        header("Content-Type: text/xml; charset=$charset");
    }

    /**
     * Set response header to plain text
     *
     * @param string $charset
     */
    static function setHeaderText(string $charset = 'utf-8') {
        header("Content-Type: text/plain; charset=$charset");
    }

    /**
     * Set response header to JavaScript
     *
     * @param string $charset
     */
    static function setHeaderJs(string $charset = 'utf-8') {
        header("Content-Type: text/javascript; charset=$charset");
    }

    /**
     * Set response header to CSS
     *
     * @param string $charset
     */
    static function setHeaderCss(string $charset = 'utf-8') {
        header("Content-Type: text/css; charset=$charset");
    }

    /**
     * Set response header to PNG image
     */
    static function setHeaderPng(): void {
        header("Content-Type: image/png;");
    }

    /**
     * Set cache control headers
     *
     * @param int $ttl
     */
    static function setHeaderCache(int $ttl = 0): void {
        $ttl === 0 ?
        header("Cache-Control: no-cache") :
        header("Cache-Control: max-age=$ttl");
    }

    /**
     * Prevent indexing by robots
     */
    static function noRobots(): void {
        header('X-Robots-Tag: noindex, nofollow');
    }

    /**
     * Set 404 Not Found response
     */
    static function set404(): void {
        header('HTTP/1.1 404 Not Found', true, 404);
    }

    /**
     * Set 403 Forbidden response
     */
    static function set403(): void {
        header('HTTP/1.1 403 Forbidden', true, 403);
    }

    /**
     * Set 401 Unauthorized response
     */
    static function set401(): void {
        header('HTTP/1.1 401 Unauthorized', true, 401);
    }

    /**
     * Set 400 Bad Request response
     */
    static function set400(): void {
        header('HTTP/1.1 400 Bad Request', true, 400);
    }

    /**
     * Set 200 OK response
     */
    static function set200(): void {
        header('HTTP/1.1 200 OK', true, 200);
    }

    /**
     * Redirect to a URL
     *
     * @param string $url
     * @param bool $is301
     */
    static function redirect(string $url, bool $is301 = false): void {
        if ($is301) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        header("Location: $url");
        exit;
    }

    /**
     * Reload the current page
     */
    static function refresh(): void {
        header("Refresh:0");
    }

    /**
     * Force HTTPS redirection
     */
    static function forceHttps(): void {
        if (empty($_SERVER['HTTPS'] || $_SERVER['HTTPS']==='off')
        && @fsockopen($_SERVER['HTTP_HOST'], 443, $e, $s, 1)) {
            $redir = "https://".
            $_SERVER['HTTP_HOST'].
            $_SERVER['REQUEST_URI'];
            header("Location:$redir");
            exit;
        }
    }

    /**
     * Force download of a file
     *
     * @param string $path
     * @param string $mime
     */
    static function downloadFile(string $path, string $mime): void {
        if (!file_exists($path)) return;

        $filename = basename($path);
        $size = filesize($path);

        header("Content-Type: $mime");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Length: $size");
        readfile($path);
        exit;
    }

    /**
     * Clear browser cache
     */
    static function clearBrowserCache(): void {
        header('Pragma: no-cache');
        header('Cache: no-cache');
        header('Expires: Mon, 01 Jan 1970 00:00:00 GMT');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
    }

    /**
     * Get base URI
     *
     * @return string
     */
    static function getBaseUri(): string {
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        return $scheme . '://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * Get browser language
     *
     * @param string $df
     * @return string
     */
    static function getBrowserLang(string $df = 'en'): string {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $al = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            return substr($al, 0, 2);
        }
        return substr($df, 0, 2);
    }

    /**
     * Get HTTP method
     *
     * @return string
     */
    static function getHTTPMethod(): string {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Get raw request body
     *
     * @return string
     */
    static function getHTTPBody(): string {
        return file_get_contents('php://input');
    }

    /**
     * Get GET and POST variables
     *
     * @return array
     */
    static function getHTTPVars(): array {
        $out = [];
        foreach ($_POST as $k => $v) {
            $out[$k] = $v;
        }
        foreach ($_GET as $k => $v) {
            $out[$k] = $v;
        }
        return $out;
    }

    /**
     * Get merged HTTP parameters from JSON body and GET/POST
     *
     * @return array
     */
    static function getHttpParams(): array {
        $body = json_decode(self::getHTTPBody(), true);
        $body = is_array($body) ? $body : [];
        $vars = self::getHTTPVars();
        return array_merge($body, $vars);
    }

    /**
     * Get a single HTTP var by key
     *
     * @param string $k
     * @param mixed $default
     * @return mixed
     */
    static function getHTTPVar(string $k = '', $default = '') {
        if ($k === '') return $default;
        if (isset($_GET[$k])) return $_GET[$k];
        if (isset($_POST[$k])) return $_POST[$k];
        return $default;
    }

    /**
     * Check if a single HTTP var exists
     *
     * @param string $k
     * @return bool
     */
    static function issetHTTPVar(string $k = ''): bool {
        if ($k === '') return false;
        return isset($_POST[$k]) || isset($_GET[$k]);
    }
}
