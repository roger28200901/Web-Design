<?php

include('config.php');

$template = $pdo->query(sprintf('select * from templates where id=%s', $_GET['id']))->fetch();
unlink($template['path']);

$pdo->query(sprintf('delete from templates where id=%s', $template['id']));

header('location:template-index.php');

