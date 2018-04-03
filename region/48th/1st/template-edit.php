<?php

include('config.php');

$type = '新增';
$action = 'template-store.php';
if (isset($_GET['id'])) {
}

$templates = $pdo->query('select * from templates where is_basic=1')->fetchAll();

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $type ?>版型</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?= $type ?>版型</h1>
    <label>選擇版型</label>
    <select id="template">
        <?php foreach ($templates as $template) {?>
        <option value="<?= $template['path'] ?>"><?= $template['name'] ?></option>
        <?php } ?>
    </select>
    <a href="e-newsletter.php">返回</a>
    <form method="post" action="<?= $action ?>">
        <input name="name" value="" required>
        <input name="content" id="content" type="hidden" required>
        <button>儲存</button>
    </form>
    <div id="templatePanel"></div>
    <div>
        <label>背景顏色</label>
        <input id="backgroundColor" type="color" value="#ffffff">
        <label>文字顏色</label>
        <input id="fontColor" type="color">
    </div>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
        $('#template').change(function () {
            $.ajax({
                url: $(this).val(),
                success: function (result) {
                    $('#templatePanel').html(result);
                }
            });
        }).change();

        var $element = null;
        $(document).on('click', '#templatePanel *', function (e) {
            $element = $(this);
            e.stopPropagation();
            $(document).find('#templatePanel *').css('border', '');
            $element.css('border', '1px solid gray');

        });
        
        $('#backgroundColor').change(function () {
            if ($element) {
                $element.css('background-color', $(this).val());
            }
        });

        $('#fontColor').change(function () {
            if ($element) {
                $element.css('color', $(this).val());
            }
        });

        $('form').submit(function () {
            $(document).find('#templatePanel *').css('border', '');
            $('#content').val($('#templatePanel').html());
        });
   </script>
</body>

</html>

