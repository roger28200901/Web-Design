<?php

include('config.php');

$sql = 'select newsletters.*, templates.* from newsletters inner join templates on templates.id=newsletters.template_id';
$newsletters = $pdo->query($sql)->fetchAll();

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>電子報製作系統</title>
</head>

<body>
    <h1>電子報製作系統</h1>
    <a href="newsletter-index.php">電子報管理</a>
    <a href="template-index.php">版型管理</a>
    <a href="index.php">返回</a>
    <div>
        <?php

        foreach ($newsletters as $newsletter) {
            $template = file_get_contents($newsletter['path']);
            $template = str_replace(
                ['標題', '內容', '圖片', '超連結'],
                [$newsletter['title'], $newsletter['text'], $newsletter['image'], $newsletter['link']],
                $template
            );
            echo $template;
        }

        ?>
    </div>
</body>

</html>

