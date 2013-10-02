<?php

class Staff extends Main_model
{
    public function  __construct()
    {
        parent::__construct();
        $this->_init('staff');
    }

    protected function _beforeLoad()
    {
		$this->db->join('company company', 'company.pk_id = main_table.fk_company', 'left');
        $this->db->select('company.name AS company_name');
    }
	
	protected function _beforeLoadCollection()
    {
		$this->db->join('company company', 'company.pk_id = main_table.fk_company', 'left');
        $this->db->select('company.name AS company_name');
    }
	
}