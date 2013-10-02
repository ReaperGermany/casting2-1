<div class="add-visible">
	<div class="return"></div>
	<div class="user">
    <label>User</label>
	<select id="user" onchange="">
		<?php foreach($data_user as $user):?>
            <option value="<?php echo $user->getId()?>"><?php echo $user->getData('login')?></option>
        <?php endforeach;?>
	</select>
	</div>
	
	<!--div class="atr" id="atr_change">
    <label>Code Attrib</label>
	<select id='atr' onchange='load_attr(this.value)' value=''>
	<option selected></option>
	<option value='pk_id'>ID</option>
	<option value='company_name'>Company name</option>	
	</select>
	</div-->
	
	<div class="atr2"></div>
	<div class="nacn">
    <label>Nacenka</label>
	<input type="text" class="text"  value="0" id="nacn"/> <label>%</label>
	</div>
	<input type="button" class="btn" onclick="addNacenka()" value="Add"/>
	</div>

	
<script type="text/javascript" src="<?php echo base_url()?>skin/js/nacenka_list.js"></script>