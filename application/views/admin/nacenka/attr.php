<div class="atr2">
<label>Attributes</label>
<select id="atr2" onchange="">
		<?php foreach($values as $val):?>
            <option value="<?php echo $val->getId()?>"><?php echo $val->getId()?></option>
        <?php endforeach;?>
	</select>
</div>