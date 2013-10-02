<?php

class Role extends Main_model
{
    public function __construct()
    {
        parent::__construct();
        $this->_init('roles');
    }

    public function loadByCode($code)
    {
        $items = $this->addFilterByField('code', $code)->getCollection();
        if (count($items)) {
            return reset($items);
        }
        else {
            return $this;
        }
    }
}
