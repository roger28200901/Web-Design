<div>
	<script src="jquery-3.2.1.min.js"></script>
	<a href="index.html">Back To Home</a>
	<?php
		include("initial.php");
		if (isLogin()) {
			?>
				<a href="logout.php">Logout</a>
			<?php
		} else {
			?>
				<a href="login.php">Login</a>
			<?php
		}
	?>
</div>

