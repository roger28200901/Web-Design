<?php
include('connect.php');
if (isset($_POST['id']))
{
	$member = fa("select * from `member` where `account`='$_POST[account]'");
	if ($member && $_POST['id'] != $member['id'])
	{
		if ($_POST['id'] != '')
		{
			$id = "?id=$_POST[id]";
		}
		echo "<script>alert('帳號重複！'); location.href='editMember.php$id'</script>";
	}
	else
	{
		if ($_POST['password'] == $_POST['checkPassword'])
		{
			if ($_POST['id'] == '')
			{
				qr("insert `member` (`account`, `password`, `name`, `hint`, `answer`, `authority`) values ('$_POST[account]', '$_POST[password]', '$_POST[name]', '$_POST[hint]', '$_POST[answer]', '$_POST[authority]')");
				echo "<script>alert('會員新增成功！')</script>";
			}
			else
			{
				qr("update `member` set `password`='$_POST[password]', `name`='$_POST[name]', `hint`='$_POST[hint]', `answer`='$_POST[answer]', `authority`='$_POST[authority]' where `id`='$_POST[id]'");
				echo "<script>alert('會員編輯成功！')</script>";
			}
			echo "<script>location.href='manageMember.php'</script>";
		}
		else
		{
			echo "<script>alert('兩次輸入的密碼不同！'); location.href='editMember.php'</script>";
		}
	}
}
else if (isset($_GET['delete']))
{
	qr("delete from `member` where `id`='$_GET[id]'");
	echo "<script>alert('刪除成功！'); location.href='manageMember.php'</script>";
}
else
{
	header('location: manageMember.php');
}