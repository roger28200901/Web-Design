<?php

include('config.php');

$action = 'user-store.php';
$type = '新增';
if (isset($_GET['id'])) {
    $user = $pdo->query(sprintf('select * from users where id=%s', $_GET['id']))->fetch();
    $action = 'user-update.php';
    $type = '編輯';
}

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $type ?>使用者</title>
</head>

<body>
    <h1><?= $type ?>使用者</h1>
    <form method="post" action="<?= $action ?>">
        <div>
            <label>帳號</label>
            <input name="account" value="<?= $user['account'] ?: null ?>" required>
        </div>
        <div>
            <label>密碼</label>
            <input name="password" type="password" required>
        </div>
        <div>
            <label>姓名</label>
            <input name="name" value="<?= $user['name'] ?: null ?>" required>
        </div>
        <div>
            <label>權限</label>
            <span>一般使用者</span>
            <input name="is_admin" type="radio" value="0" checked>
            <span>管理者</span>
            <input name="is_admin" type="radio" value="1"<?= $user['is_admin'] ? ' checked' : null ?>>
        </div>
        <div>
            <button><?= $type ?></button>
            <a href="admin-page.php">取消</a>
        </div>
    </form>
</body>

</html>

