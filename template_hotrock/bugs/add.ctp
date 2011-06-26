<style>
*{
	font: 12px/15px Arial,Helvetica,sans-serif;
}

a{
	color:#FF3706;
	text-decoration:none;
}
a:hover{
	text-decoration:underline;
}
label {
padding:5px;
}
.bugs_wrapper {
	background-color: #efefef;
	width:600px;
	padding:10px;
}
.rInput{
	text-align:right;
	margin-right:100px	
}
input#BugTitle,#BugUrl{
	width:400px;
}
textarea {
	width:350px;
}
.example {
	font-size:11px;
	color:#555;
}
</style>


<p>Cảm ơn Ninja đã tìm thẫy lỗi! 
  </p>
  <p>Trước khi bạn bắt đầu, xin vui lòng kiểm tra lại danh sách cách <a href="#" target="_blank">tính năng đang phát triển</a> và <a href="#" target="_blank">các lỗi đã được tìm thấy</a> để bạn đỡ mất thời gian thông báo. Ban cũng có thể bình luận và vote cho các tính năng/lỗi trong đó. </p>


<?php echo $form->create('Bug', array('action' => 'add'));?>
	<fieldset>
					<?php echo $form->input('title', array('label' => __('Tựa đề', true), 'div' => 'rInput'));?>
					<p><strong>1) Phân loại</strong></p>
					<div class="rRadio">
						
						<?php echo $form->hidden('bug_type_id', array('value' => ''));?>
					
						<?php foreach($bug_types as $type):?>
							<input type="radio" <?php if(!empty($this->data['Bug']['bug_type_id']) && $this->data['Bug']['bug_type_id'] == $type['BugType']['id']) echo 'checked="checked"';?> 
								title="<?php echo $type['BugType']['name'];?>" id="bug_type_<?php echo $type['BugType']['id'];?>" name="data[Bug][bug_type_id]" value="<?php echo $type['BugType']['id'];?>"/>
							<label class="radio" for="BugType_<?php echo $type['BugType']['id'];?>"><?php echo $type['BugType']['name'];?></label>
							<?php endforeach;?>
						<?php echo $form->error('bug_type_id');?>
					</div>
					
				
					<p><strong>2) Các bước để tìm lỗi:</strong></p>
					<?php
					echo $form->input('url', array('label' => __('URL', true), 'div' => 'rInput'));
					echo $form->input('location', array('value' => '', 'type' => 'textarea', 'label' =>  __('Vị trí cụ thể *',true), 'div' => 'rInput'));
					?>
					<p><strong>3) Mức độ nghiêm trọng/quan trọng</strong></p>
					
					<div class="rRadio">
						
					<?php echo $form->hidden('severity', array('value' => ''));?>
					
							<input type="radio" id="severity_1" name="data[Bug][severity]" value="1"/>
							<label class="radio" for="severity_1">1 - Rất Nhẹ </label>
							
							<input type="radio" id="severity_2" name="data[Bug][severity]" value="2"/>
							<label class="radio" for="severity_2">2 - Nhẹ </label>
							
							<input type="radio" id="severity_3" name="data[Bug][severity]" value="3"/>
							<label class="radio" for="severity_3">3 - Vừa phải </label>
							
							<input type="radio" id="severity_4" name="data[Bug][severity]" value="4"/>
							<label class="radio" for="severity_4">4 - Nặng </label>
							
							<input type="radio" id="severity_5" name="data[Bug][severity]" value="5"/>
							<label class="radio" for="severity_5">5 - Nặng </label>
							
						<?php echo $form->error('severity');?>
					</div>
					
					<p><strong>4) Miêu tả cụ thể:</strong></p>
					<p>(1 - Bạn đang làm gì khi gặp phải lỗi/vấn đề; 2 - Bạn mong đợi điều gì xảy ra; 3 - Chuyện gì đã xảy ra ngoài ý muốn )</p>
					<?php
					echo $form->input('description', array('value' => '', 'type' => 'textarea', 'label' =>  __('Miêu tả cụ thể lối/vấn đề*:',true), 'div' => 'rInput'));
					?>
					<p class="example">
					Ví dụ về báo cáo kém chất lượng:<br/>
					"Tôi không thay đổi được tên truy cập" - Ninja A<br/>
					"Bid tự động của tôi không hoạt động" - Ninja B<br/>
					</p>
					<p class="example">
					Ví dụ về báo cáo xịn:<br/>
"Tôi định thay đổi tên truy cập. Tôi vào "Tài khoản cá nhân" và click vào "Thông tin tài khoản" rồi đặt tên mới và lưu thay đổi. Tuy nhiên sau khi tôi quay lại trang đấu giá thì tên truy cập của tôi vẫn như cũ. Tôi thử đăng xuất và đăng nhập trở lại nhưng vẫn không được."<br/> - Ninja "xịn" A <br/>
"Tôi đang đấu giá chiếc iPad vào lúc 2:30 chiều hôm nay (24/12). Tôi đặt Bid Tự Động từ 2000VND tới 5000VND. Tuy nhiên khi tôi click vào "Bổ sung" thì có thông báo lỗi hiện ra: "Error 2206: Cậu vào tù, đồ hư hỏng" và tôi không thể đặt được bid tự động. Tôi thử rất lại sau 5 phút và lỗi 2206 lại hiện ra"<br/> - Ninja "xịn" B
</p>
			</fieldset>
			
			<?php echo $form->end('Register');?>

		