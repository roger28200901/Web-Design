<?php
    require_once('config.php');
    $avatar_path = 'img/avatar/default_avatar.png';
    $nickname = $_POST['nickname'];
    $steps = 0;
    $difficulty = $_POST['difficulty'];
    $bricks = array_fill(0, $difficulty, 0);
    
    /*** upload avatar ***/
    $avatar = $_FILES['avatar'];

    switch ($avatar['error']) { // check error message
        case UPLOAD_ERR_OK: // allow upload file
            /* check file type */
            $type = $avatar['type'];
            $pattern = '/image\/(jpg|jpeg|png|gif)/';
            if (!preg_match($pattern, $type)) {
                print 'Invalid Type Error';
                exit();
            }

            /* store avatar */
            $avatar_path = 'img/avatar/' . time() . '.png';
            move_uploaded_file($avatar['tmp_name'], $avatar_path);

            break;
        case UPLOAD_ERR_FORM_SIZE: // limit file size
            print 'Exceed The Limit Of File Size';
            exit();
        case UPLOAD_ERR_PARTIAL: // upload error
            print 'The uploaded file was only partially uploaded';
            exit();
        case UPLOAD_ERR_NO_FILE: // no file uploaded
            break;
        case UPLOAD_ERR_NO_TMP_DIR: // no temporary file directory
            print 'Missing a temporary folder';
            exit();
        case UPLOAD_ERR_CANT_WRITE: // data can't write to file
            print 'Failed to write file to disk';
            exit();
        case UPLOAD_ERR_EXTENSION: // upload stoped by extension_loaded
            print 'File upload stopped by extension';
            exit();
        default: // unknown error
            $message = "Unknown upload error"; 
            exit; 
    }

    /* try to store into table */
    try {
        $statement = $link->prepare('insert into `scores` (`avatar_path`, `nickname`, `difficulty`) values (?, ?, ?)');
        $link->beginTransaction();
        $statement->execute([$avatar_path, $nickname, $difficulty]);
        $id = $link->lastInsertId();
        $link->commit();
    } catch (PDOException $exception) {
        $link->rollback();
        print $exception->getMessage();
        exit();
    }

    $data = json_encode(compact('id', 'steps', 'difficulty', 'bricks'));
    $_SESSION['nickname'] = $nickname;
    $_SESSION['data'] = $data;
    $_SESSION['error_message'] = '';
    $_SESSION['complete'] = false;

    header('location:game.php');
    exit();
