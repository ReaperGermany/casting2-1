<?php

/**
 * Общий класс для всех атрибутов
 */
class Attribute_value extends Main_model
{

    const CODE_COLOR = "color";
    const CODE_MODEL = "model";
    const CODE_MANUFACTURER = "manufacturer";
    const CODE_SPEC = "spec";
    const CODE_CURRENCY = "currency";

    protected $_labels = array(
        self::CODE_COLOR =>'Color',
        self::CODE_MODEL =>'Model',
        self::CODE_MANUFACTURER =>'Manufacturer',
        self::CODE_SPEC =>'Spec',
        self::CODE_CURRENCY =>'Currency'
    );

    protected static $_required = array(
        self::CODE_MODEL,
        self::CODE_MANUFACTURER,
        self::CODE_CURRENCY
    );
    
    protected $_attribute_code;

    public function  __construct()
    {
        parent::__construct();
        $this->_init('attribute_values');
    }

    public static function isRequired($code)
    {
        return (in_array($code, self::$_required));
    }

    public function getAttributeCode()
    {
        if ($this->_attribute_code)
            return $this->_attribute_code;
        return $this->getData('code');
    }

    public function setAttributeCode($code)
    {
        $this->_attribute_code = $code;
        return $this;
    }

    protected function  _beforeLoad()
    {


        parent::_beforeLoad();
        //$this->db->join('attributes a','a.pk_id = main_table.fk_attribute', 'left');
        if ($this->_attribute_code){
            $this->db->where('main_table.code',$this->_attribute_code);
        }
    }

    public function prepareRequires()
    {
        $requires = array();
        if ($this->getAttributeCode() == self::CODE_MODEL) {
            $query = $this->db
                    ->from('requires')
                    ->join('attribute_values','requires.fk_value=attribute_values.pk_id','left')
                    ->where('fk_model',$this->getId())
                    ->get();

            foreach($query->result() as $row){
                $requires[$row->code][] = $row->fk_value;
            }
        }

        $this->setData('requires',$requires);
    }

    protected function  _afterLoad()
    {
        parent::_afterLoad();
        $this->prepareRequires();
    }

    protected function _afterSave()
    {
        parent::_afterSave();
        $requires=$this->getData('requires');
        if (!is_array($requires) || $this->getAttributeCode() !== self::CODE_MODEL) return;
        $this->db->delete('requires',array('fk_model'=>$this->getId()));
        foreach ($requires as $code => $values)
        {
            $this->_saveRequires($code, $values);
        }
    }

    protected function _saveRequires($code, $values)
    {
        if (!is_array($values)) return;
        foreach ($values as $value)
        {
            $this->db->insert('requires',array('fk_model'=>$this->getId(),'fk_value'=>$value));
        }
    }

    protected function  _beforeLoadCollection()
    {
        if (!count($this->_order)) {
            $this->addOrderByField('value');
        }
		
        parent::_beforeLoadCollection();
		if (isset($this->_subscribe)){
		if ($this->_subscribe)
		{
			$this->db->join('sends snd', 'main_table.pk_id = snd.manufacturer_id AND snd.user_id = '.$this->id_user, 'LEFT');
			$this->db->select('main_table.* , snd.status status');
			
		}
		}
        if ($this->_attribute_code){
			$this->db->where('main_table.code',$this->_attribute_code);
        }
    }

    public function getCollection()
    {
        $retval = parent::getCollection();
        foreach ($retval as $item){
            $item->prepareRequires();
        }
        return $retval;
    }

    public function getCodesList()
    {
        return array(
              //  self::CODE_COLOR,
                self::CODE_MANUFACTURER,
				self::CODE_MODEL,
               // self::CODE_SPEC,
                self::CODE_CURRENCY
            );
    }

    public function getLabel($code ="")
    {
        if ($code=="") $code=$this->getAttributeCode();
        if (isset($this->_labels[$code])) return $this->_labels[$code];

        return $code;
    }
}
