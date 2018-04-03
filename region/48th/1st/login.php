<?php

include('config.php');

if (3 <= $_SESSION['login_times']) {
    header('location:login-fail.php');
    exit();
}

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>會員登入</title>
</head>

<body>
    <h1>會員登入</h1>
    <form method="post" action="auth.php">
        <div>
            <label>帳號</label>
            <input name="account" type="text" required>
        </div>
        <div>
            <label>密碼</label>
            <input name="password" type="password" required>
        </div>
        <div>
            <label>圖形驗證碼</label>
            <input name="verificationAnswer" id="verificationAnswer" type="text" required>
        </div>
        <div>
            <label><small>請按照 Ascii 碼<strong id="verificationRule"></strong>排序輸入</small></label>
            <img id="verificationImage0">
            <img id="verificationImage1">
            <img id="verificationImage2">
            <img id="verificationImage3">
            <a id="renewVerification" href="#">驗證碼重新產生</a>
        </div>
        <div>
            <button>登入</button>
            <a href="index.php">返回</html>
        </div>
    </form>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
        var character = null;
        $('#renewVerification').click(function () {
            $.ajax({
                url: 'verification-code.php',
                success: function (result) {
                    var resultArray = result.split(';');
                    for (var i = 0; i < resultArray[0].length; i++) {
                        $('#verificationImage' + i).attr({'src': 'draw-character-image?character=' + resultArray[0].charAt(i), 'data-character': resultArray[0].charAt(i)});
                    }
                    $('#verificationRule').text('ascend' === resultArray[1] ? '遞增' : '遞減');
                }
            });
        }).click();

        $('img').on('dragstart', function () {
            character = $(this).data('character');
        });

        $('#verificationAnswer').on('drop', function () {
            $(this).val($(this).val() + character);
        });
    </script>
</body>

</html>

