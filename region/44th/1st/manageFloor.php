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
			$('.class_image').mousemove(function() {
				$(this).css('background-color', '#BBC');
			}).mouseleave(function() {
				$(this).css('background-color', '#99A');
			}).click(function() {
				$('.class_image').mousemove(function() {
					$(this).css('background-color', '#BBC');
				}).mouseleave(function() {
					$(this).css('background-color', '#99A');
				});
				$('.class_image').css('background-color', '#99A');
				$(this).off('mousemove').off('mouseleave').css('background-color', '#779');
				var id = $(this).attr('id');
				var floor = $(this).parent().attr('id');
				$.ajax({
					url: 'checkOffer.php?id='+id,
					success: function(result)
					{
						$('[id^="offer"]').text('');
						$('#offer'+floor).append(result);
					}
				})
			});
			$('body').on('click', '[type="submit"]', function(e) {
				if ($('[name="offer"]:checked').val() == 0)
				{
					var id = $('[name="id"]').val();
					$.ajax({
						url: 'beforeClose.php?id='+id,
						success: function(result)
						{
							if (result == '')
							{
								$('form').submit();
							}
							else
							{
								if (confirm(result))
								{
									$('form').submit();
								}
							}
						}
					});
					return false;
				}
			}).on('click', '#cancel', function() {
				$('[id^="offer"]').text('');
				$('.class_image').css('background-color', '#99A');
			});
			$('[id^="delete"]').click(function() {
				if (confirm('確定刪除？'))
				{
					var id = $(this).attr('id').substr(6);
					$.ajax({
						url: 'beforeClose.php?id='+id,
						success: function(result)
						{
							if (result == '')
							{
								location.href='saveFloor.php?delete&id='+id;
							}
							else
							{
								if (confirm(result))
								{
									location.href='saveFloor.php?delete&id='+id;
								}
							}
						}
					})
				}
			});
		})
		</script>
		<meta charset="utf8">
		<title>樓層管理</title>
	</head>
	
	<body>
		<center>
			<h2>
				後台管理 - 樓層管理系統&nbsp;
				<button onclick="location.href='editFloor.php'" class="ordinary_button">新增樓層</button>
			</h2>
			<?php
			$floors = faa("select * from `floor` where `parent`='0'");
			foreach ($floors as $floor)
			{
				?>
				<div class="floor_div">
					<center>
						<h2>
							<?= $floor['name'] ?>&nbsp;
							<button  onclick="location.href='editFloor.php?id=<?= $floor['id'] ?>'" class="ordinary_button">編輯</button>&nbsp;
							<button id="delete<?= $floor['id'] ?>" class="ordinary_button">刪除</button>
						</h2>
						<h3>樓層平面圖</h3>
						<div id="<?= $floor['id'] ?>" class="floor_image">
							<?php
							$classes = faa("select * from `floor` where `parent`='$floor[id]'");
							$ids = array();
							foreach($classes as $class)
							{
								$ids[] = $class['id'];
							}
							?>
							<div id="<?= $ids[0] ?>" class="class_image">1室</div>
							<div id="<?= $ids[1] ?>" class="class_image">2室</div>
							<div id="<?= $ids[2] ?>" class="class_image">3室</div>
							<div id="offer<?= $floor['id'] ?>" style="height: 91px; margin: 20px 0px;"></div>
							<div id="<?= $ids[3] ?>" class="class_image">4室</div>
							<div id="<?= $ids[4] ?>" class="class_image">5室</div>
							<div id="<?= $ids[5] ?>" class="class_image">6室</div>
						</div>
					</center>
				</div>
				<p>
				<?php
			}
			?>
		</center>
	</body>
</html>