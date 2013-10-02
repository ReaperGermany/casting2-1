<?php

class Company extends Main_model
{
    public function  __construct()
    {
        parent::__construct();
        $this->_init('company');
    }

}