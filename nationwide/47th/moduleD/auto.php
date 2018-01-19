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
    $auto = $_SESSION['auto'];

    $hanoi = new Hanoi($steps, $difficulty, $bricks, $moves);

    if ($auto) {
        $move = array_shift($moves);

        if ($hanoi->complete()) {
            $hanoi->init();
            $steps = $hanoi->getSteps();
            $bricks = $hanoi->getBricks();
            $moves = [];
            $auto = false;

            $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
            $_SESSION['nickname'] = $nickname;
            $_SESSION['data'] = $data;
            $_SESSION['error_message'] = $error_message;
            $_SESSION['moves'] = json_encode($moves);
            $_SESSION['complete'] = false;
            $_SESSION['auto'] = $auto;
            header('location:game.php');
            exit();
        }

        $from_stack_id = $move->from_stack_id;
        $to_stack_id = $move->to_stack_id;
        $brick_id = $move->brick_id;
        $_SESSION['moves'] = json_encode($moves);
        header("location:move.php?fromStackId=$from_stack_id&toStackId=$to_stack_id&brickId=$brick_id");
        exit();
    }

    $hanoi->auto();
    $moves = $hanoi->getMoves();
    $auto = true;

    $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
    $_SESSION['nickname'] = $nickname;
    $_SESSION['data'] = $data;
    $_SESSION['error_message'] = $error_message;
    $_SESSION['moves'] = json_encode($moves);
    $_SESSION['auto'] = $auto;

    header('location:game.php');
    exit();
