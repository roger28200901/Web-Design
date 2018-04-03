<!doctyp html>
<body>
	<head>
		<?php
		include('connect.php');
		$floor = fa("select * from `floor` where `id`='$_GET[id]'");
		?>
		<meta charset="utf8">
	</head>
	
	<body>
		<div style="border: 1px solid #000; width: 220px; height: 91px">
			<form method="post" action="saveFloor.php">
				<h3 style="margin: 10px">
					<?= $floor['name'] ?>&nbsp;
					<input type="radio" name="offer" value="1"<?= $floor['offer'] ? ' checked' : '' ?>>開放&nbsp;
					<input type="radio" name="offer" value="0"<?= !$floor['offer'] ? ' checked' : '' ?>>關閉
					<input type="hidden" name="id" value="<?= $floor['id'] ?>">
				</h3>
				<input type="submit" value="修改" style="font-size: 18px; background-color: #55F">&nbsp;
				<input type="button" id="cancel" value="取消" style="font-size: 18px; background-color: #55F">
			</form>
		</div>
	</body>
</body>