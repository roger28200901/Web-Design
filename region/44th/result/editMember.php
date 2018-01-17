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
		$member = fa("select * from `member` where `id`='$_GET[id]'");
		?>
		<meta charset="utf8">
		<title><?= isset($_GET['id']) ? '編輯' : '新增' ?>會員</title>
	</head>
	
	<body>
		<center>
			<h2>後台管理 - <?= isset($_GET['id']) ? '編輯' : '新增' ?>會員</h2>
			<form method="post" action="saveMember.php">
				<table>
					<tr>
						<th align="right">帳號</th>
						<td><input type="text" name="account" value="<?= $member['account'] ?>" required<?= $_GET['id'] ? ' readonly' : '' ?>></td>
					</tr>
					<tr>
						<th align="right">密碼</th>
						<td><input type="password" name="password" required></td>
					</tr>
					<tr>
						<th align="right">密碼確認</th>
						<td><input type="password" name="checkPassword" required></td>
					</tr>
					<tr>
						<th align="right">姓名</th>
						<td><input type="text" name="name" value="<?= $member['name'] ?>" required></td>
					</tr>
					<tr>
						<th align="right">密碼提示語</th>
						<td><input type="text" name="hint" value="<?= $member['hint'] ?>" required></td>
					</tr>
					<tr>
						<th align="right">答案</th>
						<td><input type="text" name="answer" value="<?= $member['answer'] ?>" required></td>
					</tr>
					<tr>
						<th align="right">權限</th>
						<td>
							<input type="radio" name="authority" value="1"<?= ($member['authority']) ? ' checked' : '' ?>>管理者&nbsp;
							<input type="radio" name="authority" value="0"<?= (!$member['authority']) ? ' checked' : '' ?>>
							一般會員
						</td>
					</tr>
					<tr>
						<td align="left">
							<input type="hidden" name="id" value="<?= $_GET['id'] ?>">
							<input type="submit" value="<?= isset($_GET['id']) ? '修改' : '新增' ?>" style="margin-left: 50px; width: 80px" class="ordinary_button">
						</td>
						<td align="right">
							<input type="button" value="取消" onclick="location.href='manageMember.php'" style="margin-right: 50px; width: 80px" class="ordinary_button">
						</td>
					</tr>
				</table>
			</form>
		</center>
	</body>
</html>