<table id="attributes_table" style="width:200px">
    <thead>
        <tr>
            <td><input type="checkbox" onclick=""></td>
            <td>ID</td>
            <td>Value</td>
            <!--td>Action</td-->
        </tr>
    </thead>
    <tbody>
        <?php if(isset($values) && is_array($values)):?>
            <?php foreach($values as $k=>$value):?>
                <tr id="row_<?php echo $k?>">
                    <td><input class="item_id" type="checkbox" value="<?php echo $value->getId()?>" onclick="alert('a')"></td>
                    <td id="row_<?php echo $k?>_id"><?php echo $value->getId()?></td>
                    <td id="row_<?php echo $k?>_value"><?php echo $value->getData('value')?></td>
                    <!--td>Action</td-->
                </tr>
            <?php endforeach;?>
        <?php endif;?>
    </tbody>
    <tfoot></tfoot>
</table>
