<?php
	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
	$auction_id=$_POST['auction_id'];
	$latest_id=$_POST['id'];
	
	$sql="select id from comments where auction_id=$auction_id and status=1 order by id desc limit 0,1";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	if($latest_id<$row['id'] || $latest_id==0):
?>

<div id="MessageList">
<?php
	$sql="select comments.*,comments.user_id,users.username from comments join users on comments.user_id=users.id where auction_id=$auction_id and status=1 order by id desc";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	if(!empty($row)):
?>
	<div class="chat_item">
		<input id='latestID' type='hidden' value='<?php echo $row['id'];?>'/>
		<div class="commentInfo" alt='<?php echo $row['user_id']?>'><?php echo $row['username'];?> </div>
		<div class="commentBox">
			<div class="commentBoxShape"> </div>
			<div class="commentContent">
				<div class="commentEmo"><img src='<?php echo $row['emo']?>'/> </div>
				<div class="commentMessage"><?php echo $row['message']?></div>
				<div class="commentDate"> <?php echo $row['time']?> </div>
			</div>
		</div>
	</div>
<?php 
	while($row=mysql_fetch_array($result))
	{
?>
	<div class="chat_item">
		<div class="commentInfo" alt='<?php echo $row['user_id']?>'><?php echo $row['username'];?> </div>
		<div class="commentBox">
			<div class="commentBoxShape"> </div>
			<div class="commentContent">
				<div class="commentEmo"><img src='<?php echo $row['emo']?>'/> </div>
				<div class="commentMessage"><?php echo $row['message']?></div>
				<div class="commentDate"> <?php echo $row['time']?> </div>
			</div>
		</div>
	</div>
<?php
	}
	endif;
?>
	<div class="clearBoth"> </div>
</div>
<?php else: echo 'none';?>
<?php endif;?>