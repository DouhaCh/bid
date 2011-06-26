<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
.quote {
	color: #808080;
	font-weight: bold;
	font-style: italic;
}
.user {
	color: #F00;
}
</style>
</head>

<body>
	<?php foreach ($testimonials as $testimonial): ?>
		<p><img src="<?php echo $testimonial['Testimonial']['img'];?>" width="100" height="100" style="padding:0;margin:2px 5px 0px 0;float:left" /></p>
		<p><?php echo $testimonial['Testimonial']['time']; ?></p>
		<p><span class="user"><?php echo $testimonial['User']['username']; ?></span> thắng phiên đấu giá <span class="user"><a href="/auction/view/<?php echo $testimonial['Auction']['id']; ?>"> "<?php echo $testimonial['Auction']['Product']['title']; ?>" </a></span>: </p>
		<p><img src="/webroot/img/format_quote.png" width="21" height="21" /><span class="aaa"><span class="quote"><span class="quote"><?php echo $testimonial['Testimonial']['content']; ?></span>
		<p>&nbsp;</p>
	<?php endforeach; ?>
</body>
</html>
