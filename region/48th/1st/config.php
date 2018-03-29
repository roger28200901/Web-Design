<?php

//error_reporting('e_all ~& e_notice');
date_default_timezone_set('asia/taipei');
session_start();

$pdo = new PDO('mysql:host=localhost;charset=utf8;dbname=web_design_48_region', 'admin', '1234');

