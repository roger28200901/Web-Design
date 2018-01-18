location.get = function (k)
{
    var data = {};
    location.search.substr(1).split('&').forEach(function (s) {
        s = s.split('=');
        var k = s.shift(),v = s.join('=');
        v = decodeURIComponent(v);
        
        data[k] = v;
	});

    if(k) return data[k];
    return data;
};

location.change = function (data, url)
{
    var get = this.get();
    for(var k in data){
        get[k] = data[k];
    }

    var arr = [];
    for(var j in get){
        arr.push(
            encodeURIComponent(j) + '=' + encodeURIComponent(get[j])
        );
    }

    this.href = url + '?' + arr.join('&');
};

window.onload = function ()
{
    document.querySelectorAll('.col3>button').forEach(function (moveButton) {
        moveButton.addEventListener('click', function () {
            var fromStackId = this.dataset.id;
            var toStackId = this.textContent;
            var brickId = document.querySelector('.col[data-id="' + fromStackId + '"]').firstElementChild.dataset.id;
            var url = 'move.php';
            location.change({
                'fromStackId': fromStackId,
                'toStackId': toStackId,
                'brickId': brickId,
            }, url)
        });
    });

    var activeBrick;

    document.querySelectorAll('.brick').forEach(function (brick) {
        brick.addEventListener('mousedown', function (event) {
            activeBrick = brick;

            /* get cursor and brick position */
            var cursorX = event.pageX;
            var cursorY = event.pageY;
            var left = brick.offsetLeft;
            var top = brick.offsetTop;

            /* mouse move action */
            document.querySelector('body').addEventListener('mousemove', function (event) {
                brick.style.position = 'absolute';
                brick.style.left = left + event.pageX - cursorX + 'px';
                brick.style.top = top + event.pageY - cursorY + 'px';
            });
            
            document.querySelector('body').addEventListener('mouseup', function (event) {
                var fromStackId = brick.parentElement.dataset.id;
                var toStackId = document.elementsFromPoint(event.pageX, event.pageY).find(function (element) {
                    return element.getAttribute('class') == 'col';
                }).dataset.id;
                var brickId = brick.dataset.id;
                var url = 'move.php';
                if (fromStackId == toStackId) {
                    location.reload();
                }
                location.change({
                    'fromStackId': fromStackId,
                    'toStackId': toStackId,
                    'brickId': brickId,
                }, url);
            });
        });
    });

    var errorMessage = document.getElementById('errorMessage').value;
    if (errorMessage) {
        alert(errorMessage);
    }

    if (document.getElementById('complete').value) {
        var url = 'complete.php';
        var interval = 5000;
        setInterval(function () {
            location.href = url;
        }, interval);
    }
}
