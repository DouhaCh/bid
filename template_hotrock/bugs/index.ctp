<?php echo $javascript->link('jquery/jquery-1.4.4.min');?>
<style type="text/css">
<!--
*{
	
}

a{
	color:#FF3706;
	text-decoration:none;
}
a:hover{
	text-decoration:underline;
}
.bugs_wrapper {
	background-color: #efefef;
	width:600px;
	padding:10px;
	font: 12px/15px Arial,Helvetica,sans-serif;
}
.left{
	float:left;
}
.cb{
	clear:both;
}
.buglist{
	list-style-type:none;
	
	}
.buglist li {
	height:73px;
	background-color:#FFF;
	width:500px;
	border:#878787 1px solid;
	margin-bottom: 15px;
	
	
}
.vote {
	float:right;
	width:100px;
	height:73px;
	background:url(/images/bug_vote_bg.gif) repeat-x top left;
	border-left:#878787 1px solid;
	text-align:center;
}
.vote_number{
	font-size:36px;
	width:100px;
	color:#900;
	height:50px;
	padding:0;
	line-height:50px;
}
.vote_button{
	
}
.vote_button button{
	background:url('/images/vote.gif') no-repeat top left;
	width:43px;
	height:20px;
	border:none;
}
.vote_button button:hover{
	background:url('/images/vote_hover.gif') no-repeat top left;
}
.vote_button button:active{
	background:url('/images/vote_active.gif') no-repeat top left;
}
.content {
	padding:4px 5px 0px 10px;
	width:380px;
	float:left;
}
.bug_title{
	font-size:16px;
	display:block;
	height:38px;
	
}
.bug_info{
	font-size:11px;
	color:#666;
	padding-right:14px;
}
.date {font-size:9px;color:#666;text-align:right;width:100%;height:15px;}
.a_title{color:#333}
-->
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('.vote_now').click(function(){
		var auctionElement = 'auction_' + $(this).attr('alt');
        $.ajax({
    		url: $(this).attr('value'),
    		dataType: 'json',
    		success: function(data){
    					$('.vote_number_'+data['Bug']['id']).html(data['Bug']['vote']);
    					
    					}
			});
	});
});

function view(id)
{
	window.location.href = '/bugs/index/' + id;
}	
</script>

<div class="bugs_wrapper">
<span class="left">
<label for="bug_type"> Xem</label>
</span>
<select id="bug_type" name="data[bug_type]">
<option value="1" onclick="view('1')">Lỗi</option>
<option value="2" onclick="view('2')">Vấn đề sử dụng</option>
<option value="3" onclick="view('3')">Yêu cầu/Gợi ý</option>
<option value="4" onclick="view('4')">Góp ý chung</option>
<option selected="selected" value="0" onclick="view('')">Tất cả</option>
</select>
 | <a href="/bugs/add">Thêm mới</a>
<div class="cb"></div>
<?php if(!empty($bugs)): ?>
<ul class="buglist">
	<?php foreach ($bugs as $bug):?>
    <li id="bug_<?php echo $bug['Bug']['id'];?>">
    	<div class="content">
        <div class="date">Đăng lúc <?php echo $bug['Bug']['created'];?></div>
        <span class="bug_title">
        	<a class="a_title" href="/bugs/view/<?php echo $bug['Bug']['id'];?>"><?php echo $bug['Bug']['title'];?></a>
        </span>
        <span class="bug_info">
        <strong>Đăng bởi</strong>:<?php echo $bug['User']['username'];?>
        </span>
        <span class="bug_info">
        	<strong><?php echo $bug['BugType']['name'];?></strong>
        </span>
        <span class="bug_info">
        	<strong>Trả lời:</strong>0 lần
        </span>
        
        </div>
        <div class="vote">
        	<div class="vote_number vote_number_<?php echo $bug['Bug']['id'];?>">
            	 <?php echo $bug['Bug']['vote'];?>
            </div>
            <div class="vote_button">
            	<button class="vote_now" value="/bugs/vote/<?php echo $bug['Bug']['id'];?>"></button>
            </div>
        </div>
        <div class="cb"></div>
    </li>
   <?php endforeach;?>
</ul>
<?php endif;?>
</div>
