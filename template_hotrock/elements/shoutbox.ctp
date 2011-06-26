
<script>
/*
	$(document).ready( function() {
		var inputMessage = $("#message");
		var loading = $("#loading");
		var messageList = $(".shoutbox");
		var submitButton = $("#shoutBoxContainer .shoutForm .submit input");
		
		
		<?php if($session->check('Auth.User')):?>
		function checkSpam(){
			for(i=0;i<5;i++)
			{
				if(<?php echo $session->read('Auth.User.id')?>!=$('.commentInfo:eq('+i+')').attr('alt'))
					return false
			}
			return true
		}
		<?php endif;?>
	
		
		//functions
		function updateShoutbox(){
			//just for the fade effect
			//messageList.hide();
			//loading.fadeIn();
			//send the post to shoutbox.php
			$.ajax({
				type: "POST", url: "/shoutboxL.php", data: "id="+$('#latestID').attr('value'),
					complete: function(data){
						//loading.fadeOut();
						if(data.responseText!='none')
						{
							//alert(data.responseText);
							$(".shoutbox").html(data.responseText);
							$(".shoutbox").show();
						}
				}
			});
		}
	
		$.ajax({
			type: "POST", url: "/shoutboxL.php", data: "id=0",
				complete: function(data){
					//loading.fadeOut();
					$(".shoutbox").html(data.responseText);
					$(".shoutbox").show();
				}
		});	
		
    	setInterval(function(){
    		 updateShoutbox();
    	},2000);
	
		//on submit event
		$("#CommentsAddForm").submit(function(){
			//set idle_time
			idle_time=0;
			if(checkSpam()!=true)
			{
				var message = $('#CommentsMessage').attr("value");
				//we deactivate submit button while sending
				submitButton.attr({ disabled:true, value:"Gửi..." });
				submitButton.blur();
				
				$('#CommentsMessage').attr("value","");
				//send the post to shoutbox.php
				$.ajax({
					type: "POST", url: "/shouts/add/", data: "message="+message+"&emo="+$(".emoSelected").attr('alt'),
					complete: function(data){
						messageList.html(data.responseText);
						updateShoutbox();
						//reactivate the send button
						submitButton.attr({ disabled:false, value:"Chém!" });
					}
				 });
			}
			else alert('No spam please');
			
			
			//reactivate the send button
			//we prevent the refresh of the page after submitting the form
			return false;
		})
	});
	
	*/
</script>

<div class="module">
<h3>Shoutbox</h3>
<div class="shoutBoxContainer">
     <?php if($session->check('Auth.User')):?>
           <div class="shoutForm">
               	<?php echo $form->create('Shouts', array(''));?>
 				<?php echo $form->input('message', array('label' => false));?>
 				<?php echo $form->hidden('emo');?>
 				<?php echo $form->end(__('Chém!',true));?>
 			</div>
 	<?php endif;?>
 										
 											
		<div class="clearBoth"> </div>
			<div id="mess_wrapper">
			
			<div class="shoutbox">
				<div id="MessageList">
					<?php foreach($shouts as $shout):?>
					<script>
						content = '<div class="chat_item">';
	            		content+= 	'<div class="commentInfo"><?php echo $shout['User']['username'];?></div>'
	            		content+= 	'<div class="commentBox">';
	            		content+=		'<div class="commentBoxShape"> </div>';	
	            		content+=		'<div class="commentContent">';
	            		content+= 			'<div class="commentMessage">'+decodeUTF8('<?php echo $shout['Shout']['message'];?>')+'</div>';
	            		content+=		'</div>';
	            		content+=	'</div>';
	            		content+= '</div>';
	        			$('#MessageList').append(content);
					</script>
					<?php endforeach;?>
				</div>
				<div class="clearBoth"> </div>
			</div>
			</div>
		</div>
</div>