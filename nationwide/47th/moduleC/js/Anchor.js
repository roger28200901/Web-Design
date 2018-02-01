/* Anchor */
var Anchor = function (data)
{
    this.point = data.point || new Point({});
}

Anchor.prototype.contain = function (point)
{
    return point.x >= this.point.x - 5 && point.x <= this.point.x + 5 && point.y >= this.point.y - 5 && point.y <= this.point.y + 5;
}
