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
		$('#UserRegisterForm').validate({
			errorPlacement: function(error, element) {
				error.appendTo(element.parent("dd").next("div"));
			},
			rules : {
				"data[User][username]" : {
					"required" : true,
					"minlength" : 4,
					"maxlength" : 50,
					"alphaNumeric_" : true,
					"remote" : {
						"url" : "/validate.php?q=1",
						"type" : "post"					
					}
				},
				"data[User][before_password]" : {
					"required" : true,
					"rangelength" : [6, 20],
				},
				"data[User][retype_password]" : {
					"required" : true,
					"rangelength" : [6, 20],
					"equalTo" : "#UserBeforePassword",
				}
			},
			messages : {
				"data[User][username]" : {
					"required" : "Tên đăng nhập không được để trống",
					"minlength" : "Tên đăng nhập phải nhiều hơn 4 kí tự",
					"maxlength" : "Tên đăng nhập phải ít hơn 50 kí tự",
					"remote" : "Tên đăng nhập đã tồn tại",
					"alphaNumeric_" : "Tên đăng nhập chỉ gồm chữ cái, chữ số và dấu gạch dưới"	
				},
				"data[User][before_password]" : {
					"required" : "Mật khẩu không được để trống",
					"rangelength" : "Mật khẩu có độ dài từ 6 đến 20 kí tự",
				},
				"data[User][retype_password]" : {
					"required" : "Xác nhận mật khẩu không được để trống",
					"rangelength" : "Xác nhận mật khẩu phải có độ dài từ 6 đến 20 kí tự",
					"equalTo" : "Xác nhận mật khẩu và mật khẩu phải giống nhau"
				}
			}
		});
	});

	$(document).ready(function() {
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


<div class="box clearfix">
	
	<?php echo $form->create(array('class'=>'userForm', 'action'=>'register'));?>
		<fieldset>
			<legend>Đăng ký tài khoản</legend>
			<dl>
  				<dt>
    				<label for="username">Tên đăng nhập:</label>
			  	</dt>
			  	<dd>
					<input type="text" class="text" id="UserUsername" value="<?php echo $this->data['User']['username']?>" maxlength="80" name="data[User][username]">
			    	<span class="hint">
			    		Tên đăng nhập chỉ được gồm chữ cái, chữ số và dấu gạch dưới
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="before_password">Mật khẩu:</label>
			  	</dt>
			  	<dd>
					<input type="password" class="text" id="UserBeforePassword" value="<?php echo $this->data['User']['before_password']?>" maxlength="80" name="data[User][before_password]">
			    	<span class="hint">
			    		Mật khẩu chỉ được gồm chữ cái và chữ số, có độ dài từ 8 đến 20 kí tự
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>
    				<label for="retype_password">Xác nhận mật khẩu:</label>
			  	</dt>
			  	<dd>
					<input type="password" class="text" id="UserRetypePassword" value="<?php echo $this->data['User']['before_password']?>" maxlength="80" name="data[User][retype_password]">
			    	<span class="hint">
			    		Mật khẩu xác nhận phải trùng khớp với mật khẩu đã nhập ở trên
			    		<span class="hint-pointer">&nbsp;</span>
			    	</span>
			  	</dd>
			  	<div class="clearBoth">&nbsp;</div>
			  	<dt>&nbsp;
			  	</dt>
			  	<dd>
			  		<p>Bằng việc click vào nút Đăng ký dưới đây, bạn đã đọc và chấp nhận <a href="/page/quy-dinh-dieu-khoan"> Quy định & Điều khoản </a>. </p>
					<input type="submit" class="submit" value="Đăng ký">
			  	</dd>
			</dl>
		</fieldset>
	</form>
