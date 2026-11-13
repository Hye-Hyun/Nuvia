<?php
    $host = '127.0.0.1';
    $port = 3306;
    $database = 'nuvia';

    $dbUsername = getenv('NUVIA_DB_USER');
    $dbPassword = getenv('NUVIA_DB_PASSWORD');

    if($dbUsername === false || $dbUsername === '' || $dbPassword === false) {
        throw new PDOException('Nuvia DB 접속 환경 변수를 확인해 주세요.');
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);