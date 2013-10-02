<?php $this->load->view('admin/header') ?>
<div class="left">
    <div>
        <form method="POST" action="<?php echo site_url('admin/matches')?>">
            <label for="match_value">Match value</label>
            <input type="text" 
                   name="match_value"
                   id="match_value"
                   value="<?php echo $match_value?>"
                   style="text-align:center"
            />
            <input type="submit" name="Match"/>
        </form>
        <input type="button" value="Filter" onclick="applyFilter()"/>
        <input type="button" value="Reset Filter" onclick="resetFilter()"/>
    </div>
</div>
<?php
$fields = array(
    "manufacturer" => "Manufacturer",
    "model" => "Model",
    "color" => "Color",
    "spec" => "Spec",
    "space_1" => "&nbsp;",
    "offer_company" => "Company",
    "offer_qty" => "Qty",
    "offer_offer_price" => "Offer Price",
    "offer_base_price" => "Adj. price",
    "offer_date" => "Date",
    "space_2" => "&nbsp;",
    "request_company" => "Company",
    "request_qty" => "Qty",
    "request_base_price" => "Adj. price",
    "request_date" => "Date"
);

$order_fields = array(
    "manufacturer",
    "model",
    "offer_company",
    "request_company",
    "offer_qty",
    "offer_base_price",
    "request_qty",
    "request_base_price"
);
$new_dir = $order['dir']=='asc'?'desc':'asc';
?>

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
            <?php foreach ($fields as $k=>$v):?>
                <?php if(in_array($k,$order_fields)):?>
                    <?php if($order['order'] == $k):?>
                        <th class="active <?php echo $order['dir']?>" id="<?php echo $k ?>"
                            onclick="sortMatchBy('<?php echo $k ?>','<?php echo $new_dir?>')">
                            <?php echo $v ?>
                            <?php if($order['dir']=='asc'):?> ↑<?php endif;?>
                            <?php if($order['dir']=='desc'):?> ↓<?php endif;?>
                        </th>
                    <?php else:?>
                        <th id="<?php echo $k ?>"
                            onclick="sortMatchBy('<?php echo $k ?>','asc')"><?php echo $v ?></th>
                    <?php endif;?>
                <?php else:?>
                    <th><?php echo $v?></th>
                <?php endif;?>
            <?php endforeach;?>
        </tr>
        <tr class="filters">
            <th>
                <input id="manufacturer_id" type="hidden" value="<?php echo (isset($filter['manufacturer_id']))?$filter['manufacturer_id']:""?>">
                <input id="manufacturer" name="manufacturer" class="autocomplete" value="<?php echo (isset($filter['manufacturer']))?$filter['manufacturer']:""?>">
            </th>
            <th>
                <input id="model" name="model" class="autocomplete" value="<?php echo (isset($filter['model']))?$filter['model']:""?>">
            </th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>
                <input id="offer_company" name="offer_company" class="autocomplete" value="<?php echo (isset($filter['offer_company']))?$filter['offer_company']:""?>">
            </th>
            <!--th>Contact</th-->
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>
                <input id="offer_date[from]" value="<?php echo (isset($filter['offer_date']['from']))?$filter['offer_date']['from']:""?>">-
                <input id="offer_date[to]" value="<?php echo (isset($filter['offer_date']['to']))?$filter['offer_date']['to']:""?>">
            </th>
            <th></th>
            <th>
                <input id="request_company" name="request_company" class="autocomplete" value="<?php echo (isset($filter['request_company']))?$filter['request_company']:""?>">
            </th>
            <!--th>Contact</th-->
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>
                <input id="request_date[from]" value="<?php echo (isset($filter['request_date']['from']))?$filter['request_date']['from']:""?>">-
                <input id="request_date[to]" value="<?php echo (isset($filter['request_date']['from']))?$filter['request_date']['to']:""?>">
            </th>

        </tr>
    </thead>
    <tbody>
<?php
$groups = array();
foreach ($items as $item) {
    if (!isset($groups[$item->getData('offer_id')])) $groups[$item->getData('offer_id')] = array();
    $groups[$item->getData('offer_id')][]=$item;
}

?>

<?php $i=0; foreach ($groups as $group):?>
        <?php $rows = count($group)?>
        <?php $j=0; foreach ($group as $item):?>
            
                <?php if(0==$j++):?>
                    <tr class="<?php echo $i++%2?"odd":"even"?> group">
                    <td rowspan="<?php echo $rows?>" class="center"><?php echo $item->getData('manufacturer')?></td>
                    <td rowspan="<?php echo $rows?>" class="center"><?php echo $item->getData('model')?></td>
                    <td rowspan="<?php echo $rows?>" class="center"><?php echo $item->getData('color')?></td>
                    <td rowspan="<?php echo $rows?>" class="center"><?php echo $item->getData('spec')?></td>
                    <td rowspan="<?php echo $rows?>" >&nbsp;&nbsp;&nbsp;</td>
                    <td rowspan="<?php echo $rows?>" class="company">
                        <?php echo $item->getData('offer_company_name')?>
                        <input type="hidden" id="offer" value="<?php echo $item->getData('offer_id')?>">
                    </td>
                    <td rowspan="<?php echo $rows?>" class="right offer"><?php echo $item->getData('offer_qty')?></td>
                    <td rowspan="<?php echo $rows?>" class="center offer"><?php echo $item->getData('offer_offer_price')?></td>
                    <td rowspan="<?php echo $rows?>" class="right offer"><?php echo $item->getData('offer_base_price')?round($item->getData('offer_base_price'),2):''?></td>
                    <td rowspan="<?php echo $rows?>" class="center offer"><?php echo date("m/d/Y",$item->getData('offer_date'))?></td>
                    <td rowspan="<?php echo $rows?>" >&nbsp;&nbsp;&nbsp;</td>
                <?php else:?>
                    <tr class="<?php echo $i%2?"even":"odd"?>">
                <?php endif;?>
                <td class="company">
                    <?php echo $item->getData('request_company_name')?>
                    <input type="hidden" id="request" value="<?php echo $item->getData('request_id')?>">
                </td>
                <td class="request right"><?php echo $item->getData('request_qty')?></td>
                <td class="request right"><?php echo $item->getData('request_base_price')?round($item->getData('request_base_price'),2):''?></td>
                <td class="request center"><?php echo date("m/d/Y",$item->getData('request_date'))?></td>
            </tr>
        <?php endforeach;?>
<?php endforeach;?>
    </tbody>

</table>
<div id="offer_data"></div>
<div id="contact_data"></div>
<script type="text/javascript">
    var attrs = <?php echo json_encode($attrs)?>
</script>
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/match.js"></script>
<?php $this->load->view('admin/footer') ?>