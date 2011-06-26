<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <script type="text/javascript" src="../../js/tiny_mce/tiny_mce.js"></script> -->
<?php  echo $javascript->link('tiny_mce/tiny_mce'); ?>
        
<script type="text/javascript">
tinyMCE.init({
	// General options
	mode : "specific_textareas",
    editor_selector : "mceEditor",
    theme : "advanced",
    plugins : "spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

    // Theme options
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    // Skin options
    skin : "o2k7",
    skin_variant : "silver"

});
</script>
<script language="JavaScript" type="text/javascript">
function clearText(thefield){
if (thefield.defaultValue==thefield.value)
thefield.value = ""
}
</script>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?> / Admin Panel [powered by QAZAuction]
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css('admin/style');
		echo $html->css('tabmenu2');
	?>
    <!--[if lt IE 7]>
        <?php echo $html->css('admin-ie'); ?>
    <![endif]-->
    <?php
        echo $javascript->link('jquery/jquery');
        echo $javascript->link('jquery/ui');
        echo $javascript->link('admin');
		echo $scripts_for_layout;
	?>

	<script type="text/javascript">
        sfHover = function() {
            var sfEls = document.getElementById("nav").getElementsByTagName("LI");
            for (var i=0; i<sfEls.length; i++) {
                sfEls[i].onmouseover=function() {
                    this.className+=" sfhover";
                }
                sfEls[i].onmouseout=function() {
                    this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
                }
            }
        }
        if (window.attachEvent) {
            window.attachEvent("onload", sfHover);
        }
    </script>

</head>
<body>
  <div id="wrapper">
  <?php echo $this->element('admin/menu_top');?>


    <div id="container">
		<div id="header" class="clearfix">
			<div class="container">
				<div class="logo">
                    <?php echo $html->link($html->image('admin/logo.png'), array('controller' => 'dashboards', 'action' => 'index', 'admin' => 'admin'), null, null , false);?>
				</div>
                                   <div id="search">
     <form name="search" action="https://qazware.com/index.php" method="post" target="_blank">
                        <input name="searchquery" class="searchtext" type="text" style="max-width:90px;" value="[Enter keyword]" onFocus="clearText(this)">
                       <input type="submit" name="Submit" value="Search" class="searchbuttonc">
                 <select name="searchtype" class="searchselect" style="visibility: hidden !important;">
                          <option value="all" selected style="visibility: hidden;">-- Support Site --</option>
                        </select>
                <input type="hidden" name="_m" value="core"><input type="hidden" name="_a" value="searchclient"></form>
                  </div>


<?php echo $this->element('admin/menu');?>
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




            <div id="content_container">

              <div id="left_side">
			<?php echo $content_for_layout; ?>
            </div>


       <div style="clear:both"></div>



<!-- footer Starts -->

	
	<!-- footer Ends -->

	</div>
    </div>
	<?php echo $cakeDebug; ?>
</body>
</html>