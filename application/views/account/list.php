<div class="manufacturer-table" style="width: 100%;">
    <table>
        <tbody>
            <?php $i=1;?>
				<tr>
				<td style="display: none;"><input type="hidden" class="man_id" value="all"/></td>
				<td>All Manufacturers</td>
				</tr>
            <?php foreach($items as $item):?>
                <tr class="<?php echo ++$i%2?'even':'odd'?> <?php echo $item->getData('profit_state')?>">
                    <td style="display: none;"><input type="hidden" class="man_id" value="<?php echo $item->getId()?>"/></td>
                    <!--?php if ($item->getData('image')==NULL) :?-->
					<td><?php echo $item->getData('value')?>
					<!--?php endif; ?--> 
                    <?php if ($item->getData('image')!=NULL) :?>
								<img src="<?php echo base_url().$item->getData('image') ?>"    alt="<?php echo $item->getData('value')?>">	
                                 <?php endif; ?> 		
								</td>
					 
					
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>