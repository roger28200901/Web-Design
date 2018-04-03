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
		?>
		<script>
		$(function() {
			$('.tr_hover').mousemove(function() {
				$(this).css('background-color', '#DDD');
			}).mouseleave(function() {
				$(this).css('background-color', '#FFF');
			})
		})
		</script>
		<meta charset="utf8">
		<title>會員管理</title>
	</head>
	
	<body>
		<center>
			<h2>
				後台管理 - 會員管理系統&nbsp;
				<button onclick="location.href='editMember.php'" class="ordinary_button">新增會員</button>
			</h2>
			<table width="60%" cellspacing="0px">
				<tr class="tr_underline">
					<th width="15%">帳號</th>
					<th width="15%">姓名</th>
					<th width="15%">密碼提示語</th>
					<th width="13%">答案</th>
					<th width="12%">權限</th>
					<th width="15%"></th>
				</tr>
				<?php
				$members = faa("select * from `member`");
				foreach ($members as $member)
				{
					?>
					<tr class="tr_underline tr_hover">
						<td><?= $member['account'] ?></td>
						<td><?= $member['name'] ?></td>
						<td><?= $member['hint'] ?></td>
						<td><?= $member['answer'] ?></td>
						<td><?= $member['authority'] ? '管理者' : '一般會員' ?></td>
						<td>
							<?php
							if ($member['account'] != 'admin')
							{
								?>
								<input type="button" value="編輯" onclick="location.href='editMember.php?id=<?= $member['id'] ?>'" class="ordinary_button">
								<?php
								if ($member['id'] != $_SESSION['member_id'])
								{
								?>
								<input type="button" value="刪除" onclick="if (confirm('確定刪除？')) location.href='saveMember.php?delete&id=<?= $member['id'] ?>'" class="ordinary_button">
								<?php
								}
							}
							?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</center>
	</body>
</html>