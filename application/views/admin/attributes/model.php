<?php

class Cmp
{
    static $attrs = array();
    static $field = "manufacturer";
    static $dir = "asc";

    static function cmp_models($item_a, $item_b) {

        $requires_a = $item_a->getData('requires');
        $requires_b = $item_b->getData('requires');

        $retval = 0;
        // Строки с незаполненными производителями идут впереди
        if (isset($requires_a['manufacturer']) && !isset($requires_b['manufacturer'])) $retval = 1;
        elseif (isset($requires_b['manufacturer']) && !isset($requires_a['manufacturer'])) $retval = -1;
        elseif (!isset($requires_b['manufacturer']) && !isset($requires_a['manufacturer'])) $retval = 0;

        elseif (isset(self::$attrs[$requires_a['manufacturer'][0]]) &&
               !isset(self::$attrs[$requires_b['manufacturer'][0]])) $retval = 1;
        elseif (isset(self::$attrs[$requires_b['manufacturer'][0]]) &&
               !isset(self::$attrs[$requires_a['manufacturer'][0]])) $retval = -1;
        elseif (!isset(self::$attrs[$requires_b['manufacturer'][0]]) &&
               !isset(self::$attrs[$requires_a['manufacturer'][0]])) $retval = 0; 

        // Сортировка по алфавиту
        elseif (self::$attrs[$requires_a['manufacturer'][0]]<
                self::$attrs[$requires_b['manufacturer'][0]]) $retval = -1;
        elseif (self::$attrs[$requires_b['manufacturer'][0]]<
                self::$attrs[$requires_a['manufacturer'][0]]) $retval = 1;

        if (self::$dir == "desc") $retval *= -1;
        return $retval;
    }
}

$attr_list = array();
foreach ($attrs as $group){
    foreach ($group as $item) {
        $attr_list[$item['id']] = $item['label'];
    }
}

if(isset($values) && is_array($values)) {
    if (isset($order['order']) && $order['order']=="manufacturer" && isset($attrs['manufacturers'])) {
        //asort($attrs['manufacturer']);
        Cmp::$attrs = $attr_list;
        Cmp::$dir = $order['dir'];
        usort($values, array('Cmp','cmp_models'));
    }
}
//var_dump($attr_list);
function showItems($items, $attr_list)
{
    $retval=array();
    foreach ($items as $item) {
        if (isset($attr_list[$item])) $retval[] = $attr_list[$item];
    }
//var_dump($items);
//var_dump($retval);
    return implode(", ", $retval);
}

?>

<?php
$fields = array(
    "id" => "ID",
    "manufacturer" => "Manufacturer",
    "model" => "Model",
    "color" => "Color",
    "spec" => "Spec"
);

$order_fields = array(
    "manufacturer",
    "model"
);
$new_dir = $order['dir']=='asc'?'desc':'asc';
?>

<div class="attribute_container">
    <?php if(isset($values)): ?>
        <input type="button" value="Add New" onclick="add_model()"/>
        <input type="button" value="Delete" onclick="delete_attr()"/>
        <table id="models_table" style="width:1000px">
            <thead>
                <tr>
                    <td><input type="checkbox" onclick="toggleCheckbox(this)"></td>
                    <!--td>ID</td-->
                    <!--td>Manufacturers</td>
                    <td>Model</td>
                    <td>Colors</td>
                    <td>Specs</td--->

                    <?php foreach ($fields as $k=>$v):?>
                        <?php if(in_array($k,$order_fields)):?>
                            <?php if($order['order'] == $k):?>
                                <th class="active <?php echo $order['dir']?>" id="<?php echo $k ?>"
                                    onclick="sortModelsBy('<?php echo $k ?>','<?php echo $new_dir?>')">
                                    <?php echo $v ?>
                                    <?php if($order['dir']=='asc'):?> ↑<?php endif;?>
                                    <?php if($order['dir']=='desc'):?> ↓<?php endif;?>
                                </th>
                            <?php else:?>
                                <th id="<?php echo $k ?>"
                                    onclick="sortModelsBy('<?php echo $k ?>','asc')"><?php echo $v ?></th>
                            <?php endif;?>
                        <?php else:?>
                            <th><?php echo $v?></th>
                        <?php endif;?>
                    <?php endforeach;?>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($values) && is_array($values)):?>
                    <?php $i=0; foreach($values as $k=>$value):?>
                        <?php $requires = $value->getData('requires')?>
                        <tr id="row_<?php echo $k?>" class="<?php echo ++$i%2?"even":"odd"?>">
                            <td style="width:50px">
                                <input class="item_id" type="checkbox" value="<?php echo $value->getId()?>" onclick="blocker=true">
                            </td>
                            <td style="width:50px">
                                <input id="row_<?php echo $k?>_id" type="hidden" value="<?php echo $value->getId()?>">
                                <?php echo $value->getId()?>
                            </td>
                            <td>
                                <input type="hidden" 
                                       value="<?php echo isset($requires['manufacturer'])?implode(',',$requires['manufacturer']):""?>"
                                       id="row_<?php echo $k?>_manufacturers">
                                <?php echo isset($requires['manufacturer'])?showItems($requires['manufacturer'],$attr_list):""?>
                            </td>
                            <td>
                                <input type="hidden" value="<?php echo $value->getData('value')?>" id="row_<?php echo $k?>_value">
                                <?php echo $value->getData('value')?>
                            </td>
                            <td>
                                <input type="hidden" 
                                       value="<?php echo isset($requires['color'])?implode(',',$requires['color']):""?>"
                                       id="row_<?php echo $k?>_colors">
                                <?php echo isset($requires['color'])?showItems($requires['color'],$attr_list):""?>
                            </td>
                            <td>
                                <input type="hidden" 
                                       value="<?php echo isset($requires['spec'])?implode(',',$requires['spec']):""?>"
                                       id="row_<?php echo $k?>_specs">
                                <?php echo isset($requires['spec'])?showItems($requires['spec'],$attr_list):""?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
            <tfoot></tfoot>
        </table>
    <?php endif;?>
</div>