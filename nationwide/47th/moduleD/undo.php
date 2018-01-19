<?php
    require_once('config.php');
    require_once('Hanoi.php');

    $nickname = $_SESSION['nickname'];
    $data = json_decode($_SESSION['data']);
    $id = $data->id;
    $steps = $data->steps;
    $difficulty = $data->difficulty;
    $bricks = $data->bricks;
    $moves = json_decode($_SESSION['moves']);

    $move = array_pop($moves);
    $from_stack_id = $move->from_stack_id;
    $to_stack_id = $move->to_stack_id;
    $brick_id = $move->brick_id;

    $hanoi = new Hanoi($steps, $difficulty, $bricks);
    $hanoi->undo($from_stack_id - 1, $to_stack_id - 1, $brick_id - 1);
    $steps = $hanoi->getSteps();
    $bricks = $hanoi->getBricks();

    $url = "location:game.php";
    if (count($moves)) {
        $move = end($moves);
        $from_stack_id = $move->from_stack_id;
        $to_stack_id = $move->to_stack_id;
        $brick_id = $move->brick_id;
        $url .= "?fromStackId=$from_stack_id&toStackId=$to_stack_id&brickId=$brick_id";
    }

    $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
    $_SESSION['nickname'] = $nickname;
    $_SESSION['data'] = $data;
    $_SESSION['moves'] = json_encode($moves);

    header($url);
    exit();
