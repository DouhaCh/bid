<?php $news = $this->requestAction('/news/getlatest/2');?>
<?php if(!empty($news)):?>
<h3 class="orange2 bold font-14" style="margin:0 0 2px;"><label>Latest news</label></h3>
<?php foreach($news as $newsItem):?><div class="latest-news">    
    <div class="date"><label><?php echo $time->niceShort($newsItem['News']['created']);?></label></div>
    <div class="title"><?php echo $html->link($newsItem['News']['title'], array('controller'=>'news', 'action' => 'view', $newsItem['News']['id']));?></div>
    <p class="brief"><label><?php echo $text->truncate($newsItem['News']['brief'], 32); ?></label></p>
</div><?php endforeach;?>
<p class="more"><a href="/news">View All News >></a></p>
<?php else:?>
	<p>No news articles.</p>
<?php endif;?>