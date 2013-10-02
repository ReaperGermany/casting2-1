<div class="offers-table">
        <?php


        $field_list = array(
                "pk_id" => array('label'=>"ID", 'filter'=>'text'),
                "user_id"=>array('label'=>"User", 'filter'=>'text'),
                "code_atr"=>array('label'=>"Code", 'filter'=>'text'),
                "value"=>array('label'=>"Value", 'filter'=>'text'),
            );
        ?>

    <table>
        <thead>
            <tr>
                <th colspan="<?php echo count($field_list)+1?>" align="right">
                    <?php $status = isset($filters[$type]['status'])?$filters[$type]['status']:""?>
                    <button class="fl" onclick="filterOffers_vis(this)">Filter</button>
                    <button class="fl" onclick="resetFilter_vis(this)">Reset Filter</button>
					<button class="fl" onclick="deleteVisible(this)">Delete</button>
                    <input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                </th>
            </tr>
            <tr>
                <th><input type="checkbox" onclick="toggleCheckbox(this)"/></th>
                <?php foreach($field_list as $field=>$data):?>
                        <th id="<?php echo $field ?>"
                            onclick="sortBy_vis('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
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
            </tr>
        </thead>
        <tbody>
            <?php $i=0;?>
            <?php foreach($items as $item):?>
                <tr class="<?php echo ++$i%2?'even':'odd'?> <?php echo $item->getData('profit_state')?>">
                    <td align="center" class="first">
                        <input type="checkbox" class="item_id" value="<?php echo $item->getId()?>"/>
                    </td>
                    <td><?php echo $item->getData('pk_id')?></td>
                    <td><?php echo $item->getData('login')?></td>
                    <td><?php echo $item->getData('code_atr')?></td>
                    <td><?php if ($item->getData('code_atr') =="company_name") {echo $item->getData('company');} else {echo $item->getData('val');}?></td>
                    
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>