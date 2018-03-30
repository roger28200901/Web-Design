<?php

include ('config.php');

$logs = $pdo->query(sprintf("select * from logs inner join users on users.id=logs.user_id"))->fetchAll();

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>系統紀錄</title>
</head>

<body>
    <a href="admin-page.php">返回</a>
    <table>
        <tr>
            <th>帳號</th>
            <th>姓名</th>
            <th>動作</th>
            <th>時間</th>
        </tr>
        <?php foreach ($logs as $log) { ?>        
        <tr>
            <td><?= $log['account'] ?></td>
            <td><?= $log['name'] ?></td>
            <td><?= $log['action'] ?></td>
            <td><?= $log['created_at'] ?></td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>

