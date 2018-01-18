<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/widgets.css" rel="stylesheet" />

        <title>河內塔</title>
    </head>

    <?php
        require_once('config.php');

        /* error prevent */
        if (!isset($_GET['difficulty'])) {
            header('location:index.html');
            exit();
        }

        $difficulty = $_GET['difficulty'];
        
        /* get all scores */
        try {
            $statement = $link->prepare('select * from `scores` where `difficulty`=? and `steps` > 0 order by `steps` asc');
            $link->beginTransaction();
            $statement->execute([$difficulty]);
            $scores = $statement->fetchAll();
            $link->commit();
        } catch (PDOException $exception) {
            print $exception->getMessage();
            exit();
        }
    ?>

    <body>
        <div id="container-game" style="width:620px !important">
            <div class="row">
                <div class="layout">
                    <h2>排行榜</h2>
                    <table class="level">
                        <tr>
                            <th>名次</th>
                            <th></th>
                            <th>暱稱</th>
                            <th>移動次數</th>
                        <tr>
                        <?php
                            $level = 0;
                            $steps = 0;
                            $same_steps = 0;
                            foreach ($scores as $score) {
                                $same_steps++;
                                if ($score['steps'] > $steps) {
                                    $level += $same_steps;
                                    $same_steps = 0;
                                    $steps = $score['steps'];
                                }
                                ?>
                                <tr>
                                    <td><?= $level ?></td>
                                    <td><img id="avatarDisplay" src="<?= $score['avatar_path'] ?>"></td>
                                    <td><?= $score['nickname'] ?></td>
                                    <td><?= $score['steps'] ?></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </table>
                    <div class="desc left">
                        <a href="index.html" class="btn btn-secondary">回到設定頁面</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>