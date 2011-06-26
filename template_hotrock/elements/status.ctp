
<div id="top_toolbar">
	<div class="main">
    	<div class="sep left"></div>
        <div class="logo left">
        	<a href="/"><img src="/images/toolbar_logo.png" /></a>
        </div>
        <div class="dropdown_button left">
        	<button id="nav_menu"></button>
        </div>
        <div class="sep left"></div>
        <!-- <div class="links left"></div> -->
        <div class="sep left"></div>
        <div class="notification left">
        	<div class="noti_left">
        		<div id='news_popup'>&nbsp;</div>
        	</div>
            <div class="noti_right">	
           
            <?php $news = $this->requestAction(array('controller' => 'news', 'action' => 'getlatest'), array('pass' => array(5)));?>
            	<?php 
            	switch ($news[0]['News']['newstype_id']) {
						case 1:
							$news[0]['News']['title'] = "<span style=\"color: #DDDDDD;\">".$news[0]['News']['title']."</span>";
							break;
						case 2:
							$news[0]['News']['title'] = "<span style=\"color: #ff0000;\"><strong>" . $news[0]['News']['title'] . "</strong></span>";
							break;						
					} 
				echo $html->link($news[0]['News']['title'], array('controller'=>'news', 'action' => 'view', $news[0]['News']['id']));
				?>
            
            <ul id="top_news" style="display:none">
				<?php foreach($news as $newsItem):?>
				<li>
					<?php
					switch ($newsItem['News']['newstype_id']) {
						case 1:
							$newsItem['News']['title'] = "<span style=\"color: #DDDDDD;\">".$newsItem['News']['title']."</span>";
							break;
						case 2:
							$newsItem['News']['title'] = "<span style=\"color: #ff0000;\"><strong>" . $newsItem['News']['title'] . "</strong></span>";
							break;						
					} 
					echo $html->link($newsItem['News']['title'], array('controller'=>'news', 'action' => 'view', $newsItem['News']['id']));?>
				<!--	<div class="meta"><?php echo $time->niceShort($newsItem['News']['created']);?></div>-->
				</li>
				<?php endforeach;?>
			</ul>
			</div>
        </div>
        
        <div class="sep left"></div>
        <div class="sep right"></div>
        <div class="userstatus right">
        
			    <?php if($session->check('Auth.User')):?>
			    <div class="dropdown_button right"><button id="user_menu"></button></div>
			    <div id="APE_user_info" style="display: none">
			    	<div id="user_id"><?php echo $session->read('Auth.User.id');?></div>
			    	<div id="passkey"><?php echo $session->read('Auth.User.passkey');?></div>
			    </div>
			    <div class="user-status right">
					<a href="/users">
					<strong>
						<?php
							$temp = explode("@", $session->read('Auth.User.username'));
							
							if(strlen($temp[0])>10){
								$temp[0] = substr($temp[0], 0, 10)."...";
							}
							echo $temp[0];
						?>
					</strong> 	
					</a>
				</div>
				<a href="/users"><img src="<?php echo $session->read('Auth.User.avatar'); ?>" style="width:20px;height:20px;float:right;padding:5px"/></a>
				  <div class="sep right"></div>
			    
				<div class="notifications right" alt="<?php if (!empty($unreadNotifications)) echo count($unreadNotifications);?>">
					<a href="javascript: void(0)">
						<img src="/images/notification.png" style="width:18px;height:18px;margin:4px 2px">
					</a>
			    </div>
			      <div class="sep right"></div>
			    
			  
			    <?php if($session->check('Auth.User')):?>
			    <div class="right"><a id="APE_user_update" href="javascript:void(0)"><img title="Cập nhật số XU" class="poshytip" src="/images/refresh.png" style="padding-top:7px"/></a></div>
        		<div class="bid_balance right" style ="padding-right:5px">
        			<span class="cur_bp" ><?php echo $bidBalance;?></span> XU
        			
       		 	</div>
        		<?php endif;?>
			 
			    <div id="notify_container">
			    	<ul>
			    		<?php if(!empty($unreadNotifications)):?>
			    			<?php foreach($unreadNotifications as $notification):?>
			    			<li class="noti_item unread">
								<a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a>
			    			</li>
			    			<?php endforeach;?>
			    		<?php endif;?>
			    	</ul>
			    	<div style="border-top: 1px solid #333333; text-align:center;margin: 2px 12px;"> <a href="/notifications/index"> See all </a> </div>
			    </div>
			    			    
				
				<?php else:?>
				<a href='/users/login' class="toolbar_link" id="t_login">Đăng nhập</a> | <a href='/users/register' class="toolbar_link" id="t_register">Đăng ký</a> 
				<?php endif;?>
				
				<div id="news_container">
			    	<ul>
			    		<?php if(!empty($news)):?>
			    			<?php foreach($news as $newsItem):?>
			    			<li class="noti_item unread">
								<a href="/news/view/<?php echo $newsItem['News']['id']?>"><?php echo $newsItem['News']['title'];?></a>
			    			</li>
			    			<?php endforeach;?>
			    		<?php endif;?>
			    	</ul>
			    	<div style="border-top: 1px solid #333333; text-align:center;margin: 2px 12px;"> <a href="/news/index"> See all </a> </div>
			    </div>
        
        </div>
        
        <div class="mainmenu" id="top_nav" style="display:none">
        		
				<?php if(!empty($menuCategories)) : ?>
					<h3>DANH MỤC</h3>
					<ul class="menu level1">
							<?php foreach($menuCategories as $menuCategory): ?>
							
								<li class="child <?php if (isset($current_category) && ($current_category == $menuCategory['Category']['id'])) echo 'current';?>">
								
								<a href="/categories/view/<?php echo $menuCategory['Category']['id']; ?>"><?php echo $menuCategory['Category']['name']; ?></a>
								</li>
							<?php endforeach; ?>
					</ul>
					<a href="/categories" style="font-size:10px;font-weight:bold;position:absolute;bottom:0px;right:5px">[xem tất cả]</a>
				
				<?php endif; ?>
        </div>

	<?php if(!$session->check('Auth.User')):?>
	<div class="top_login_form" style="display:none">
	
				<?php echo $form->create('User', array('action' => 'login'));?>
				<div id="top_login">
			    <img src="/images/user.gif">
			    <?php echo $form->input('username', array('id' => 'loginUsername', 'error' => false, 'value' => '', 'div' => false, 'label' => false, 'class' => 'textbox', 'value' => 'username'));?>               
			    <img src="/images/pass.gif">
			    <?php echo $form->input('password', array('id' => 'loginPassword', 'error' => false, 'value' => '', 'div' => false, 'label' => false, 'class' => 'textbox password', 'value' => 'password'));?>
			          
			    <button type="submit" class="login_button" style="display: inline-block; vertical-align: top;"><?php echo __('Login',true);?></button>
			    <div class="clearBoth"></div>
				
				<div class="forgot_pass left">
				<?php echo $html->link(__('Forgotten Your Password?', true), array('controller'=> 'users','action'=>'reset'));?>
				</div>
				<?php echo $form->hidden('url', array('value' => '/'.$this->params['url']['url']));?>
				</div>
				<?php echo $form->end();?>
			
		</div>
		<?php else:?>
			<div class="mainmenu" id="user_nav" style="display:none">
				<ul class="usermenu">
					<li><?php echo $html->link("Thông tin cá nhân", array('controller' => 'users', 'action' => 'edit'));?></li>
					<li><?php echo $html->link("Thay đổi mật khẩu", array('controller' => 'users', 'action' => 'changepassword'));?></li>
				</ul>
	
				<ul class="usermenu" style="padding-top:10px">
					<li><?php echo $html->link("Mua XU", array('controller' => 'packages', 'action' => 'index'));?></li>
					<li><?php echo $html->link("Lịch sử bid", array('controller' => 'bids', 'action' => 'index'));?></li>
					<?php if(!empty($appConfigurations['credits']['active'])) : ?>
						<li><?php echo $html->link("Lịch sử giao dịch", array('controller' => 'credits', 'action' => 'index'));?></li>
					<?php endif; ?>
					<li><?php echo $html->link("Danh sách theo dõi", array('controller' => 'watchlists', 'action' => 'index'));?></li>
					<li><?php echo $html->link("Các phiên đấu giá đã chiến thắng", array('controller' => 'auctions', 'action' => 'won'));?></li>
				</ul>
				<ul class="usermenu" style="padding-top:10px">
					<li><?php echo $html->link("Giới thiệu bạn bè", array('controller' => 'invites', 'action' => 'index'));?></li>					
				</ul>
				<ul class="usermenu" style="padding-top:10px">
				<?php if($session->read('Auth.User.admin') == 1): ?>
					<li><?php echo $html->link('Admin', array('controller' => 'admin', 'action'=>'index'), null, null, false); ?></li>
				<?php endif; ?><li><strong><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action'=>'logout')); ?></strong></li>
				</ul>
		</div>
		   
        <?php endif;?>
    </div>
</div>

<?php if($session->check('Auth.User') && $session->read('Auth.User.admin') == '1'):?>
<div id="adminPanel">
	<form id="adminMessageForm">
		<input type="text" class="adminMessage">
		<input type="submit" value="Gửi" class="submit">
	</form>
</div>
<?php endif;?>


<script type="text/javascript">
$(document).ready(function(){
	
	$('#loginUsername').blur(function(){
		if ($('#loginUsername').val() == ''){
			$('#loginUsername').val('username');
		}
	});

	$('#loginUsername').focus(function(){
		if ($('#loginUsername').val() == 'username'){
			$('#loginUsername').val('');
		}
	});

	$('#loginPassword').blur(function(){
		if ($('#loginPassword').val() == ''){
			$('#loginPassword').val('password');
		}
	});

	$('#loginPassword').focus(function(){
		if ($('#loginPassword').val() == 'password'){
			$('#loginPassword').val('');
		}
	});

	$('.notifications').click(function(){
		$('#notify_container').toggle();
	});

	$('#news_popup').click(function(){
		$('#news_container').toggle();
	});
	
	$('#nav_menu').click(function(){
		$('#top_nav').show();
		
		if ($('#top_nav').css('display')!='none')
			$(this).addClass('selected');
		else 
			$(this).removeClass('selected');
		//return false;
	});
	$('#user_menu').click(function(){
		$('#user_nav').show();
		
		if ($('#user_nav').css('display')!='none')
			$(this).addClass('selected');
		else 
			$(this).removeClass('selected');
		//return false;
	});
	var i=1;
	var items = $('#top_news').children();
	//alert( $('#top_news').children()[1].innerHTML);
	setInterval(function(){
		
		$('.noti_right').html(items[i].innerHTML);
		i++;
		if (i>=items.length) i=0;
	}, 5000);
	$('#t_login').click(function(){
			$('.top_login_form').toggle();
			return false;
		});
	$('.top_login_form img').click(function(){return false});
	$('.top_login_form input').click(function(){return false});
	$(document).click(function(event) {
		
		if (!$(event.target).hasClass('mainmenu')&&!$(event.target).hasClass('top_login_form')&& $(event.target).attr('id') != 'user_menu'){
			//alert($(event.target).parent().parent().hasClass('notifications'));
	
			//$('.top_login_form').hide()
			$('#user_nav').hide();
				
			$(".dropdown_button button").removeClass('selected');
		}

		if (!$(event.target).parent().parent().hasClass('notifications')){
			$('#notify_container').hide();
		}

		if ($(event.target).attr('id') != 'news_popup'){
			//alert('aa');
			$('#news_container').hide();
		}
		
		if ($(event.target).attr('id') != 'nav_menu'){
			$('#top_nav').hide();
		}
	});

	
	
	
});
</script>
