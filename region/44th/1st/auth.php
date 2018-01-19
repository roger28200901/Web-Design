<?php
include('connect.php');
if (isset($_POST['account']))
{
	if ($_POST['yourAnswer'] == $_POST['correctAnswer'])
	{
		if ($member = fa("select * from `member` where `account`='$_POST[account]'"))
		{
			if ($_POST['password'] == $member['password'])
			{
				echo "<script>alert('登入成功！')</script>";
				$_SESSION['member_id'] = $member['id'];
				$_SESSION['member_name'] = $member['name'];
				if ($member['authority'])
				{
					$_SESSION['manager_login'] = true;
					echo "<script>location.href='manageMember.php'</script>";
				}
				else
				{
					$_SESSION['user_login'] = true;
					echo "<script>location.href='useBookAndCancel.php'</script>";
				}
			}
			else
			{
				echo "<script>alert('密碼有誤！'); location.href='login.php'</script>";
			}
		}
		else
		{
			echo "<script>alert('帳號有誤！'); location.href='login.php'</script>";
		}
	}
	else
	{
		echo "<script>alert('驗證碼有誤！'); location.href='login.php'</script>";
	}
}
else if (isset($_POST['answer']))
{
	if ($_POST['yourAnswer'] == $_POST['correctAnswer'])
	{
		if ($member = fa("select * from `member` where `id`='$_POST[id]'"))
		{
			if ($_POST['answer'] == $member['answer'])
			{
				echo "<script>alert('登入成功！')</script>";
				if ($member['authority'])
				{
					$_SESSION['manager_login'] = true;
					echo "<script>location.href='manageMember.php'</script>";
				}
				else
				{
					$_SESSION['user_login'] = true;
					echo "<script>location.href='useBookAndCancel.php'</script>";
				}
			}
			else
			{
				echo "<script>alert('答案有誤！'); location.href='forget.php?account=$member[account]'</script>";
			}
		}
		else
		{
			echo "<script>alert('找不到使用者！'); location.href='login.php'</script>";
		}
	}
	else
	{
		echo "<script>alert('驗證碼有誤！'); location.href='login.php'</script>";
	}
}
else
{
	header('location: login.php');
}