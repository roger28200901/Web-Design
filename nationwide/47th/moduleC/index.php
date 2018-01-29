<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/style.css">
        <title>繪圖系統</title>
    </head>

    <body>
        <section class="canvas-frame" id="canvasFrame">
            <div class="panel panel-draw" id="panelDraw">
                <div class="panel panel-component" id="panelComponent">
                    <div class="panel panel-pen" id="panelPen">
                        <div class="panel panel-color" id="panelColor"></div>
                        <div class="panel panel-line" id="panelLine"></div>
                    </div>
                    <div class="panel panel-illustration" id="panelIllustration"></div>
                </div>
                <div class="panel panel-paint" id="panelPaint">
                    <div class="panel panel-function" id="panelFunction">
                        <div class="panel panel-mode" id="panelMode"></div>
                        <div class="panel panel-shape" id="panelShape"></div>
                    </div>
                    <div class="panel panel-canvas" id="panelCanvas">
                        <canvas class="canvas" id="canvas"></canvas>
                    </div>
                </div>
            </div>
            <div class="panel panel-edit">
                <div class="panel panel-layer" id="panelLayer"></div>
                <div class="panel panel-button" id="panelButton">
                    <button class="button-edit" id="storeAsImage">存成圖片檔</button>
                    <button class="button-edit" id="storeAsJson">存成可編輯檔</button>
                    <button class="button-edit" id="loadAsJson">載入可編輯檔</button>
                </div>
            </div>
        </section>
        <script src="js/jquery.js"></script>
        <script src="js/CanvasFrame.js"></script>
        <script src="js/CanvasPanel.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>
