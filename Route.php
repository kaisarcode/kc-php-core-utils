<?php
// Router
/**
 * Class Route
 *
 * Minimalistic HTTP router that matches request method and URI path.
 * Automatically halts on first matching route unless the callback returns false.
 *
 * Usage:
 * Route::get('/path', function() { return 'Hello'; });
 * Route::all('/.*', function() { return false; }); // fallback
 *
 * Returned values from route callbacks:
 * - false → continue to next route
 * - true → stop, no output
 * - string or number → echoed as response
 * - array or object → JSON-encoded and echoed
 */
class Route {
    protected static $matched = false;

    /**
     * Registers a route for a specific HTTP method.
     *
     * @param string $mth HTTP method (GET, POST, etc.)
     * @param string $pth URI pattern, simple or regex-like
     * @param callable $cb Callback to execute if matched
     * @return void
     */
    static function add($mth, $pth, $cb) {
        if (self::$matched) return;
        $mth = strtoupper(trim($mth));
        $mthd = $_SERVER['REQUEST_METHOD'];
        if ($mth == $mthd || $mth == 'ALL') {
            $path = $_SERVER['REQUEST_URI'];
            $path = parse_url($path, PHP_URL_PATH);
            $patt = str_replace('/', '\\/', $pth);
            if (preg_match_all("/^$patt$/", $path, $mtch)) {
                $res = $cb($mtch);
                if ($res === false) return;
                if (is_string($res) || is_numeric($res)) echo $res;
                elseif (is_array($res) || is_object($res)) echo json_encode($res, JSON_PRETTY_PRINT);
                self::$matched = true;
            }
        }
    }

    /**
     * Registers a route for any HTTP method.
     *
     * @param string $pth
     * @param callable $cb
     * @return void
     */
    static function all($pth, $cb) {
        self::add('all', $pth, $cb);
    }

    /**
     * Registers a GET route.
     *
     * @param string $pth
     * @param callable $cb
     * @return void
     */
    static function get($pth, $cb) {
        self::add('get', $pth, $cb);
    }

    /**
     * Registers a POST route.
     *
     * @param string $pth
     * @param callable $cb
     * @return void
     */
    static function post($pth, $cb) {
        self::add('post', $pth, $cb);
    }

    /**
     * Registers a PUT route.
     *
     * @param string $pth
     * @param callable $cb
     * @return void
     */
    static function put($pth, $cb) {
        self::add('put', $pth, $cb);
    }

    /**
     * Registers a DELETE route.
     *
     * @param string $pth
     * @param callable $cb
     * @return void
     */
    static function delete($pth, $cb) {
        self::add('delete', $pth, $cb);
    }
}
