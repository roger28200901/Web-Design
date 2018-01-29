/* CanvasFrame */
var CanvasFrame = function (canvasPanel)
{
    this.canvasPanel = canvasPanel || null;

    this.modes = [];
    this.shapes = [];
    this.colors = [];
    this.lines = [];
    this.illustrations = [];

    this.initial();
}

CanvasFrame.prototype.initial = function ()
{
    this.initModes();
    this.initShapes();
    this.initColors();
    this.initLines();
    this.initIllustrations();

    this.setMode(this.modes[0]);
    this.setShape(this.shapes[0]);
    this.setColor(this.colors[0]);
    this.setLine(this.lines[3]);
}

CanvasFrame.prototype.initModes = function ()
{
    var modes = ['choose', 'paint-bucket', 'brush', 'shape'];
    var modeUrls = ['img/選取.png', 'img/油漆桶.png', 'img/筆刷.png', 'img/幾何圖形.png'];

    var panelMode = document.getElementById('panelMode');
    var canvasFrame = this;

    modes.forEach(function (mode, index) {
        var imageMode = document.createElement('img');
        imageMode.classList.add('component');
        imageMode.style.width = '70px';
        imageMode.style.height = '70px';
        imageMode.style.backgroundImage = 'url("' + modeUrls[index] + '")';
        imageMode.dataset.mode = mode;

        canvasFrame.modes.push(imageMode);

        imageMode.addEventListener('click', function (event) {
            canvasFrame.setMode(imageMode);
        });
        panelMode.append(imageMode);
    });
}

CanvasFrame.prototype.initShapes = function ()
{
    var shapes = ['rectangle', 'oval', 'polygon', 'star'];
    var shapeUrls = ['img/矩形.png', 'img/橢圓形.png', 'img/多邊形.png', 'img/星形.png'];

    var panelShape = document.getElementById('panelShape');
    var canvasFrame = this;

    shapes.forEach(function (shape, index) {
        var imageShape = document.createElement('img');
        imageShape.classList.add('component');
        imageShape.style.width = '110px';
        imageShape.style.height = '110px';
        imageShape.style.backgroundImage = 'url("' + shapeUrls[index] + '")';
        imageShape.dataset.shape = shape;

        canvasFrame.shapes.push(imageShape);

        imageShape.addEventListener('click', function (event) {
            canvasFrame.setShape(imageShape);
        });
        panelShape.append(imageShape);
    });
}

CanvasFrame.prototype.initColors = function ()
{
    var colors = ['black', 'white', 'red', 'orange', 'yellow', 'green', 'blue', 'purple'];

    var panelColor = document.getElementById('panelColor');
    var canvasFrame = this;

    colors.forEach(function (color) {
        var divColor = document.createElement('div');
        divColor.classList.add('component');
        divColor.style.width = '60px';
        divColor.style.height = '60px';
        divColor.dataset.color = color;

        var colorPiece = document.createElement('div');
        colorPiece.style.width = '40px';
        colorPiece.style.height = '40px';
        colorPiece.style.backgroundColor = color;

        divColor.append(colorPiece);

        canvasFrame.colors.push(divColor);

        divColor.addEventListener('click', function (event) {
            canvasFrame.setColor(divColor);
        });

        panelColor.append(divColor);
    });

    var inputColor = document.createElement('input');
    inputColor.classList.add('component');
    inputColor.style.width = '60px';
    inputColor.style.height = '60px';
    inputColor.type = 'color';

    inputColor.addEventListener('click', function (event) {
        canvasFrame.currentColor = this.value;
        this.addEventListener('change', function (event) {
            canvasFrame.currentColor = this.value;
        });
    });

    panelColor.append(inputColor);
}

CanvasFrame.prototype.initLines = function ()
{
    var lines = [1, 2, 3, 4, 5, 6, 7, 8];

    var panelLine = document.getElementById('panelLine');
    var canvasFrame = this;

    lines.forEach(function (line) {
        var divLine = document.createElement('div');
        divLine.classList.add('component');
        divLine.style.width = '60px';
        divLine.style.height = '60px';
        divLine.dataset.line = line;

        var linePiece = document.createElement('div');
        linePiece.style.width = (line * 2) + 'px';
        linePiece.style.height = (line * 2) + 'px';
        linePiece.style.backgroundColor = 'black';

        divLine.append(linePiece);

        canvasFrame.lines.push(divLine);

        divLine.addEventListener('click', function (event) {
            canvasFrame.setLine(divLine);
        });

        panelLine.append(divLine);
    });
}

CanvasFrame.prototype.initIllustrations = function ()
{
    var illustrations = ['C', 'H', 'A', 'M', 'P', 'I', 'O', 'N'];
    var illustrationUrls = ['img/C.png', 'img/H.png', 'img/A.png', 'img/N.png', 'img/P.png', 'img/I.png', 'img/O.png', 'img/N.png'];

    var panelIllustration = document.getElementById('panelIllustration');
    var canvasFrame = this;

    illustrations.forEach(function (illustration, index) {
        var imageIllustration = document.createElement('img');
        imageIllustration.classList.add('component');
        imageIllustration.style.width = '65px';
        imageIllustration.style.height = '90px';
        imageIllustration.style.backgroundImage = 'url("' + illustrationUrls[index] + '")';
        imageIllustration.dataset.illustration = illustration;

        canvasFrame.illustrations.push(imageIllustration);

        imageIllustration.addEventListener('click', function (event) {
            canvasFrame.setIllustration(imageIllustration);
        });
        panelIllustration.append(imageIllustration);
    });
}

CanvasFrame.prototype.setMode = function (move)
{
    this.cancelModes();
    this.canvasPanel.currentMode = move.dataset.mode;
    move.style.borderColor = '#315';
}

CanvasFrame.prototype.setShape = function (shape)
{
    this.cancelShapes();
    this.canvasPanel.currentShape = shape.dataset.shape;
    shape.style.borderColor = '#315';
}

CanvasFrame.prototype.setColor = function (color)
{
    this.cancelColors();
    this.canvasPanel.currentColor = color.dataset.color;
    color.style.borderColor = '#dae';
}

CanvasFrame.prototype.setLine = function (line)
{
    this.cancelLines();
    this.canvasPanel.currentLine = line.dataset.line;
    line.style.borderColor = '#dae';
}

CanvasFrame.prototype.setIllustration = function (illustration)
{
    this.cancelIllustrations();
    this.canvasPanel.currentIllustration = illustration.dataset.illustration;
    illustration.style.borderColor = '#315';
}

CanvasFrame.prototype.cancelModes = function ()
{
    this.modes.forEach(function (mode) {
        mode.style.borderColor = 'white';
    });

    this.canvasPanel.currentMode = null;
}

CanvasFrame.prototype.cancelShapes = function ()
{
    this.shapes.forEach(function (shape) {
        shape.style.borderColor = 'white';
    });

    this.canvasPanel.currentShape = null;
}

CanvasFrame.prototype.cancelColors = function ()
{
    this.colors.forEach(function (color) {
        color.style.borderColor = 'black';
    });

    this.canvasPanel.currentColor = null;
}

CanvasFrame.prototype.cancelLines = function ()
{
    this.lines.forEach(function (line) {
        line.style.borderColor = 'black';
    });

    this.canvasPanel.currentLine = null;
}

CanvasFrame.prototype.cancelIllustrations = function ()
{
    this.illustrations.forEach(function (illustration) {
        illustration.style.borderColor = 'white';
    });

    this.canvasPanel.currentIllustration = null;
}
