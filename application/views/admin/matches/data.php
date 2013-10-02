<div id="phone_descr">
    <?php $item=reset($items);?>
    <span>Manufacturer: <b><?php echo $item->getData("manufacturer")?></b></span>
    <span>Model: <b><?php echo $item->getData("model")?></b></span>
    <span>Color: <b><?php echo $item->getData("color")?></b></span>
    <span>Spec: <b><?php echo $item->getData("spec")?></b></span>
</div>
<table class="matches-table">
    <thead>
        <tr>
            <th colspan="4">Phone</th>
            <th></th>
            <th colspan="5">Offer</th>
            <th></th>
            <th colspan="4">Request</th>
        </tr>
        <tr>
            <th>Manufacturer</th>
            <th>Model</th>
            <th>Color</th>
            <th>Spec</th>
            <th></th>
            <th>Contact</th>
            <th>Qty</th>
            <th>Offer Price</th>
            <th>Adj. price</th>
            <th>Date</th>
            <th></th>
            <th>Contact</th>
            <th>Qty</th>
            <th>Adj. price</th>
            <th>Date</th>

        </tr>
    </thead>
    <tbody>
<?php $i=0; foreach ($items as $item):?>
        <tr class="<?php echo $i++%2?"odd":"even"?>">
            <td class="center"><?php echo $item->getData('manufacturer')?></td>
            <td class="center"><?php echo $item->getData('model')?></td>
            <td class="center"><?php echo $item->getData('color')?></td>
            <td class="center"><?php echo $item->getData('spec')?></td>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $item->getData('offer_staff_id')?></td>
            <td class="right"><?php echo $item->getData('offer_qty')?></td>
            <td class="center"><?php echo $item->getData('offer_offer_price')?></td>
            <td class="right"><?php echo $item->getData('offer_base_price')?></td>
            <td class="center"><?php echo date("m-d-Y",$item->getData('offer_date'))?></td>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $item->getData('request_staff_id')?></td>
            <td class="right"><?php echo $item->getData('request_qty')?></td>
            <td class="right"><?php echo $item->getData('request_base_price')?></td>
            <td class="center"><?php echo date("m-d-Y",$item->getData('request_date'))?></td>
        </tr>
<?php endforeach;?>
    </tbody>

</table>