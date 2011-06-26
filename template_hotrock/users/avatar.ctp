<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
*{
font: normal normal normal 13px/18px Arial, Helvetica, sans-serif;
}
.module{
	background-color:#f3f3f3;
	width:390px;
	height:100px;
	padding:10px;
	border:#d3d3d3 1px solid;
}
.module div.imgwrapper {
	border:#FFF 3px solid;
	float:left;
}
label {display:block}
.image_holder {
	width:410px;
	height:350px;
	border:#d3d3d3 1px solid;
	margin-top:10px;
}
.blah{
	width:280px;
	height:150px;
	text-align:center;
	float:left;
}
</style>
<script type="text/javascript">
	document.domain="1bid.vn";
</script>
</head>
<body>
<?php if ($action!='save'&&$action!='upload'):?>
<div class="module" style="font-type:arial">
<?php echo $form->create('Users', array('action' => 'avatar/upload', "enctype" => "multipart/form-data"));?>
    <?php
        echo $form->input('image',array("label"=>"Chọn hình ảnh để upload","type" => "file")); 
        echo $form->end('Upload');
    ?> 
</div>
<div class="image_holder">
</div>
<?php endif;?>

<?php if ($action=='upload'):?>
	<?php
	
	echo $html->css('/css/imgArea/imgareaselect-default');
	if(isset($javascript)):
	        echo $javascript->link('jquery/jquery-1.4.4.min');
	        echo $javascript->link('jquery/jquery.imgareaselect.min');
	endif;
	        echo $form->create('Users', array('action' => 'avatar/save',"enctype" => "multipart/form-data"));
	        echo $cropimage->createJavaScript($uploaded['imageWidth'],$uploaded['imageHeight'],80,80);      
	        echo $cropimage->createForm($uploaded["imagePath"], 80, 80);
	        $p='/img/profile_images/thumb/temp.jpg';
	        echo $form->end(); 
	?> 
<?php endif;?>

<?php if ($action=='save'):?>
	<script type="text/javascript">
		parent.eval('tb_remove();profice_pic_update("<?php echo $path;?>")');
	</script>
<?php endif;?>

</body>