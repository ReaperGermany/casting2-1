<?php

class Offer_front extends Main_model
{
    const TYPE_OFFER = "offer";
    const TYPE_REQUEST = "request";
    const TYPE_OFFER_DEFAULT_PRICE = -1;
    const TYPE_REQUEST_DEFAULT_PRICE = 100000000;

    const STATUS_ACTIVE = "active";
    const STATUS_ARHIVE = "arhive";


    private $default_currency = 'USD';
    private $profit_state = array('profitable', '');

    public function __construct()
    {
        parent::__construct();
        $this->_init('offers');
    }

    public function getDefaultCurrency()
    {
        return $this->default_currency;
    }

    protected function _beforeLoad()
    {
        parent::_beforeLoad();

        $this->db->join('staff s', 'main_table.fk_staff = s.pk_id', 'LEFT');
        $this->db->join('company c', 's.fk_company = c.pk_id', 'LEFT');
		//$this->db->join('nacenka ncom', 'c.pk_id = ncom.value AND ncom.user_id = '.$this->id_user, 'LEFT');
        $this->db->join('phones p', 'main_table.fk_phone = p.pk_id', 'LEFT');
        $this->db->join('attribute_values avm', 'p.fk_model = avm.pk_id', 'LEFT');
        $this->db->join('attribute_values avc', 'p.fk_color = avc.pk_id', 'LEFT');
        $this->db->join('attribute_values avs', 'p.fk_spec = avs.pk_id', 'LEFT');
        $this->db->join('attribute_values avman', 'p.fk_manufacturer = avman.pk_id', 'LEFT');

        $this->db->join('attribute_values av', 'main_table.fk_currency = av.pk_id', 'LEFT');
		//$this->db->join('nacenka nid', 'main_table.pk_id = nid.value AND nid.user_id = '.$this->id_user, 'LEFT');
        $this->db->join('currency_rates cr', 'main_table.fk_currency = cr.fk_currency', 'LEFT');
        $this->db->select('
                    p.*,
                    main_table.*,
                    av.value currency_code,
                    avc.value color,
                    avs.value spec,
                    avman.value manufacturer,'.
					//ncom.mnog nc_com,
					//nid.mnog nc_id,
                    'cr.rate currency_rate,
                    s.appeal,
                    c.name company_name,
                    c.pk_id company_id,
                    avm.value model');
					
		if($this->col_c) $this->db->where_not_in('avc.pk_id', $this->col_c);
		if($this->col_m) $this->db->where_not_in('avm.pk_id', $this->col_m);
		if($this->col_s) $this->db->where_not_in('avs.pk_id', $this->col_s);
		if($this->col_ma) $this->db->where_not_in('avman.pk_id', $this->col_ma);
		if($this->col_cp) $this->db->where_not_in('s.pk_id', $this->col_cp);
		
//        $this->db->join('attribute_values av','main_table.fk_currency = av.pk_id','LEFT');
//        $this->db->join('currency_rates cr','main_table.fk_currency = cr.fk_currency','LEFT');
//        $this->db->select('av.value currency_code, cr.rate currency_rate, s.*, c.name company_name, main_table.pk_id');
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();
        if (self::TYPE_REQUEST_DEFAULT_PRICE == $this->getData('price') || self::TYPE_OFFER_DEFAULT_PRICE == $this->getData('price')) {
            $this->setData('price', '');
        }
		
		if (!($this->getData('send.user_id')>0))  
				{$this->setData('send.user_id', 0);}
		/*	
			if ($this->getData('nc_id')) 
				{
				$this->setData('price', round($this->getData('price')+$this->getData('base_price')*$this->getData('nc_id')/100,2));
				}
				elseif ($this->getData('nc_com')) 
				{
				$this->setData('price', round($this->getData('price')+$this->getData('price')*$this->getData('nc_com')/100,2));}
			*/
			// qty pereschet with factor qty
			if ($this->getData('qty')<2 || round($this->getData('qty')*$this->getData('qty_factor'))<2) 
			{$this->setData('qty', $this->getData('qty'));} 
			else 
			{ $this->setData('qty', round($this->getData('qty')*$this->getData('qty_factor')));}
    }

    protected function _beforeLoadCollection()
    {
        parent::_beforeLoadCollection();

        $this->db->join('staff s', 'main_table.fk_staff = s.pk_id', 'LEFT');
        $this->db->join('company c', 's.fk_company = c.pk_id', 'LEFT');
		//$this->db->join('nacenka ncom', 'c.pk_id = ncom.value AND ncom.user_id = '.$this->id_user, 'LEFT');
        $this->db->join('phones p', 'main_table.fk_phone = p.pk_id', 'LEFT');
        $this->db->join('attribute_values avm', 'p.fk_model = avm.pk_id', 'LEFT');
        $this->db->join('attribute_values avc', 'p.fk_color = avc.pk_id', 'LEFT');
        $this->db->join('attribute_values avs', 'p.fk_spec = avs.pk_id', 'LEFT');
        $this->db->join('attribute_values avman', 'p.fk_manufacturer = avman.pk_id', 'LEFT');
        $this->db->join('attribute_values av', 'main_table.fk_currency = av.pk_id', 'LEFT');
        $this->db->join('currency_rates cr', 'main_table.fk_currency = cr.fk_currency', 'LEFT');
		//$this->db->join('nacenka nid', 'main_table.pk_id = nid.value AND nid.user_id = '.$this->id_user, 'LEFT');
		$this->db->join('sends send', 'main_table.pk_id = send.offer_id AND send.user_id = '.$this->id_user, 'LEFT');
        $this->db->select('
                    main_table.price * cr.rate as base_price,
					main_table.pk_id id,
                    av.value currency_code,
                    avc.value color,
                    avs.value spec,
                    avman.value manufacturer,'.
					//ncom.mnog nc_com,
					//nid.mnog nc_id,
                    'cr.rate currency_rate,
                    s.appeal,
					send.user_id user_id,
                    c.name company_name,
                    avm.value model');
		if(isset($this->col_update) && $this->_cron2) $this->db->where_in('p.fk_manufacturer', $this->col_update);
		if($this->col_c) $this->db->where_not_in('avc.pk_id', $this->col_c);
		if($this->col_m) $this->db->where_not_in('avm.pk_id', $this->col_m);
		if($this->col_s) $this->db->where_not_in('avs.pk_id', $this->col_s);
		if($this->col_ma) $this->db->where_not_in('avman.pk_id', $this->col_ma);
		if($this->col_cp) $this->db->where_not_in('c.pk_id', $this->col_cp);
		
	//	if(!isset($this->group)) $this->db->group_by('avm.value');
	//	if(isset($this->group)) if($this->group) $this->db->group_by('avm.value');
    }

    protected function _afterLoadCollection($collection)
    {
        $collection = parent::_afterLoadCollection($collection);
        foreach ($collection as $item) 
            {
			if (self::TYPE_REQUEST_DEFAULT_PRICE == $item->getData('price') || self::TYPE_OFFER_DEFAULT_PRICE == $item->getData('price')) {
                $item->setData('price', '');
            }
			// price pereschet with nacenka
			/*if (!($item->getData('send.user_id')>0))  
				{$item->setData('send.user_id', 0);}
			
			if ($item->getData('nc_id')) 
				{
				$item->setData('price', round($item->getData('price')+$item->getData('base_price')*$item->getData('nc_id')/100,2));
				}
				elseif ($item->getData('nc_com')) 
				{
				$item->setData('price', round($item->getData('price')+$item->getData('price')*$item->getData('nc_com')/100,2));}
			*/
			// qty pereschet with factor qty
			if ($item->getData('qty')<2 || round($item->getData('qty')*$item->getData('qty_factor'))<2) 
			{$item->setData('qty', $item->getData('qty'));} 
			else 
			{ $item->setData('qty', round($item->getData('qty')*$item->getData('qty_factor')));}
			
			}
        
        return $collection;
    }

    public function getPhone()
    {
        if (!isset($this->phone)) {
            $this->load->model('Phone', 'phone');
            $CI = & get_instance();
            $this->phone = $CI->phone->load($this->getData('fk_phone'));
        }

        return $this->phone;
    }

    public function applyFilters($filters)
    {
        if (!is_array($filters)) return $this;
		if (isset($filters['subscribe']) && $filters['subscribe'] == "subscribe")
                $this->addFilterByField('send.user_id >', 0);
		
		//if (isset($filters['subscribe']) && $filters['subscribe'] == "unsubscribe")
       //         $this->addFilterByField('send.user_id ', '');
		
		
        if (isset($filters['model']) && $filters['model'] != "")
                $this->addFilterByField('avm.value LIKE', '%' . $filters['model'] . '%');

        if (isset($filters['manufacturer']) && $filters['manufacturer'] != "")
                $this->addFilterByField('avman.value LIKE', '%' . $filters['manufacturer'] . '%');

        if (isset($filters['color']) && $filters['color'] != "")
                $this->addFilterByField('avc.value LIKE', '%' . $filters['color'] . '%');

        if (isset($filters['spec']) && $filters['spec'] != "")
                $this->addFilterByField('avs.value LIKE', '%' . $filters['spec'] . '%');

        if (isset($filters['company_name']) && $filters['company_name'] != "")
                $this->addFilterByField('c.name LIKE', '%' . $filters['company_name'] . '%');

        if (isset($filters['status']) && $filters['status'] != "")
                $this->addFilterByField('main_table.status', $filters['status']);

//        if ($filters['appeal'] != "")
//            $this->addFilterByField('s.appeal LIKE','%'.$filters['appeal'].'%');

        if (isset($filters['date']['from'])) {
            $filters['date']['from'] = str_replace("\\", "-", $filters['date']['from']);
        }
        if (isset($filters['date']['to'])) {
            $filters['date']['to'] = str_replace("\\", "-", $filters['date']['to']);
        }
        if (isset($filters['date'])) {
		if ($filters['date']['from'] != "" && $filters['date']['to'] != "") {
            $this->addFilterByField('date BETWEEN ' . strtotime($filters['date']['from']) . ' AND ', strtotime($filters['date']['to']));
        }
        elseif ($filters['date']['from'] != "") {
            $this->addFilterByField('date > ', strtotime($filters['date']['from']));
        }
        elseif ($filters['date']['to'] != "") {
            $this->addFilterByField('date < ', strtotime($filters['date']['to']));
        }
		}
		/////// update at
		
		if (isset($filters['updated_at']['from']) && $filters['updated_at']['from']!='') {
		    $arr = '';
			$arr = explode("/", $filters['updated_at']['from']);
			if (isset($arr[1]) && isset($arr[0]) && isset($arr[2])) {
			$filters['updated_at']['from'] = date('Y-m-d H:i:s', mktime(0, 0, 0, (int)$arr[0], (int)$arr[1], (int)$arr[2]));}
			else {$filters['updated_at']['from']='';}			
        }
        if (isset($filters['updated_at']['to']) && $filters['updated_at']['to']!='') {
            $arr = '';
			$arr = explode("/", $filters['updated_at']['to']);
			if (isset($arr[1]) && isset($arr[0]) && isset($arr[2])) {
			$filters['updated_at']['to'] = date('Y-m-d H:i:s', mktime(0, 0, 0, (int)$arr[0], (int)$arr[1], (int)$arr[2]));}
			else {$filters['updated_at']['to']='';}
        }
        if (isset($filters['updated_at'])) {
		if ($filters['updated_at']['from'] != "" && $filters['updated_at']['to'] != "") {
            $this->addFilterByField('updated_at BETWEEN ' . $filters['updated_at']['from'] . ' AND ', $filters['updated_at']['to']);
        }
        elseif ($filters['updated_at']['from'] != "") {
            $this->addFilterByField('updated_at > ',$filters['updated_at']['from']);
        }
        elseif ($filters['updated_at']['to'] != "") {
            $this->addFilterByField('updated_at < ', $filters['updated_at']['to']);
        }
		}

        //cr.rate
        if (isset($filters['base_price'])) {
            if ($filters['base_price']['from'] != "" && $filters['base_price']['to'] != "") {
                $this->addFilterByField('price BETWEEN ' . $filters['base_price']['from'] . '*cr.rate AND ', $filters['base_price']['to'] . "*cr.rate");
            }
            elseif ($filters['base_price']['from'] != "") {
                $this->addFilterByField('price > ', $filters['base_price']['from'] . "*cr.rate");
            }
            elseif ($filters['base_price']['to'] != "") {
                $this->addFilterByField('price < ', $filters['base_price']['to'] . "*cr.rate");
            }
        }

        if (isset($filters['price'])) {
            if ($filters['price']['from'] != "" && $filters['price']['to'] != "") {
                $this->addFilterByField('price BETWEEN ' . $filters['price']['from'] . ' AND ', $filters['price']['to']);
            }
            elseif ($filters['price']['from'] != "") {
                $this->addFilterByField('price > ', $filters['price']['from']);
            }
            elseif ($filters['price']['to'] != "") {
                $this->addFilterByField('price < ', $filters['price']['to']);
            }
        }
        if (isset($filters['offer_price'])) {
            if ($filters['offer_price']['from'] != "" && $filters['offer_price']['to'] != "") {
                $this->addFilterByField('offer_price BETWEEN ' . $filters['offer_price']['from'] . ' AND ', $filters['offer_price']['to']);
            }
            elseif ($filters['offer_price']['from'] != "") {
                $this->addFilterByField('offer_price > ', $filters['offer_price']['from']);
            }
            elseif ($filters['offer_price']['to'] != "") {
                $this->addFilterByField('offer_price < ', $filters['offer_price']['to']);
            }
        }

        if (isset($filters['qty'])) {
            if ($filters['qty']['from'] != "" && $filters['qty']['to'] != "") {
                $this->addFilterByField('qty BETWEEN ' . $filters['qty']['from'] . ' AND ', $filters['qty']['to']);
            }
            elseif ($filters['qty']['from'] != "") {
                $this->addFilterByField('offer_price > ', $filters['qty']['from']);
            }
            elseif ($filters['qty']['to'] != "") {
                $this->addFilterByField('qty < ', $filters['qty']['to']);
            }
        }
		if (isset($filters['dt_from']) && isset($filters['dt_to'])) {
        if ($filters['dt_from'] != "" && $filters['dt_to'] != "") {
                $this->addFilterByField('updated_at <', $filters['dt_to']);
				$this->addFilterByField('updated_at >', $filters['dt_from']);
            }
		}
		
		if (isset($filters['dt_from2']) && isset($filters['dt_to2'])) {
        if ($filters['dt_from2'] != "" && $filters['dt_to2'] != "") {
                $this->addFilterByField('date <', (int)$filters['dt_to2']);
				$this->addFilterByField('date >', (int)$filters['dt_from2']);
            }
		}
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();
    }

    public function save($checkDublicate =false)
    {

        if ($checkDublicate) {
            $doubles = $this->addFilterByField('fk_phone', $this->getData('fk_phone'))
                            ->addFilterByField('fk_staff', $this->getData('fk_staff'))
                            ->addFilterByField('type', $this->getData('type'))
                            //->addFilterByField('qty', $this->getData('qty'))
                            //->addFilterByField('price', $this->getData('price'))
                            ->addFilterByField('main_table.status', self::STATUS_ACTIVE)
                            //->addFilterByField('date', $this->getData('date'))
                            ->getCollection();
            if (count($doubles)) {
                foreach ($doubles as $offer) {
                    if ($offer->getData('date') == $this->getData('date')) {
                        // Если несколько одинаковых заявок имеют одинаковое время добавления, то выводим ошибку
                        $this->error = "Offer is already in base";
                        return false;
                    }
                    elseif ($offer->getData('date') < $this->getData('date')) {
                        // Отправляем в архив устаревшие заявки
                        $offer->setData('status',self::STATUS_ARHIVE)->save();
                    }
                    else {
                        // Отправляем в архив добавляемую заявку, если в базе имеется более новая
                        $this->setData('status',self::STATUS_ARHIVE);
                    }
                }
            }
        }

        if (!$this->getData('price')) {
            if ($this->getData('type') == self::TYPE_OFFER) {
                $this->setData('price', self::TYPE_OFFER_DEFAULT_PRICE);
            }
            elseif ($this->getData('type') == self::TYPE_REQUEST) {
                $this->setData('price', self::TYPE_REQUEST_DEFAULT_PRICE);
            }
        }
        return parent::save();
    }

    public function getError()
    {
        return isset($this->error) ? $this->error : "";
    }

    public function getProfitStates()
    {
        return $this->profit_state;
    }

}