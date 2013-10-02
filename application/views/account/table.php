<div class="offers-table" style="width: 100%;">
    <h2>
        <?php
            if ($type=='offer') echo 'Offers List';
            elseif($type=='request') echo 'Requests List';

            $field_list = array(
                "manufacturer" => array('label'=>"Manufacturer", 'filter'=>'text'),
                "model"=>array('label'=>"Model", 'filter'=>'text'),
                //"color"=>array('label'=>"Color", 'filter'=>'text'),
                //"spec"=>array('label'=>"Spec", 'filter'=>'text'),
                //"company_name"=>array('label'=>"Company", 'filter'=>'text'),
				//"brand"=>array('label'=>"Brand", 'filter'=>'text'),
				//"location"=>array('label'=>"Location", 'filter'=>'text'),
                //"date"=>array('label'=>"Date", 'filter'=>'range'),
                //"updated_at"=>array('label'=>"Updated at", 'filter'=>'range'),
                "base_price"=>array('label'=>"Price", 'filter'=>'range'),
                //"offer_price"=>array('label'=>"Offer Price", 'filter'=>'range'),
                //"qty"=>array('label'=>"Qty", 'filter'=>'range'),
				//"user_id"=>array('label'=>"Subscribe", 'filter'=>''),
				//"cart"=>array('label'=>"", 'filter'=>''),
            );
			if(isset($filters['offer_front']['model']) && $filters['offer_front']['model']!="") {echo 'Search results for "'.$filters['offer_front']['model'].'"';}
        ?>


    </h2>
    <table>
        <thead>
            <tr id="head_table"  style="display: none;">
                <th colspan="<?php echo count($field_list)+2?>" align="right">
                    <!--
					<?php if (isset($filters[$type]['subscribe']))
					{$subscribe = $filters[$type]['subscribe'];}
					else
					{$subscribe = 'all';}?>
                    <button onclick="resetFilterFront(this)"><span>Reset Filter</span></button>
                    <button onclick="unsubscribe(this)"><span>Unsubscribe</span></button>
                    <button onclick="subscribe(this)"><span>Subscribe</span></button>
					<select name="subscribe" id="subscribe" onchange="filterOffersFront(this)" class="filter">
                        <option value="all" <?php if ($subscribe == "all") echo "selected"?>>All</option>
                        <option value="subscribe" <?php if ($subscribe == 'subscribe') echo "selected"?>>Subscribe</option>
                        <option value="unsubscribe" <?php if ($subscribe == 'unsubscribe') echo "selected"?>>Unsubscribe</option>
                    </select>
					<label>Show:</label> --Þ
						
					<!--button onclick="filterOffersFront(this)">Filter</button-->
					
					  
                    <input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                </th>
				
            </tr>
            <tr>
                <!--th><input type="checkbox" onclick="toggleCheckbox(this)"/></th>
           <!--     <?php foreach($field_list as $field=>$data):?>
                        <th id="<?php echo $field ?>"
                            onclick="sortBy_front('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
                <?php endforeach;?> -->
				
				<?php $new_dir = $orders[$type]['dir']=='asc'?'desc':'asc'?>
                
				<?php foreach($field_list as $field=>$data):?>
                    <?php if($orders[$type]['order'] == $field):?>
                        <th class="active <?php echo $orders[$type]['dir']?>" id="<?php echo $field ?>" >
                            <?php echo $data['label'] ?>
                            
                        </th>
                    <?php else:?>
                        <th id="<?php echo $field ?>"><?php echo $data['label'] ?></th>
                    <?php endif;?>
                <?php endforeach;?>
				
            </tr>
            <tr style="display: none;">
				
                <?php foreach($field_list as $field=>$data):?>
                    <?php if($data['filter'] == 'text' && $field != 'pk_id'):?>
                        <th id="<?php echo $field?>"><input type="text" name="<?php echo $field?>" class="autocomplete filter" id="<?php echo $field?>"
                           value="<?php echo isset($filters[$type][$field])?$filters[$type][$field]:""?>"/></th>
                    <?php elseif ($data['filter'] == 'range'):?>
                        <th id="<?php echo $field?>">
                            <input type="text" class="filter" name="<?php echo $field?>[from]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['from'])?$filters[$type][$field]['from']:""?>"/><p>-</p>
                            <input type="text" class="filter" name="<?php echo $field?>[to]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['to'])?$filters[$type][$field]['to']:""?>"/>
                        </th>
                    <?php endif;?>
                <?php endforeach;?>
				<th></th>
				<th></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;?>
            <?php foreach($items as $item):?>
			<?php if (!($subscribe=='unsubscribe' && ($item->getData('user_id')>0))) :?>
                <tr class="<?php echo ++$i%2?'even':'odd'?> <?php echo $item->getData('profit_state')?>">
					 <!--td align="center" class="first" style="width: 2%;">
                        <input type="checkbox" class="item_id" value="<?php echo $item->getId()?>"/>
                    </td-->
                    <td style="width: 9%;"><?php echo $item->getData('manufacturer')?></td>
                    <td style="width: 27%;"><?php echo $item->getData('model')?></td>
                    <!--td style="width: 9%;"><?php echo $item->getData('color')?></td>
                    <td style="width: 9%;"><?php echo $item->getData('spec')?></td>
                    <!--td class="company_name"><?php echo $item->getData('company_name')?></td>
					  <td><?php echo $item->getData('brand')?></td>
					  <td><?php echo $item->getData('location')?></td-->
                    <!--td style="width: 8%;"><?php echo date('m/d/Y',$item->getData('date'))?></td>
                    <td style="width: 8%;"><?php echo date('m/d/Y',strtotime($item->getData('updated_at')))?></td-->
                    <td style="width: 5%;"><?php 
                        /*if ($item->getData('nc_id')) {
                        echo round($item->getData('base_price')+$item->getData('base_price')*$item->getData('nc_id')/100,2);}
                        elseif ($item->getData('nc_com')) {
                        echo round($item->getData('base_price')+$item->getData('base_price')*$item->getData('nc_com')/100,2);}
                        else echo round($item->getData('base_price'),2);*/
                       if ($item->getData('currency_code')=='USD') {echo '$'.format_price($item->getData('price'));}
					   elseif ($item->getData('currency_code')=='EURO') {echo '€'.format_price($item->getData('price'));}
					   else {echo format_price($item->getData('price'));}
					?></td>
              <!--      <?php if($type=='offer_front'):?>
                	<td><?php echo $item->getData('offer_price')?></td>
            	    <?php endif;?> -->
                    <!--td class="last" style="width: 5%;"><?php 
					/*if ($item->getData('qty')<2 || round($item->getData('qty')*$item->getData('qty_factor'))<2) {echo $item->getData('qty');} else { echo round($item->getData('qty')*$item->getData('qty_factor'));}*/
					echo $item->getData('qty');
					?></td>
					<td style="width: 9%;">
					<?php if ($item->getData('user_id')>0) 
						{echo 'Subscribe';}
						else
						{echo 'Unsubscribe';}
					
					?>
					</td>
					<td style="width: 8%;">
					<input type="button" class="btn" value="Add Request" onclick=addrequest(<?php echo $item->getId().','.$item->getData('qty');?>) />
					<input id="qty_<?php echo $item->getId()?>"  type="text" value="" onkeyup="this.value = this.value.replace (/\D/, '')"/>
					</td-->
                </tr>
			<?php endif; ?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>