<?php
	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
	$rs = mysql_query("SELECT bids, value FROM bets WHERE active = 1 AND auction_id = '".$_REQUEST['aid']."' AND user_id = '".$_REQUEST['uid']."' LIMIT 1");
	$bet = mysql_fetch_array($rs);
	if(empty($bet)):
?>
<script>
$('#slider').slider({
	range: "min",
	value: 1,
	min: 1,
	max: 500,
	slide: function( event, ui ) {
		$( "#amount" ).html(ui.value);
	}
});

$('#betAddForm').submit(function(){
  	client.core.request.send('addBet', {'auction_id': $('#betAddForm #aid').attr('value'), 'bids': $('#betAddForm #amount').html(), 'value': $('#betAddForm #betType').attr('value'), 'user_id' : APE_user_id, 'passkey' : APE_passkey});
  	tb_remove();
	return false;
});
</script>

<p>Chọn số lượng XU bạn muốn đặt cược: </p>
<form id="betAddForm">
<input type="hidden" id="aid" value="<?php echo $_REQUEST['aid']?>">
<input type="hidden" id="betType" value="<?php echo $_REQUEST['type']?>">
<div><span id="amount">1</span> XU</div>
<div id="slider"></div>
<input class="submit" type="submit" value="Đặt cược">
</form>
<?php 
	else:
?>
	<p>Bạn đã đặt cược <b><?php echo $bet["bids"]?></b> vào bên <b><?php if($bet["value"] == 1) echo "Lẻ"; else echo "Chẵn";?></b> phiên đấu giá này</p>
<?php 
	endif;
?>