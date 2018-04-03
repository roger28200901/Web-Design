<?php

include('config.php');

$pdo->query(sprintf('delete from templates where id=%s', $_GET['id']));

header('location:template-index.php');

