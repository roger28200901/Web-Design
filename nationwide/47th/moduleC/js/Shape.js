/* Shape */
var Shape = function (data)
{
    this.start = data.start || new Point({});
    this.end = data.end || new Point({});
    this.mode = data.mode || '';
    this.color = data.color || 'black';
    this.line = data.line || 1;
    this.points = data.points || [];
    this.numberOfAngles = data.numberOfAngles || 3;
    this.shape = data.shape || '';
    this.illustration = data.illustration || '';
    this.isFilled = data.isFilled || false;

    this.withShift = false;
    this.withCtrl = false;

    this.width = 0;
    this.height = 0;

    this.leftTop = new Point({});
    this.rightBottom = new Point({});
}

Shape.prototype.draw = function (context)
{
    context.beginPath();
    switch (this.mode) {
        case 'brush':
            var shape = this;
            shape.points.forEach(function (point) {
                context.lineTo(shape.start.x + point.x, shape.start.y + point.y);
                context.moveTo(shape.start.x + point.x, shape.start.y + point.y);
            });
            break;
        case 'line':
            context.moveTo(this.start.x, this.start.y);
            if (this.withShift) {
                var distanceX = Math.abs(this.end.x - this.start.x);
                var distanceY = Math.abs(this.end.y - this.start.y);
                if (distanceX > distanceY) {
                    context.lineTo(this.end.x, this.start.y);
                } else {
                    context.lineTo(this.start.x, this.end.y);
                }
            } else {
                context.lineTo(this.end.x, this.end.y);
            }
            break;
        case 'shape':
            switch (this.shape) {
                case 'oval':
                    var width = Math.abs(this.start.x - this.end.x);
                    var height = Math.abs(this.start.y - this.end.y);

                    context.save();
                    if (this.withShift) {
                        context.arc(this.start.x, this.start.y, width, 0, 2 * Math.PI);
                    } else {
                        context.scale(1, height / width);
                        context.arc(this.start.x, this.start.y / (height / width), width, 0, 2 * Math.PI);
                    }
                    context.restore();

                    break;
                case 'polygon':
                    var width = Math.abs(this.start.x - this.end.x);
                    var height = Math.abs(this.start.y - this.end.y);
                    var angle = 2 * Math.PI / this.numberOfAngles;
                    var rotate = Math.PI / 2;
                    var radius = Math.sqrt(Math.pow(width, 2) + Math.pow(height, 2));

                    for (var i = 0; i < this.numberOfAngles + 1; i++) {
                        var degree = angle * (i % this.numberOfAngles) - rotate
                        var x = this.start.x + radius * Math.cos(degree);
                        var y = this.start.y + radius * Math.sin(degree);
                        if (!this.withShift) {
                            x = this.start.x + (x - this.start.x) * width / height;
                            y = this.start.y + (y - this.start.y) * height / width;
                        }
                        context.lineTo(x, y);
                    }

                    break;
                case 'star':
                    var width = Math.abs(this.start.x - this.end.x);
                    var height = Math.abs(this.start.y - this.end.y);
                    var angle = 2 * Math.PI / this.numberOfAngles;
                    var rotate = Math.PI / 2;
                    var radius = Math.sqrt(Math.pow(width, 2) + Math.pow(height, 2));

                    for (var i = 0; i < this.numberOfAngles + 1; i++) {
                        var degree = angle * (i % this.numberOfAngles) - rotate
                        var x = this.start.x + radius * Math.cos(degree);
                        var y = this.start.y + radius * Math.sin(degree);
                        if (!this.withShift) {
                            x = this.start.x + (x - this.start.x) * width / height;
                            y = this.start.y + (y - this.start.y) * height / width;
                        }
                        context.lineTo(x, y);
                        degree = angle * (i % this.numberOfAngles + 0.5) - rotate
                        x = this.start.x + radius * Math.cos(degree) / 2;
                        y = this.start.y + radius * Math.sin(degree) / 2;
                        if (!this.withShift) {
                            x = this.start.x + (x - this.start.x) * width / height;
                            y = this.start.y + (y - this.start.y) * height / width;
                        }
                        context.lineTo(x, y);
                    }
                    break;
            }
            break;
        case 'illustration':
            context.drawImage(this.illustration, this.end.x, this.end.y, this.width, this.height);
            break;
    }
    context.closePath();
    context.lineWidth = this.line;
    context.strokeStyle = this.color;
    context.stroke();
    if (this.isFilled) {
        context.fillStyle = this.color;
        context.fill();
    }
}

Shape.prototype.focus = function (context)
{
    this.getBound();
    context.beginPath();
    context.rect(this.leftTop.x, this.leftTop.y, this.rightBottom.x - this.leftTop.x, this.rightBottom.y - this.leftTop.y);
    context.closePath();
    context.lineWidth = 5;
    context.strokeStyle = 'red';
    context.stroke();
}

Shape.prototype.contain = function (point)
{
    this.getBound();
    return point.x >= this.leftTop.x && point.x <= this.rightBottom.x && point.y >= this.leftTop.y && point.y <= this.rightBottom.y;
}

Shape.prototype.getBound = function ()
{
    var shape = this;
    shape.leftTop = new Point(shape.start);
    shape.rightBottom = new Point(shape.end);
    if ('brush' === shape.mode) {
        shape.points.forEach(function (point) {
            shape.leftTop.x = Math.min(shape.leftTop.x, shape.start.x + point.x);
            shape.leftTop.y = Math.min(shape.leftTop.y, shape.start.y + point.y);
            shape.rightBottom.x = Math.max(shape.rightBottom.x, shape.start.x + point.x);
            shape.rightBottom.y = Math.max(shape.rightBottom.y, shape.start.y + point.y);
        });
        return;
    }

    if ('line' === shape.mode) {
        if (shape.leftTop.x > shape.rightBottom.x) {
            shape.rightBottom.x = [shape.leftTop.x, shape.leftTop.x = shape.rightBottom.x][0]; // Swap
        }
        if (shape.leftTop.y > shape.rightBottom.y) {
            shape.rightBottom.y = [shape.leftTop.y, shape.leftTop.y = shape.rightBottom.y][0]; // Swap
        }
        return;
    }

    if ('shape' === shape.mode) {
        if ('oval' === shape.shape) {
            if (this.withShift) {
                var radius = Math.abs(shape.end.x - shape.start.x);
                shape.leftTop.x = shape.start.x - radius;
                shape.leftTop.y = shape.start.y - radius;
                shape.rightBottom.x = shape.start.x + radius;
                shape.rightBottom.y = shape.start.y + radius;
                return;
            }
            shape.leftTop.x = shape.start.x - (shape.end.x - shape.start.x);
            shape.leftTop.y = shape.start.y - (shape.end.y - shape.start.y);
            return;
        }
        var width = Math.abs(this.start.x - this.end.x);
        var height = Math.abs(this.start.y - this.end.y);
        var radius = Math.sqrt(Math.pow(width, 2) + Math.pow(height, 2));
        if (this.withShift) {
            shape.leftTop.x = shape.start.x - radius;
            shape.leftTop.y = shape.start.y - radius;
            shape.rightBottom.x = shape.start.x + radius;
            shape.rightBottom.y = shape.start.y + radius;
            return;
        }
        shape.leftTop.x = shape.start.x - radius * width / height;
        shape.rightBottom.x = shape.start.x + radius * width / height;
        shape.leftTop.y = shape.start.y - radius * height / width;
        shape.rightBottom.y = shape.start.y + radius * height / width;
        return;
    }

    if ('illustration' === shape.mode) {
        this.leftTop = new Point(this.end);
        this.rightBottom.x = this.leftTop.x + this.width;
        this.rightBottom.y = this.leftTop.y + this.height;
    }
}
