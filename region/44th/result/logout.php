<?php
include('connect.php');
unset($_SESSION['manager_login']);
unset($_SESSION['user_login']);
unset($_SESSION['member_id']);
unset($_SESSION['member_name']);
echo "<script>alert('登出成功！'); location.href='index.php'</script>";