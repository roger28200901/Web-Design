<?php

include('config.php');

$type = '新增';
$action = 'newsletter-store.php';
if (isset($_GET['id'])) {
    $type = '編輯';
    $action = 'newsletter-update.php';
    $newsletter = $pdo->query(sprintf('select * from newsletters where id=%s', $_GET['id']))->fetch();
}

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $type ?>電子報</title>
</head>

<body>
    <h1><?= $type ?>電子報<h1>
    <form method="post" action="<?= $action ?>">
        <input name="id" type="hidden" value="<?= $newsletter['id'] ?: null ?>">
        <div>
            <label>標題</label>
            <input name="title" value="<?= $newsletter['title'] ?: null ?>">
        </div>
        <div>
            <label>文字內容</label>
            <textarea name="text"><?= $newsletter['text'] ?: null ?></textarea>
        </div>
        <div>
            <label>圖片</label>
            <input name="image" type="image">
        </div>
        <div>
            <label>相關連結</label>
            <input name="link" value="<?= $newsletter['link'] ?: null ?>">
        </div>
        <button><?= $type ?></button>
        <a href="newsletter-index.php">取消</a>
    </form>    
</body>

</html>

