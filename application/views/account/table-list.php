<div class="offers-table" style="width: 100%;">
    <h2>
        <?php
            $field_list = array(
                "manufacturer" => array('label'=>"Manufacturer", 'filter'=>'text'),
                "model"=>array('label'=>"Model", 'filter'=>'text'),
                "base_price"=>array('label'=>"Price", 'filter'=>'range'),
                "qty"=>array('label'=>"Qty", 'filter'=>'range'),
				"user_id"=>array('label'=>"Subscribe", 'filter'=>'')
            );
			if(isset($filters['offer_front']['model']) && $filters['offer_front']['model']!="") {echo 'Search results for "'.$filters['offer_front']['model'].'"';}
        ?>


    </h2>
    <table>
        <thead>
            <tr id="head_table">
                <th colspan="<?php echo count($field_list)+2?>" align="right">
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
					<label>Show:</label>
                    <input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                </th>
				
            </tr>
            <tr style="display: none;">
				<th></th>
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
            
        </tbody>
    </table>
	<ul>
	<?php $man = ""; $i = 0 ;?>
            <?php foreach($items as $item):?>
			<?php if (!($subscribe=='unsubscribe' && ($item->getData('user_id')>0))) :?>
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
				
				
			<?php endif; ?>
            <?php endforeach;?>
	</ul>
</div>