<script>
	$(document).ready(function() {
		$('#transactionTable tr:even').addClass('dark');
	});
</script>

<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb(__('My Bids', true), '/bids');
echo $this->element('crumb_user');
?>

<h1><?php __('My Bids');?></h1>

<?php if(!empty($bids)): ?>
	<?php echo $this->element('pagination'); ?>
<div class='bidsTable'>
	<table class='tab01' align="center" border="0" cellpadding="0" cellspacing="0" id="transactionTable">
       <thead>
          <tr>
              <th align='center' id="date" colspan="2"><?php echo __('Date');?></th>
              <th align='center' id="flag"><a href="https://www.paypal.com/vn/cgi-bin/webscr?cmd=_login-done&amp;login_access=1290584939#flagheader" tabindex="0" title="When an icon appears next to one of your transactions, it means there is more information available or a note attached. Move your cursor over the icon to learn more about the transaction."><img src="https://www.paypalobjects.com/WEBSCR-640-20101108-1/en_US/i/icon/icon_flag_gray_16x16.gif" border="0" alt="flag column" /></a></th>
              <th align='center' id="type"><?php echo __('Type');?></th>
              <th align='center' id="productName"><?php echo __('Description');?></th>
              <th align='center' id="plus"><?php echo __('Plus');?></th>
              <th align='center' id="minus" class="last alignright" ><?php echo __('Minus');?></th>
          </tr>
       </thead>
       <?php foreach($bids as $bid):?>
       	  <tr>
       	  	  <td align='center'><?php echo date('M d, Y',strtotime($bid['0']['date']));?></td>
       	  	  <td colspan='2' align='center'> &nbsp; </td>
       	  	  <td align='center'><?php echo $bid['Bid']['type'];?></td>
       	  	  <td align='center'>
       	  	  <?php if(!empty($bid['products']['title'])):?>
       	  	  	<a href="/auctions/view/<?php echo $bid['Bid']['auction_id'];?>"><?php echo $bid['products']['title'];?></a>
       	  	  <?php else:?>
       	  	  	<?php echo $bid['Bid']['description'];?>
       	  	  <?php endif;?>
       	  	  </td>
       	  	  <td align='center'><?php echo $bid['0']['credit'];?></td>
       	  	  <td align='center'><?php echo $bid['0']['debit'];?></td>
       	  </tr>
       <?php endforeach;?>
    </table>
</div>

	<?php echo $this->element('pagination'); ?>

<?php else:?>
	<p><?php __('You have no account transations at the moment.');?></p>
<?php endif;?>
