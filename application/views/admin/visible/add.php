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
	
	<div class="atr">
    <label>Code Attrib</label>
	<select id="atr" onchange="load_attr(this.value)" value="">
		<option selected></option>
		<?php foreach($data_attr->getCodesList() as $attr):?>
            <?php if ($attr!='currency'):?> 
			<option value="<?php echo $attr?>"><?php echo $attr?></option>
			<?php endif;?>
        <?php endforeach;?>
		<option value="company_name">Company name</option>
	</select>
	</div>
	
	<div class="atr2"></div>
	<input type="button" class="btn" onclick="addUnvisible()" value="Add"/>
</div>
<script type="text/javascript" src="<?php echo base_url()?>skin/js/visible_list.js"></script>