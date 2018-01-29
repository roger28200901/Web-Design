/* Script */
window.onload = function ()
{
    var canvasWidth = prompt('請輸入畫布寬度(預設為 800px，最大為 980px)') || 800;
    var canvasHeight = prompt('請輸入畫布高度(預設為 600px，最大為 680px)') || 600;
    var canvasBackgroundColor = prompt('請輸入畫布背景顏色(色碼 or 英文，預設為 White #FFFFFF)') || 'white';

    var canvas = document.getElementById('canvas');
    var data = {
        'width': canvasWidth,
        'height': canvasHeight,
        'backgroundColor': canvasBackgroundColor,
        'canvas': canvas,
    };

    var canvasPanel = new CanvasPanel(data);
    var canvasFrame = new CanvasFrame(canvasPanel);
}
