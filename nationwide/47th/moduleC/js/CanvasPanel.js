/* CanvasPanel */
var CanvasPanel = function (data)
{
    this.width = Math.max(100, Math.min(980, parseInt(data.width) || 800));
    this.height = Math.max(100, Math.min(680, parseInt(data.height) || 600));
    this.backgroundColor = data.backgroundColor || 'white';
    this.canvas = data.canvas || null;
    this.panelLayer = data.panelLayer || null;

    this.canvas.width = this.width;
    this.canvas.height = this.height;
    this.context = this.canvas.getContext('2d');
    this.context.lineCap = 'round';

    this.modes = [];
    this.shapes = [];
    this.colors = [];
    this.lines = [];
    this.illustrations = [];
    this.layers = [];
    this.currentMode = null;
    this.currentShape = null;
    this.currentColor = null;
    this.currentLine = null;
    this.currentIllustration = null;
    this.numberOfAngles = null;

    this.activeShape = null;
    this.activeLayer = null;

    this.refresh = false;

    this.init();
}

CanvasPanel.prototype.init = function ()
{
    this.initModes();
    this.initShapes();
    this.initColors();
    this.initLines();
    this.initIllustrations();
    this.newLayer();

    this.clear();

    var canvasPanel = this;

    var interval = 30;
    setInterval(function () {
        canvasPanel.redraw();
    }, interval);

    document.getElementById('newLayer').addEventListener('click', function (event) {
        canvasPanel.newLayer();
    });

canvasPanel.canvas.addEventListener('mousedown', function (event) {
    var mouse = canvasPanel.getMouse(event);
    switch (canvasPanel.currentMode) {
        case 'choose':
            break;
        case 'paint-bucket':
            break;
        case 'brush':
        case 'line':
        case 'shape':
        case 'illustration':
            var shape = new Shape({
                'start': mouse,
                'end': mouse,
                'mode': canvasPanel.currentMode,
                'color': canvasPanel.currentColor,
                'line': canvasPanel.currentLine,
                'points': [mouse],
                'numberOfAngles': canvasPanel.numberOfAngles,
                'shape': canvasPanel.currentShape,
                'illustration': canvasPanel.currentIllustration,
            });
            if ('shape' === canvasPanel.currentMode) {
            }
            canvasPanel.activeShape = shape;
            canvasPanel.activeLayer.shapes.push(shape);
            break;
    }
    canvasPanel.refresh = true;
});

canvasPanel.canvas.addEventListener('mousemove', function (event) {
    if (canvasPanel.activeShape) {
        var mouse = canvasPanel.getMouse(event);
        switch (canvasPanel.activeShape.mode) {
            case 'choose':
                break;
            case 'paint-bucket':
                break;
            case 'brush':
                canvasPanel.activeShape.points.push(mouse);
                break;
            case 'line':
            case 'shape':
                canvasPanel.activeShape.end = mouse;
                break;
            case 'illustration':
                canvasPanel.activeShape.points = [mouse];
                break;
        }
        canvasPanel.refresh = true;
    }
});

canvasPanel.canvas.addEventListener('mouseup', function (event) {
    canvasPanel.activeShape = null;
});

}

CanvasPanel.prototype.keydown = function (event)
{
    if (this.activeShape) {
        if (16 === event.keyCode) {
            this.activeShape.withShift = true;
        } else if (17 === event.keyCode) {
            this.activeShape.withCtrl = true;
        }
        this.refresh = true;
    }
}

CanvasPanel.prototype.keyup = function (event)
{
    if (this.activeShape) {
        if (16 === event.keyCode) {
            this.activeShape.withShift = false;
        } else if (17 === event.keyCode) {
            this.activeShape.withCtrl = false;
        }
        this.refresh = true;
    }
}

CanvasPanel.prototype.getMouse = function (event)
{
    var point = new Point({
        'x': event.offsetX,
        'y': event.offsetY,
    });
    return point;
}

CanvasPanel.prototype.clear = function()
{
    this.context.fillStyle = this.backgroundColor;
    this.context.fillRect(0, 0, this.width, this.height);
    this.context.fill();
}

CanvasPanel.prototype.redraw = function()
{
    if (this.refresh) {
        var context = this.context;
        this.refresh = false;
        this.clear();
        this.layers.forEach(function (layer) {
            layer.shapes.forEach(function (shape) {
                shape.draw(context);
            });
        });
    }
}

CanvasPanel.prototype.initModes = function ()
{
    var modes = ['choose', 'paint-bucket', 'brush', 'line', 'shape', 'illustration'];
    var modeUrls = ['img/選取.png', 'img/油漆桶.png', 'img/筆刷.png', 'img/直線.png', 'img/形狀.png', 'img/插圖.png'];

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
            canvasPanel.setMode(this);
        });
        panelMode.append(imageMode);
    });

    this.setMode(this.modes[0]);
}

CanvasPanel.prototype.initShapes = function ()
{
    var shapes = ['oval', 'polygon', 'star'];
    var shapeUrls = ['img/橢圓形.png', 'img/多邊形.png', 'img/星形.png'];

    var panelShape = document.getElementById('panelShape');
    var canvasPanel = this;

    shapes.forEach(function (shape, index) {
        var imageShape = document.createElement('img');
        imageShape.classList.add('component');
        imageShape.style.width = '100px';
        imageShape.style.height = '100px';
        imageShape.style.backgroundImage = 'url("' + shapeUrls[index] + '")';
        imageShape.dataset.shape = shape;

        canvasPanel.shapes.push(imageShape);

        imageShape.addEventListener('click', function (event) {
            canvasPanel.setShape(this);
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
            canvasPanel.setColor(this);
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
    var lines = [2, 4, 6, 8, 10, 12, 14, 16];

    var panelLine = document.getElementById('panelLine');
    var canvasPanel = this;

    lines.forEach(function (line) {
        var divLine = document.createElement('div');
        divLine.classList.add('component');
        divLine.style.width = '60px';
        divLine.style.height = '60px';
        divLine.dataset.line = line;

        var linePiece = document.createElement('div');
        linePiece.style.width = line + 'px';
        linePiece.style.height = line + 'px';
        linePiece.style.backgroundColor = 'black';

        divLine.append(linePiece);

        canvasPanel.lines.push(divLine);

        divLine.addEventListener('click', function (event) {
            canvasPanel.setLine(this);
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
        imageIllustration.src = illustrationUrls[index];
        imageIllustration.dataset.illustration = illustration;

        canvasPanel.illustrations.push(imageIllustration);

        imageIllustration.addEventListener('click', function (event) {
            canvasPanel.setIllustration(this);
        });
        panelIllustration.append(imageIllustration);
    });
}

CanvasPanel.prototype.newLayer = function ()
{
    var layerElement = document.createElement('li');
    var numberOfLayers = this.layers.length + 1;
    layerElement.classList.add('active');
    layerElement.textContent = '圖層' + numberOfLayers;

    var data = {
        'name': layerElement.textContent,
        'element': layerElement,
    };
    var layer = new Layer(data);

    this.layers.push(layer);
    this.panelLayer.append(layerElement);

    var canvasPanel = this;
    layerElement.addEventListener('click', function (event) {
        canvasPanel.setLayer(layer);
    });

    canvasPanel.setLayer(layer);
}

CanvasPanel.prototype.setMode = function (mode)
{
    this.cancelModes();
    this.currentMode = mode.dataset.mode;
    switch (this.currentMode) {
        case 'choose':
        case 'paint-bucket':
            this.cancelShapes();
            this.cancelColors();
            this.cancelLines();
            this.cancelIllustrations();
            break;
        case 'brush':
        case 'line':
            this.cancelShapes();
            this.cancelIllustrations();
            if (!this.currentColor) {
                this.setColor(this.colors[0]);
            }
            if (!this.currentLine) {
                this.setLine(this.lines[3]);
            }
            break;
        case 'shape':
            this.cancelIllustrations();
            if (!this.currentShape) {
                this.setShape(this.shapes[0]);
            }
            if (!this.currentColor) {
                this.setColor(this.colors[0]);
            }
            if (!this.currentLine) {
                this.setLine(this.lines[3]);
            }
            break;
        case 'illustration':
            this.cancelShapes();
            this.cancelColors();
            this.cancelLines();
            if (!this.currentIllustration) {
                this.setIllustration(this.illustrations[0]);
            }
            break;
    }
    mode.style.borderColor = '#315';
}

CanvasPanel.prototype.setShape = function (shape)
{
    this.cancelShapes();
    this.currentShape = shape.dataset.shape;
    this.setMode(this.modes[4]);
    switch (this.currentShape) {
        case 'polygon':
            var message = message || '請輸入多邊形邊數 N (N > 2)';
        case 'star':
            var message = message || '請輸入星形頂點數 V (V > 2)';
            this.numberOfAngles = Math.max(parseInt(prompt(message)) || 3, 3);
            break;
    }
    shape.style.borderColor = '#315';
}

CanvasPanel.prototype.setColor = function (color)
{
    if ('brush' === this.currentMode || 'line' === this.currentMode || 'shape' === this.currentMode) {
        this.cancelColors();
        this.currentColor = color.dataset.color;
        color.style.borderColor = '#dae';
    }
}

CanvasPanel.prototype.setLine = function (line)
{
    if ('brush' === this.currentMode || 'line' === this.currentMode || 'shape' === this.currentMode) {
        this.cancelLines();
        this.currentLine = line.dataset.line;
        line.style.borderColor = '#dae';
    }
}

CanvasPanel.prototype.setIllustration = function (illustration)
{
    this.cancelIllustrations();
    this.currentIllustration = illustration;
    this.setMode(this.modes[5]);
    illustration.style.borderColor = '#315';
}

CanvasPanel.prototype.setLayer = function (layer)
{
    this.cancelLayers();
    this.activeLayer = layer;
    layer.element.classList.add('active');
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
    this.numberOfAngles = null;
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

CanvasPanel.prototype.cancelLayers = function ()
{
    this.layers.forEach(function (layer) {
        layer.element.classList.remove('active');
    });

    this.activeLayer = null;
}
