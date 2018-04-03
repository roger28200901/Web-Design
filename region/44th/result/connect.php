<?php
error_reporting('e_all ~& e_notice');
session_start();
date_default_timezone_set('Asia/Taipei');
$dsn = 'mysql:host=localhost;dbname=web_design_44_region';
try {
	$link = new PDO($dsn, 'admin', 1234);
}
catch (Exception $error) {
	echo 'Could not found database, ' . $error->getMessage();
}
$link->exec('set names utf8');

function qr($sql)
{
	global $link;
	return $link->query($sql);
}

function fa($sql)
{
	global $link;
	return qr($sql)->fetch();
}

function faa($sql)
{
	global $link;
	return qr($sql)->fetchAll();
}

function isLogin()
{
	if (isset($_SESSION['manager_login']) || isset($_SESSION['user_login']))
	{
		return true;
	}
	return false;
}

function isNotLogin()
{
	if (isset($_SESSION['manager_login']) || isset($_SESSION['user_login']))
	{
		return false;
	}
	return true;
}

function isManager()
{
	if (isset($_SESSION['manager_login']))
	{
		return true;
	}
	return false;
}

function isUser()
{
	if (isset($_SESSION['user_login']))
	{
		return true;
	}
	return false;
}