<div class="offers-table">
        <?php

		if ($type=='request') {
            $field_list = array(
                "manufacturer" => array('label'=>"Manufacturer", 'filter'=>'text'),
                "model"=>array('label'=>"Model", 'filter'=>'text'),
                "color"=>array('label'=>"Color", 'filter'=>'text'),
                "spec"=>array('label'=>"Spec", 'filter'=>'text'),
                "company_name"=>array('label'=>"Company", 'filter'=>'text'),
                "date"=>array('label'=>"Date", 'filter'=>'range'),
                "updated_at"=>array('label'=>"Updated at", 'filter'=>'range'),
                "base_price"=>array('label'=>"Price", 'filter'=>'range'),
                "qty"=>array('label'=>"Qty", 'filter'=>'range'),
				"login"=>array('label'=>"User", 'filter'=>'range')
            );
		}
		else
		{
			$field_list = array(
                "manufacturer" => array('label'=>"Manufacturer", 'filter'=>'text'),
                "model"=>array('label'=>"Model", 'filter'=>'text'),
                "date"=>array('label'=>"Date", 'filter'=>'range'),
                "base_price"=>array('label'=>"Offer Price", 'filter'=>'range'),
                "qty"=>array('label'=>"Qty", 'filter'=>'range'),
            );
		}

        ?>


    <div class="top_toolbar">
    	<?php $status = isset($filters[$type]['status'])?$filters[$type]['status']:""?>
        <label for="status">Show</label>
        <select name="status" id="status" onchange="filterOffers(this)" class="filter">
            <option value="" <?php if ($status == "") echo "selected"?>>All</option>
            <option value="<?php echo Offer::STATUS_ACTIVE?>" <?php if ($status == Offer::STATUS_ACTIVE) echo "selected"?>>Active</option>
            <option value="<?php echo Offer::STATUS_ARHIVE?>" <?php if ($status == Offer::STATUS_ARHIVE) echo "selected"?>>Arhive</option>
        </select>
        <?php if($status == Offer::STATUS_ARHIVE):?>
            <button onclick="toArhive(this, '<?php echo Offer::STATUS_ACTIVE?>')"><span>Unarhive</span></button>
        <?php else:?>
            <button onclick="toArhive(this, '<?php echo Offer::STATUS_ARHIVE?>')"><span>Arhive</span></button>
        <?php endif;?>
        <button onclick="changeStockStatus(this,1)"><span>Sold Out</span></button>
        <button onclick="changeStockStatus(this,0)"><span>In Stock</span></button>
        <button onclick="makeActive(this,'profitable')"><span>Make Active</span></button>
        <button onclick="makeActive(this,'')"><span>Make Inactive</span></button>
        <button onclick="exportOffers(this)"><span>Export</span></button>
        <button class="last" onclick="deleteOffers(this)"><span>Delete</span></button>
        
        <div class="clear"></div>
     </div>               
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr id="head_table">
				<input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
            </tr>
            <tr style="display:none;">
                <td></td>
                <?php foreach($field_list as $field=>$data):?>
                    <?php if($data['filter'] == 'text'):?>
                        <td id="<?php echo $field?>"><input type="text" name="<?php echo $field?>" class="autocomplete filter" id="<?php echo $field?>"
                           value="<?php echo isset($filters[$type][$field])?$filters[$type][$field]:""?>"/></td>
                    <?php elseif ($data['filter'] == 'range'):?>
                        <td id="<?php echo $field?>">
                            <input type="text" class="filter" name="<?php echo $field?>[from]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['from'])?$filters[$type][$field]['from']:""?>"/><p>-<p>
                            <input type="text" class="filter" name="<?php echo $field?>[to]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['to'])?$filters[$type][$field]['to']:""?>"/>
                        </td>
                    <?php endif;?>
                <?php endforeach;?>
            </tr>
        </thead>
    </table>
	<ul>
	<?php $man = ""; $i = 0 ;?>
            <?php foreach($items as $item):?>
				<?php if ($man != $item->getData('manufacturer') && $i==1) :?>
					</ul>
                </li>
				<?php ?>
				<?php endif; ?>
				<?php if ($man != $item->getData('manufacturer')) :?>
				<li>
				<?php echo $item->getData('manufacturer')?> 
				<?php $man = $item->getData('manufacturer'); $i=1;?>
                   
				   <ul class="child">
				<?php endif; ?>
				   <li>-  
				   <?php echo $item->getData('model')?>
                   <?php 
                       if ($item->getData('currency_code')=='USD') {echo '$'.format_price($item->getData('price'));}
					   elseif ($item->getData('currency_code')=='EURO') {echo '€'.format_price($item->getData('price'));}
					   else {echo format_price($item->getData('price'));}
					?>
					</li>
            <?php endforeach;?>
	</ul>
</div>