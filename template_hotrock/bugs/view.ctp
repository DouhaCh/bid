<?php echo $javascript->link('jquery/jquery-1.4.4.min');?>
<style type="text/css">
<!--
.body{
margin:0;
padding:0;
font: 12px/15px Arial,Helvetica,sans-serif;
}
.bugs_wrapper {
	background-color: #efefef;
	width:600px;
	padding:10px;
	
	
}

h4{padding:0;margin:0}
.cb{
	clear:both;
}

.right {
	float:right;
}
.action_button{
	padding:5px;
}
.vote_button {
	margin:3px;
	background:url('/images/vote.gif') no-repeat top left;
	width:43px;
	height:20px;
	border:none;
}
.vote_button:hover{
	background-position:0 -40px;
}
.vote_button:active{
	background-position:0 -20px;
}
.reply_button {
	margin:0 0 5px 0!important;
	background:url('/images/reply.gif') no-repeat top left!important;
	width:54px!important;
	height:20px!important;
	border:none!important;
}
.reply_button:hover{
	background-position:0 -40px!important;
}
.reply_button:active{
	background-position:0 -20px!important;
}
.content {
	padding:4px 5px 0px 10px;
	background-color:#FFF
}
.content .bug_header{
	border-bottom:#AAA 1px solid;
	margin-bottom:10px;
} 

.bug_info{
	font-size:11px;
	color:#666;
	padding-right:14px;
}
.date {font-size:9px;color:#666;text-align:right;width:100%;height:15px;}
.comments {
	width:610px;
	padding:2px 0px 0 10px;
	
}
.comments ul {
	list-style-type:none;
}
.comments ul li {
	height:auto;
	background-color:#FFF;
	width:582px;
	border:#878787 1px solid;
	padding:5px;
	margin-bottom: 15px;
}
textarea
{
    border:1px solid #999999;
    width:600px;
    height:60px;
    padding:0;
    margin-bottom:10px;
}
.reply_text{padding-left:5px;}
-->
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('.vote_now').click(function(){
		var auctionElement = 'auction_' + $(this).attr('alt');
        $.ajax({
    		url: $(this).attr('value'),
    		dataType: 'json',
    		success: function(data){
    					$('.vote_number_'+data['Bug']['id']).html(data['Bug']['vote']);
    					}
			});
	});
});

</script>
<div class="body">
<div class="bugs_wrapper">
<div class="main_bug_post">
	<h3><?php echo $bugs['Bug']['title'];?> (<span class="vote_number_<?php echo $bugs['Bug']['id'];?>"><?php echo $bugs['Bug']['vote'];?> </span>votes)</h3>
	<span class="bug_info">
	<strong>Đăng bởi</strong>: <?php echo $bugs['User']['username'];?>
	</span>
	<span class="bug_info">
		<strong>Thể loại:</strong> <?php echo $bugs['BugType']['name'];?>
	</span>
	<span class="bug_info">
		<strong>Mức độ nghiêm trọng:</strong> <?php echo $bugs['Bug']['servertiy'];?>
	</span>
	<span class="bug_info">
		<strong>Trạng thái:</strong> 
		<?php
		if ($bugs['Bug']['status']==0) echo 'Chờ xem xét';
		elseif ($bugs['Bug']['status']==1) echo 'Đang sửa chữa';
		elseif ($bugs['Bug']['status']==2) echo 'Đã cập nhật';
		?>
	</span>
	<div class="clb"></div>
	<div class="content">
		<p class="bug_header">
		<strong>URL: </strong><a href="<?php echo $bugs['Bug']['url'];?>"><?php echo $bugs['Bug']['url'];?></i></a>
		<br>
		<i><?php echo $bugs['Bug']['location'];?></i>
		</p>
		<?php echo $bugs['Bug']['description'];?>
	</div>
	<div class="clb"></div>
</div>
<div class="action_button">
	<button class="vote_button vote_now" value="/bugs/vote/<?php echo $bugs['Bug']['id'];?>"></button>
	<button class="reply_button" onclick="$('.reply').toggle('500');"></button>
	
	
</div>
</div>

<div class="comments">
<div class="reply" style='display:none'>
	<?php echo $form->create('BugComment', array('action' => 'add'));?>
	<textarea id="BugCommentComment" name="data[BugComment][comment]"></textarea>
	<?php echo $form->hidden('l_url', array('value' => $this->here));?>
	<?php echo $form->hidden('bug_id', array('value' => $bugs['Bug']['id']));?>
	<?php echo $form->end(array('label'=>' ','class'=>'reply_button'));?>
	
</div>
<div class="clb"></div>
	<ul>
	<?php foreach ($comments as $comment):?>
		<li class="comment_post">
			<p><strong><?php echo $comment['User']['username'];?></strong><span class="date"> (<?php echo $comment['BugComment']['created'];?>)</span></p>
			<p class="reply_text"><?php echo $comment['BugComment']['comment'];?></p>
		</li>
	<?endforeach;?>
	
	</ul>
</div>
</div>