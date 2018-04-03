window.onload = function ()
{
    document.getElementById('avatarDisplay').addEventListener('click', function (e) { // image frame on click
        document.getElementById('avatar').click(); // click the file element to upload
        e.preventDefault();
    });
    
    document.getElementById('avatar').addEventListener('change', function (e) {
        
        /* load file */
        var file = this.files[0];
        if (!file.type.match(/image\/(jpg|jpeg|png|gif)/)) { // check file type
            alert('Invalid File Type');
            e.preventDefault();
            return false;
        }
        if (8 * 1024 * 1024 < file.size) { // limit file size
            alert('Exceed The Limit Of File Size');
            e.preventDefault();
            return false;
        }

        /* upload file */
        var fReader = new FileReader();
        fReader.onload = function (event)
        {
            document.getElementById('avatarDisplay').src = this.result; // put image into image frame
        }

        fReader.readAsDataURL(file); // read as base64
    });
}
