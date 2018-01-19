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
    var brickId = document.querySelector('.col[data-id="' + fromStackId + '"]').firstElementChild.dataset.id;
    var url = 'move.php';
    if (fromStackId == toStackId) {
        location.reload();
        return false;
    }
    location.change({
        'fromStackId': fromStackId,
        'toStackId': toStackId,
        'brickId': brickId,
    }, url)
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
