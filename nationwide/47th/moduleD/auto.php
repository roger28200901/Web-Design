<?php
    require_once('config.php');
    require_once('Hanoi.php');

    $nickname = $_SESSION['nickname'];
    $data = json_decode($_SESSION['data']);
    $id = $data->id;
    $steps = $data->steps;
    $difficulty = $data->difficulty;
    $bricks = $data->bricks;
    $error_message = '';

    $hanoi = new Hanoi($steps, $difficulty, $bricks);
    $moves = $hanoi->auto();

    $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
    $_SESSION['nickname'] = $nickname;
    $_SESSION['data'] = $data;
    $_SESSION['error_message'] = $error_message;
    $_SESSION['moves'] = json_encode($moves);

    header('location:game.php');
    exit();
