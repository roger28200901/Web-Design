<?php
	include("initial.php");	
	if (checkAuth()) {
		header('location: members.php');
		exit();
	}
	header('location: login.php');
	exit();

	function checkAuth()
	{
		if (!$member = fetch("select * from `members` where `account`='$_POST[account]'")) {
			$_SESSION['message'] = 'CAN\'T NOT FOUND OR WRONG PASSWORD';
			return false;
		}
		if ($member['password'] != $_POST['password']) {
			$_SESSION['message'] = 'CAN\'T NOT FOUND OR WRONG PASSWORD';
			return false;
		}
		$_SESSION['message'] = 'Login Successfully';
		$_SESSION['is_login'] = true;
		if ($member['is_admin']) {
			$_SESSION['is_admin'] = true;
		}
		return true;
	}

