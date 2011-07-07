<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
  	<title>
		<?php echo $appConfigurations['name'];?>::
		<?php echo $title_for_layout; ?> 
	</title>
		<style type="text/css">
	button, img, input, #cp_stats { behavior: url(/iepngfix.htc) }
	</style> 
	

	<?php
		if(!empty($meta_description)) :
			echo $html->meta('description', $meta_description);
		endif;
		if(!empty($meta_keywords)) :
			echo $html->meta('keywords', $meta_keywords);
		endif;
		echo $html->css('jqModal');
		echo $html->css('thickbox');
		echo $html->css('/css/constant');
		echo $html->css('/css/template');
		echo $html->css('/css/tip-darkgray/tip-darkgray.css');
		echo $html->css('/css/tip-yellow/tip-yellow.css');
		echo $html->css('/css/tip-yellowsimple/tip-yellowsimple.css');
		
		echo $html->css('/js/jquery/jquery.jgrowl.css');
		echo $html->css('/js/jquery/jquery-ui-1.8.10.custom.css');
		//echo $javascript->link('iepngfix_tilebg.js');
		echo $javascript->link('jquery/jquery-1.4.4.min');
		//echo $javascript->link('ifixpng');
		echo $javascript->link('corner');
		echo $javascript->link('textfill');
		echo $javascript->link('jquery.poshytip.js');
		echo $javascript->link('jquery/jquery.thickbox.min');
		echo $javascript->link('jquery/jquery.imgareaselect.min');
		echo $javascript->link('jquery/jquery.numberformatter-1.1.0.js');
		echo $javascript->link('jquery/jquery.jgrowl.js');
		echo $javascript->link('jquery/jquery.cookie');
		echo $javascript->link('jquery/jquery-ui-1.8.10.custom.min.js');
		echo $javascript->link('dateformat.js');
		echo $javascript->link('jquery/jquery.cookie');
		echo $javascript->link('default');
		echo $javascript->link('jquery/jquery.validate.min.js');
		echo $scripts_for_layout;
	?>

		<script type="text/javascript">
	
   		$(document).ready(function(){
   		
			$('.tabbed').children().hide();
			$('#tab3').show();
			$('#pi_tab1').show();
			$('#ad_menu ul li a').click(function(){
														$('#ad_content > div').hide();
														var opentab = this.className;
														$('div#'+opentab).show();
														$('div#ad_menu li').removeClass("active");
														$(this).parent().addClass("active");
													});
			$('div#product_info_nav ul li a').click(function(){
														$('#product_info_content').children().hide();
														var opentab = this.className;
														$('div#'+opentab).show();
														$('div#product_info_nav li').removeClass("active");
														$(this).parent().addClass("active");
													});
		
		
			$('#feedback_link').click(function(){
				tb_show("Thông báo lỗi","/bugs/?KeepThis=true&TB_iframe=true&height=460&width=640",false);
			});
			
		});
		</script>
		
		<script type="text/javascript">
		</script>
</head>
<body>
<script type="text/javascript">
	
</script>
<!-- 
<div id="feedback" style="position:fixed;right:0; top:100px;cursor:pointer;z-index:10">
<img src="/images/feedback.gif" id="feedback_link"/>
</div>
 -->
<a id='idle' class='thickbox' href="/idle.htm?height=180&width=275" style='display:none'> idle </a>

<div id="container">
    <div id="header">
        <div class="main">
            <div class="top">
               
                <div class="login">
				<?php echo $this->element('status');?>
                </div>
            </div>
            <div class="row">
                <div id="topmenu">
                <?php echo $this->element('menu_top');?>
                </div>
               
                <div id="search">
                                       
                </div>
            </div>
        </div>
    </div>
    
    
    <div id="wrapper">
            <div class="main">
            
                <div class="banners">
                    <div class="bannergroup-s1">

                        <div class="banneritem-s1">
                            <a href="/">
                                <img alt="Banner" src="/images/banner_ongoing.gif">
                            </a>
                            <div class="clr">
                            </div>
                        </div>
                        <div class="banneritem-s1">
                            <a href="/auctions/closed">
                                <img alt="Banner" src="/images/banner_sold.gif">
                            </a>
                            <div class="clr">
                            </div>
                        </div>
                        <div class="banneritem-s1">
                            <a href="#">
                                <img alt="Banner" src="/images/banner_hot.gif">
                            </a>
                            <div class="clr">
                            </div>
                        </div>

                    </div>
                </div>
                
                	<?php
					if($session->check('Message.flash')){
						$session->flash();
					}
		
					if($session->check('Message.auth')){
						$session->flash('auth');
					}
					?>
                	<?php echo $content_for_layout; ?>

                    

                
            <div id="more_auctions">
            	
    			
    		</div>
            </div>



        </div>
         
        <?php echo $this->element('footer');?>
<?php echo $cakeDebug; ?>
</body>
</html>
