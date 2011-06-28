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
		echo $javascript->link('live');
		echo $javascript->link('app');
		
		echo $html->css('/css/reset');
		echo $html->css('/css/style');
		echo $html->css('/css/default');
		
		echo $scripts_for_layout;
	?>
</head>

<body>
	<div id="wraper">
		<?php echo $this->element('header');?>
        <div id="container">
        	<?php echo $this->element('feature');?>
            <div id="content">
            	<div class="top">
                    	<form action="" name="phanloai" method="post" id="phanloai">
                        	<label id="lbphanloai"><span>Các 100 phiên đấu giá đang diễn ra | </span>Phân loại sản phẩm  </label>
                        	<select>
                            	<option value="0">Tất cả</option>
                                <option value="0">Theo giá</option>
                                <option value="0">Theo ...</option>
                            </select>
                        </form>
                </div><!--end .top-->
                <div id="hotitem">
                	<div class="tophot">
                    	<a href="">"Nóng" nhất</a>
                    
                    </div><!--End .top-->
                    <div class="bottomhot">
                     <?php for($i=0; $i<9; $i++):?>
                     <div class="item">
                     	<?php echo $this->element('auction_1');?>
                     </div>
                     <?php endfor;?> 
                        
                        
                     <div class="clear"></div>
                    </div><!--end .bottom-->
                </div><!--end hotitem-->
   
                <div id="directbid">
                	<div class="topdirectbid">
                    	<a href="">ĐANG DIỄN RA</a>
                    </div><!--end .top-->
                    <div class="bodydirectbid">

                    	<table>
                            <tr class="title">
                                <td>SẢN PHẨM</td>
                                <td>&nbsp;</td>
                                <td>GIÁ HIỆN TẠI NGƯỜI ĐẶT GIÁ</td>
                                <td>THỜI GIAN</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php for($i=0; $i<5; $i++):?>
                     		<tr class="item">
                     			<td colspan="5">
                     		<?php echo $this->element('auction_2');?>
                     			</td>
                     		</tr>
                     		<?php endfor;?> 
                        </table>
                    </div><!--End .body-->
                </div><!--end #directbid-->
               
              <?php //echo $this->element('auction');?>
            </div><!--End #content-->
        	 <?php echo $this->element('right');?>
             <?php echo $this->element('linkout');?>
        </div><!--End #container-->
       
        <?php echo $this->element('footer');?>
    </div><!--end #wraper-->
</body>
</html> 
