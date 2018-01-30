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

    this.withShift = false;
    this.withCtrl = false;
}

Shape.prototype.draw = function (context)
{
    context.beginPath();
    switch (this.mode) {
        case 'brush':
            context.lineWidth = this.line;
            this.points.forEach(function (point) {
                context.lineTo(point.x, point.y);
                context.moveTo(point.x, point.y);
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
                    context.scale(1, height / width);
                    context.arc(this.start.x, this.start.y / (height / width), width, 0, 2 * Math.PI);
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
                        context.lineTo(x, y);
                        context.moveTo(x, y);
                        degree = angle * (i % this.numberOfAngles + 0.5) - rotate
                        x = this.start.x + radius * Math.cos(degree) / 2;
                        y = this.start.y + radius * Math.sin(degree) / 2;
                        context.lineTo(x, y);
                        context.moveTo(x, y);
                    }
                    break;
            }
            break;
        case 'illustration':
            context.drawImage(this.illustration, this.points[0].x, this.points[0].y);
            break;
    }
    context.closePath();
    context.lineWidth = this.line;
    context.strokeStyle = this.color;
    context.stroke();
    // context.fillStyle = this.color;
    // context.fill();
}
