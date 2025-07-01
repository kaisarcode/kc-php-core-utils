<?php
// Filesystem
class Fs {

    // File get contents wrapper
    static function get($fl, $use_inc_path = true) {
        return file_get_contents($fl, $use_inc_path);
    }

    // List folders in directory
    static function lsDirs($dir, $rec = 0) {
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

    // List files in directory
    static function lsFiles($dir, $rec = false) {
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
}
