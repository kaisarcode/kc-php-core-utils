<?php
// Filesystem
class Fs {

    /**
     * File get contents wrapper
     *
     * @param string $fl
     * @param bool $use_inc_path
     * @return string|false
     */
    static function get(string $fl, bool $use_inc_path = true) {
        return file_get_contents($fl, $use_inc_path);
    }

    /**
     * List folders in directory
     *
     * @param string $dir
     * @param int $rec
     * @return array
     */
    static function lsDirs(string $dir, int $rec = 0): array {
        $arr = array();
        $dir = new \DirectoryIterator($dir);
        foreach ($dir as $d) {
            $pth = $d->getPathname();
            $d->isDir() && !$d->isDot()
            && array_push($arr, $pth) &&
            $rec && $arr = array_merge
            ($arr, self::lsDirs($pth));
        } return $arr;
    }

    /**
     * List files in directory
     *
     * @param string $dir
     * @param bool $rec
     * @return array
     */
    static function lsFiles(string $dir, bool $rec = false): array {
        $arr = array();
        if (!is_dir($dir)) { return $arr; }
        $dir = new \DirectoryIterator($dir);
        foreach ($dir as $d) {
            $pth = $d->getPathname();
            if ($d->isFile()) {
                array_push($arr, $pth);
            } elseif ($d->isDir() && !$d->isDot() && $rec) {
                $arr = array_merge($arr, self::lsFiles($pth, $rec));
            }
        }
        return $arr;
    }

    /**
     * Recursively create directory with optional permissions
     *
     * @param string $dir
     * @param int|null $perms
     * @return bool
     */
    static function mkdirp(string $dir, ?int $perms = null): bool {
        if (is_dir($dir)) {
            return true;
        }

        $ok = mkdir($dir, 0777, true);
        $perms = $perms ?? 0777;
        $ok && chmod($dir, $perms);
        return $ok;
    }

    /**
     * Write contents to file, always using default permissions if not set
     *
     * @param string $file
     * @param string $contents
     * @param int|null $perms
     * @param bool $append
     * @return bool
     */
    static function write(string $file, string $contents, ?int $perms = null, bool $append = false): bool {
        $perms = $perms ?? 0777;
        $dir = dirname($file);
        self::mkdirp($dir, $perms);
        $mode = $append ? FILE_APPEND : 0;
        $ok = file_put_contents($file, $contents, $mode) !== false;
        $ok && chmod($file, $perms);
        return $ok;
    }
}
