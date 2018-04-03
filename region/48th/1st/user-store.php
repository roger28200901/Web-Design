<?php

include('config.php');

$pdo->query(sprintf("insert into users (account, password, name, is_admin) values ('%s', '%s', '%s', '%s')",
    $_POST['account'],
    $_POST['password'],
    $_POST['name'],
    $_POST['is_admin']
));

header('location:admin-page.php');

