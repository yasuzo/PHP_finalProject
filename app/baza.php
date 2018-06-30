<?php
try {
    $dsn = 'mysql:host=oipa-db;charset=utf8;dbname=ssp';
    $db = new PDO(
        $dsn,
        'root',
        'oipa-password',
        [
            PDO::ATTR_DEFAULT_FETCH_MODE =>
            PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) { throw $e; }
