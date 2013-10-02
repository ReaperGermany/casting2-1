<?php

class Import extends Main_model
{
    private $errors = array();
    private $headers = array();
    private $fp = null;
    private $attrs = array();
    private $current_row = 0;
    private $log_file = null;
    private $log_file_name = null;
    private $saved_rows = 0;

    public function  __construct()
    {
        parent::__construct();
        $this->log_file_name = 'logs/'.date('Y-m-d_H-i-s').'.log';
        $this->log_file = fopen($this->log_file_name,'a+');
        if ($this->log_file)
            fputs($this->log_file,"============= Import Started at ".date('d-M-Y H:i:s')." =============\n\n");
    }

    /**
     * Парсит входной файл
     *
     * @param file $fp
     * @return array
     */
    public function parceFile($fp)
    {
        $this->fp = $fp;
        $this->_prepareAttrs();
        $this->_loadHeaders();
        while ($row = $this->getRow()) {
            $data = array();
			$data = $this->_applyAttrs($row);
            $this->saveRow($data);
        }

        return array('total_rows' => $this->current_row, 'saved_rows' => $this->saved_rows);
    }

    public function getLogFile()
    {
        return $this->log_file_name;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function saveRow($data)
    {
        if (trim($data['price'])=="") {
            $this->addError("Price is empty");
        }
		$data['type'] = "offer";
        if (!in_array($data['type'],array('offer', 'request'))) {
            $this->addError("Wront offer type");
        }

        if ($this->hasCurrentErrors()) return;

        //$data['date'] = time();
		$data['fk_color'] = 0;
		$data['fk_spec'] = 0;
        $data['status'] = 'active';
        $phone = $this->getModel('Phone');
        $phone->setData($data)->save();
        $data['fk_phone'] = $phone->getId();
        $offer = $this->getModel('Offer');
        if ($offer->setData($data)->save(true)) {
            $this->saved_rows++;
            $this->updateRequires($data);
            return $offer->getId();
        }
        else {
            $this->addError($offer->getError());
        }
        return false;
    }

    private function updateRequires($data)
    {
        $model = $this->getModel('Attribute_value')->setAttributeCode('model')->load($data['fk_model']);
        if ($model->getId()) {
            $requires = $model->getData('requires');
            $new_requires = array(
               // Attribute_value::CODE_COLOR => array($data['color']),
                Attribute_value::CODE_MANUFACTURER => array($data['fk_manufacturer']),
               // Attribute_value::CODE_SPEC => array($data['fk_spec']),
            );
            $model->setData('requires',array_merge($requires,$new_requires));
            $model->save();
        }
    }

    private function getRow()
    {
        if ($row = fgetcsv($this->fp))
        {
            if (count($row) != count($this->headers)) {
                if (count($row) != 1) {
                    $this->current_row++;
                    $this->addError('Wrong line format');
                }
                return $this->getRow();
            }
            $this->current_row++;
            $row = $this->_applyHeaders($row);
            return $row;
        }
        return false;
    }

    private function log($string, $type ="ERROR")
    {
        if ($this->log_file) {
            fprintf($this->log_file, "%s in line #%d - %s\n", $type, $this->current_row, $string );
        }

        return $this;
    }

    private function addError($error)
    {
        $this->log($error);
        if (!isset($this->errors[$this->current_row])) {
            $this->errors[$this->current_row] = array();
        }
        $this->errors[$this->current_row][] = $error;

        return $this;
    }

    private function hasCurrentErrors()
    {
        return isset($this->errors[$this->current_row]);
    }

    /**
     * Вычитывает и сохраняет заголовки полей из входного файла
     *
     * @return Import
     */
    private function _loadHeaders()
    {
        $this->headers = fgetcsv($this->fp);
        foreach ($this->headers as $k=>$v)
        {
            $this->headers[$k] = strtolower($v);
        }
        return $this;
    }

    /**
     * Применяет заголовки к полям
     *
     * Формирует ассоциативный массив. Ключами в массиве выступают значения из первой строки файла.
     * Значение берутся из входного массива.
     *
     * @param array $row
     * @return array
     */
    private function _applyHeaders($row)
    {
        $retval = array();
        foreach($this->headers as $key=>$field) {
            $retval[$field] = isset($row[$key])?$row[$key]:null;
        }
        return $retval;
    }

    /**
     * Для значений всех полей подставляет id, под которым значение хранится в базе.
     *
     * @param array $row
     * @return array
     */
    private function _applyAttrs($row)
    {
        /**
         * Обработка всех записей, кроме сотрудника
         */
        $retval = array();
        foreach ($row as $field => $value) {
            if ($field == 'offeror' || $field == 'date') continue;
			if (!isset($this->attrs[$field])) $retval[$field] = $value;
            else {
                //$value = strtolower($value);
                if (isset($this->attrs[$field][strtolower($value)])) {$retval["fk_".$field] = $this->attrs[$field][strtolower($value)];}
                elseif ($value == "") {
                    if (Attribute_value::isRequired($field)) {
                        $this->addError('Required filed "'.$field.'" is empty');
                    }
                    else {
                        $retval["fk_".$field] = 0;
                    }
                }
				elseif ($field == "manufacturer" || $field == "model") {
					$av = $this->getModel('Attribute_value');
					$id = $av->setData('code', $field)->setData('value', $value)->save()->getId();
					$this->attrs[$field][strtolower($value)] = $id;;
					$retval["fk_".$field] = $id;
				}
                else {
                    $retval["fk_".$field] = null;
                    $this->addError('Unknown value: "'.$value.'" in field "'.$field.'"');
                }
			}
        }

        $retval['date'] = time();//strtotime($row['date']);  //2013/03/13

        /**
         * Обработка данных сотрудника. Учитывается принадлежность сотрудника к компании.
         */
        if (isset($this->attrs['staff'][strtolower($row['offeror'])]))
        {
            $retval['fk_staff'] = $this->attrs['staff'][strtolower($row['offeror'])];
        }
        elseif ($row['offeror']!=""){
			$st = $this->getModel('Staff');
			$id = $st->setData('email', $row['offeror'])->save()->getId();
			$retval['fk_staff'] = $id;
			$this->attrs['staff'][strtolower($row['offeror'])] = $id;
		}
		else {
            $retval['fk_staff'] = null;
            $this->addError('Unknown value: "'.$value.'" in field "offeror"');
        }
        return $retval;
    }

    /**
     * Формирует массив, который будет использоваться для подстановки id вместо полей исходной строки.
     */
    private function _prepareAttrs()
    {
        $av = $this->getModel('Attribute_value');
        foreach($av->getCodesList() as $code) {
            $$code = $this->getModel('Attribute_value',$code);
            $items = $$code->setAttributeCode($code)->getCollection();
            $retval = array();
            foreach ($items as $item) {
                $retval[strtolower($item->getData('value'))] = $item->getId();
            }
            $this->attrs[$code] = $retval;
        }
        $staff = $this->getModel('Staff');
        $items = $staff->getCollection();
        $retval = array();
        foreach ($items as $item) {
			   $retval[strtolower($item->getData('email'))] = $item->getId();
        }
        $this->attrs['staff'] = $retval;
    }

    /**
     * Загрузка модели.
     *
     * @param string $model
     * @return mixed
     */
    private function getModel($model)
    {
        $aliace = "import_load_".$model;
        if (!isset($this->$aliace)) {
            $this->load->model($model,$aliace);
            $CI =& get_instance();
            $this->$aliace = $CI->$aliace;
        }

        $this->$aliace->setId(null);
        return $this->$aliace;
    }
}
