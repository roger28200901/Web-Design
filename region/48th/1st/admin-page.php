<?php

include('config.php');
$sql = 'select * from users';
if (isset($_GET['keyword'])) {
    $sql .= " where account like '%$_GET[keyword]%' order by $_GET[orderBy] $_GET[sort]";
}
$users = $pdo->query($sql)->fetchAll();

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>管理者專區</title>
</head>

<body>
    <h1>管理者專區</h1>
    <a href="user-edit.php">新增使用者</a>
    <a href="log.php">檢視紀錄</a>
    <a href="logout.php">登出</a>
    <form>
        <label>關鍵字：</label>
        <input name="keyword" value="<?= $_GET['keyword'] ?: null ?>">
        <label>依照：</label>
        <select name="orderBy">
            <option value="account" selected>帳號</option>
            <option value="name"<?= 'name' === $_GET['orderBy'] ? ' selected' : '' ?>>姓名</option>
            <option value="id"<?= 'id' === $_GET['orderBy'] ? ' selected' : '' ?>>使用者編號</option>
        </select>
        <select name="sort">
            <option value="asc" selected>遞增</option>
            <option value="desc"<?= 'desc' === $_GET['sort'] ? ' selected' : '' ?>>遞減</option>
        </select>
        <label>排序</label>
        <button>查詢</button>
    </form>
    <table>
        <tr>
            <th>使用者編號</th>
            <th>帳號</th>
            <th>密碼</th>
            <th>姓名</th>
            <th>權限</th>
            <th></th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= sprintf('%03d', $user['id'] - 1) ?></td>
            <td><?= $user['account'] ?></td>
            <td><?= $user['password'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['is_admin'] ? '管理者' : '一般使用者' ?></td>
            <td>
                <?php if (1 != $user['id']) { ?>
                    <a href="user-edit.php?id=<?= $user['id'] ?>">編輯</a>
                    <a href="user-delete.php?id=<?= $user['id'] ?>">刪除</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>

