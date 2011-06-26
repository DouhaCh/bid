
<div id="MessageList">
<?php foreach($comments as $comment):?>
	<div class="commentInfo"><?php echo $comment['User']['username'];?> </div>
	<div class="commentBox">
		<div class="commentBoxShape"> </div>
		<div class="commentContent">
			<div class="commentEmo"><img src='<?php echo $comment['Comment']['emo']?>'/> </div>
			<div class="commentMessage"><?php echo $comment['Comment']['message']?></div>
			<div class="commentDate"> <?php echo $comment['Comment']['time']?> </div>
		</div>
	</div>
<?php endforeach;?>
</div>
