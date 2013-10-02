<?php

function prepare_autocomplete_list($data)
{
    $retval = array();
    foreach ($data as $item) {
        $retval[] = json_encode($item);
    }

    return "[".implode(',',$retval)."]";
}

function format_date($date, $format="m/d/Y")
{
    if (is_string($date)) $date = strtotime($date);
    return date($format, $date);
}

function format_price($price)
{
    if ($price>0) {return number_format($price, 2);}
	else {return 0;}
}

function currency_code($currency_id)
{
    if (!$codes = Core::registry("currency_codes")) {
        $codes = array();
        foreach (Core::getModel("attribute_value")->setAttributeCode(Attribute_value::CODE_CURRENCY)->getCollection() as $code) {
            $codes[$code->getId()] = $code->getData("value");
        }

        Core::register("currency_codes", $codes);
    }

    return isset($codes[$currency_id])?$codes[$currency_id]:"";
}

function company_code($staff_id)
{
    if ($staff_id>0) {
        
    return Core::getModel("staff")->load($staff_id)->getData('email');

    }
	else return "";
}
