<script>
	$(document).ready(function() {
		$('#notification_container .dataButton').click(function() {
			$('#notification_container .dataDiv').hide();
			$('#notification_container .'+$(this).attr('alt')).show();
			$('#notification_container ul li').removeClass('selected');
			$(this).parent().addClass('selected');
		});

		$('.thickbox').click(function(){
			$(this).parent().removeClass('unread');
			$(this).parent().children('img').remove();
		});
	});

</script>

<?php
	$html->addCrumb(__('Notifications', true), '/notifications');
	echo $this->element('crumb_auction');
?>

<div id="notification_container">
	<ul class="left">
  		<li class="selected"><a href="javascript:void(0)" class="dataButton" alt="tab01">Tất cả</a></li>
    	<li> <a href="javascript:void(0)" class="dataButton" alt="tab02">Sản phẩm</button></li>
   		<li class="last"> <a href="javascript:void(0)" class="dataButton" alt="tab03">Bid tự động</a></li>
    </ul>
    <div class="clearBoth"> </div>
    
   	<div class="tab01 dataDiv">
   		<div class="date">
		<h3>
			<?php echo date('Y-m-d',strtotime($notifications[0]['Notification']['created']));?>
		</h3>
	</div>
	<?php foreach($notifications as $key => $notification):?>
	<div class='item'>
		<?php if($notification['Notification']['status']=='0'):?>
			<div class="notification_title unread"><a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a><img src='/img/new.png' style='width:18px; height:18px'/></div>
		<?php else:?>
			<div class="notification_title"><a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a></div>
		<?php endif;?>
		<div class="notification_message"><?php echo $notification['Notification']['message'];?></div>
		<div class="notification_created right"><?php echo $notification['Notification']['created'];?></div>
		<div class="clearBoth"></div>
	</div>
		<?php if($key < count($notifications)-1):?>
			<?php if(date('Y-m-d',strtotime($notification['Notification']['created']))!=date('Y-m-d',strtotime($notifications[$key+1]['Notification']['created']))):?>
			<div class="date">
				<h3>
					<?php echo date('Y-m-d',strtotime($notifications[$key+1]['Notification']['created']));?>
				</h3>
			</div>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach;?>
	</div>
	
	<div class="tab02 dataDiv" style="display: none">
	<?php if(!empty($productNotifications)):?>
   		<div class="date">
		<h3>
			<?php echo date('Y-m-d',strtotime($productNotifications[0]['Notification']['created']));?>
		</h3>
	</div>
	<?php foreach($productNotifications as $key => $notification):?>
	<div class='item'>
		<?php if($notification['Notification']['status']=='0'):?>
			<div class="notification_title unread"><a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a><img src='/img/new.png' style='width:18px; height:18px'/></div>
		<?php else:?>
			<div class="notification_title"><a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a></div>
		<?php endif;?>
		<div class="notification_message"><?php echo $notification['Notification']['message'];?></div>
		<div class="notification_created right"><?php echo $notification['Notification']['created'];?></div>
		<div class="clearBoth"></div>
	</div>
		<?php if($key < count($productNotifications)-1):?>
			<?php if(date('Y-m-d',strtotime($notification['Notification']['created']))!=date('Y-m-d',strtotime($productNotifications[$key+1]['Notification']['created']))):?>
			<div class="date">
				<h3>
					<?php echo date('Y-m-d',strtotime($productNotifications[$key+1]['Notification']['created']));?>
				</h3>
			</div>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach;?>
	<?php endif;?>
	</div>
	
	<div class="tab03 dataDiv" style="display: none">
	<?php if(!empty($bidbutlerNotifications)):?>
   		<div class="date">
		<h3>
			<?php echo date('Y-m-d',strtotime($bidbutlerNotifications[0]['Notification']['created']));?>
		</h3>
	</div>
	<?php foreach($bidbutlerNotifications as $key => $notification):?>
	<div class='item'>
		<?php if($notification['Notification']['status']=='0'):?>
			<div class="notification_title unread"><a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a><img src='/img/new.png' style='width:18px; height:18px'/></div>
		<?php else:?>
			<div class="notification_title"><a class='thickbox' href="/notifications/view/<?php echo $notification['Notification']['id']?>?height=160&width=295"><?php echo $notification['Notification']['title'];?></a></div>
		<?php endif;?>		<div class="notification_message"><?php echo $notification['Notification']['message'];?></div>
		<div class="notification_created right"><?php echo $notification['Notification']['created'];?></div>
		<div class="clearBoth"></div>
	</div>
		<?php if($key < count($bidbutlerNotifications)-1):?>
			<?php if(date('Y-m-d',strtotime($notification['Notification']['created']))!=date('Y-m-d',strtotime($bidbutlerNotifications[$key+1]['Notification']['created']))):?>
			<div class="date">
				<h3>
					<?php echo date('Y-m-d',strtotime($bidbutlerNotifications[$key+1]['Notification']['created']));?>
				</h3>
			</div>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach;?>
	<?php endif;?>
	</div>
</div>