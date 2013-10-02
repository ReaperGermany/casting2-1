<?php

class Phone extends Main_model
{

    protected $_filter = array();
    protected $_attributes = array('model', 'manufacturer', 'color', 'spec');

    public function  __construct()
    {
        parent::__construct();
        $this->_init('phones');
    }

    protected function _beforeLoad()
    {
        parent::_beforeLoad();
        $this->db->select("av.value manufacturer, avm.value model, avc.value color, avs.value spec");
        $this->db->join("attribute_values av","main_table.fk_manufacturer = av.pk_id","left");
        $this->db->join("attribute_values avm","main_table.fk_model = avm.pk_id","left");
        $this->db->join("attribute_values avc","main_table.fk_color = avc.pk_id","left");
        $this->db->join("attribute_values avs","main_table.fk_spec = avs.pk_id","left");
    }

    public function save()
    {
        if ($phone = $this->loadByData()){
            $this->setId($phone->getId());
            return $this;
        }
        return parent::save();
    }

    public function loadByData($data =array())
    {
        if (count($data)) {
            $this->setData($data);
        }

        $data = $this->getData();
        if (!count($data)){
            return null;
        }

        foreach ($this->_getFieldsForSave() as $field)
        {
            if (isset($data[$field])) {
                $this->addFilterByField($field, $data[$field]);
            }            
        }
        
        foreach ($this->getCollection() as $phone) {
            return $phone;
        }
        return null;
    }

    public function applyMatchFilter($filter,$type =null)
    {
        if (!is_array($filter)) return $this;

        if (!$type && isset($filter['type'])) {
            $type = $filter['type'];
        }

        if (isset($filter['type'])) unset($filter['type']);

        if (isset($filter['company_name'])) unset($filter['company_name']);

        /**
         * Заменяем дату, переданную в текстовом виде, на timestamp
         */
        if (isset($filter['date'])) {
            if (isset($filter['date']['from']))
                $filter['date']['from'] = strtotime($filter['date']['from']);
            if (isset($filter['date']['to']))
                $filter['date']['to'] = strtotime($filter['date']['to']);
        }

        /**
         * Формирование массива для подстановки в запрос
         */
        foreach($filter as $k => $v) {
            $field = in_array($k, $this->_attributes)?$k:$type."_".$k;
            if (is_array($v)) {
                if (isset($v['from']) && $v['from']!="")
                    $this->_filter[$field." > "] = $v['from'];
                if (isset($v['to']) && $v['to']!="")
                    $this->_filter[$field." < "] = $v['to'];
            }
            else {
                if ($v !="" ) $this->_filter[$field." LIKE "] = "%".$v."%";
            }
        }

        return $this;
    }

    public function applyFilter($filter)
    {
        if (!is_array($filter)) return $this;

        if (isset($filter['offer_company_name'])) unset($filter['offer_company_name']);
        if (isset($filter['request_company_name'])) unset($filter['request_company_name']);
        if (isset($filter['manufacturer_id'])) unset($filter['manufacturer_id']);

        /**
         * Заменяем дату, переданную в текстовом виде, на timestamp
         */
        if (isset($filter['request_date'])) {
            if (isset($filter['request_date']['from']))
                $filter['request_date']['from'] = strtotime($filter['request_date']['from']);
            if (isset($filter['request_date']['to']))
                $filter['request_date']['to'] = strtotime($filter['request_date']['to']);
        }

        if (isset($filter['offer_date'])) {
            if (isset($filter['offer_date']['from']))
                $filter['offer_date']['from'] = strtotime($filter['offer_date']['from']);
            if (isset($filter['offer_date']['to']))
                $filter['offer_date']['to'] = strtotime($filter['offer_date']['to']);
        }

        /**
         * Формирование массива для подстановки в запрос
         */
        foreach($filter as $field => $v) {
            if (is_array($v)) {
                if (isset($v['from']) && $v['from']!="")
                    $this->_filter[$field." > "] = $v['from'];
                if (isset($v['to']) && $v['to']!="")
                    $this->_filter[$field." < "] = $v['to'];
            }
            else {
                if ($v !="" ) $this->_filter[$field." LIKE "] = "%".$v."%";
            }
        }

        return $this;
    }

    public function resetMatchFilter()
    {
        $this->_filter = array();
        return $this;
    }

    public function getMatches($diff =null)
    {
        $data['items'] = array();

        $this->db
                    ->from('matches'.' main_table')
                    ->select('  main_table.*,
                                of_company.name offer_company_name,
                                re_company.name request_company_name,
                                of_staff.appeal offer_staff,
                                re_staff.appeal request_staff
                    ')
                    ->join('staff of_staff','main_table.offer_staff_id = of_staff.pk_id','left')
                    ->join('company of_company','of_staff.fk_company = of_company.pk_id','left')
                    ->join('staff re_staff','main_table.request_staff_id = re_staff.pk_id','left')
                    ->join('company re_company','re_staff.fk_company = re_company.pk_id','left')
                    ;//->group_by('main_table.pk_id');

        if (!is_null($diff) && $diff!==false){
            $this->db->where('`request_base_price`-`offer_base_price` > ',$diff);
        }

        if (count($this->_filter)) $this->db->where($this->_filter);
        if (count($this->_order)){
            $this->db->order_by(implode(',',$this->_order));
        }

        $query = $this->db->get();
        $retval = array();

        foreach ($query->result_array() as $row) {
            $model = clone $this;
            $retval[] = $model->setData($row);
        }

        return $retval;
    }

    public function getMatchData($phone_id, $diff =null)
    {
        $data['items'] = array();

        $this->db
                    ->from('matches'.' main_table')
                    ->select('  main_table.*,
                                of_company.name offer_company, 
                                re_company.name request_company,
                                of_staff.appeal offer_staff, 
                                re_staff.appeal request_staff
                    ')
                    ->join('staff of_staff','main_table.offer_staff_id = of_staff.pk_id','left')
                    ->join('company of_company','of_staff.fk_company = of_company.pk_id','left')
                    ->join('staff re_staff','main_table.request_staff_id = re_staff.pk_id','left')
                    ->join('company re_company','re_staff.fk_company = re_company.pk_id','left')
                    ->where('main_table.pk_id',$phone_id);

        if (!is_null($diff) && $diff!==false){
            $this->db->where('`request_base_price`-`offer_base_price` > ',$diff);
        }
        $query = $this->db->get();
        $retval = array();

        foreach ($query->result_array() as $row) {
            $model = clone $this;
            $retval[] = $model->setData($row);
        }

        return $retval;
    }

}