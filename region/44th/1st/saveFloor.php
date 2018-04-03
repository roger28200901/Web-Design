<?php
include('connect.php');
if (isset($_POST['id']))
{
	$floor = fa("select * from `floor` where `name`='$_POST[name]'");
	if ($floor && $_POST['id'] != $floor['id'])
	{
		if ($_POST['id'] != '')
		{
			$id = "?id=$_POST[id]";
		}
		echo "<script>alert('樓層名稱重複！'); location.href='editFloor.php$id'</script>";
	}
	else
	{
		if ($_POST['id'] == '')
		{
			qr("insert `floor` (`name`) values ('$_POST[name]')");
			$floor = fa("select * from `floor` order by `id` desc");
			for ($i = 1; $i <= 6; $i++)
			{
				qr("insert `floor` (`parent`, `name`, `offer`) values ('$floor[id]', '{$i}室', '1')");
			}
			echo "<script>alert('樓層新增成功！')</script>";
		}
		else
		{
			$floor = fa("select * from `floor` where `id`='$_POST[id]'");
			if ($floor['parent'])
			{
				if (!$_POST['offer'])
				{
					qr("delete from `book` where `classId`='$_POST[id]'");
				}
				qr("update `floor` set `offer`='$_POST[offer]' where `id`='$_POST[id]'");
				echo "<script>alert('會議室編輯成功！')</script>";
			}
			else
			{
				qr("update `floor` set `name`='$_POST[name]' where `id`='$_POST[id]'");
				echo "<script>alert('樓層編輯成功')</script>";
			}
		}
		echo "<script>location.href='manageFloor.php'</script>";
	}
}
else if (isset($_GET['delete']))
{
	qr("delete from `book` where `floorId`='$_GET[id]'");
	qr("delete from `floor` where `id`='$_GET[id]' or `parent`='$_GET[id]'");
	echo "<script>alert('刪除成功！'); location.href='manageFloor.php'</script>";
}
else
{
	header('location: manageFloor.php');
}