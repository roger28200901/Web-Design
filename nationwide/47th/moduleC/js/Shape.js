/* Shape */
var Shape = function (data)
{
    this.start = data.start || new Point({});
    this.end = data.end || new Point({});
    this.mode = data.mode || '';
    this.color = data.color || 'black';
    this.line = data.line || 1;
    this.points = data.points || [];
    this.corner = data.corner || 3;

    this.withShift = false;
    this.withCtrl = false;
}

Shape.prototype.draw = function (context)
{
    context.beginPath();
    switch (this.mode) {
        case 'brush':
            context.strokeStyle = this.color;
            context.lineWidth = this.line;
            this.points.forEach(function (point) {
                context.lineTo(point.x, point.y);
                context.moveTo(point.x, point.y);
            });
            context.stroke();
            break;
        case 'line':
            context.strokeStyle = this.color;
            context.lineWidth = this.line;
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
            context.stroke();
            break;
    }
}
