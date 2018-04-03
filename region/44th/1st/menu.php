<!doctype html>
<html>
	<head>
		<?php
		include('connect.php');
		$url = pathinfo($_SERVER['SCRIPT_NAME']);
		$url = str_replace('.php', '', $url['basename']);
		switch ($url)
		{
			case 'manageMember':
			case 'editMember':
				$page = 'manage_member';
				break;
			case 'manageFloor':
			case 'editFloor':
				$page = 'manage_floor';
				break;
			case 'manageBook':
				$page = 'manage_book';
				break;
			case 'useBookAndCancel':
				$page = 'use_bookAndCancel';
				break;
			case 'useSearchAndBook':
				$page = 'use_searchAndBook';
				break;
			case 'useBrowse':
				$page = 'use_browse';
				break;
		}
		?>
		<meta charset="utf8">
		<link rel="stylesheet" href="style.css">
		<script src="jquery-1.12.1.min.js"></script>
		<script>
		$(function() {
			$('#<?= $page ?>').css('background-color', '#55F');
			$('.menu_item').mousemove(function() {
				$(this).css('background-color', '#AAF');
			}).mouseleave(function() {
				if ('<?= $page ?>' == $(this).attr('id'))
				{
					$(this).css('background-color', '#55F');
				}
				else
				{
					$(this).css('background-color', '#FFF');
				}
			});
		})
		</script>
	</head>
	
	<body>
		<h1 style="text-align: center">會議室預約系統</h1>
		<?php
		if (isset($_SESSION['manager_login']))
		{
			?>
			<button onclick="location.href='logout.php'" class="status_button">管理者登出</button>
			<?php
		}
		else if (isset($_SESSION['user_login']))
		{
			?>
			<button onclick="location.href='logout.php'" class="status_button">會員登出</button>
			<?php
		}
		else
		{
			?>
			<button onclick="location.href='login.php'" class="status_button">會員登入</button>
			<?php
		}
		?>
		<hr>
		<?php
		if (isset($_SESSION['manager_login']))
		{
			?>
			<table width="100%" cellspacing="0px">
				<tr>
					<th id="manage_member" onclick="location.href='manageMember.php'" class="menu_item">會員管理</th>
					<th id="manage_floor" onclick="location.href='manageFloor.php'" class="menu_item">樓層管理</th>
					<th id="manage_book" onclick="location.href='manageBook.php'" class="menu_item">預約單管理</th>
				</tr>
			</table>
			<hr>
			<?php
		}
		else if (isset($_SESSION['user_login']))
		{
			?>
			<table width="100%" cellspacing="0px">
				<tr>
					<th id="use_bookAndCancel" onclick="location.href='useBookAndCancel.php'" class="menu_item">預約單/取消</th>
					<th id="use_searchAndBook" onclick="location.href='useSearchAndBook.php'" class="menu_item">查詢/預約</th>
					<th id="use_browse" onclick="location.href='useBrowse.php'" class="menu_item">會議室瀏覽</th>
				</tr>
			</table>
			<hr>
			<?php
		}
		?>
		<br>
	</body>
</html>