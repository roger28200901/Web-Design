<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<title>Login</title>
	</head>

	<body>
		<?php
			include("members-layout.php");
		?>
		<form action="auth.php" method="post">
			<div>
				<label for="account">
					<span>Account</span>
					<input name="account" id="account" type="text" required>
				</label>
			</div>
			<div>
				<label for="password">
					<span>Password</span>
					<input name="password" id="password" type="password" required>
				</label>
			</div>
			<div>
				<span>Verification Code</span>
			</div>
			<div>
				<img id="verificationCode0">
				<img id="verificationCode0">
				<img id="verificationCode0">
				<img id="verificationCode0">
				<input id="renew" type="button" value="Generate">
			</div>
			<div>
				<label for="answer">
					<span>Your Answer</span>
					<input name="answer" id="answer" type="text" required>
				</label>
			</div>
			<div>
				<input type="submit" value="Login">
				<input type="reset" value="Reset">
			</div>
		</form>
	</body>
</html>

