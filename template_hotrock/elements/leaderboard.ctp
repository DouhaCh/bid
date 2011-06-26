<div class="module" style="position:relative">
<h3>Bảng xếp hạng</h3>
<?php $fsize=14;?>
<img src="/images/ipad.jpg" style="position:absolute;right:0px;top:20px;z-index:10px;"/>
<ul style="list-style-type:decimal;padding-left:20px; margin-left:3px;">
<?php foreach ($leaders as $leader):?>
<li style="font-size:<?php $fsize=$fsize-0.5; echo $fsize;?>px;"><?php echo $leader['User']['username'];?></li>
<?php endforeach;?>
</ul>
</div>