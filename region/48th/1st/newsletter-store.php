<?php

include('config.php');

$image_path = sprintf('images/%s', $_FILES['image']['name']);

move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

$pdo->query(sprintf("insert into newsletters (template_id, title, text, image, link) values ('%s', '%s', '%s', '%s', '%s')",
    $_POST['template_id'],
    $_POST['title'],
    $_POST['text'],
    $image_path,
    $_POST['link']
));

header('location:newsletter-index.php');

