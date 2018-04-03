<?php
    // error_reporting('e_all ~& e_notice');
    
    $dbname = 'web_design_47_nationwide_d';
    $charset = 'utf8';
    $user = 'admin';
    $password = '1234';
    
    try {
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=$charset"; // charset option needs over php version 5.3.6
        $link = new PDO($dsn, $user, $password);
    } catch (PDOException $exception) {
        print $exception->getMessage();
        exit();
    }

    session_start();
