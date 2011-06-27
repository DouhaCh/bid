<?php $head_ban = array('home'); ?>
<?php $left_col = array('home','users'); ?>
<?php $cat_panel = array("Auctions.view"); ?>
<?php $no_left = array('Pages'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $appConfigurations['name'];?>::
		<?php echo 'Đấu giá xu!' ?> 
	</title>
	<style type="text/css">
		img, a, input, button { behavior: url(/iepngfix.htc);}
	</style> 
	
	<?php
		if(!empty($meta_description)) :
			echo $html->meta('description', $meta_description);
		endif;
		if(!empty($meta_keywords)) :
			echo $html->meta('keywords', $meta_keywords);
		endif;
		
		echo $javascript->link('jquery/jquery-1.4.4.min');
		echo $javascript->link('jquery/s3Slider');
		echo $javascript->link('jquery/superfish');
		
		echo $html->css('/css/reset.css');
		echo $html->css('/css/960');
		echo $html->css('/css/default');
		
		echo $scripts_for_layout;
	?>
</head>

<body>
    <div id="container" class="container_12" style="background: #FBFBFB; width: 960px; height: 1000px;">
		<div id="header" class="grid_12">
			<div id="top-image" class="grid_12">
				<a href="#"><img src="../img/slide3.png" width="940px" height="200px"/></a>
			</div>
			<div id="top-banners" class="grid_12">
				<div id="steps-to-play" class="grid_2 alpha">
					<a href="#"><img src="../img/slide3.png" width="140px" height="150px"/></a>
				</div>
				<div id="banner" class="grid_6">
					<a href="#"><img src="../img/slide3.png" width="460px" height="150px"/></a>
				</div>
				<div id="charge" class="grid_4 omega">
					<a href="#"><img src="../img/slide3.png" width="300px" height="150px"/></a>
				</div>
			</div>
			<div id="nav" class="grid_12">
				<ul class="sf-menu grid_12">
					<li class="grid_2"><a href="#">Menu 1</a>
					<li class="grid_2"><a href="#">Menu 2</a>
						<ul>
							<li class="grid_2 alpha omega"><a href="#">Menu 2 1</a>
							</li>
							<li class="grid_2 alpha omega"><a href="#">Menu 2 2</a>
							</li>
						</ul></li>
					<li class="grid_2"><a href="#">Menu 3</a>
						<ul>
							<li class="grid_2 alpha omega"><a href="#">Menu 3 1</a>
							</li>
							<li class="grid_2 alpha omega"><a href="#">Menu 3 2</a>
							</li>
							<li class="grid_2 alpha omega"><a href="#">Menu 3 3</a>
							</li>
						</ul></li>
					<li class="grid_2"><a href="#">Menu 4</a>
						<ul>
							<li class="grid_2 alpha omega"><a href="#">Menu 4 1</a>
							</li>
							<li class="grid_2 alpha omega"><a href="#">Menu 4 2</a>
							</li>
						</ul></li>
				</ul>
			</div>
		</div>
		<div id="content" class="grid_9">
			<div class="content_box">
				<div class="title">Nong nhat</div>
				<div class="content">Content</div>
			</div>
			
			<div class="content_box">
				<div class="title">Dang dien ra</div>
				<div class="content">Content</div>
			</div>
		</div>
		<div id="sidebar" class="grid_3 omega">
			<div id="how-to-play">
				<div>
					<button>Hướng dẫn tham gia >> </button>
				</div>
				<div class="clear"></div>
				<div>(*)Danh 1 phut de tim hieu cach dau gia</div>
			</div>
			<div class="side_box">
				<div class="title">Sap dien ra</div>
				<div class="content">Content</div>
			</div>
			<div class="side_box">
				<div class="title">Nguoi chien thang</div>
				<div class="content">Content</div>
			</div>
			<div class="side_box">
				<div class="title">Top dai gia</div>
				<div class="content">Content</div>
			</div>
		</div>
		<div id="footer" class="grid_12">Footer</div>
	</div>
</body>
</html> 
