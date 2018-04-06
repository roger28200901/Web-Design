<?php

include('config.php');

$type = '新增';
$action = 'newsletter-store.php';
if (isset($_GET['id'])) {
    $type = '編輯';
    $action = 'newsletter-update.php';
    $newsletter = $pdo->query(sprintf('select newsletters.*, templates.name from newsletters inner join templates on templates.id=newsletters.template_id where newsletters.id=%s', $_GET['id']))->fetch();
}

$templates = $pdo->query('select * from templates')->fetchAll();

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $type ?>電子報</title>
</head>

<body>
    <h1><?= $type ?>電子報</h1>
    <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
        <input name="id" type="hidden" value="<?= $newsletter['id'] ?: null ?>">
        <div>
            <label>標題</label>
            <input name="title" value="<?= $newsletter['title'] ?: null ?>" required>
        </div>
        <div>
            <label>文字內容</label>
            <textarea name="text" required><?= $newsletter['text'] ?: null ?></textarea>
        </div>
        <div>
            <label>圖片</label>
            <img src="<?= $newsletter['image'] ?: '#' ?>">
            <input name="image" id="image" type="file" hidden>
        </div>
        <div>
            <label>相關連結</label>
            <input name="link" value="<?= $newsletter['link'] ?: null ?>" required>
        </div>
        <div>
            <label>樣板</label>
            <select name="template_id">
                <?php foreach ($templates as $template) { ?>
                <option value="<?= $template['id'] ?>"<?= $newsletter['name'] === $template['name'] ? ' selected' : null ?>><?= $template['name'] ?></option>
                <?php } ?>                
            </select>
        </div>
        <button><?= $type ?></button>
        <a href="newsletter-index.php">取消</a>
    </form>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
        $('#image').change(function () {
            var image = this.files[0];

            var fileReader = new FileReader();

            fileReader.onload = function (e)
            {
                $('img').attr('src', e.target.result);
            };

            fileReader.readAsDataURL(image);
        });

        $('img').click(function () {
            $('#image').click();
        });
    </script>
</body>

</html>

