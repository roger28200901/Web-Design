<?php
	include("initial.php");
	session_unset();
	$_SESSION['message'] = 'Logout Successfully';
	header('location: login.php');

