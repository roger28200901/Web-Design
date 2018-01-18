<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/widgets.css" rel="stylesheet" />
        <script src="js/game-script.js"></script>

        <title>河內塔</title>
    </head>

    <?php
        require_once('config.php');

        /* unknowned user data */
        if (!isset($_SESSION['nickname'])) {
            header('location:index.html');
            exit();
        }

        /* get datas */
        $data = json_decode($_SESSION['data']);
        $nickname = $_SESSION['nickname'];
        $steps = $data->steps;
        $bricks = $data->bricks;

        $error_message = $_SESSION['error_message'];
        $complete = $_SESSION['complete'];
    ?>

    <body>
        <input id="errorMessage" type="hidden" value="<?= $error_message ?>">
        <input id="complete" type="hidden" value="<?= $complete ?>">
        <div<?= $complete ? '' : ' hidden' ?> class="success">
            <div class="container">
                <div class="message">
                    遊戲成功!
                </div>
            </div>
        </div>
        <div id="container-game" class="gameRunning">
            <div class="row">
                <div class="layout">
                    <h2>河內塔</h2>

                    <div class="game">
                        <div class="clear colNumber">
                            <div class="col3">
                                <span>1</span>
                            </div>
                            <div class="col3">
                                <span>2</span>
                            </div>
                            <div class="col3">
                                <span>3</span>
                            </div>
                        </div>

                        <?php
                            for ($i = 0; $i < 3; $i++) {
                                ?>
                                <div class="col" data-id="<?= $i + 1 ?>">
                                    <?php
                                        foreach ($bricks as $index => $brick) {
                                            if ($brick == $i) {
                                                ?>
                                                <div class="brick b<?= $index + 1 ?>" data-id="<?= $index + 1 ?>"></div>
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>

                        <div class="clear moveButton">
                            <div class="col3">
                                <button data-id="1">2</button>
                                <button data-id="1">3</button>
                            </div>
                            <div class="col3">
                                <button data-id="2">1</button>
                                <button data-id="2">3</button>
                            </div>
                            <div class="col3">
                                <button data-id="3">1</button>
                                <button data-id="3">2</button>
                            </div>
                        </div>

                    </div>
                    <div class="desc left">
                        <a href="reset.php" class="btn btn-secondary">回到設定頁面</a>
                    </div>
                </div>
                <div class="module">
                    <div class="mod">
                        <h4>暱稱</h4>
                        <h2><?= $nickname ?></h2>
                    </div>
                    <div class="mod" id="move">
                        <h4>移動次數</h4>
                        <h2><?= $steps ?></h2>
                    </div>
                     <div class="mod" id="asideFuncButtons">
                        <button id="back">上一步</button>
                        <button id="auto">自動解答</button>
                        <button id="repeat">重播</button>
                     </div>
                </div>
            </div>
        </div>
    </body>
</html>
