<?php $news = $this->requestAction('/news/getlatest/5');?>

<div class="random-testimonial module">
	<div class="module_box">
		<div class="module_header">Tin tức</div>
		<div class="module_content">
			
			<ul style="text-align:left">
		<?php foreach($news as $newsItem):?>
		<li>
			<?php echo $html->link($newsItem['News']['title'], array('controller'=>'news', 'action' => 'view', $newsItem['News']['id']));?>
		<!--	<div class="meta"><?php echo $time->niceShort($newsItem['News']['created']);?></div>-->
		</li>
		<?php endforeach;?>
	</ul>
		</div>
		<div class="module_footer_2"><a href="/thong-tin/">Xem tất cả</a></div>
	</div>
	
    
</div>