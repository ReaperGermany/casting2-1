<?php

class Offer extends Main_model
{
    const TYPE_OFFER = "offer";
    const TYPE_REQUEST = "request";
    const TYPE_OFFER_DEFAULT_PRICE = -1;
    const TYPE_REQUEST_DEFAULT_PRICE = 100000000;

    const STATUS_ACTIVE = "active";
    const STATUS_ARHIVE = "arhive";


    private $default_currency = 'USD';
    private $profit_state = array('profitable', '');
    private $_origin_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->_init('offers');
    }

    public function getDefaultCurrency()
    {
        return $this->default_currency;
    }

    public function prepareOriginData()
    {
        $this->_origin_data = $this->_data;
        return $this;
    }

    protected function _beforeLoad()
    {
        parent::_beforeLoad();

        $this->db->join('staff s', 'main_table.fk_staff = s.pk_id', 'LEFT');
        $this->db->join('company c', 's.fk_company = c.pk_id', 'LEFT');

        $this->db->join('phones p', 'main_table.fk_phone = p.pk_id', 'LEFT');
        $this->db->join('attribute_values avm', 'p.fk_model = avm.pk_id', 'LEFT');
        $this->db->join('attribute_values avc', 'p.fk_color = avc.pk_id', 'LEFT');
        $this->db->join('attribute_values avs', 'p.fk_spec = avs.pk_id', 'LEFT');
        $this->db->join('attribute_values avman', 'p.fk_manufacturer = avman.pk_id', 'LEFT');

        $this->db->join('attribute_values av', 'main_table.fk_currency = av.pk_id', 'LEFT');
        $this->db->join('currency_rates cr', 'main_table.fk_currency = cr.fk_currency', 'LEFT');
        $this->db->select('
                    p.*,
                    main_table.*,
                    av.value currency_code,
                    avc.value color,
                    avs.value spec,
                    avman.value manufacturer,
                    cr.rate currency_rate,
                    s.appeal,
                    c.name company_name,
                    c.pk_id company_id,
                    avm.value model,
					s.email email');

//        $this->db->join('attribute_values av','main_table.fk_currency = av.pk_id','LEFT');
//        $this->db->join('currency_rates cr','main_table.fk_currency = cr.fk_currency','LEFT');
//        $this->db->select('av.value currency_code, cr.rate currency_rate, s.*, c.name company_name, main_table.pk_id');
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();
        $this->prepareOriginData();
        if (self::TYPE_REQUEST_DEFAULT_PRICE == $this->getData('price') || self::TYPE_OFFER_DEFAULT_PRICE == $this->getData('price')) {
            $this->setData('price', '');
        }
    }

    protected function _beforeLoadCollection()
    {
        parent::_beforeLoadCollection();

        $this->db->join('staff s', 'main_table.fk_staff = s.pk_id', 'LEFT');
        $this->db->join('company c', 's.fk_company = c.pk_id', 'LEFT');
        $this->db->join('phones p', 'main_table.fk_phone = p.pk_id', 'LEFT');
        $this->db->join('attribute_values avm', 'p.fk_model = avm.pk_id', 'LEFT');
        $this->db->join('attribute_values avc', 'p.fk_color = avc.pk_id', 'LEFT');
        $this->db->join('attribute_values avs', 'p.fk_spec = avs.pk_id', 'LEFT');
        $this->db->join('attribute_values avman', 'p.fk_manufacturer = avman.pk_id', 'LEFT');
		$this->db->join('userf us', 'main_table.user_id = us.pk_id', 'LEFT');
        $this->db->join('attribute_values av', 'main_table.fk_currency = av.pk_id', 'LEFT');
        $this->db->join('currency_rates cr', 'main_table.fk_currency = cr.fk_currency', 'LEFT');
        $this->db->select('
                    main_table.price * cr.rate as base_price,
                    av.value currency_code,
                    avc.value color,
                    avs.value spec,
					us.login login,
                    avman.value manufacturer,
                    cr.rate currency_rate,
                    s.appeal,
                    c.name company_name,
                    avm.value model,
					s.email email');
		
		//if(!isset($this->group)) $this->db->group_by('avm.value');
		//if(isset($this->group)) if($this->group) $this->db->group_by('avm.value');
    }

    protected function _afterLoadCollection($collection)
    {
        $collection = parent::_afterLoadCollection($collection);
        foreach ($collection as $item) {
            $item->prepareOriginData();
            if (self::TYPE_REQUEST_DEFAULT_PRICE == $item->getData('price') || self::TYPE_OFFER_DEFAULT_PRICE == $item->getData('price')) {
                $item->setData('price', '');
            }
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
        if ($filters['date']['from'] != "" && $filters['date']['to'] != "") {
            $this->addFilterByField('date BETWEEN ' . strtotime($filters['date']['from']) . ' AND ', strtotime($filters['date']['to']) + 3600*24);
        }
        elseif ($filters['date']['from'] != "") {
            $this->addFilterByField('date >= ', strtotime($filters['date']['from']));
        }
        elseif ($filters['date']['to'] != "") {
            $this->addFilterByField('date <= ', strtotime($filters['date']['to']) + 3600*24);
        }

        if (isset($filters['updated_at']['from'])) {
            $filters['updated_at']['from'] = str_replace("\\", "-", $filters['updated_at']['from']);
        }
        if (isset($filters['updated_at']['to'])) {
            $filters['updated_at']['to'] = str_replace("\\", "-", $filters['updated_at']['to']);
        }
        if (isset($filters['updated_at']['from']) && isset($filters['updated_at']['to']) && $filters['updated_at']['from'] != "" && $filters['updated_at']['to'] != "") {
            $this->addFilterByField('updated_at BETWEEN ' . strtotime($filters['updated_at']['from']) . ' AND ', date("Y-m-d H:i:s", strtotime($filters['updated_at']['to']) + 3600*24));
        }
        elseif (isset($filters['updated_at']['from']) && $filters['updated_at']['from'] != "") {
            $this->addFilterByField('updated_at >= ', date("Y-m-d H:i:s", strtotime($filters['updated_at']['from'])));
        }
        elseif (isset($filters['updated_at']['to']) && $filters['updated_at']['to'] != "") {
            $this->addFilterByField('updated_at <= ', date("Y-m-d H:i:s", strtotime($filters['updated_at']['to']) + 3600*24));
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
    }

    protected function _beforeSave()
    {
        $this->setData("updated_at", date("Y-m-d H:i:s"));
        parent::_beforeSave();
    }

    protected function _afterSave()
    {
        if (!count($this->_origin_data) || $this->_origin_data['price'] != $this->getData("price") || $this->_origin_data['fk_currency'] != $this->getData("fk_currency")) {
            Core::getModel("price_history")->setData(
                array(
                    "offer_id" => $this->getId(),
					"fk_staff" => $this->getData('fk_staff'),
                    "price" => $this->getData("price"),
                    "currency_id" => $this->getData("fk_currency")
                )
            )
            ->save();
        }
        $this->prepareOriginData();
    }

    public function save($checkDublicate =false)
    {
        if (!$this->getId() && $checkDublicate) {
            $doubles = $this->addFilterByField('fk_phone', $this->getData('fk_phone'))
                            ->addFilterByField('fk_staff', $this->getData('fk_staff'))
                            ->addFilterByField('type', $this->getData('type'))
                            ->addFilterByField('main_table.status', self::STATUS_ACTIVE)
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