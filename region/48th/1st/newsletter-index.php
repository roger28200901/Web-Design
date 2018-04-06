<?php

include('config.php');

$newsletters = $pdo->query("select newsletters.*, templates.name from newsletters inner join templates on templates.id=newsletters.template_id")->fetchAll();

?>
<!doctype>
<html>

<head>
    <meta charset="utf-8">
    <title>電子報製作系統</title>
</head>

<body>
    <h1>電子報製作系統</h1>
    <a href="newsletter-edit.php">新增電子報</a>
    <a href="template-index.php">管理版型</a>
    <a href="index.php">返回</a>
    <table>
        <tr>
            <th>電子報編號</th>
            <th>標題</th>
            <th>文字內容</th>
            <th>圖片</th>
            <th>相關連結</th>
            <th>版型名稱</th>
            <th></th>
        </tr>
        <?php foreach ($newsletters as $newsletter) { ?>
        <tr>
            <td><?= $newsletter['id'] ?></td>
            <td><?= $newsletter['title'] ?></td>
            <td><?= $newsletter['text'] ?></td>
            <td><?= $newsletter['image'] ?></td>
            <td><?= $newsletter['link'] ?></td>
            <td><?= $newsletter['name'] ?></td>
            <td>
                <a href="#">預覽</a>
                <a href="#">編輯</a>
                <a href="#">刪除</a>
            </td>
        </tr>        
        <?php } ?>
    </table>
</body>

</html>

