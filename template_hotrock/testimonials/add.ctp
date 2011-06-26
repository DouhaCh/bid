<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Testimonial</title>
<style type="text/css">
.comment {
	font-style: italic;
}
</style>
</head>

<body>
<?php echo $form->create('Testimonial', array('type'=>'file', 'action' => 'add/'.$auction_id));?>
  <?php echo $form->input('image1', array('type' => 'file', 'label'=>'Insert Image')); ?> 
  <p align="center">
    <textarea name="data[Testimonial][content]" cols="45" rows="5" id="content" ><?php if(isset($testi_content)) {echo $testi_content;} else echo "";?></textarea>
  </p>
  <p align="center"><span class="comment">After clicking the &quot;Submit&quot; button, your testimonial will be pushed on queue. </span></p>
  <p align="center"><span class="comment">It will not appear until being approved by 1bid admin. Thank you!</span></p>
  <p align="center"><input type="submit" name="submit" id="submit" value="Submit"> </p>
</form>

<p>&nbsp;</p>
</body>
</html>
