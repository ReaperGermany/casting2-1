<?php

class Requires extends Main_model
{
    public function  __construct()
    {
        parent::__construct();
        $this->_init('requires');
    }

    public function getCollection()
    {
        $retval = array();
        $this->db
                    ->from($this->_table_name.' main_table')
                    ->join('attribute_values av','main_table.fk_value = av.pk_id','left')
                    ->join('attribute_values avm','main_table.fk_model = avm.pk_id','left')
                    ->select('main_table.*, av.code')
                    ->order_by('av.value, avm.value');
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            if ($row['code'] == 'manufacturer') {
                $retval[$row['fk_value']][] = $row['fk_model'];
            }
            else {
                $retval[$row['fk_model']][] = $row['fk_value'];
            }
        }

        return $retval;
    }

    public function prepareAttrs($attrs)
    {
        /**
         * маска для построения дерева.
         */
        $reqs = array(
            "manufacturer"  => array(
                "require"   => false,
                "data"      => array()
            ),
            "model"         => array(
                "require"   => "manufacturer",
                "data"      => array()
            ),
            "color"         => array(
                "require"   => "model",
                "data"      => array()
            ),
            "spec"         => array(
                "require"   => "model",
                "data"      => array()
            ),
            "currency"  => array(
                "require"   => false,
                "data"      => array(),
                "default"   => "USD"
            )
        );

        foreach ($attrs as $code=>$value) {
            if (!isset($reqs[$code])){
                $reqs[$code] = array(
                    "require"   => false,
                    "data"      => array()
                );
            }
        }

        $requires = $this->getCollection();

        $models = array();
        $manufacturers = array();

        $retval = array();

        /**
         * Построение дерева атрибутов с учетом зависимостей
         */
        foreach ($reqs as $code=>$mask)
        {
            $retval[$code] = array();
            $retval[$code]['require'] = $mask['require'];
            $retval[$code]['data'] = array();

            /**
             *  Если атрибут не имеет зависимостей, то просто выводим все элементы
             */
             $i = 0;
            if (!$mask['require'] && isset($attrs[$code])) {
                foreach($attrs[$code] as $item)
                {
                    $retval[$code]['data'][] = $item;
                    if (isset($mask['default']) && $item['value'] == $mask['default']) {
                	$retval[$code]['default_val'] = $i;
                    }
                    $i++;
                }
            }

            /**
             * если зависимости имеются, то для каждого атрибута, от которого зависит данный,
             * строим массив допустимых атрибутов
             */
            elseif ($mask['require']) {
                foreach($attrs[$mask['require']] as $id => $item) {
                    if (!isset($retval[$code]['data'][$id])) $retval[$code]['data'][$id] = array();
                    if (!isset($requires[$id])) continue;
                    foreach ($requires[$id] as $attr_id) {
                        if (!isset($attrs[$code][$attr_id])) continue;
                        $retval[$code]['data'][$id][] = $attrs[$code][$attr_id];
                    }
                }
            }
        }

        return $retval;
    }
	
	public function prepareAttrs_add($attrs)
    {
        /**
         * маска для построения дерева.
         */
        $reqs = array(
            "manufacturer"  => array(
                "require"   => false,
                "data"      => array()
            ),
            "model"         => array(
                "require"   => false,//"manufacturer",
                "data"      => array()
            ),
            "color"         => array(
                "require"   => false,//"model",
                "data"      => array()
            ),
            "spec"         => array(
                "require"   => false,//"model",
                "data"      => array()
            ),
            "currency"  => array(
                "require"   => false,
                "data"      => array(),
                "default"   => "USD"
            )
        );

        foreach ($attrs as $code=>$value) {
            if (!isset($reqs[$code])){
                $reqs[$code] = array(
                    "require"   => false,
                    "data"      => array()
                );
            }
        }

        $requires = $this->getCollection();

        $models = array();
        $manufacturers = array();

        $retval = array();

        /**
         * Построение дерева атрибутов с учетом зависимостей
         */
        foreach ($reqs as $code=>$mask)
        {
            $retval[$code] = array();
            $retval[$code]['require'] = $mask['require'];
            $retval[$code]['data'] = array();

            /**
             *  Если атрибут не имеет зависимостей, то просто выводим все элементы
             */
             $i = 0;
            if (!$mask['require'] && isset($attrs[$code])) {
                foreach($attrs[$code] as $item)
                {
                    $retval[$code]['data'][] = $item;
                    if (isset($mask['default']) && $item['value'] == $mask['default']) {
                	$retval[$code]['default_val'] = $i;
                    }
                    $i++;
                }
            }

            /**
             * если зависимости имеются, то для каждого атрибута, от которого зависит данный,
             * строим массив допустимых атрибутов
             */
            elseif ($mask['require']) {
                foreach($attrs[$mask['require']] as $id => $item) {
                    if (!isset($retval[$code]['data'][$id])) $retval[$code]['data'][$id] = array();
                    if (!isset($requires[$id])) continue;
                    foreach ($requires[$id] as $attr_id) {
                        if (!isset($attrs[$code][$attr_id])) continue;
                        $retval[$code]['data'][$id][] = $attrs[$code][$attr_id];
                    }
                }
            }
        }

        return $retval;
    }
	
    public function add(Attribute_value $item, $require_code, $require_id)
    {
        if ($item->getAttributeCode() == Attribute_value::CODE_MODEL)
        {
            return $this->setData('fk_model', $item->getId())
                 ->setData('fk_value', $require_id)
                 ->save();
        }
        elseif ($require_code == Attribute_value::CODE_MODEL) {
            return $this->setData('fk_model', $require_id)
                 ->setData('fk_value', $item->getId())
                 ->save();
        }
    }

}