<?php
	include("initial.php");
	$characters = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z'));
	shuffle($characters);
	$verification_code = $characters[0] . $characters[1] . $characters[2] . $characters[3];
	$_SESSION['verification_code'] = $verification_code;
	echo $verification_code;

