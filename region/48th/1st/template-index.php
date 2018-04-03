<?php

include('config.php');

$templates = $pdo->query('select * from templates')->fetchAll();

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>版型管理</title>
</head>

<body>
    <h1>版型管理</h1>
    <a href="template-edit.php">新增</a>
    <a href="e-newsletter.php">返回</a>
    <table>
        <tr>
            <th>版型編號</th>
            <th>名稱</th>
            <th>檔案位置</th>
            <th></th>
        </tr>
        <?php foreach ($templates as $template) { ?>
        <tr>
            <td><?= $template['id'] ?></td>
            <td><?= $template['name'] ?></td>
            <td><?= $template['path'] ?></td>
            <td>
                <a href="template-edit.php?id=<?= $template['id'] ?>">編輯</a>
                <a href="template-show.php?id=<?= $template['id'] ?>">預覽</a>
                <a href="template-delete.php?id=<?= $template['id'] ?>">刪除</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>

