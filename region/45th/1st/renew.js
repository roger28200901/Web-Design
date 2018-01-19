(function () {
	var renew = function () {
		$.ajax({
			url: 'renew.php',
			success: function (verificationCode)
			{
				for (var i = 0; i < verificationCode.length; i++) {
					$('#verificationCode' + i).attr('src', 'verificationCodeImage?character=' + verificationCode.charAt(i));
				}
			}
		});
	}

	renew();

	$('#renew').click(function () {
		renew();
	});
})()

