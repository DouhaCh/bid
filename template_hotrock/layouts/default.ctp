<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BID--NAPNOD</title>
<link href="/css/style_login.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
	<link rel="stylesheet" type="text/css" href="style/hack_ie_login.css"></link>
<![endif]-->
<?php 
	echo $javascript->link('jquery/jquery-1.4.4.min');
	echo $javascript->link('live');
	echo $javascript->link('app');
	echo $javascript->link('jquery/jquery.jgrowl.js');
	echo $html->css('/js/jquery/jquery.jgrowl');
	echo $scripts_for_layout;
?>
</head>

<body>
	<div id="wraper">
    	<div id="header">
        	<?php echo $this->element("header");?>
        </div><!--End #header-->
        
        <div id="content">
        	<div id="top-content">
            	<div id="feature">
                	<a href="/"><img src="/images/feature.png" /></a>
                </div><!--End feature-->
                <div id="guide">
                	<p class="title_big">Tạo tài khoản & nạp E-Gold</p>
                    <p class="title_small">Bạn chỉ mất 1 phút</p>
                    <p class="title_big">Tìm kiếm sản phẩm bạn thích</p>
                    <p class="title_small">Nhanh hơn với phân loại sản phẩm</p>
                    <p class="title_big">Đặt giá và Chiến thắng</p>
                    <p class="title_small">Sản phẩm giá rẻ hơn thị trường</p>
                    <a href=""><!-- button xem huong dan--></a>
                </div><!--end guide-->
                
          
            </div><!--end top-content-->
            
            <div id="body">
            	<?php echo $content_for_layout;?>
            </div><!--End body-->
            <div class="clear"></div>
        </div><!--End #content-->
        
        <div id="footer">
 			<?php echo $this->element("footer");?>       
        </div><!--End #footer-->
    </div><!--end #wraper-->

</body>
</html>