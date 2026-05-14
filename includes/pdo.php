<?php

require_once __DIR__ . '/db.php';

function getPdo()
{
    global $dbhost, $db, $dbuser, $dbpass;

    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = "mysql:host={$dbhost};dbname={$db};charset=utf8";

    $pdo = new PDO(
        $dsn,
        $dbuser,
        $dbpass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        )
    );

    $pdo->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

    return $pdo;
}