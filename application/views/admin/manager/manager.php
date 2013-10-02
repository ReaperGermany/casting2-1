<?php $this->load->view('admin/header')?>
<div>
    <ul class="right_top">
    	<li><a href="<?php echo site_url("admin/nacenka"); ?>">Nacenka users</a></li>
    </ul>
	<h2 class="top_title">User Controls</h2>

</div>
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/manager.js"></script>
<?php $this->load->view('admin/manager/table',array('items'=>$users, 'title'=>'Users', 'type'=>'users'))?>
</br>

<div class="info_block">
	<div id="error" style="display: none;"></div>
	<div class="info"></div>
</div>

<div id="pass_change" class="pass" style="display: none;">
	<form id="pass_form" onsubmit="pass(); return false;">
		<input id="pk_id2" name="pk_id2" type="hidden"  value=""/>
        <label for="pass2" id="pass_l">Password:</label>
        <input id="pass2" class="text" name="password2" type="password" value=""/>
        <label for="pass_comfirm2" id="pass_comfirm_l">Password confirm:</label>
        <input id="pass_comfirm2" class="text" name="password_comfirm2" type="password" value=""/>
		<input id="but1" name="but1" class="btn" type="button" onclick="cancel()" value="Cancel" style="position: relative;top: 10;"/>
		<input id="but2" name="but2" class="btn" type="submit" value="Change Password" style="float: right;top: -12px; position: relative;"/>
	</form>
</div>
<?php $this->load->view('admin/footer')?>
