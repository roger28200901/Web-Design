<?php

include('config.php');

$template = $pdo->query(sprintf('select * from templates where id=%s', $_POST['id']))->fetch();

unlink($template['path']);
$path = sprintf("templates/%s.html", $_POST['name']);
file_put_contents($path, $_POST['content']);

$pdo->query(sprintf("update templates set name='%s', path='%s' where id=%s",
    $_POST['name'],
    $path,
    $template['id']
));

header('location:template-index.php');

