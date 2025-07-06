<?php
/**
 * Class Str
 *
 * Provides utility functions for string manipulation.
 */
class Str {

    /**
     * Minify string by collapsing whitespace.
     *
     * @param string $str
     * @return string
     */
    public static function min(string $str = ''): string {
        return preg_replace('/\s+/', ' ', $str);
    }

    /**
     * Remove HTML and JS comments from string.
     *
     * @param string $str
     * @return string
     */
    public static function rmc(string $str = ''): string {
        $str = preg_replace('/<!--[\s\S]*?-->/', '', $str);
        $str = preg_replace('/\/\*[\s\S]*?\*\/|(?<!:)\/\/.*$/', '', $str);
        return $str;
    }

    /**
     * Truncate string with optional ellipsis.
     *
     * @param string $str
     * @param int $len
     * @param string $ellipsis
     * @return string
     */
    public static function truncate(string $str, int $len = 30, string $ellipsis = '...'): string {
        return strlen($str) > $len ? substr($str, 0, $len) . $ellipsis : $str;
    }

    /**
     * Sanitize string to prevent XSS.
     *
     * @param string $str
     * @return string
     */
    public static function sanitize(string $str = ''): string {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a random string.
     *
     * @param int $len
     * @param string $chars
     * @return string
     */
    public static function random(int $len, string $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'): string {
        $result = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $len; $i++) {
            $result .= $chars[rand(0, $max)];
        }
        return $result;
    }

    /**
     * Escape formatting inside <pre> blocks.
     *
     * @param string $str
     * @return string
     */
    public static function keepPre(string $str = ''): string {
        return preg_replace_callback('/<pre>(.*?)<\/pre>/is', function ($matches) {
            $escaped = str_replace(
                ["\n", " ", "<", ">"],
                ["__NL__", "__SP__", "__LT__", "__GT__"],
                $matches[1]
            );
            return '<pre>' . $escaped . '</pre>';
        }, $str);
    }

    /**
     * Restore formatting inside <pre> blocks.
     *
     * @param string $str
     * @return string
     */
    public static function restorePre(string $str = ''): string {
        return str_replace(
            ["__NL__", "__SP__", "__LT__", "__GT__"],
            ["\n", " ", "<", ">"],
            $str
        );
    }
}
