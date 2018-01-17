<!doctype html>
<html>
	<head>
		<?php
		include('menu.php');
		if (isnotLogin())
		{
			echo "<script>alert('請先登入'); location.href='login.php'</script>";
		}
		else
		{
			if (isUser())
			{
				header('location: useBookAndCancel.php');
			}
		}
		$floor = fa("select * from `floor` where `id`='$_GET[id]'");
		?>
		<meta charset="utf8">
		<title>樓層管理</title>
	</head>
	
	<body>
		<center>
			<h2>後台管理 - <?= isset($_GET['id']) ? '編輯' : '新增' ?>樓層</h2>
			<form method="post" action="saveFloor.php">
				<table>
					<tr>
						<th align="right">樓層名稱</th>
						<td><input type="text" name="name" value="<?= $floor['name'] ?>" required></td>
					</tr>
					<tr>
						<td align="left">
							<input type="hidden" name="id" value="<?= $_GET['id'] ?>">
							<input type="submit" value="<?= isset($_GET['id']) ? '修改' : '新增' ?>" style="margin-left: 50px; width: 80px" class="ordinary_button">
						</td>
						<td align="right">
							<input type="button" value="取消" onclick="location.href='manageFloor.php'" style="margin-right: 50px; width: 80px" class="ordinary_button">
						</td>
					</tr>
				</table>
			</form>
		</center>
	</body>
</html>