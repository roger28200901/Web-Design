<?php
    require_once('config.php');

    $data = json_decode($_SESSION['data']);
    $id = $data->id;
    $steps = $data->steps;
    $difficulty = $data->difficulty;

    session_destroy();

    /* try to store into table */
    try {
        $statement = $link->prepare('update `scores` set `steps`=? where `id`=?');
        $link->beginTransaction();
        $statement->execute([$steps, $id]);
        $link->commit();
    } catch (PDOException $exception) {
        $link->rollback();
        print $exception->getMessage();
        exit();
    }

    header("location:scores.php?difficulty=$difficulty");
    exit();