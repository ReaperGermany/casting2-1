<?php
 
class Nacenka_model extends Main_model
{
    public function __construct()
    {
        parent::__construct();
        $this->_init('nacenka');
    }
	
	protected function _beforeLoadCollection()
    {
        parent::_beforeLoadCollection();

        $this->db->join('userf', 'main_table.user_id = userf.pk_id', 'LEFT');
		$this->db->join('attribute_values', 'main_table.value = attribute_values.pk_id', 'LEFT');
		$this->db->join('company', 'main_table.value = company.pk_id', 'LEFT');
        $this->db->select(' userf.login, attribute_values.value as val,  company.name as company');
    }
	
	public function applyFilters($filters)
    {
        if (!is_array($filters)) return $this;
       // if (isset($filters['pk_id']) && $filters['pk_id'] != "")
      //          $this->addFilterByField('pk_id =', $filters['pk_id']);

        if (isset($filters['user_id']) && $filters['user_id'] != "")
                $this->addFilterByField('userf.login LIKE', '%' . $filters['user_id'] . '%');

        if (isset($filters['code_atr']) && $filters['code_atr'] != "")
                $this->addFilterByField('code_atr LIKE', '%' . $filters['code_atr'] . '%');

        if (isset($filters['value']) && $filters['value'] != "")
            {  //  $this->addFilterByField('attribute_values.value LIKE', '%' . $filters['value'] . '%');
			$this->db->like('attribute_values.value', $filters['value']);
			$this->db->or_like('company.name', $filters['value']);}
		
		if (isset($filters['mnog'])) {
            if ($filters['mnog']['from'] != "" && $filters['mnog']['to'] != "") {
                $this->addFilterByField('mnog BETWEEN ' . $filters['mnog']['from'] . ' AND ', $filters['mnog']['to']);
            }
            elseif ($filters['mnog']['from'] != "") {
                $this->addFilterByField('mnog > ', $filters['mnog']['from']);
            }
            elseif ($filters['mnog']['to'] != "") {
                $this->addFilterByField('mnog < ', $filters['mnog']['to']);
            }
        }
		
    }
	
}