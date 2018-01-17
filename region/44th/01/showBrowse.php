<!doctype html>
<html>
	<head>
		<?php
		include('connect.php');
		$date = $_GET['date'];
		$time = $_GET['time'];
		$class = fa("select * from `floor` where `id`='$_GET[id]'");
		$floor = fa("select * from `floor` where `id`='$class[parent]'");
		?>
		<meta charset="utf8">
	</head>
	
	<body>
		<h3><?= $floor['name'] ?>平面圖</h3>
		<div style="width: 50%" class="floor_image">
			<?php
			$classes = faa("select * from `floor` where `parent`='$class[parent]'");
			$ids = array();
			$book = array();
			foreach($classes as $class)
			{
				$ids[] = $class['id'];
				if (!$class['offer'])
				{
					$book[] = 2;
				}
				else if (fa("select * from `book` where `date`='$date' and `time`='$time' and `classId`='$class[id]'"))
				{
					$book[] = 1;
				}
				else
				{
					$book[] = 0;
				}
			}
			?>
			<div id="<?= $ids[0] ?>" class="class_image<?= !$book[0] ? ' bookable' : '' ?>">1室<br><?php
			switch ($book[0])
			{
				case 0:
					echo '可預約';
					break;
				case 1:
					echo '已被預約';
					break;
				case 2:
					echo '未開放';
					break;
			}
			?></div>
			<div id="<?= $ids[1] ?>" class="class_image<?= !$book[1] ? ' bookable' : '' ?>">2室<br><?php
			switch ($book[1])
			{
				case 0:
					echo '可預約';
					break;
				case 1:
					echo '已被預約';
					break;
				case 2:
					echo '未開放';
					break;
			}
			?></div>
			<div id="<?= $ids[2] ?>" class="class_image<?= !$book[2] ? ' bookable' : '' ?>">3室<br><?php
			switch ($book[2])
			{
				case 0:
					echo '可預約';
					break;
				case 1:
					echo '已被預約';
					break;
				case 2:
					echo '未開放';
					break;
			}
			?></div>
			<div style="height: 91px; margin: 20px 0px;"></div>
			<div id="<?= $ids[3] ?>" class="class_image<?= !$book[3] ? ' bookable' : '' ?>">4室<br><?php
			switch ($book[3])
			{
				case 0:
					echo '可預約';
					break;
				case 1:
					echo '已被預約';
					break;
				case 2:
					echo '未開放';
					break;
			}
			?></div>
			<div id="<?= $ids[4] ?>" class="class_image<?= !$book[4] ? ' bookable' : '' ?>">5室<br><?php
			switch ($book[4])
			{
				case 0:
					echo '可預約';
					break;
				case 1:
					echo '已被預約';
					break;
				case 0:
					echo '未開放';
					break;
			}
			?></div>
			<div id="<?= $ids[5] ?>" class="class_image<?= !$book[5] ? ' bookable' : '' ?>">6室<br><?php
			switch ($book[5])
			{
				case 0:
					echo '可預約';
					break;
				case 1:
					echo '已被預約';
					break;
				case 0:
					echo '未開放';
					break;
			}
			?></div>
		</div>
		<p>
		<button id="back" style="width: 200px; height: 35px" class="ordinary_button">回查詢結果頁面</button>
	</body>
</html>