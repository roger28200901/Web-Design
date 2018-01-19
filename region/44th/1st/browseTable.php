<!doctype html>
<html>
	<head>
		<?php
		include('connect.php');
		$floor = fa("select * from `floor` where `id`='$_GET[id]'");
		unset($_SESSION['month']);
		?>
		<meta charset="utf8">
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
				$.ajax({
					url: 'calender.php?id='+id,
					success: function(result)
					{
						$('#calender').empty().append(result).fadeIn(500);
					}
				})
			});
		});
		</script>
		<meta charset="utf8">
	</head>
	<body>
		<center>
			<h3><?= $floor['name'] ?>平面圖</h3>
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
				<div style="height: 91px; margin: 20px 0px;"></div>
				<div id="<?= $ids[3] ?>" class="class_image">4室</div>
				<div id="<?= $ids[4] ?>" class="class_image">5室</div>
				<div id="<?= $ids[5] ?>" class="class_image">6室</div>
			</div>
		</center>
	</body>
</html>