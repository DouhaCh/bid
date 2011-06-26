 
<?php if(!$session->check('Auth.User')):?>
                <div class="reg_form">
	                <span class="rf_left"></span>
	                <div class="rf_content">
	                	<div class="bleft">
	                	<h1>
	                		<?php echo __("Register Now",true);?>
	                	</h1>
	                	
	                	<p><?php echo __("Register rightaway or use your existing account from:",true);?></p>
	                	<ul class="network_icon">
	                		<li id="r_google"><img src="/img/btn_login_facebook.gif" title="Facebook" class="poshytip"/></li>
	                		<li id="r_facebook"><img src="/img/btn_login_google.gif" title="Google" class="poshytip"/></li>
	                		<li id="r_yahoo"><img src="/img/btn_login_yahoo.gif" title="Yahoo" class="poshytip"/></li>
	                	</ul>
	                	</div>
	                	<button id="top_register" class="linkb" href="/users/register"><?php echo __('Register',true);?></button>	                	
	                </div>
	                <span class="rf_right"></span>
	                <div class="clearBoth"></div>
	                <div class="promo_alert"><?php echo sprintf(__("On your first login, you will be rewarded with <strong>%s bp</strong>.",true),' 50,000')?></div>
                </div>
<?php else:?> 
<div class="reg_form">

	                <span class="rf_left"></span>
	                <div class="rf_content">
	                <div id="profile_pic_top">
                           	<div class="profile_pic_wrapper">
                           		<a href="/users"><img src="<?php echo $userImg;?>"/></a>
                           	</div>
                    </div>
	                <ul class="acc_info">
	                	<li><span class="top_h1">Lượng bp hiện có: </span><span class="cur_bp"><?php echo $bidBalance;?></span> Phi tiêu</li>
	                	<li><span class="top_h1">Số điểm BETA: </span><span id="cur_credit"><?php echo round($currentBalance/1000);?> VNĐ</span></li>
	                </ul>
	                	<button class="linkb topb" href="/watchlists"><?php echo __('Watchlists',true);?></button>
	                	<button class="linkb topb" href="/packages"><?php echo __('Buy bids',true);?></button>
	                	<button class="linkb topb" href="/users/logout"><?php echo __('Logout',true);?></button>
	                </div>
	                <span class="rf_right"></span>
</div>               
<?php endif;?>