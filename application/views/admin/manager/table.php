<div class="offers-table">
    <h2>
        <?php

        $field_list = array(
                "pk_id" => array('label'=>"ID", 'filter'=>'text'),
                "login"=>array('label'=>"Login", 'filter'=>'text'),
                "mail"=>array('label'=>"E-mail", 'filter'=>'text'),
                "role_id"=>array('label'=>"Status", 'filter'=>'text'),
            );
        ?>
    </h2>
	<?php
		$limit = $filters[$type]['limit'];
		$limit_from = $filters[$type]['limit_from'];
		if ($count/$limit>1) {
			echo '<a href="'.base_url().'admin/manager/index/page/1"><<</a>';
			if ($page-2>0) {$n = $page-2;} else $n=1;
			if ($page+2<$count/$limit) {$k = $page+2;} else $k=$count/$limit;
			if ($n==1 && $n+4<=$count/$limit) {$k = $n+4;}
			elseif ($n==1 && $n+4>$count/$limit) {$k = $count/$limit;}
			if ($k==$count/$limit && $k-4>0) {$n = $k-4;}
			elseif ($k==$count/$limit && $k-4<=0) {$n = 1;}
			
			for ($i = $n; $i<=$k; $i++ )
			{
				echo '<a href="'.base_url().'admin/manager/index/page/'.$i.'">'.$i.'</a>';		
			}
			echo '<a href="'.base_url().'admin/manager/index/page/'.$count/$limit.'">>></a>';
		}
	
	
	?>	
    <table>
        <thead>
            <tr>
                <th colspan="<?php echo count($field_list)+2?>" align="right">
                    <?php $status = isset($filters[$type]['status'])?$filters[$type]['status']:""?>
                    <button class="fl" onclick="filterOffers_user(this)">Filter</button>
                    <button class="fl" onclick="resetFilter_user(this)">Reset Filter</button>
					<!--button onclick="deleteVisible(this)">Delete</button-->
                    <input type="hidden" class="item_type filter" name="type" value="<?php echo $type?>"/>
                </th>
				
            </tr>
            <tr>
                <th><input type="checkbox" onclick="toggleCheckbox(this)"/></th>
                <?php foreach($field_list as $field=>$data):?>
                        <th id="<?php echo $field ?>"
                            onclick="sortBy_User('<?php echo $type?>','<?php echo $field ?>','asc')"><?php echo $data['label'] ?></th>
                <?php endforeach;?>
				<th></th>
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
                <tr class="<?php echo ++$i%2?'even':'odd'?>  pointer">
                    <td align="center" class="first">
                        <input type="checkbox" class="item_id" value="<?php echo $item->getId()?>"/>
                    </td>
                    <td><?php echo $item->getData('pk_id')?></td>
                    <td><?php echo $item->getData('login')?></td>
                    <td><?php echo $item->getData('email')?></td>
                    <td><?php if ($item->getRole()->getData('code')=='verifying') echo 'Inactive'; else echo 'Active'?></td>
                    <td><button class="fl" onclick="change_pass(<?php echo $item->getData('pk_id')?>)">Change password</button></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>