<?php $head_ban = array('home'); ?>
<?php $left_col = array('home','users'); ?>
<?php $cat_panel = array("Auctions.view"); ?>
<?php $no_left = array('Pages'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $appConfigurations['name'];?>::
		<?php echo 'Đấu giá xu!' ?> 
	</title>
	<style type="text/css">
		img, a, input, button { behavior: url(/iepngfix.htc);}
	</style> 
	
	<?php
		if(!empty($meta_description)) :
			echo $html->meta('description', $meta_description);
		endif;
		if(!empty($meta_keywords)) :
			echo $html->meta('keywords', $meta_keywords);
		endif;

		echo $html->css('thickbox');
		echo $html->css('/css/constant');
		echo $html->css('/css/template');
		echo $html->css('/css/tip-darkgray/tip-darkgray.css');
		echo $html->css('/css/tip-yellow/tip-yellow.css');
		echo $html->css('/css/tip-yellowsimple/tip-yellowsimple.css');
		echo $html->css('/js/jquery/jquery.jgrowl.css');
		//echo $javascript->link('jquery/ui');
		
		//echo $javascript->link('ifixpng');
		echo $javascript->link('corner');
		echo $javascript->link('jquery/jquery-1.4.4.min');
		echo $javascript->link('jquery/jquery.thickbox.min');
		echo $javascript->link('jquery/jquery.imgareaselect.min');
		echo $javascript->link('jquery/jquery.numberformatter-1.1.0.js');
		echo $javascript->link('jquery/jquery.jgrowl');
		echo $javascript->link('jquery/jquery.cookie');
		echo $javascript->link('fadeslideshow');
		
		echo $javascript->link('dateformat.js');
		echo $javascript->link('jquery/jquery.cookie');
		echo $javascript->link('json2');
		echo $javascript->link('apps');
		echo $javascript->link('jquery/jquery.validate.min.js');

		echo $scripts_for_layout;
	?>

        <script>

   		$(document).ready(function(){
   	   		
   			<?php if($session->check('Auth.User')):?>
   			<?php if(!empty($reminders)):?>
   			<?php foreach($reminders as $reminder):?>
   				$.jGrowl('<a href="<?php echo $reminder['Reminder']['link'];?>"><?php echo $reminder['Reminder']['description']?></a>', { sticky: true });
   			<?php endforeach;?>
   			<?php endif;?>
   			<?php endif;?>
   			
   			$.validator.addMethod("alphaNumeric", function(value) {
   				var patern = /^[a-zA-Z0-9]+$/;
   				return patern.test(value);
   			}, '');

   			$.validator.addMethod("alphaNumeric_", function(value) {
   				var patern = /^[a-zA-Z0-9_]+$/;
   				return patern.test(value);
   			}, '');

   			$.validator.addMethod("alphaSpace", function(value) {
   				var patern = /^[a-zA-Z\s]+$/;
   				return patern.test(value);
   			}, '');
   			
			$('.tabbed').children().hide();
			$('#tab1').show();
			$('#pi_tab1').show();
			$('#ad_menu ul li a').click(function(){
				$('#ad_content div').hide();
				var opentab = this.className;
				$('div#'+opentab).show();
				$('div#ad_menu li').removeClass("active");
				$(this).parent().addClass("active");
			});
			$('div#product_info_nav ul li a').click(function(){
				$('#product_info_content').children().hide();
				var opentab = this.className;
				$('div#'+opentab).show();
				$('div#product_info_nav li').removeClass("active");
				$(this).parent().addClass("active");
			});
		
			$('#feedback_link').click(function(){
				tb_show("Thông báo lỗi","/bugs/?KeepThis=true&TB_iframe=true&height=460&width=640",false);
			});

		});
		</script>
</head>

<body id="body">
    <div id="header">
        <div class="main">
            <div class="top">
                <div class="logo">
                    
                </div>
                <div class="login">
				<?php echo $this->element('status');?>
                </div>
				
            </div>
            <?php //echo $this->element('register_top');?>
 
            <div class="row">
                <div id="topmenu">
	                <?php echo $this->element('menu_top');?>
                </div>
                <div class="right" style="margin:10px;">
                	<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2F1bid.vn&amp;layout=button_count&amp;show_faces=false&amp;width=96&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:96px; height:21px;" allowTransparency="true"></iframe>
                </div>
                <div id="search">
                </div>
            </div>
        </div>
    </div>
    <div id="wrapper">
        <div class="main">
            <div class="banners">
		            <?php if(in_array($this->action, $head_ban)):?>
						            
		                <div class="bannergroup">
							
							<div class="banneritem">
							<!--
							<a class="link-prev"><</a>
							<a class="link-next">></a>-->
							<script type="text/javascript">
					           	 	var myimages= [["/images/slide2.png",""],["/images/slide6.jpg","http://www.zing.vn/news/xa-hoi/hoi-tuong-lai-dem-valentine-ky-dieu/a107099.html"],["/images/slide4.jpg","http://contest.bsb.com.vn"]];
									new dropinslideshow(myimages, 638, 220, 3000);
							</script>
							</div>
							<?php if(!$session->check('Auth.User')):?>
								<script>
									$(document).ready(function(){
										$('#quickRegisterForm').validate({
											errorElement: "span",
											rules : {
												"data[User][email]" : {
													"required" : true,
													"email"	   : true,
													"remote" : "/validate.php?q=4",
												},
												"data[User][before_password]" : {
													"required" : true,
													"rangelength" : [6, 20],
													"alphaNumeric" : true,
												},
												"data[User][retype_password]" : {
													"required" : true,
													"rangelength" : [6, 20],
													"equalTo" : "#UserBeforePassword",
													"alphaNumeric" : true,
												}
											},
											messages : {
												"data[User][email]" : {
													"required" : "&nbsp;",
													"email"	   : "&nbsp;",
													"remote"   : "&nbsp;"
												},
												"data[User][before_password]" : {
													"required" : "&nbsp;",
													"rangelength" : "&nbsp;",
													"alphaNumeric" : "&nbsp;"
												},
												"data[User][retype_password]" : {
													"required" : "&nbsp;",
													"rangelength" : "&nbsp;",
													"alphaNumeric" : "&nbsp;",
													"equalTo" : "&nbsp;"
												}
											},
											success: function(label) {
												// set &nbsp; as text for IE
												label.html("&nbsp;").addClass("success");
											}
										});
									});
								</script>
			    				<div class="rightbanner">
				  					<div class="regform_cap">
				  						ĐĂNG KÝ NHANH
			    					</div>
			    					<div class="regform">
			    					<?php
			    						echo $form->create('User', array('action' => 'register', 'id'=>'quickRegisterForm'));
			    					 	echo $form->input('email', array('label' => 'Email', 'div' => 'rInput', 'title' => 'Địa chỉ email của bạn'));
										echo $form->input('before_password', array('value' => '', 'type' => 'password', 'label' =>  __('Mật mã',true), 'div' => 'rInput', 'title' => 'Từ 6 đến 8 kí tự'));
										echo $form->input('retype_password', array('value' => '', 'type' => 'password', 'label' =>  __('Xác nhận lại',true), 'div' => 'rInput', 'title' => 'Nhập lại mật khẩu như đã nhập ở trên'));
										echo $form->end(__('Register',true));
									?>
										<div class="reg_info">
											Với việc đăng ký, bạn đã đồng ý với <a href="/thong-tin/quy-dinh-dieu-khoan">Quy định & điều khoản</a>
										</div>
			    					</div>
	
			    				</div>
							<?php else:?>
								<div class="prepaid_banner" >
									<div style="text-align:center;margin-top:3px">
									<a href="/nap-xu"><img src="/images/muaxu.png"></a>
									<a href="/thecao.html" target="_blank">Hướng dẫn chi tiết</a>
									<img src="/images/prepaidcards.png">	
									</div>
								</div>			    
								<script type="text/javascript">
								  $(document).ready(function(){
									$('#coupon_redem').click(function(){
											$.ajax({
							                    url: '/coupons/redeem/' + $('#i_p').attr('value'),
							                    dataType: 'json',
							                    success: function(data){
							                        alert(data.mes);
							                    }
							                });
										});
							
								  });
								</script>				
			    			<?php endif;?>
			    			<div class="clearBoth"></div>
		    			</div>
					<?php endif;?>
				<div class="bannergroup-s1">
					
					<div class="banneritem-s1"><a href="/"><img src="/images/banner_ongoing.gif" alt="Banner"></a><div class="clr"></div> 
					    </div>
					<div class="banneritem-s1"><a href="/da-ban"><img src="/images/banner_sold.gif" alt="Banner"></a><div class="clr"></div>
					    </div>
					<div class="banneritem-s1"><a href="/dau-gia-hot"><img src="/images/banner_hot.gif" alt="Banner"></a><div class="clr"></div>
					    </div>

				</div>
         	</div>
            <div class="clear">
            <?php if(!in_array($this->name, $no_left)):?>
                <div id="left">
                	<?php if(!in_array($this->name.'.'.$this->action, $cat_panel)):?>
                    <?php endif;?>    
            		
            		
    				<?php echo $this->element('invite');?>
    				<?php echo $this->element('testimonial');?>
    				<?php echo $this->element('support');?>
    				
    				<?php echo $this->element('pricelist');?>
    				<?php echo $this->element('recently_sold');?>
                </div>                  
            <?php endif;?>
                <div class="container">
                                    
                
    			<?php
					if($session->check('Message.flash')){
						echo "<script> $.jGrowl('".$session->read('Message.flash.message')."')</script>";
						$session->flash();
					}
		
					if($session->check('Message.auth')){
						$session->flash('auth');
					}
				?>
				<?php echo $content_for_layout; ?>               
                </div>
    			<div class="clearBoth"> </div>
            </div>
            <div class="clearBoth"> </div>  
    </div>
    <?php echo $this->element('footer');?>
</body>
</html> 
