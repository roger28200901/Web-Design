<?php
    require_once('config.php');

    $data = json_decode($_SESSION['data']);
    $id = $data->id;
    /* delete user record */
    try {
        $statement = $link->prepare('delete from `scores` where `id`=?');
        $link->beginTransaction();
        $statement->execute([$id]);
        $link->commit();
    } catch (PDOException $exception) {
        print $exception->getMessage();
        exit();
    }

    session_destroy();
    header('location:index.html');
