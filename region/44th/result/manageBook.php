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
		$sql = "select * from `book`";
		if ($_GET['date'])
		{
			$sql .=  " where `classId`='$_GET[class]'";
			if ($_GET['date'] != '')
			{
				$sql .= " and `date`='$_GET[date]'";
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
			$('#search').click(function() {
				$('#searchBar').slideToggle(500);
			});
			$('#clear').click(function() {
				$('[name="date"]').val('');
			});
		})
		</script>
		<meta charset="utf8">
		<title>預約單管理</title>
	</head>
	
	<body>
		<center>
			<h2>後台管理 - 預約單管理系統</h2>
			<button id="search" style="width: 20%; margin: 10px" class="ordinary_button">搜尋</button>
			<form method="get" id="searchBar" style="border: 1px solid #CCC; width: 20%; padding: 10px; margin: 10px"<?= isset($_GET['date']) ? '' : ' hidden' ?>>
				<table>
					<tr>
						<th>日期：</th>
						<td><input type="date" name="date" value="<?= $_GET['date'] ?>"></td>
					</tr>
					<tr>
						<th>會議室：</th>
						<td>
							<select name="class">
								<?php
								$floors = faa("select * from `floor` where `parent`='0'");
								foreach ($floors as $floor)
								{
									$classes = faa("select * from `floor` where `parent`='$floor[id]'");
									foreach ($classes as $class)
									{
										?>
										<option value="<?= $class['id'] ?>"<?= $_GET['class'] == $class['id'] ? ' selected' : '' ?>><?= "$floor[name]-$class[name]" ?></option>
										<?php
									}
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="確定" class="ordinary_button">&nbsp;
							<input type="button" id="clear" value="清除" class="ordinary_button">
						</td>
					</tr>
				</table>
			</form>
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
				$books = faa($sql);
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