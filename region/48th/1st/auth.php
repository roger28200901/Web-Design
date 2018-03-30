<?php

include('config.php');

if (!isset($_SESSION['login_times'])) {
    $_SESSION['login_times'] = 0;
}
$_SESSION['login_times']++;

if ($_POST['verificationAnswer'] !== $_SESSION['verification_code']) {
    header('location:login.php');
    exit();
}

if (!$user = $pdo->query(sprintf("select * from users where account='%s'", $_POST['account']))->fetch()) {
    header('location:login.php');
    exit();
}

if ($_POST['password'] !== $user['password']) {
    header('location:login.php');
    exit();
}

unset($_SESSION['login_times']);
$_SESSION['is_admin'] = $user['is_admin'];
$_SESSION['user_id'] = $user['id'];

$pdo->query(sprintf("insert into logs (user_id, action, created_at) values ('%s', '登入', current_timestamp)", $user['id']));

$location = $_SESSION['is_admin'] ? 'admin-page.php' : 'user-logged.php'; 
header("location:$location");

