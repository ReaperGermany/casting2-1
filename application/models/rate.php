<?php

class Rate extends Main_model
{
    public function  __construct()
    {
        parent::__construct();
        $this->_init('currency_rates');
    }

    protected function  _beforeLoadCollection()
    {
        parent::_beforeLoadCollection();
        $this->db->join('attribute_values av','main_table.fk_currency = av.pk_id','RIGHT');
        $this->db->where('av.code','currency');
        $this->db->select('av.value as currency, av.pk_id as currency_id');
    }
}