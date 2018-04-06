<?php

include('config.php');

$newsletter = $pdo->query(sprintf('select * from newsletters where id=%s', $_POST['id']))->fetch();

$image_path = $newsletter['image'];
if (!$_FILES['image']['error']) {
    $image_path = sprintf('images/%s', $_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
}

$pdo->query(sprintf("update newsletters set template_id='%s', title='%s', text='%s', image='%s', link='%s' where id=%s",
    $_POST['template_id'],
    $_POST['title'],
    $_POST['text'],
    $image_path,
    $_POST['link'],
    $_POST['id']
));

header('location:newsletter-index.php');

