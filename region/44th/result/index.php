<!doctype html>
<html>
	<head>
		<?php
		include('menu.php');
		if (isLogin())
		{
			header('location: manageMember.php');
		}
		?>
		<meta charset="utf8">
		<title>首頁</title>
	</head>
	
	<body>
		<center>
			<button onclick="location.href='login.php'" class="ordinary_button">會員登入</button>
		</center>
	</body>
</html>