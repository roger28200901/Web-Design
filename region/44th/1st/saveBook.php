<?php
include('connect.php');
if (isset($_GET['book']))
{
	$class = fa("select * from `floor` where `id`='$_GET[id]'");
	qr("insert `book` (`date`, `time`, `floorId`, `classId`, `name`) values ('$_GET[date]', '$_GET[time]', '$class[parent]', '$class[id]', '$_SESSION[member_name]')");
	echo "<script>alert('預約成功！'); location.href='useBookAndCancel.php'</script>";
}
else if (isset($_GET['delete']))
{
	qr("delete from `book` where `id`='$_GET[id]'");
	echo "<script>alert('取消成功！')</script>";
	if ($_SESSION['manager_login'])
	{
		echo "<script>location.href='manageBook.php'</script>";
	}
	else
	{
		echo "<script>location.href='useBookAndCancel.php'</script>";
	}
}
else
{
	if ($_SESSION['manager_login'])
	{
		header('location: manageBook');
	}
	else
	{
		header('location: useBookAndCancel');
	}
}