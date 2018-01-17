<?php
    // error_reporting('E_ALL ~& E_NOTICE');
    session_start();
    date_default_timezone_set('Asia/Taipei');

    try  {
	$driver = 'mysql';
	$host = 'localhost';
	$db_name = 'competition_45_region';
	$user = 'admin';
	$password = '1234';
        $dsn = "$driver:host=$host;dbname=$db_name";
        $link = new PDO($dsn, $user, $password);
        $link->exec('set names utf-8');
    } catch (PDOException $exception) {
        echo $exception->getMessage();
        die();
    }

    function query($statement)
    {
        global $link;
        return $link->query($statement);
    }

    function fetch($statement)
    {
        global $link;
        return query($statement)->fetch();
    }

    function fetchAll($statement)
    {
        global $link;
        return query($statement)->fetchAll();
    }

	function isLogin()
	{
		if (isset($_SESSION['is_login'])) {
			return true;
		}
		return false;
	}

	function isAdmin()
	{
		if (isset($_SESSION['is_admin'])) {
			return true;
		}
		return false;
	}

