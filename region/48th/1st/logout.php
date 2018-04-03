<?php

include('config.php');

$pdo->query(sprintf("insert into logs (user_id, action, created_at) values ('%s', '登出', current_timestamp)", $_SESSION['user_id']));

session_destroy();

header('location:index.php');

