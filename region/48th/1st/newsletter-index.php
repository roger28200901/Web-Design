<?php

include('config.php');

$newsletters = $pdo->query("select newsletters.*, templates.name from newsletters inner join templates on templates.id=newsletters.template_id")->fetchAll();

?>
<!doctype>
<html>

<head>
    <meta charset="utf-8">
    <title>電子報管理</title>
</head>

<body>
    <h1>電子報管理</h1>
    <a href="newsletter-edit.php">新增電子報</a>
    <a href="e-newsletter.php">返回</a>
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
            <td style="word-break: break-all;"><?= $newsletter['text'] ?></td>
            <td><?= $newsletter['image'] ?></td>
            <td><?= $newsletter['link'] ?></td>
            <td><?= $newsletter['name'] ?></td>
            <td>
                <a href="newsletter-edit.php?id=<?= $newsletter['id'] ?>">編輯</a>
                <a href="#">刪除</a>
            </td>
        </tr>        
        <?php } ?>
    </table>
</body>

</html>

