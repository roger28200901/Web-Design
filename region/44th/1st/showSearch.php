<!doctype html>
<html>
	<head>
		<?php
		include('connect.php');
		$date = $_GET['date'];
		$time = $_GET['time'];
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
	</head>
	
	<body>
		<table width="60%" cellspacing="0px">
			<tr class="tr_underline">
				<th width="18%">使用日期<br>(年/月/日)</th>
				<th width="18%">使用時段</th>
				<th width="18%">會議室號碼</th>
				<th width="15%"></th>
			</tr>
			<?php
			$floors = faa("select * from `floor` where `parent`='0'");
			foreach ($floors as $floor)
			{
				$classes = faa("select * from `floor` where `parent`='$floor[id]'");
				foreach ($classes as $class)
				{
					if ($class['offer'] && !fa("select * from `book` where `date`='$date' and `time`='$time' and `classId`='$class[id]'"))
					{
						?>
						<tr class="tr_underline tr_hover">
							<td><?= $date ?></td>
							<td><?= $time ?></td>
							<td id="browseFloor<?= $class['id'] ?>"><?= sprintf('%s-%s', $floor['name'], $class['name']) ?></td>
							<td><button id="book<?= $class['id'] ?>" class="ordinary_button">預約</button></td>
						</tr>
						<?php
					}
				}
			}
			?>
		</table>
		<p>
		<button id="reSearch" style="width: 150px; height: 35px" class="ordinary_button">重新查詢</button>
	</body>
</html>