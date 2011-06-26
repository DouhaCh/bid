<script type="text/javascript" src="/js/clipboard/ZeroClipboard.js">
</script>
<script type="text/javascript">
function my_complete( client ) {
    //findPos(document.getElementById('clip_button'));
    //openCopyMessage()
    alert("Đường dẫn đã được copy.\nGiờ bạn hãy phát tán nó đến với bạn bè nhé!");
}

ZeroClipboard.setMoviePath( '<?php echo $appConfigurations['ref_url'];?>/js/clipboard/ZeroClipboard.swf' );
var clip = new ZeroClipboard.Client();


</script>
<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb(__('Referrals', true), '/gioi-thieu-ban');
echo $this->element('crumb_user');
?>
<div id="invite_form">
<h1>Hãy giới thiệu 1bid.vn đến với bạn bè bạn</h1>
<div class="info">
	<p class="quote" style="" align="justify"><strong>1bid</strong> hiểu rõ sức mạnh cộng đồng và câu thành ngữ <strong>"tiếng lành đồn xa"</strong>, vì thế chúng tôi sẽ trao tặng bạn những phần thưởng xứng đáng khi bạn mời bạn bè người thân đến với chúng tôi.
	</p>
	<!--  <div class="refer_wrap" style="height:33px">
		<input type="text" id="ref_link" class="bigi" readonly="readonly" value="<?php echo $appConfigurations['ref_url']."/?khuyenmai=".$user_id;?>">
		<div id="d_clip_button" style="position:relative;text-align:center;float:left;width: 60px">
			<input id="clip_button" type="button" value="Copy">
		</div>
		
	</div>-->
	<div class="clearBoth"></div>
	<script type="text/javascript">
	    clip.setText( document.getElementById('ref_link').value );
	    clip.glue( 'clip_button', 'd_clip_button' );
	    clip.addEventListener( 'oncomplete', my_complete );
	</script>
	<div class="benefit">
	
	<img class="left" src="/images/Coin-icon.png"/>
	<h4>Bạn sẽ nhận được:</h4>
	<ul class="refer_benefit left">
	
	<li><span class="highlight">200 XU</span> cho mỗi người đăng ký thành viên và nạp XU tại <a href="/">1bid.vn</a> theo sự giới thiệu của bạn. <span style="font-style: italic"><b>Lưu ý:</b> bạn chỉ nhận được XU thưởng khi người chơi đó nạp tiền lần đầu tiên.</li>
	<li>Khi thành viên do bạn giới thiệu mua gói XU của chúng tôi, <a href="/">1bid.vn</a> sẽ tặng bạn 10% giá trị lần nạp XU đầu tiên của người đó (bạn sẽ nhận được số XU tương ứng, chỉ giới hạn cho lần nạp bid đầu tiên).</span></li>
<span style="color:#FF3706;font-size:11px;font-weight:bold">* <i>cả 2 chính sách thưởng trên có thể được áp dụng song song</i></span>
	</ul>
	</div>
	<div class="clearBoth"></div>
</div>
<div class="friends_bar">
	Những liên kết đã thành công
</div>
<div class="friends_main info">
	<div class="invite_stats left">
		<p><strong>Bạn đã giới thiệu được:</strong></p>
		<p>Số người đăng ký và nạp XU qua giới thiệu của bạn: <span class="highlight blue"><?php echo $info['overall']['register']?></span>; tương đương với <span class="highlight "> <?php echo ($info['overall']['register']*$appConfigurations['bidPerRegister'])?> XU </span><br/> 
		</p>
	</div>
	<div class="withdraw left">
		<p>Tổng giá trị <b>XU</b> bạn mới được thưởng thêm:</p>
		<p class="xu"><?php echo ($info['unclaimed']['register']*$appConfigurations['bidPerRegister']+$info['unclaimed']['visit']*$appConfigurations['bidPerVisit']);?> XU</p>
		<button class="whiteb" id="withdrawb" >Chuyển vào tài khoản</button>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#withdrawb').click(function(){
			$(this).html('Xin chờ giây lát');
			$(this).attr("disabled", true);
			
			$.ajax({
	    		url: "/referrals/withdraw",
	    		dataType: 'json',
	    		success: function(data){
	    				$.jGrowl(data.message);
	    				if (data.error=='0') 
		    				$('.xu').html('0 XU');
	    				$('#withdrawb').html('Chuyển vào tài khoản');
	    				$('#withdrawb').attr("disabled", false);
		    		}
			});
		});
	});
	</script>
	<div class="clearBoth"></div>
</div>


<!--
<div class="form">
	<div id="importer">
		<p><?php __('Import your contact from webmail services');?></p>
		<?php echo $html->link($html->image('gmail.gif'), array('action' => 'import', 'gmail'), array('class' => 'importAction', 'title' => 'gmail.com'), null, false);?>
		<?php echo $html->link($html->image('yahoo.gif'), array('action' => 'import', 'yahoo'), array('class' => 'importAction', 'title' => 'yahoo.com'), null, false);?>
	</div>
	<h3><?php __('Fill your friends email addresses, separate email by comma (,)');?></h3>
	<div>example: friend1@mail.com, friend2@mail.com, friend3@mail.com</div>
	<?php echo $form->create('Invite', array('action' => 'index')); ?>
	<?php echo $form->textarea('friends_email', array('id'=>'recipient_list','div' => false, 'label' => false,'cols'=> 50,'rows'=>10)); ?>
	<h3><?php echo __('Invite Message')?></h3>
	<?php echo $form->textarea('message', array('div' => false, 'label' => false, 'cols'=> 50,'rows'=>10)); ?>
	
	<?php echo $form->end(__('Invite Now', true)); ?>


	<div id="importer_form" style="display: none">
	<fieldset>
		<?php echo $form->create('User', array('action' => 'import'));?>
			<?php echo $form->input('login', array('class' => 'importerLogin', 'after' => '@<span id="importer_service">&nbsp;</span>'));?>
			<?php echo $form->input('password', array('class' => 'importerPassword'));?>
			<?php echo $form->submit(__('Import', true), array('class' => 'importerSubmit'));?>
		<?php echo $form->end();?>
	</fieldset>
	</div>
	<div id="importer_inprogress" style="display: none">
		
		<?php echo $html->image('spinner2.gif');?><?php __('Please wait while we importing your contacts...');?>
	</div>
</div>-->
<div class="clearBoth"></div>
<?php echo $javascript->link('importer');?>
</div>