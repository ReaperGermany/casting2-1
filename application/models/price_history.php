<?php

class Price_history extends Main_model
{
    public function __construct()
    {
        parent::__construct();
        $this->_init('price_history');
    }

    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $this->setData("created_at", date("Y-m-d H:i:s"));
        }
    }
}
