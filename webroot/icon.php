
<?php
	for($count=1;$count<=28;$count++)
	{
		echo "<span class='emo' onclick='updateEmo(".$count.")' style='padding:0px; margin:0px; cursor: pointer'> <img src='/img/emoticons/".$count.".png' style='margin-left: 5px; margin-top: 5px'/> </span>";
	}