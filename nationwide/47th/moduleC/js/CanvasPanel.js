/* CanvasPanel */
var CanvasPanel = function (data)
{
    this.width = Math.max(100, Math.min(980, data.width || 800));
    this.height = Math.max(100, Math.min(680, data.height || 600));
    this.backgroundColor = data.backgroundColor || 'white';
    this.canvas = data.canvas || null;

    this.modes = [];
    this.shapes = [];
    this.colors = [];
    this.lines = [];
    this.illustrations = [];

    this.currentMode = null;
    this.currentShape = null;
    this.currentColor = null;
    this.currentLine = null;
    this.currentIllustration = null;

    this.canvas.width = this.width;
    this.canvas.height = this.height;
    this.context = this.canvas.getContext('2d');
    this.context.lineCap = 'round';

    this.initModes();
    this.initShapes();
    this.initColors();
    this.initLines();
    this.initIllustrations();

    this.setMode(this.modes[0]);
    this.setShape(this.shapes[0]);
    this.setColor(this.colors[0]);
    this.setLine(this.lines[3]);

    this.clear();

    this.interval = 300;
    setInterval(this.redraw(), this.interval);

    var canvasPanel = this;
    canvasPanel.canvas.addEventListener('mousedown', function (event) {
        switch (canvasPanel.currentMode) {
            case 'choose':
                break;
            case 'paint-bucket':
                break;
            case 'brush':
                break;
            case 'line':
                break;
        }
    });
}

CanvasPanel.prototype.initModes = function ()
{
    var modes = ['choose', 'paint-bucket', 'brush', 'line'];
    var modeUrls = ['img/選取.png', 'img/油漆桶.png', 'img/筆刷.png', 'img/直線.png'];

    var panelMode = document.getElementById('panelMode');
    var canvasPanel = this;

    modes.forEach(function (mode, index) {
        var imageMode = document.createElement('img');
        imageMode.classList.add('component');
        imageMode.style.width = '70px';
        imageMode.style.height = '70px';
        imageMode.style.backgroundImage = 'url("' + modeUrls[index] + '")';
        imageMode.dataset.mode = mode;

        canvasPanel.modes.push(imageMode);

        imageMode.addEventListener('click', function (event) {
            canvasPanel.setMode(imageMode);
        });
        panelMode.append(imageMode);
    });
}

CanvasPanel.prototype.initShapes = function ()
{
    var shapes = ['rectangle', 'oval', 'polygon', 'star'];
    var shapeUrls = ['img/矩形.png', 'img/橢圓形.png', 'img/多邊形.png', 'img/星形.png'];

    var panelShape = document.getElementById('panelShape');
    var canvasPanel = this;

    shapes.forEach(function (shape, index) {
        var imageShape = document.createElement('img');
        imageShape.classList.add('component');
        imageShape.style.width = '110px';
        imageShape.style.height = '110px';
        imageShape.style.backgroundImage = 'url("' + shapeUrls[index] + '")';
        imageShape.dataset.shape = shape;

        canvasPanel.shapes.push(imageShape);

        imageShape.addEventListener('click', function (event) {
            canvasPanel.setShape(imageShape);
        });
        panelShape.append(imageShape);
    });
}

CanvasPanel.prototype.initColors = function ()
{
    var colors = ['black', 'white', 'red', 'orange', 'yellow', 'green', 'blue', 'purple'];

    var panelColor = document.getElementById('panelColor');
    var canvasPanel = this;

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

        canvasPanel.colors.push(divColor);

        divColor.addEventListener('click', function (event) {
            canvasPanel.setColor(divColor);
        });

        panelColor.append(divColor);
    });

    var inputColor = document.createElement('input');
    inputColor.classList.add('component');
    inputColor.style.width = '60px';
    inputColor.style.height = '60px';
    inputColor.type = 'color';

    inputColor.addEventListener('click', function (event) {
        canvasPanel.currentColor = this.value;
        this.addEventListener('change', function (event) {
            canvasPanel.currentColor = this.value;
        });
    });

    panelColor.append(inputColor);
}

CanvasPanel.prototype.initLines = function ()
{
    var lines = [1, 2, 3, 4, 5, 6, 7, 8];

    var panelLine = document.getElementById('panelLine');
    var canvasPanel = this;

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

        canvasPanel.lines.push(divLine);

        divLine.addEventListener('click', function (event) {
            canvasPanel.setLine(divLine);
        });

        panelLine.append(divLine);
    });
}

CanvasPanel.prototype.initIllustrations = function ()
{
    var illustrations = ['C', 'H', 'A', 'M', 'P', 'I', 'O', 'N'];
    var illustrationUrls = ['img/C.png', 'img/H.png', 'img/A.png', 'img/N.png', 'img/P.png', 'img/I.png', 'img/O.png', 'img/N.png'];

    var panelIllustration = document.getElementById('panelIllustration');
    var canvasPanel = this;

    illustrations.forEach(function (illustration, index) {
        var imageIllustration = document.createElement('img');
        imageIllustration.classList.add('component');
        imageIllustration.style.width = '65px';
        imageIllustration.style.height = '90px';
        imageIllustration.style.backgroundImage = 'url("' + illustrationUrls[index] + '")';
        imageIllustration.dataset.illustration = illustration;

        canvasPanel.illustrations.push(imageIllustration);

        imageIllustration.addEventListener('click', function (event) {
            canvasPanel.setIllustration(imageIllustration);
        });
        panelIllustration.append(imageIllustration);
    });
}

CanvasPanel.prototype.setMode = function (move)
{
    this.cancelModes();
    this.currentMode = move.dataset.mode;
    move.style.borderColor = '#315';
}

CanvasPanel.prototype.setShape = function (shape)
{
    this.cancelShapes();
    this.currentShape = shape.dataset.shape;
    shape.style.borderColor = '#315';
}

CanvasPanel.prototype.setColor = function (color)
{
    this.cancelColors();
    this.currentColor = color.dataset.color;
    color.style.borderColor = '#dae';
}

CanvasPanel.prototype.setLine = function (line)
{
    this.cancelLines();
    this.currentLine = line.dataset.line;
    line.style.borderColor = '#dae';
}

CanvasPanel.prototype.setIllustration = function (illustration)
{
    this.cancelIllustrations();
    this.currentIllustration = illustration.dataset.illustration;
    illustration.style.borderColor = '#315';
}

CanvasPanel.prototype.cancelModes = function ()
{
    this.modes.forEach(function (mode) {
        mode.style.borderColor = 'white';
    });

    this.currentMode = null;
}

CanvasPanel.prototype.cancelShapes = function ()
{
    this.shapes.forEach(function (shape) {
        shape.style.borderColor = 'white';
    });

    this.currentShape = null;
}

CanvasPanel.prototype.cancelColors = function ()
{
    this.colors.forEach(function (color) {
        color.style.borderColor = 'black';
    });

    this.currentColor = null;
}

CanvasPanel.prototype.cancelLines = function ()
{
    this.lines.forEach(function (line) {
        line.style.borderColor = 'black';
    });

    this.currentLine = null;
}

CanvasPanel.prototype.cancelIllustrations = function ()
{
    this.illustrations.forEach(function (illustration) {
        illustration.style.borderColor = 'white';
    });

    this.currentIllustration = null;
}

CanvasPanel.prototype.clear = function()
{
    this.context.fillStyle = this.backgroundColor;
    this.context.fillRect(0, 0, this.width, this.height);
}

CanvasPanel.prototype.redraw = function()
{
    // this.clear();
}
