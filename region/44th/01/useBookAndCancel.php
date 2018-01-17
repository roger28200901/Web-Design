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
			if (isManager())
			{
				header('location: manageMember.php');
			}
		}
		?>
		<script>
		$(function() {
			$('.tr_hover').mousemove(function() {
				$(this).css('background-color', '#DDD');
			}).mouseleave(function() {
				$(this).css('background-color', '#FFF');
			});
		})
		</script>
		<meta charset="utf8">
		<title>預約單/取消</title>
	</head>
	
	<body>
		<center>
			<h2>前台服務 - 預約單/取消</h2>
			<table width="60%" cellspacing="0px">
				<tr class="tr_underline">
					<th width="18%">預約單編號</th>
					<th width="18%">使用日期<br>(年/月/日)</th>
					<th width="18%">使用時段</th>
					<th width="18%">會議室號碼</th>
					<th width="18%">借用人</th>
					<th width="15%"></th>
				</tr>
				<?php
				$books = faa("select * from `book` where `name`='$_SESSION[member_name]' order by `id` desc");
				foreach ($books as $book)
				{
					?>
					<tr class="tr_underline tr_hover">
						<td><?= sprintf('%07d', $book['id']); ?></td>
						<td><?= str_replace('-', '/', $book['date']) ?></td>
						<td><?= $book['time'] ?></td>
						<td><?php
							$floor = fa("select * from `floor` where `id`='$book[floorId]'");
							$class = fa("select * from `floor` where `id`='$book[classId]'");
							echo "$floor[name]-$class[name]";
						?></td>
						<td><?= $book['name'] ?></td>
						<td><button onclick="if (confirm('確定取消？')) location.href='saveBook.php?delete&id=<?= $book['id'] ?>'" class="ordinary_button">取消</button></td>
					</tr>
					<?php
				}
				?>
			</table>
		</center>
	</body>
</html>