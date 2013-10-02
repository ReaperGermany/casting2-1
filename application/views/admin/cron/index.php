<?php $this->load->view('admin/header')?>
<div><h2 class="top_title">Subscribe</h2></div>
<div>
    <label>Sending</label>
	<select style="float:left; margin-top:5px" id='send' onchange='sending(this.value)' value=''>
	<option value='1' <?php if($status) echo 'selected'?>>Enable</option>
	<option value='0' <?php if(!$status) echo 'selected'?>>Disable</option>	
	</select>
</div>
<div class="clear"></div>
<div>
    <?php $this->load->view('admin/cron/table',array('items'=>$users, 'title'=>'Users subscribe', 'type'=>'subscribe'))?>
    <div class="clear"></div>
</div>
<?php $this->load->view('admin/footer')?>
