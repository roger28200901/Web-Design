<?php

include('config.php');

if ($_POST['verificationAnswer'] !== $_SESSION['verification_code']) {
    header('location:index.php');
    exit();
}

if (!$user = $pdo->query(sprintf("select * from `users` where `account`='%s'", $_POST['account']))->fetch()) {
    header('location:index.php');
    exit();
}

if ($_POST['password'] !== $user['password']) {
    header('location:index.php');
    exit();
}

$_SESSION['is_login'] = true;
$_SESSION['is_admin'] = $user['is_admin'];

