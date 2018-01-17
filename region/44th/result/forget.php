<!doctype html>
<html>
	<head>
		<?php
		include('menu.php');
		if (isLogin())
		{
			header('location: manageMember.php');
		}
		if (isset($_GET['account']))
		{
			if (!$member = fa("select * from `member` where `account`='$_GET[account]'"))
			{
				echo "<script>alert('找不到使用者！'); location.href='login.php'</script>";
			}
		}
		else
		{
			header('location: login.php');
		}
		?>
		<script>
		$(function() {
			$('#renew').click(function() {
				$.ajax({
					url: 'renew.php',
					success: function(result)
					{
						$('[name="correctAnswer"]').val(result.substr(4));
						for (i = 0; i < 4; i++)
						{
							var no = result.charAt(i);
							$('#img'+i).attr({'src':'drawNo.php?no='+no, 'no':no});
						}
					}
				})
			})
			$('#renew').click();
			$('img').click(function() {
				$('[name="yourAnswer"]').val($('[name="yourAnswer"]').val() + $(this).attr('no'));
			})
		})
		</script>
		<meta charset="utf8">
		<title>忘記密碼</title>
	</head>
	
	<body>
		<center>
			<form method="post" action="auth.php">
				<table>
					<tr>
						<th align="right">帳號</th>
						<td colspan="2" style="font-size: 22px"><?= $member['account'] ?></td>
					</tr>
					<tr>
						<th align="right">密碼提示語</th>
						<td colspan="2" style="font-size: 22px"><?= $member['hint'] ?></td>
					</tr>
					<tr>
						<th align="right">您的答案</th>
						<td colspan="2"><input type="text" name="answer"  required></td>
					</tr>
					<tr>
						<td align="right">
							<b>認證碼圖片</b>
							<br>
							<font size="0">
								(由左到右由大到小<br>正確的排列順序)
							</font>
						</td>
						<td>
							<img id="img0" width="50px" height="50px">
							<img id="img1" width="50px" height="50px">
							<img id="img2" width="50px" height="50px">
							<img id="img3" width="50px" height="50px">
						</td>
						<td><input type="button" id="renew" value="重新產生" class="ordinary_button"></td>
					</tr>
					<tr>
						<th align="right">認證碼作答區</th>
						<td colspan="2">
							<input type="text" name="yourAnswer" required>
							<input type="hidden" name="correctAnswer">
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2">
							<input type="submit" value="確定" class="ordinary_button">
							&nbsp;
							<input type="button" value="取消" onclick="location.href='login.php'" class="ordinary_button">
							<input type="hidden" name="id" value="<?= $member['id'] ?>">
						</td>
					</tr>
				</table>
			</form>
		</center>
	</body>
</html>