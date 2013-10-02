<div class="offers-table">
        <?php
        $field_list = array(
                "pk_id" => array('label'=>"ID", 'filter'=>''),
                "login"=>array('label'=>"Login", 'filter'=>'text'),
                "status"=>array('label'=>"Subscribe", 'filter'=>'')
            );
        ?>
    <table>
        <thead>
            <tr>
                <th colspan="<?php echo count($field_list)+1?>" align="right">
				<button class="fl" onclick="filterUser(this)">Filter</button>
				<button class="fl" onclick="resetFilterUser(this)">Reset Filter</button>
				<button class="fl" onclick="cron_subscribe(this)">Subscribe</button> 
				<button class="fl" onclick="cron_unsubscribe(this)">Unsubscribe</button>
                    <input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                </th>
            </tr>
            <tr>
                <th><input type="checkbox" onclick="toggleCheckbox(this)"/></th>
               <?php foreach($field_list as $field=>$data):?>
                     <?php if ($field!="status")  :?>  
					   <th id="<?php echo $field ?>"
                            onclick="sortBySubscribe('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
						<?php else : ?>	
						<th id="<?php echo $field ?>"><?php echo $data['label'] ?></th>	
						<?php endif;?>
                <?php endforeach;?>
            </tr>
			<tr>
                <th></th>
				<th></th>
                <?php foreach($field_list as $field=>$data):?>
                    <?php if($data['filter'] == 'text' && $field != 'pk_id'):?>
                        <th id="<?php echo $field?>"><input type="text" name="<?php echo $field?>" class="autocomplete filter text" id="<?php echo $field?>"
                           value="<?php echo isset($filters[$type][$field])?$filters[$type][$field]:""?>"/></th>
                    <?php elseif ($data['filter'] == 'range'):?>
                        <th id="<?php echo $field?>">
                            <input type="text" class="filter text" name="<?php echo $field?>[from]"
                                value="<?php echo isset($filters[$type][$field])&&isset($filters[$type][$field]['from'])?$filters[$type][$field]['from']:""?>"/>-
                            <input type="text" class="filter text" name="<?php echo $field?>[to]"
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
                <tr class="<?php echo ++$i%2?'even':'odd'?>">
                    <td align="center" class="first">
                        <input type="checkbox" class="item_id" value="<?php echo $item['id']?>"/>
                    </td>
                    <td><?php echo $item['id']?></td>
                    <td><?php echo $item['login']?></td>
                    <td><?php if ($item['status']== 1) {echo 'Subscribe';} else {echo 'Unsubscribe';}?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>