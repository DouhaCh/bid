<script>
	$(document).ready(function() {
		$('.datatable tbody tr:odd').addClass('dark');
		
		$('#activities .dataButton').click(function() {
			$('.datatable .table_wrapper #transactionTable').hide();
			$('.datatable .table_wrapper .'+$(this).attr('alt')).show();
			$('#activities ul li').removeClass('selected');
			$(this).parent().addClass('selected');
		});
	});
</script>

<?php
$bidBalance = $this->requestAction('/bids/balance/'.$user['User']['id']);
$html->addCrumb(__('Dash Board',true), '/users');
echo $this->element('crumb_user');
?>         					
	<div class="account_box">	
                  <div id="account_headline">
                    <h2 class="left">Chào mừng, <?php echo $userName; ?></h2>
                    <div class="right smalltext" >Lần cuối đăng nhập là vào ngày <strong> <?php echo $lastLoginDate;?> </strong>lúc <strong><?php echo $lastLoginTime;?></strong> với IP: <?php echo $ip;?></div>
                    <div class="clearBoth"></div>
                    <!--<ul class="smalltext metadata">
                      <li>Loại Tài khoản: Bình thường  </li>
                      <li class="last"> Hiện trạng: <a><?php echo $userStatus; ?></a></li>
                    </ul> -->
                  </div>
                  <div id="messageBox"></div>
                  <div id="account_main">
                  	 <div class="user_panel">
						<?php if($session->check('Auth.User')):?>
						<?php echo $this->element('menu_user');?>
						<?php endif; ?>
					</div>
                    <div>
                      <div>
                      	<div id="profile_pic">
                           	<div class="profile_pic_wrapper" title="Click để thay đổi avatar" id="ppic">
                           		<img src="<?php echo $userImg;?>"/>
                           	</div>
                        </div>
                        <div id="basic_info">
                        	<h6><?php echo $uname; ?></h6>
                        	
                        	<h5>Việc dang dở:</h5>
<ul class="to-do">
<?php if(!empty($userAddress)) : ?>
	<?php foreach($userAddress as $name => $address) : ?>
		<?php if(empty($address)) : ?>
			<?php $count = 1; ?>
			<li><a href="/addresses/add/<?php echo $name; ?>">Add a <?php echo $name; ?> Address</a></li>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if($bidBalance == 0) : ?>
	<?php $count = 1; ?>
	<li><a href="/packages">Nap XU</a></li>
<?php endif; ?>

<?php if($unpaidAuctions > 0) : ?>
	<?php $count = 1; ?>
 	<li><a href="/auctions/won">Nhận giải thưởng</a></li>
<?php endif; ?>

<?php if($untestiAuctions > 0) : ?>
	<?php $count = 1; ?>
 	<li><a href="/auctions/won">Viết <?php echo $untestiAuctions;?> cảm nghĩ chiến thắng</a></li>
<?php endif; ?>

<?php if(empty($count)) : ?>
	<li>Không có</li>
<?php endif; ?>
</ul>
                        </div>
                        <div class="clearBoth"></div>
                        <div id="balance">
                          <ul class="box">
                            <li><strong>Tài khoản xu: </strong><span class="balance"><strong><?php echo $bidBalance; ?> xu</strong> </span></li>
                          </ul>
                        </div>

                        <div id="todo">
                        


<p>Hiện tại bạn đang có <strong><?php echo $unpaidAuctions; ?></strong> sản phẩm chiến thắng. <a href="/auctions/won">Xem tất cả các sản phẩm đã chiến thắng</a></p>
<strong>Mã số rút thăm của bạn:</strong><br/><p style="padding:0 10px;"><?php foreach ($lotte as $number) echo($number['Lottery']['id'].', ');?></p>
						</div>
                        <div id="activities">
                          <ul class="left">
                            <!--  <li class="selected"><a href="javascript:void(0)" class="dataButton" alt="tab01">Giao dịch gần đây nhất</a></li>-->
                            <li class="selected"> <a href="javascript:void(0)" class="dataButton" alt="tab02">Tài khoản cộng</button></li>
                            <li class="last"> <a href="javascript:void(0)" class="dataButton" alt="tab03">Tài khoản trừ</a></li>
                          </ul>
                          <span class="right"><a href="/bids">Xem tất cả các giao dịch</a></span>
                        </div>
                      
                          <div class="datatable">
                            <div class="table_title">
                              <h3>Giao dịch gần đây<span>- 7 ngày gần nhất (<?php echo date('M d, Y', strtotime('-7 days')).'-'.date('M d, Y', strtotime('now'));?>)</span></h3>
                            </div>
                            <div class="table_wrapper">
                              
                             <!--   <table class='tab01' align="center" border="0" cellpadding="0" cellspacing="0" id="transactionTable">
                                <thead>
                                  <tr>
                                    <th id="selectColumn"> <input type="checkbox" id="recent_all_selected" name="recent_all_selected" value="0" /></th>
                                    <th id="date" colspan="2"><?php echo __('Date');?></th>
                                    <th id="flag"><a href="https://www.paypal.com/vn/cgi-bin/webscr?cmd=_login-done&amp;login_access=1290584939#flagheader" tabindex="0" title="When an icon appears next to one of your transactions, it means there is more information available or a note attached. Move your cursor over the icon to learn more about the transaction."><img src="https://www.paypalobjects.com/WEBSCR-640-20101108-1/en_US/i/icon/icon_flag_gray_16x16.gif" border="0" alt="flag column" /></a></th>
                                    <th id="type"><?php echo __('Type');?></th>
                                    <th id="productName"><?php echo __('Product name');?></th>
                                    <th id="actions"><?php echo __('Order status/Actions');?></th>
                                    <th id="gross" class="last alignright" ><?php echo __('Gross');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($bids as $bid):?>
                                <tr>
                                	 <td headers="selectColumn"> <input type="checkbox"/> </td>
                                	 <td headers="date"></td>
                                	 <td headers="date" nowrap="nowrap"><?php echo date('M d, Y',strtotime($bid[0]['date']));?></td>
                                     <td headers="flag"></td>
                                     <td headers="type"><?php echo $bid['Bid']['type'];?></td>
                                     <td headers="productName"><a href="/auctions/view/<?php echo $bid['Bid']['auction_id'];?>"><?php echo $bid['products']['title'];?></a></td>
                                     <td headers="actions" nowrap="nowrap"></td>
                                     <td headers="gross" class="alignright" nowrap="nowrap"><?php echo $bid[0]['gross'];?> XU</td>
                                  
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                                </table>-->
                                
                                
                               <table style="" class='tab02' align="center" border="0" cellpadding="0" cellspacing="0" id="transactionTable">
                                <thead>
                                  <tr>
                                    <th id="selectColumn">
                                     
                                      <input type="checkbox" id="recent_all_selected" name="recent_all_selected" value="0" /></th>
                                    <th id="date" colspan="2"><?php echo __('Date');?></th>
                                    <th id="flag"><a href="https://www.paypal.com/vn/cgi-bin/webscr?cmd=_login-done&amp;login_access=1290584939#flagheader" tabindex="0" title="When an icon appears next to one of your transactions, it means there is more information available or a note attached. Move your cursor over the icon to learn more about the transaction."><img src="https://www.paypalobjects.com/WEBSCR-640-20101108-1/en_US/i/icon/icon_flag_gray_16x16.gif" border="0" alt="flag column" /></a>
                                    </th>
                                    <th id="type"><?php echo __('Type');?></th>
                                    <th id="productName"><?php echo __('Description');?></th>
                                    <th id="actions"><?php echo __('Order status/Actions');?></th>
                                    <th id="gross" class="last alignright" ><?php echo __('Gross');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($received_bids as $bid):?>
                                <tr>
                                	 <td headers="selectColumn"> <input type="checkbox"/> </td>
                                	 <td headers="date"></td>
                                	 <td headers="date" nowrap="nowrap"><?php echo date('M d, Y',strtotime($bid[0]['date']));?></td>
                                     <td headers="flag"></td>
                                     <td headers="type"><?php echo $bid['Bid']['type'];?></td>
                                     <td headers="productName"><?php echo $bid['Bid']['description'];?></td>
                                     <td headers="actions" nowrap="nowrap"></td>
                                     <td headers="gross" class="alignright" nowrap="nowrap"><?php echo $bid[0]['gross'];?> XU</td>
                                  
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                                </table>
                                
                                <table class='tab03' style="display:none;" class='tab02' align="center" border="0" cellpadding="0" cellspacing="0" id="transactionTable">
                                <thead>
                                  <tr>
                                    <th id="selectColumn">
                                     
                                      <input type="checkbox" id="recent_all_selected" name="recent_all_selected" value="0" /></th>
                                    <th id="date" colspan="2"><?php echo __('Date');?></th>
                                    <th id="flag"><a href="https://www.paypal.com/vn/cgi-bin/webscr?cmd=_login-done&amp;login_access=1290584939#flagheader" tabindex="0" title="When an icon appears next to one of your transactions, it means there is more information available or a note attached. Move your cursor over the icon to learn more about the transaction."><img src="https://www.paypalobjects.com/WEBSCR-640-20101108-1/en_US/i/icon/icon_flag_gray_16x16.gif" border="0" alt="flag column" /></a>
                                    </th>
                                    <th id="type"><?php echo __('Type');?></th>
                                    <th id="productName"><?php echo __('Product name');?></th>
                                    <th id="actions"><?php echo __('Order status/Actions');?></th>
                                    <th id="gross" class="last alignright" ><?php echo __('Gross');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($spent_bids as $bid):?>
                                <tr>
                                	 <td headers="selectColumn"> <input type="checkbox"/> </td>
                                	 <td headers="date"></td>
                                	 <td headers="date" nowrap="nowrap"><?php echo date('M d, Y',strtotime($bid[0]['date']));?></td>
                                     <td headers="flag"></td>
                                     <td headers="type"><?php echo $bid['Bid']['type'];?></td>
                                     <td headers="productName"><a href="/auctions/view/<?php echo $bid['Bid']['auction_id'];?>"><?php echo $bid['products']['title'];?></a></td>
                                     <td headers="actions" nowrap="nowrap"></td>
                                     <td headers="gross" class="alignright" nowrap="nowrap"><?php echo $bid[0]['gross'];?> XU</td>
                                  
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                              </table>
                            
                            </div>
                          </div>
           				  
           				  
           				  <div class="datatable">
                            <div class="table_title">
                              <h3>Thống kê sản phẩm thắng gần đây:</h3>
                            </div>
                            <div class="table_wrapper">
                            <table>
                            	<thead>
                                  <tr>
                                   	<th>Nội dung</th>
                                   	<th>Số lượng/Giới hạn</th>
                                  </tr>
                                </thead>
                                <tbody>
                                	<tr>
                                		<td>Số sản phẩm thắng hôm nay: </td>
                                		<td><?php echo $won_count['day_count'];?>/2</td>
                                	</tr>
                                	<tr>
                                		<td>Số sản phẩm thắng hôm nay(không phải gói XU): </td>
                                		<td><?php echo $won_count['day_count_nxu'];?>/1</td>
                                	</tr>
                                	<tr>
                                		<td>Số sản phẩm thắng tuần này: </td>
                                		<td><?php echo $won_count['week_count'];?>/5</td>
                                	</tr>
                                	<tr>
                                		<td>Số sản phẩm thắng tuần này(không phải gói XU): </td>
                                		<td><?php echo $won_count['week_count_nxu'];?>/3</td>
                                	</tr>
                                	<tr>
                                		<td>Số sản phẩm thắng tháng này: </td>
                                		<td><?php echo $won_count['month_count'];?>/10</td>
                                	</tr>
                                	<tr>
                                		<td>Số sản phẩm thắng tháng này(không phải gói XU): </td>
                                		<td><?php echo $won_count['month_count_nxu'];?>/8</td>
                                	</tr>
                                </tbody>
                            </table>
                            </div>
                           </div>
                      </div>
                    </div>
                  </div>
                  
           </div>
          
           
</div>





