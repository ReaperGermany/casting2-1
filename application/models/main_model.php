<?php

/**
 *
 */
class Main_model extends Model
{

    protected $_table_name;
    protected $_id_field = 'pk_id';
    protected $_data = array();
    protected $_filter = array();
    protected $_order = array();

    /**
     * Получение списка полей, подлежащих сохранению
     * 
     * @return array
     */
    protected function _getFieldsForSave()
    {
        $fields = $this->db->list_fields($this->_table_name);
        return $fields;
    }

    public function __construct()
    {
        parent::Model();
    }

    /**
     * Установка таблицы для объекта
     *
     * @param string $table_name
     * @return Main_model
     */
    protected function _init($table_name)
    {
        $this->_table_name = $table_name;
        return $this;
    }

    protected function _beforeLoad() {}
    protected function _afterLoad() {}

    /**
     * Получение данных объекта из базы
     *
     * @param int $id
     * @return Main_model
     */
    public function load($id)
    {
        $this->db
                    ->from($this->_table_name.' main_table')
                    ->select('main_table.*')
                    ->where('main_table.'.$this->_id_field,$id);
        $this->_beforeLoad();
        $query = $this->db->limit(1)->get();
        $this->setData($query->row_array());
        $this->_afterLoad();

        return $this;
    }

    protected function _beforeSave() {}

    /**
     * Сохранение данных объекта в базу
     *
     * @return Main_model
     */
    public function save()
    {
        $fields = $this->_getFieldsForSave();
        if (!count($fields)) {
            $this->save_result = false;
            return $this;
        }

        $this->_beforeSave();
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->getData($field);
        }

        if ($id = $this->getId()) {
            $this->db->set($data);
            $this->db->where($this->_id_field,$id);
            $this->db->update($this->_table_name);
        }
        else {
            foreach ($data as $k => $v) {
                if (is_null($v)) unset($data[$k]);
            }
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $this->setId($this->db->insert_id());
        }
        $this->_afterSave();
        return $this;
    }

    protected function _afterSave() {}

//    public function __get($var_name)
//    {
//        return $this->getData($var_name);
//    }
//
//    public function __set($var_name, $value)
//    {
//        return $this->setData($var_name, $value);
//    }

    public function getId()
    {
        return $this->getData($this->_id_field);
    }

    public function setId($id)
    {
        return $this->setData($this->_id_field, $id);
    }

    /**
     * Получение данных по ключу
     *
     * @param numeric|string $var_name
     * @param mixed $default_value
     * @return mixed
     */
    public function getData($var_name =NULL, $default_value =NULL)
    {
        if (is_null($var_name)) {
            return $this->_data;
        }

        if (isset($this->_data[$var_name])) {
            return $this->_data[$var_name];
        }

        return $default_value;
    }

    /**
     *
     * @param array|numeric|string $data
     * @param mixed $value
     * @return Main_model
     */
    public function setData($data, $value =NULL)
    {
        if (is_array($data)) {
            $this->_data = $data;
        }
        elseif (!is_null($data)) {
            $this->_data[$data] = $value;
        }

        return $this;
    }

    public function delete($ids)
    {
        if (!is_array($ids)) $ids = array($ids);
        $this->db->where_in($this->_id_field,$ids);
        $this->db->delete($this->_table_name);
        return $this;
    }

    public function getCollection()
    {
        $retval = array();
        $this->db
                    ->from($this->_table_name.' main_table')
                    ->select('main_table.*');
        $this->_beforeLoadCollection();
        $query = $this->db->get();
        //$this->setData($query->row_array());
        foreach ($query->result_array() as $row) {
            $model = clone $this;
            $retval[] = $model->setData($row);
        }
        
	return $this->_afterLoadCollection($retval);
        //return $retval;
    }

    public function addFilterByField($field_name, $value)
    {
        $this->_filter[$field_name] = $value;
        return $this;
    }

    public function resetFilter()
    {
        $this->_filter = array();
        return $this;
    }

    public function addOrderByField($field_name, $direction ='asc')
    {
        if (!in_array($direction,array('asc','desc','random'))) $direction = 'asc';
        if ($field_name) {
            $this->_order[] = $field_name." ".$direction;
        }

        return $this;
    }

    protected function _beforeLoadCollection()
    {
        foreach ($this->_filter as $key=>$filter)
        {
            if (is_array($filter)) {
                $this->db->where_in($key, $filter);
                unset($this->_filter[$key]);
            }
        }
        if (count($this->_filter)){
            $this->db->where($this->_filter);
        }
        if (count($this->_order)){
            $this->db->order_by(implode(',',$this->_order));
        }
    }

    protected function _afterLoadCollection($collection)
    {
        return $collection;
    }
}