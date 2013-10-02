<div class="attribute_container">
    <?php if(isset($values)): ?>
        <input type="button" class="btn" value="Add New" onclick="add_attr()"/>
        <input type="button" class="btn" value="Delete" onclick="delete_attr()"/>
        <table id="attributes_table" <?php if ($type=='model') {echo 'style="width:500px"';} else {echo 'style="width:200px"';} ?>>
            <thead>
                <tr>
                    <td><input type="checkbox" onclick="toggleCheckbox(this)"></td>
                    <td style="width: 5%;" >ID</td>
                    <td style="width: 90%;">Value</td>
					<?php if ($type=='manufacturer') :?>
					  <td style="width: 10%;">Logo</td>
					<?php endif; ?>
                    <!--td>Action</td-->
                </tr>
            </thead>
            <tbody>
                <?php if(isset($values) && is_array($values)):?>
                    <?php foreach($values as $k=>$value):?>
                        <tr id="row_<?php echo $k?>">
                            <td><input class="item_id" type="checkbox" value="<?php echo $value->getId()?>" onclick="blocker=true"></td>
                            <td id="row_<?php echo $k?>_id"><?php echo $value->getId()?></td>
                            <td id="row_<?php echo $k?>_value"><?php echo $value->getData('value')?></td>
                            <?php if ($type=='manufacturer' && $value->getData('image')!=NULL) :?>
								<td style="width: 10%;"><img src="<?php echo base_url().$value->getData('image') ?>"  alt="<?php echo $value->getData('value')?>">			
								<input type="button" class="btn" value="Delete" onclick=delete_image(<?php echo $value->getId()?>) >
								<input type="hidden" name="row_<?php echo $k?>_image" id="row_<?php echo $k?>_image" value="<?php echo $value->getData('image') ?>"/>
								</td>
							  <?php endif; ?> 
							   <?php if ($type=='manufacturer' && $value->getData('image')==NULL) :?>
							<td>
							<form class="file" id="<?php echo "upload-".$value->getId();?>" name="<?php echo "upload-".$value->getId();?>" action="<?php echo base_url().'admin/attributes/saveImage' ?>" method="post" enctype="multipart/form-data">
						  <input name="id" id="id" type="hidden" value="<?php echo $value->getId();?>" >
					      <input type="file" class="file" name="filename">
					      <input type="submit" class="btn" value="Upload">
					      </form>
							</td>
							<?php endif; ?>
							<!--td>Action</td-->
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
            <tfoot></tfoot>
        </table>
    <?php endif;?>
</div>