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
			$('#sure').click(function() {
				var id = $('#floor').val();
				$.ajax({
					url: 'browseTable.php?id='+id,
					success: function(result)
					{
						$('#calender').empty();
						$('#browse').empty().append(result).fadeIn(500);
					}
				})
			});
		});
		</script>
		<meta charset="utf8">
		<title>會議室瀏覽</title>
	</head>
	
	<body>
		<center>
			<h1>前台服務 - 會議室瀏覽</h1>
			<hr style="width: 600px">
			<h2>
				請選擇樓層
				<select id="floor" style="width: 100px; font-size: 22px; text-align-last: center">
					<?php
					$floors = faa("select * from `floor` where `parent`='0'");
					foreach ($floors as $floor)
					{
						?>
						<option value="<?= $floor['id'] ?>"><?= $floor['name'] ?></option>
						<?php
					}
					?>
				</select>
				&nbsp;
				<button id="sure" class="ordinary_button">確定</button>
			</h2>
		</center>
		<div style="display: flex">
			<div id="browse" style="width: 45%" hidden></div>
			<div id="calender" style="width: 53%" hidden></div>
		</div>
	</body>
</html>