<?php

include('config.php');

$path = sprintf("templates/%s.html", $_POST['name']);
file_put_contents($path, $_POST['content']);

$pdo->query(sprintf("insert into templates (name, path, is_basic) values ('%s', '%s', 0)",
    $_POST['name'],
    $path
));

header('location:template-index.php');

