<p>Thông tin của bạn đang được cập nhật, xin vui lòng đợi trong giây lát ...</p>
<p>Trình duyệt sẽ tự động chuyển trong vòng 5s</p>
<script>
	var c = new APE.Client();
	c.load();

	c.addEvent('load', function() {
		c.core.start();
	});

	c.addEvent('ready', function() {
		APE_user_id = $("#APE_user_info #user_id").html();
		APE_passkey = $("#APE_user_info #passkey").html();
		c.core.request.send('updateUser', {'user_id' : APE_user_id, 'passkey' : APE_passkey});
	});

	setTimeout(function(){
		window.location = '/users';
	}, 5000)
</script>