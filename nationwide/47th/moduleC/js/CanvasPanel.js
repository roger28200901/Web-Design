/* CanvasPanel */
var CanvasPanel = function (data)
{
    this.width = Math.max(100, Math.min(980, data.width || 800));
    this.height = Math.max(100, Math.min(680, data.height || 600));
    this.backgroundColor = data.backgroundColor || 'white';
    this.canvas = data.canvas || null;

    this.currentMode = null;
    this.currentShape = null;
    this.currentColor = null;
    this.currentLine = null;
    this.currentIllustration = null;

    this.initial();
}

CanvasPanel.prototype.initial = function ()
{
    this.canvas.width = this.width;
    this.canvas.height = this.height;
    this.context = this.canvas.getContext('2d');
    this.clear();
}

CanvasPanel.prototype.clear = function()
{
    this.context.fillStyle = this.backgroundColor;
    this.context.fillRect(0, 0, this.width, this.height);
}
