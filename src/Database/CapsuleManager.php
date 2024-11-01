<?php

namespace ELKLab\ELKAnalytics\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

global $wpdb;

$capsule = new Capsule;

$host = DB_HOST;
$port = 3306;
$unixSocket = '';
if (defined('DB_CHARSET')) {
    if (DB_CHARSET == 'utf8mb4') {
        $collation = 'utf8mb4_unicode_ci';
    } else if (DB_CHARSET == 'utf8') {
        $collation = 'utf8_unicode_ci';
    } else {
        $collation = DB_CHARSET . '_unicode_ci';
    }    
}
if (defined('DB_COLLATE')) {        
    if (DB_COLLATE != '') {
        $collation = DB_COLLATE;
    }
}

if (strpos(DB_HOST, ':') !== false) {
    if (preg_match('/^(.*):(\d+)$/', DB_HOST, $matches)) {
        $host = $matches[1];
        if (defined('DB_PORT')) {
            $port = (int) DB_PORT;
        } else {
            $port = (int) $matches[2];
        }
    } elseif (preg_match('/^(.*):(.+\.sock)$/', DB_HOST, $matches)) {
        $host = $matches[1];
        $unixSocket = $matches[2];
    }
}



$connection = [
    'driver'    => 'mysql',
    'host'      => $host,
    'port'      => $port,
    'database'  => DB_NAME,
    'username'  => DB_USER,
    'password'  => DB_PASSWORD,
    'charset'   => DB_CHARSET,
    'collation' => $collation,
    'prefix'    => $wpdb->prefix
];

if ($unixSocket) {
    $connection['unix_socket'] = $unixSocket;
}

$capsule->addConnection($connection);

$capsule->setAsGlobal();
$capsule->bootEloquent();