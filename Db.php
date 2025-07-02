<?php
// Handles connection with RedbeanPHP
class Db {
    public static function init(object $CONF): void {
        $dsn = "mysql:host={$CONF->db_host};dbname={$CONF->db_name};charset={$CONF->db_charset}";
        R::setup($dsn, $CONF->db_user, $CONF->db_pass);
        R::freeze(false);
    }
}
