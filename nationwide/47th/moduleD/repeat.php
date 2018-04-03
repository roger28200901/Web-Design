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
    $moves = json_decode($_SESSION['moves']);
    $repeat = $_SESSION['repeat'];
    $total_steps = $_SESSION['total_steps'];

    $hanoi = new Hanoi($steps, $difficulty, $bricks, $moves);

    if ($repeat) {
        $move = array_shift($moves);

        if ($steps == $total_steps) {
            $move = end($moves);
            $from_stack_id = $move->from_stack_id;
            $to_stack_id = $move->to_stack_id;
            $brick_id = $move->brick_id;
            $repeat = false;

            $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
            $_SESSION['nickname'] = $nickname;
            $_SESSION['data'] = $data;
            $_SESSION['repeat'] = $repeat;
            header("location:game.php?fromStackId=$from_stack_id&toStackId=$to_stack_id&brickId=$brick_id");
            exit();
        }

        $from_stack_id = $move->from_stack_id;
        $to_stack_id = $move->to_stack_id;
        $brick_id = $move->brick_id;
        $_SESSION['moves'] = json_encode($moves);
        $move = current($moves);
        $interval = $move->interval;
        $_SESSION['interval'] = $move->interval;
        header("location:move.php?fromStackId=$from_stack_id&toStackId=$to_stack_id&brickId=$brick_id");
        exit();
    }

    $total_steps = $hanoi->getSteps();
    $hanoi->repeat();
    $steps = $hanoi->getSteps();
    $bricks = $hanoi->getBricks();
    $repeat = true; 
    $move = current($moves);
    $interval = $move->interval;

    $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
    $_SESSION['nickname'] = $nickname;
    $_SESSION['data'] = $data;
    $_SESSION['error_message'] = $error_message;
    $_SESSION['moves'] = json_encode($moves);
    $_SESSION['repeat'] = $repeat;
    $_SESSION['interval'] = $interval;
    $_SESSION['total_steps'] = $total_steps;

    header('location:game.php');
    exit();
