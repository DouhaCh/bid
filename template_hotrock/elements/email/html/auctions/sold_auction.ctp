<p>Xin chào</p>

<p>Sản phảm <?echo $data['Product']['title'];?> đã được bán! </p>

<p>Để xem chi tiết về sản phẩm hãy truy cập link dưới đây:</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $data['Auction']['id'];?>">
        <?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $data['Auction']['id'];?>
    </a>
</p>