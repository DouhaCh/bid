<?php echo $javascript->link('jquery/jquery.validate.min.js');?>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.rInput input').poshytip({
			className: 'tip-darkgray',
			showOn: 'focus',
			alignTo: 'target',
			alignX: 'right',
			alignY: 'center',
			offsetX: 10
		});
	});
	
	$(document).ready(function(){
		$('.rCheckbox').poshytip({
			content: 'Nhận thông tin từ website',
			className: 'tip-darkgray',
			showOn: 'hover',
			alignTo: 'target',
			alignX: 'center',
			alignY: 'bottom',
			/*offsetX: 20,*/
			offsetY: 5
		});
	});
	
	$().ready(function() {
		$('#UserEditForm').validate({
			errorPlacement: function(error, element) {
				error.appendTo(element.parent("dd").next("div"));
			},
			rules : {
				"data[User][email]" : {
					"required" : true,
					"email" : true,
					"remote" : {
						"url" : "/validate.php?q=4&user_id="+$("input#user_id").attr("value"),
						"type" : "post",					
					},
				},
				"data[User][first_name]" : {
					"required" : true,
				},
				"data[User][mobile]" : {
					"minlength" : 10,
					"maxlength" : 11,
					"digits"	: true,
					"remote" : "/validate.php?q=2&user_id="+$("input#user_id").attr("value")
				},
				"data[User][sid]" : {
					"required" : true,
					"minlength" : 9,
					"maxlength" : 9,
					"digits"	: true,
					"remote" : "/validate.php?q=3&user_id="+$("input#user_id").attr("value")
				}
			},
			messages : {
				"data[User][email]" : {
					"required" : "Email không được để trống",
					"email" : "Email phải đúng định dạng",
					"remote" : "Email đã tồn tại, xin chọn một email khác",
				},
				"data[User][first_name]" : {
					"required" : "Họ tên không được để trống",
				},
				"data[User][mobile]" : {
					"minlength" : "Số di động chỉ gồm 10 hoặc 11 kí tự",
					"maxlength" : "Số di động chỉ gồm 10 hoặc 11 kí tự",
					"digits"	: "Số di dộng chỉ gồm chữ số",
					"remote" : "Số di động của bạn đã tồn tại"
				},
				"data[User][sid]" : {
					"required" : "Số CMND không được để trống",
					"minlength" : "Số CMND phải chỉ gồm đúng 9 kí tự",
					"maxlength" : "Số CMND phải chỉ gồm đúng 9 kí tự",
					"digits"	: "Số CMND chỉ gồm chữ số",
					"remote" : "Số CMND của bạn đã tồn tại"
				}
			}
		});
	});
	
	$().ready(function() {
		$(".hint").hide();
		
		$('.userForm .text').focus(function(){
			$(this).addClass('focus');
			$(this).next(".hint").css('display','inline');
		});

		$('.userForm .text').blur(function(){
			$(this).removeClass('focus');
			$(this).next(".hint").css('display','none');
		});
	});
	
</script>
<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb(__('Edit Profile',true), '/users/edit');
echo $this->element('crumb_user');
?>

	<?php echo $form->create(array('class'=>'userForm', 'action'=>'edit'));?>
		<input type="hidden" value="<?php echo $session->read('Auth.User.id')?>" id="user_id">
		<fieldset>
			<legend>Thông tin cá nhân</legend>
			<dl>
				<dt>
    				<label for="username">Tên đăng nhập:</label>
			  	</dt>
			  	<dd>
					<input type="text" disabled="true" class="text" id="UserUsername" value="<?php echo $this->data['User']['username']?>" maxlength="80" name="data[User][username]">
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<?php if($session->read('Auth.User.changed')=='0'):?>
			  	<dt>
    				<label for="email">Email:</label>
			  	</dt>
			  	<dd>
					<input type="text" class="text" id="UserEmail" value="<?php echo $this->data['User']['email']?>" maxlength="80" name="data[User][email]">
			    	<span class="hint">
			    		Email phải đúng định dạng (ví dụ:abc@yahoo.com, abc@gmail.com ...). <strong> Lưu ý: Địa chỉ email chỉ được phép thay đổi 1 lần </strong>
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="firstname">Họ và tên:</label>
			  	</dt>
			  	<dd>
					<input type="text" class="text" id="UserFirstName" value="<?php echo $this->data['User']['first_name']?>" maxlength="80" name="data[User][first_name]">
			    	<span class="hint">
			    		Họ và tên không được chứa chữ số và các kí tự đặc biệt. <strong> Lưu ý: Họ và tên phải trùng khớp với CMND và chỉ được phép thay đổi 1 lần </strong>.
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="sid">Số CMND:</label>
			  	</dt>
			  	<dd>
					<input type="text" class="text" id="UserSid" value="<?php echo $this->data['User']['sid']?>" maxlength="80" name="data[User][sid]">
			    	<span class="hint">
			    		Số CMND chỉ được gồm chữ số, có độ dài 9 kí tự.<strong>Lưu ý: Số CMND chỉ được phép thay đổi 1 lần</strong>
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="mobile">Điện thoại di động:</label>
			  	</dt>
			  	<dd>
					<input type="text" class="text" id="UserMobile" value="<?php echo $this->data['User']['mobile']?>" maxlength="80" name="data[User][mobile]">
			    	<span class="hint">
			    		Số điện thoại chỉ được gồm chữ số, có độ dài 10 hoặc 11 kí tự.<strong>Lưu ý: Số điện thoại chỉ được phép thay đổi 1 lần</strong>
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<?php else:?>
			  	<dt>
    				<label for="email">Email:</label>
			  	</dt>
			  	<dd>
					<input type="text" disabled="true" class="text" id="UserEmail" value="<?php echo $this->data['User']['email']?>" maxlength="80" name="data[User][email]">
			    	<span class="hint">
			    		Email phải đúng định dạng (ví dụ:abc@yahoo.com, abc@gmail.com ...). <strong> Lưu ý: Địa chỉ email chỉ được phép thay đổi 1 lần </strong>
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="firstname">Họ và tên:</label>
			  	</dt>
			  	<dd>
					<input type="text" disabled="true" class="text" id="UserFirstName" value="<?php echo $this->data['User']['first_name']?>" maxlength="80" name="data[User][first_name]">
			    	<span class="hint">
			    		Họ và tên không được chứa chữ số và các kí tự đặc biệt. <strong> Lưu ý: Họ và tên phải trùng khớp với CMND và chỉ được phép thay đổi 1 lần </strong>.
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="sid">Số CMND:</label>
			  	</dt>
			  	<dd>
					<input type="text" disabled="true" class="text" id="UserSid" value="<?php echo $this->data['User']['sid']?>" maxlength="80" name="data[User][sid]">
			    	<span class="hint">
			    		Số CMND chỉ được gồm chữ số, có độ dài 9 kí tự.<strong>Lưu ý: Số CMND chỉ được phép thay đổi 1 lần</strong>
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="mobile">Điện thoại di động:</label>
			  	</dt>
			  	<dd>
					<input type="text" disabled="true" class="text" id="UserMobile" value="<?php echo $this->data['User']['mobile']?>" maxlength="80" name="data[User][mobile]">
			    	<span class="hint">
			    		Số điện thoại chỉ được gồm chữ số, có độ dài 10 hoặc 11 kí tự.<strong>Lưu ý: Số điện thoại chỉ được phép thay đổi 1 lần</strong>
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<?php endif;?>
			  	
			  	<dt>
    				<label for="address">Địa chỉ liên hệ:</label>
			  	</dt>
			  	<dd>
					<?php echo $form->textarea('address', array('label' => false, 'div' => false, 'class' => 'text', 'rows' => '4'));?>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="mobile">Ngày sinh:</label>
			  	</dt>
			  	<dd>
					<?php echo $form->input('date_of_birth', array('minYear' => $appConfigurations['Dob']['year_min'], 'maxYear' => $appConfigurations['Dob']['year_max'], 'label' => false, 'div' => false));?>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="mobile">Giới tính:</label>
			  	</dt>
			  	<dd>
					<?php echo $form->input('gender_id', array('type' => 'select', 'label' => false, 'div' => false));?>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>&nbsp;
			  	</dt>
			  	<dd>
					<input type="submit" class="submit" value="Lưu thay đổi">
			  	</dd>
			  		<div class="clearBoth"></div>
			  	<dt>&nbsp;
			  	</dt>
			  	<!--  <dd class="editSend">
			  		Nếu bạn muốn thay đổi những mục không thể thay đổi ở trên, vui lòng click vào <a href="#">đây</a> để thông báo cho chúng tôi.
			  	</dd>-->
			</dl>
		</fieldset>
	</form>
