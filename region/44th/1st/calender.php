<!doctype html>
<html>
	<head>
		<?php
		include('connect.php');
		if (!isset($_SESSION['month']))
		{
			$_SESSION['year'] = date('Y');
			$_SESSION['month'] = date('m');
		}
		if ($_GET['preMonth'])
		{
			$_SESSION['month'] = sprintf('%02d', $_SESSION['month'] - 1);
			if ($_SESSION['month'] == 0)
			{
				$_SESSION['year']--;
				$_SESSION['month'] = 12;
			}
		}
		else if ($_GET['nextMonth'])
		{
			$_SESSION['month'] = sprintf('%02d', $_SESSION['month'] + 1);
			if ($_SESSION['month'] > 12)
			{
				$_SESSION['year']++;
				$_SESSION['month'] = 1;
			}
		}
		$class = fa("select * from `floor` where `id`='$_GET[id]'");
		$floor = fa("select * from `floor` where `id`='$class[parent]'");
		?>
		<script>
		$(function() {
			$('.tr_hover td').mousemove(function() {
				$(this).css('background-color', '#DDD');
			}).mouseleave(function() {
				$(this).css('background-color', '#FFF');
			});
			$('#preMonth, #nextMonth').click(function() {
				var id = '<?= $class['id'] ?>';
				var method = $(this).attr('id');
				$.ajax({
					url: 'calender.php?'+method+'=true&id='+id,
					success: function(result)
					{
						$('#calender').empty().append(result);
						$(this).off('click');
					}
				});
			});
			$('[id^="time"]').click(function() {
				if (confirm('確定預約？'))
				{
					var time = $(this).attr('id').substr(4);
					var date = $(this).parent().attr('id');
					var id = '<?= $class['id'] ?>';
					location.href='saveBook.php?book&date='+date+'&time='+time+'&id='+id;
				}
			});
		});
		</script>
		<link rel="stylesheet" href="style.css">
		<meta charset="utf8">
	</head>
	
	<body>
		<center>
			<h2>
				<?= $_SESSION['year'] . '年' . $_SESSION['month'] . '月' ?>
				<p>
				<button id="preMonth" class="ordinary_button">前一個月</button>
				<span style="border: 2px solid #669; padding: 5px; margin: 5px"><?= sprintf('%s', "$floor[name]-$class[name]"); ?></span>
				<button id="nextMonth" class="ordinary_button">後一個月</button>
			</h2>
			<table width="90%" cellspacing="0px">
				<tr class="tr_underline">
					<th>星期日</th>
					<th>星期一</th>
					<th>星期二</th>
					<th>星期三</th>
					<th>星期四</th>
					<th>星期五</th>
					<th>星期六</th>
				</tr>
				<?php
				$today = "$_SESSION[year]/$_SESSION[month]/01";
				/*echo "<script>alert('$today')</script>";*/
				$dayOfWeek = date('D', strtotime($today));
				while ($dayOfWeek != 'Sun')
				{
					$today = date('Y/m/d', strtotime($today) - 86400);
					$dayOfWeek = date('D', strtotime($today));
				}
				for ($i = 0; $i < 42; $i++)
				{
					if ($i % 7 == 0)
					{
						if ($i > 0)
						{
							?>
							</tr>
							<?php
						}
						if ($i < 41)
						{
							?>
							<tr align="center" class="tr_underline tr_hover">
							<?php
						}
					}
					$book = array();
					$times = array('10:00-12:00', '12:00-14:00', '14:00-16:00', '16:00-18:00');
					$date = date('Y/m/d', strtotime($today));
					foreach ($times as $time)
					{
						if (fa("select * from `book` where `date`='$date' and `time`='$time' and `classId`='$class[id]'"))
						{
							$book[] = 1;
						}
						else if (!$class['offer'])
						{
							$book[] = 2;
						}
						else
						{
							$book[] = 0;
						}
					}
					?>
					<td id="<?= date('m') == date('m', strtotime($today)) ? $date : '' ?>">
						<?php
						if ($_SESSION['month'] == date('m', strtotime($today)))
						{
							echo '<h2>' . date('d', strtotime($today)) . '</h2>';
							foreach ($times as $index => $time)
							{
								?>
								<div id="<?= $book[$index] ? '' : "time$time" ?>"><?= "$time<br>" ?><?php
								switch ($book[$index])
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
								<br>
								<?php
							}
						}
						?>
					</td>
					<?php
					$today = date('m/d', strtotime($today) + 86400);
				}
				?>
			</table>
		</center>
	</body>
</html>