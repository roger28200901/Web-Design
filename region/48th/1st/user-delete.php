<?php

include('config.php');

$pdo->query(sprintf("delete from users where id='%s'", $_GET['id']));

header('location:admin-page.php');

