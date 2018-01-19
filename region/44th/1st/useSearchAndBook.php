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
			var currentId = 0;
			checkTime();
			$('#date').change(function() {
				checkTime();
			});
			$('#clear').click(function() {
				$('#date').val($('#today').val());
				checkTime();
			});
			$('#sure').click(function() {
				if ($('#time').val())
				{
					var date = $('#date').val();
					var time = $('#time').val();
					$('#search').fadeToggle(100);
					$.ajax({
						url: 'showSearch.php?date='+date+'&time='+time,
						success: function(result)
						{
							$('#showSearch').append(result).fadeToggle(500);
						}
					});
					return true;
				}
				alert('請選擇時段！');
				return false;
			});
			$('body').on('click', '[id^="browseFloor"]', function() {
				var date = $('#date').val();
				var time = $('#time').val();
				currentId = $(this).attr('id').substr(11);
				$.ajax({
					url: 'showBrowse.php?date='+date+'&time='+time+'&id='+currentId,
					success: function(result)
					{
						$('#showSearch').fadeToggle(100);
						$('#showBrowse').append(result).fadeToggle(500);
						$('#'+currentId).css('background-color', '#779');
					}
				});
			}).on('mousemove', '.class_image', function() {
				$(this).css('background-color', '#BBC');
			}).on('mouseleave', '.class_image', function() {
				$(this).css('background-color', '#99A');
				$('#'+currentId).css('background-color', '#779');
			}).on('click', '#back', function() {
				$('#showBrowse').empty().fadeToggle(100);
				$('#showSearch').fadeToggle(500);
			}).on('click', '#reSearch', function() {
				$('#search').slideToggle(500);
				$('#showSearch').hide().empty();
			}).on('click', '.bookable, [id^="book"]', function() {
				if (confirm('確定預約？'))
				{
					var date = $('#date').val();
					var time = $('#time').val();
					var id = $(this).attr('id');
					if (id.indexOf('book') > -1)
					{
						id = id.substr(4);
					}
					location.href='saveBook.php?book&date='+date+'&time='+time+'&id='+id;
				}
			});
		})
		function checkTime()
		{
			$('#time').empty();
			if ($('#today').val() < $('#date').val())
			{
				for (i = 10; i < 18; i += 2)
				{
					var option = '<option>'+i+':00-'+(i + 2)+':00</option>';
					$('#time').append(option);
				}
			}
			else
			{
				var hour = $('#hour').val();
				for (i = 10; i < 18; i += 2)
				{
					if (hour < i)
					{
						var option = '<option>'+i+':00-'+(i + 2)+':00</option>';
						$('#time').append(option);
					}
				}
			}
		}
		</script>
		<meta charset="utf8">
		<title>查詢/預約</title>
	</head>
	
	<body>
		<center>
			<h2>前台服務 - 預約單/取消</h2>
			<hr style="width: 600px">
			<div id="search">
				<p style="font-size: 24px">請選擇日期及時段</p>
				<table>
					<tr>
						<th>日期</th>
						<td>
							<select id="date" style="width: 145px">
								<?php
								$today = date("Y/m/d");
								for ($i = 0; $i < 7; $i++)
								{
									?>
									<option><?= date("Y/m/d", strtotime($today) + $i * 86400); ?></option>
									<?php
								}
								?>
							</select>
							<input type="hidden" id="today" value="<?= $today ?>">
						</td>
					</tr>
					<tr>
						<th>時段</th>
						<td>
							<select id="time" style="width: 145px"></select>
							<input type="hidden" id="hour" value="<?= date('H') ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="button" id="sure" value="確定" class="ordinary_button">&nbsp;
							<input type="button" id="clear" value="清除" class="ordinary_button">
						</td>
					</tr>
				</table>
			</div>
			<div id="showSearch" hidden>
				<p style="font-size: 24px"><b>查詢結果 - </b>可預約的會議室</p>
			</div>
			<div id="showBrowse" hidden>
			</div>
		</center>
	</body>
</html>