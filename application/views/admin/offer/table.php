<div class="offers-table">
    <!--h2-->
        <?php
       // if ($type=='offer') echo 'Offers List';
       // elseif($type=='request') echo 'Requests List';
		
		if ($type=='request') {
            $field_list = array(
                "manufacturer" => array('label'=>"Manufacturer", 'filter'=>'text'),
                "model"=>array('label'=>"Model", 'filter'=>'text'),
                "color"=>array('label'=>"Color", 'filter'=>'text'),
                "spec"=>array('label'=>"Spec", 'filter'=>'text'),
                "company_name"=>array('label'=>"Company", 'filter'=>'text'),
				//"brand"=>array('label'=>"Brand", 'filter'=>'text'),
				//"location"=>array('label'=>"Location", 'filter'=>'text'),
                "date"=>array('label'=>"Date", 'filter'=>'range'),
                "updated_at"=>array('label'=>"Updated at", 'filter'=>'range'),
                "base_price"=>array('label'=>"Price", 'filter'=>'range'),
                "qty"=>array('label'=>"Qty", 'filter'=>'range'),
				//"qty_factor"=>array('label'=>"Qty factor", 'filter'=>'range'),
				"login"=>array('label'=>"User", 'filter'=>'range')
            );
		}
		else
		{
			$field_list = array(
                "manufacturer" => array('label'=>"Manufacturer", 'filter'=>'text'),
                "model"=>array('label'=>"Model", 'filter'=>'text'),
                //"color"=>array('label'=>"Color", 'filter'=>'text'),
                //"spec"=>array('label'=>"Spec", 'filter'=>'text'),
                //"company_name"=>array('label'=>"Company", 'filter'=>'text'),
				//"brand"=>array('label'=>"Brand", 'filter'=>'text'),
				//"location"=>array('label'=>"Location", 'filter'=>'text'),
                "date"=>array('label'=>"Date", 'filter'=>'range'),
                //"updated_at"=>array('label'=>"Updated at", 'filter'=>'range'),
                "base_price"=>array('label'=>"Offer Price", 'filter'=>'range'),
                //"offer_price"=>array('label'=>"Offer Price", 'filter'=>'range'),
                //"qty"=>array('label'=>"Qty", 'filter'=>'range'),
				//"qty_factor"=>array('label'=>"Qty factor", 'filter'=>'range')
            );
		}

        ?>


    <!--/h2-->
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
        <!--button onclick="filterOffers(this)"><span>Filter</span></button>
        <button onclick="resetFilter(this)"><span>Reset Filter</span></button-->
		<button onclick="mergeOffers(this)"><span>Merge</span></button>
        <button class="last" onclick="deleteOffers(this)"><span>Delete</span></button>
        
        <div class="clear"></div>
     </div>               
    <table cellpadding="0" cellspacing="0">
        <thead>
            <!--<tr>
                <th colspan="<?php echo count($field_list)+1?>" align="right">
                    
                </th>
            </tr>-->
            <tr id="head_table">
				<input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                <th style="width:15px"><input type="checkbox" onclick="toggleCheckbox(this)"/></th>
                <?php $new_dir = $orders[$type]['dir']=='asc'?'desc':'asc'?>
                <?php foreach($field_list as $field=>$data):?>
                    <?php if($orders[$type]['order'] == $field):?>
                        <th class="active <?php echo $orders[$type]['dir']?>" id="<?php echo $field ?>"
                            onclick="sortBy('<?php echo $type?>','<?php echo $field ?>','<?php echo $new_dir?>')">
                            <?php echo $data['label'] ?>
                            <?php if($orders[$type]['dir']=='asc'):?><img src="<?php echo base_url().'skin/images/up.png' ?>" alt=""/><?php endif;?>
                            <?php if($orders[$type]['dir']=='desc'):?><img src="<?php echo base_url().'skin/images/down.png' ?>" alt=""/><?php endif;?>
                        </th>
                    <?php else:?>
                        <th id="<?php echo $field ?>"
                            onclick="sortBy('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
                    <?php endif;?>
                <?php endforeach;?>
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
        <tbody>
            <?php $i=0;?>
            <?php foreach($items as $item):?>
                <tr class="<?php echo ++$i%2?'even':'odd'?> <?php echo $item->getData('profit_state')?>">
                    <td align="center" class="first" style="width: 1%;">
                        <input type="checkbox" class="item_id" value="<?php echo $item->getId()?>"/>
                    </td>
                    <td style="width: 15%;"><?php echo $item->getData('manufacturer')?></td>
                    <td style="width: 40%;" id="<?php echo "model-".$item->getId()?>"><?php echo $item->getData('model')?></td>
                    <!--td style="width: 8%;"><?php echo $item->getData('color')?></td>
                    <td style="width: 8%;"><?php echo $item->getData('spec')?></td-->
                    <!--td class="company_name"><?php echo $item->getData('email')?></td-->
					<!--td><?php echo $item->getData('brand')?></td>
					<td><?php echo $item->getData('location')?></td-->
                    <td style="width: 7%;"><?php echo date("m/d/Y", $item->getData('date'))?></td>
                    <!--td style="width: 7%;"><?php echo format_date($item->getData('updated_at'))?></td-->
                    <td class="offer_price" style="width: 7%;"><?php if ($item->getData('currency_code')=='USD') {echo '$'.format_price($item->getData('price'));}
					   elseif ($item->getData('currency_code')=='EURO') {echo '€'.format_price($item->getData('price'));}
					   else {echo format_price($item->getData('price'));}?></td>
                 <!--   <?php if($type=='offer'):?>
                	    <td><?php echo format_price($item->getData('offer_price'))?></td>
            	    <?php endif;?> -->
                    <!--td class="last"><?php echo $item->getData('qty')?></td-->
					<!--td class="last"><?php echo $item->getData('qty_factor')?></td-->
					<?php if($type=='request'):?>
                	    <td><?php echo $item->getData('login')?></td>
            	    <?php endif;?>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>