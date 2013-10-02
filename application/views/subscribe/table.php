<div class="offers-table">
    <h2>
        <?php
            if ($type=='offer') echo 'Offers List';
            elseif($type=='request') echo 'Requests List';

            $field_list = array(
                "value" => array('label'=>"Manufacturer", 'filter'=>'text'),
				"user_id"=>array('label'=>"Subscribe", 'filter'=>''),
            );

        ?>


    </h2>
    <table>
        <thead>
            <tr>
                <th colspan="<?php echo count($field_list)+1?>" align="right">
                    <?php if (isset($filters[$type]['subscribe']))
					{$subscribe = $filters[$type]['subscribe'];}
					else
					{$subscribe = 'all';}?>
					<select name="subscribe" id="subscribe" onchange="filterOffersFront2(this)" class="filter">
                        <option value="all" <?php if ($subscribe == "all") echo "selected"?>>All</option>
                        <option value="subscribe" <?php if ($subscribe == 'subscribe') echo "selected"?>>Subscribe</option>
                        <option value="unsubscribe" <?php if ($subscribe == 'unsubscribe') echo "selected"?>>Unsubscribe</option>
                    </select>
					<button onclick="subscribe2(this)">Subscribe</button>
					<button onclick="unsubscribe2(this)">Unsubscribe</button>	
					<button onclick="filterOffersFront2(this)">Filter</button>
					<button onclick="resetFilterFront2(this)">Reset Filter</button>
					  
                    <input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                </th>
            </tr>
            <tr>
                <th><input type="checkbox" onclick="toggleCheckbox(this)"/></th>
           <!--     <?php foreach($field_list as $field=>$data):?>
                        <th id="<?php echo $field ?>"
                            onclick="sortBy_front('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
                <?php endforeach;?> -->
				
				<?php $new_dir = $orders[$type]['dir']=='asc'?'desc':'asc'?>
                
				<?php foreach($field_list as $field=>$data):?>
                    <?php if($orders[$type]['order'] == $field):?>
                        <th class="active <?php echo $orders[$type]['dir']?>" id="<?php echo $field ?>" 
						onclick="sortBy_front2('<?php echo $type?>','<?php echo $field ?>','<?php echo $new_dir?>')">
                            <?php echo $data['label'] ?>
                            <?php if($orders[$type]['dir']=='asc'):?> ↑<?php endif;?>
                            <?php if($orders[$type]['dir']=='desc'):?> ↓<?php endif;?>
                        </th>
                    <?php else:?>
                        <th id="<?php echo $field ?>"onclick="sortBy_front2('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
                    <?php endif;?>
                <?php endforeach;?>
				
            </tr>
            <tr>
				<th></th>
                <?php foreach($field_list as $field=>$data):?>
                    <?php if($data['filter'] == 'text' && $field != 'pk_id'):?>
                        <th id="<?php echo $field?>"><input type="text" name="<?php echo $field?>" class="autocomplete filter" id="<?php echo $field?>"
                           value="<?php echo isset($filters[$type][$field])?$filters[$type][$field]:""?>"/></th>
                    <?php elseif ($data['filter'] == 'range'):?>
                        <th id="<?php echo $field?>">
                            <input type="text" class="filter" name="<?php echo $field?>[from]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['from'])?$filters[$type][$field]['from']:""?>"/>-
                            <input type="text" class="filter" name="<?php echo $field?>[to]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['to'])?$filters[$type][$field]['to']:""?>"/>
                        </th>
                    <?php endif;?>
                <?php endforeach;?>
				<th></th>
			
            </tr>
        </thead>
        <tbody>
            <?php $i=0;?>
            <?php foreach($items as $item):?>
			<?php if (($subscribe=='subscribe' && ($item->getData('status')>0)) || ($subscribe=='unsubscribe' && !($item->getData('status')>0)) || $subscribe=='all') :?>
                <tr class="<?php echo ++$i%2?'even':'odd'?> <?php echo $item->getData('profit_state')?>">
					 <td align="center" class="first">
                        <input type="checkbox" class="item_id" value="<?php echo $item->getId()?>"/>
                    </td>
                    <td><?php echo $item->getData('value')?></td>            
					<td>
					<?php if ($item->getData('status')>0) 
						{echo 'Subscribe';}
						else
						{echo 'Unsubscribe';}
					
					?>
					</td>
                </tr>
			<?php endif; ?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>