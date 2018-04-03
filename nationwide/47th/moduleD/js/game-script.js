window.onload = function ()
{
    var fromStackId = 0,
        toStackId = 0,
        brickId = 0,
        url = '';

    document.querySelectorAll('.brick').forEach(function (brick) {
        brick.addEventListener('dragstart', dragStart, false);
    });

    document.querySelectorAll('.col').forEach(function (stack) {
        stack.addEventListener('dragover', dragOver, false);
        stack.addEventListener('drop', drop, false);
    });

    document.querySelectorAll('.col3>button').forEach(function (moveButton) {
        moveButton.addEventListener('click', moveByButton, false);
    });

    document.getElementById('undo').addEventListener('click', function (event) {
        location.href = 'undo.php';
    });

    document.getElementById('auto').addEventListener('click', function (event) {
        location.href = 'auto.php';
    });

    document.getElementById('repeat').addEventListener('click', function (event) {
        location.href = 'repeat.php';
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

    if (0 < parseInt(document.getElementById('steps').textContent)) {
        document.getElementById('auto').style.display = 'none';
    } else {
        document.getElementById('undo').style.display = 'none';
        document.getElementById('repeat').style.display = 'none';
    }

    var isAuto = document.getElementById('isAuto').value;
    var isRepeat = document.getElementById('isRepeat').value;
    if (isAuto | isRepeat) {
        document.getElementById('auto').setAttribute('disabled', true);
        document.getElementById('undo').setAttribute('disabled', true);
        document.getElementById('repeat').setAttribute('disabled', true);
        var interval = (document.getElementById('interval').value || 0.5) * 1000;
        var url = 'auto.php';
        if (isRepeat) {
            url = 'repeat.php';
        }
        setInterval(function () {
            location.href = url;
        }, interval);
    }
}

function dragStart(event)
{
    fromStackId = this.parentElement.dataset.id;
    brickId = this.dataset.id;
}

function dragOver(event)
{
    event.preventDefault();
    return false;
}

function drop(event)
{
    toStackId = this.dataset.id;
    url = 'move.php';
    if (fromStackId == toStackId) {
        location.reload();
        return false;
    }
    location.change({
        'fromStackId': fromStackId,
        'toStackId': toStackId,
        'brickId': brickId,
    }, url)
    event.preventDefault();
    return false;
}

function moveByButton(event) {
    var fromStackId = this.dataset.id;
    var toStackId = this.textContent;
    var brick = document.querySelector('.col[data-id="' + fromStackId + '"]').firstElementChild;
    if (!brick) {
        return false;
    }
    var brickId = brick.dataset.id;
    var url = 'move.php';
    if (fromStackId == toStackId) {
        location.reload();
        return false;
    }
    location.change({
        'fromStackId': fromStackId,
        'toStackId': toStackId,
        'brickId': brickId,
    }, url);
}

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
