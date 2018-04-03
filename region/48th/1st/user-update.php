<?php

include('config.php');

$pdo->query(sprintf("update users set account='%s', password='%s', name='%s', is_admin='%s' where id='%s'",
    $_POST['account'],
    $_POST['password'],
    $_POST['name'],
    $_POST['is_admin'],
    $_POST['id']
));

header('location:admin-page.php');

