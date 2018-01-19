<?php
    require_once('config.php');
    require_once('Hanoi.php');

    $from_stack_id = $_GET['fromStackId'];
    $to_stack_id = $_GET['toStackId'];
    $brick_id = $_GET['brickId'];

    $data = json_decode($_SESSION['data']);
    $nickname = $_SESSION['nickname'];
    $id = $data->id;
    $steps = $data->steps;
    $difficulty = $data->difficulty;
    $bricks = $data->bricks;
    $error_message = '';

    $hanoi = new Hanoi($steps, $difficulty, $bricks);
    if ($hanoi->tryMove($from_stack_id - 1, $to_stack_id - 1, $brick_id - 1)) {
        $steps = $hanoi->getsteps();
        $bricks = $hanoi->getBricks();
        setMove($from_stack_id, $to_stack_id, $brick_id);
    } else {
        $error_message = $hanoi->getErrorMessage();
    }

    $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
    $_SESSION['nickname'] = $nickname;
    $_SESSION['data'] = $data;
    $_SESSION['complete'] = $hanoi->complete();
    $_SESSION['error_message'] = $error_message;

    header("location:game.php?fromStackId=$from_stack_id&toStackId=$to_stack_id&brickId=$brick_id");
    exit();

    function setMove($from_stack_id, $to_stack_id, $brick_id)
    {
        $moves = json_decode($_SESSION['moves']);
        $moves[] = [
            'from_stack_id' => $from_stack_id,
            'to_stack_id' => $to_stack_id,
            'brick_id' => $brick_id
        ];
        $_SESSION['moves'] = json_encode($moves);
    }
