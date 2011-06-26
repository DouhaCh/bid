<script>
	$(document).ready( function() {
		var inputMessage = $("#message");
		var loading = $("#loading");
		var messageList = $(".shoutbox ul");
	
		//functions
		function updateShoutbox(){
			//just for the fade effect
			messageList.hide();
			loading.fadeIn();
			//send the post to shoutbox.php
			$.ajax({
				type: "POST", url: "/comments/shoutbox/<?php echo $comments[0]['Comment']['auction_id']; ?>", data: "action=update",
					complete: function(data){
						loading.fadeOut();
						$(".shoutbox ul").delay(500).html(data.responseText);
						$(".shoutbox ul").fadeIn(500);
				}
			});
		}
	
		updateShoutbox();
	
		//on submit event
		$("#CommentsAddForm").submit(function(){
			var message = $('#CommentsMessage').attr("value");
			//we deactivate submit button while sending
			$(".submit input").attr({ disabled:true, value:"Xin chờ..." });
			$(".submit input").blur();
			//send the post to shoutbox.php
			$.ajax({
				type: "POST", url: "/comments/add/", data: "id=<?php echo $comments[0]['Comment']['auction_id']; ?>&message="+message,
				complete: function(data){
					messageList.html(data.responseText);
					updateShoutbox();
					//reactivate the send button
					$(".submit input").attr({ disabled:false, value:"Chém!" });
				}
			 });
			
			//reactivate the send button
			//we prevent the refresh of the page after submitting the form
			return false;
		})
			
	});
		
</script>

<?php
	echo $form->create('Comments', array(''));
 	echo $form->input('message', array('label' => 'Message *'));
	echo $form->end(__('Register',true));
?>




<div>
	<ul>
		<li>Shoutbox</li>
	</ul>
	<span></span>
	<div class="shoutbox">
		<div id="loading"><img src="/css/images/loading.gif" alt="Loading..." /></div>
		<ul>
		</ul>
	</div>
</div>
