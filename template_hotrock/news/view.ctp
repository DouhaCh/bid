<?php
$html->addCrumb(__('Tin Tức', true), '/news');
$html->addCrumb($news['News']['title'], '/news/view/'.$news['News']['id']);
echo $this->element('crumb');
?>


<h1 class="page-title"><?php echo $news['News']['title']; ?></h1>

<div class="news-content">
<p><?php echo nl2br($news['News']['content']); ?></p>


<div class="meta">Date &amp; Time Posted <?php echo $time->niceShort($news['News']['created']); ?></div>

<p><?php echo $html->link(__('<< Back to news', true), array('action'=>'index')); ?></p>
</div>
