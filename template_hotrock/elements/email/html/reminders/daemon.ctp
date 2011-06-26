<p>Chào <?php echo $data['User']['first_name'];?> <?php echo $data['User']['last_name'];?>,</p>

<p>Mình gửi thư này nhắc bạn rằng phiên đấu giá "<?php echo $data['Auction']['title'];?>"
đã bắt đầu. Bạn có thể tìm hiểu chi tiết hơn:</p>

<p><?php echo Configure::read('App.url').'/auctions/view/'.$data['Auction']['id'];?></p>

<p>Thank You<br/>
<?php echo Configure::read('App.name');?></p>