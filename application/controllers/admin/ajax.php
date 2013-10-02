<?php
/**
 * @todo Уйти от использования данного класса. Разнести методы по своим классам.
 */
class Ajax extends Controller
{

    function __construct()
    {
        parent::Controller();
    }

    /**
     * Получение списка копманий
     */
    function getCompanyList()
    {
        $this->load->model('Company','company');
        $retval = array();
        foreach ($this->company->getCollection() as $company) {
            $retval[] = array(
                    'label' => $company->getData('name'),
                    'value' => $company->getData('name'),
                    'id'    => $company->getId()
                );
        }

        echo $this->prepareOutput($retval);
    }

    /**
     * Получение списка сотрудников для копмании
     */
    function getStaffList()
    {
        $this->load->model('Staff','staff');
        $retval = array();
        $this->staff->addFilterByField('fk_company', (int)$_POST['company']);
        foreach ($this->staff->getCollection() as $staff) {
			if ($staff->getData('fk_company')==(int)$_POST['company'])
			{
            $staff_data = $staff->getData();
            $staff_data['label'] = $staff->getData('appeal');
            $staff_data['value'] = $staff->getData('appeal');
            $staff_data['id'] = $staff->getId();
            $retval[] = $staff_data;
			}
        }

        echo $this->prepareOutput($retval);
    }
	
	function getStaff()
    {
        $this->load->model('Staff','staff');
        $retval = array();
        foreach ($this->staff->getCollection() as $staff) {
			if ($staff->getData('pk_id')==(int)$_POST['pk_id'])
			{
            $staff_data = $staff->getData();
            $staff_data['label'] = $staff->getData('appeal');
            $staff_data['value'] = $staff->getData('appeal');
            $staff_data['id'] = $staff->getId();
            $retval[] = $staff_data;
			}
        }

        echo $this->prepareOutput($retval);
    }

    /**
     *  Добавление новой компании
     *
     */
    function addCompany()
    {
        if (isset($_POST['name']) && count(trim($_POST['name']))) {
            $this->load->model('Company', 'company');
            $this->company->setData('name',$_POST['name'])->save();
            echo json_encode(array('id'=>$this->db->insert_id(), 'name'=>$this->company->getData('name')));
            return;
        }
        echo json_encode($_POST);
    }

    /**
     * Сохранение сотрудника
     */
    function saveStaff()
    {
        $this->load->model("Staff","staff");
        $this->staff->setData($_POST);
        $this->staff->setData('fk_company',(int)$_POST['company_id']);
        if (isset($_POST['staff_id']) && (int)$_POST['staff_id']) {
            $this->staff->setId((int)$_POST['staff_id']);
        }
        $this->staff->save();
        echo json_encode(array('id'=>$this->db->insert_id()));
    }
	
	function Offeror()
	{
		$this->load->model("Staff","staff");
			$this->load->model("Staff","staff3");
			$this->staff3->setData('email', $_POST['offeror'])->save();

		$retval = array();
        $retval['status'] = "OK";
		$retval['staff_id'] = $this->db->insert_id();
        echo json_encode($retval);
	}

    /**
     * Удаление заявок
     * @return <type>
     */
    public function deleteOffers()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
            $this->load->model('Offer','offer');
            $this->offer->delete($_POST['ids']);
            $retval['status'] = "OK";
            $data = $this->getData($_POST['type']);
            $retval['content'] = $this->load->view('admin/offer/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
	
	public function mergeOffer()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
			$ids = $_POST['ids'];
			$new_id = $_POST['new_id'];
			$this->load->model('Offer','offer2');
			$price = $this->offer2->load($new_id)->getData('price');
			$new_price = $price;
			foreach($ids as $id)
			{
				if (!($id==$new_id)) {
					$this->load->model('Offer','offer');
					if ($new_price>$this->offer->load($id)->getData('price')) $new_price=$this->offer->load($id)->getData('price');
					$this->offer->delete($id);
					$this->load->model('Price_history','history');
					$colect = $this->history
								->addFilterByField('offer_id', $id)
								->getCollection();
					foreach ($colect as $col)
					{
						$this->load->model('Price_history','hist');
						$this->hist->load($col->getData('pk_id'))->setData('offer_id', $new_id)->save();
					}
				}
			}
			if ($new_price<$price) {
				$this->load->model('Offer','offer3');
				$this->offer3->load($new_id)->setData('price', $new_price)->save();
			}
            $retval['status'] = "OK";
            //$data = $this->getData($_POST['type']);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }

	   public function deleteVisible()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
            $this->load->model('visible_model','vis');
            $this->vis->delete($_POST['ids']);
            $retval['status'] = "OK";
            $data = $this->getData($_POST['type']);
            $retval['content'] = $this->load->view('admin/visible/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
	
	  public function deleteNacenka()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
            $this->load->model('nacenka_model','nac');
            $this->nac->delete($_POST['ids']);
            $retval['status'] = "OK";
            $data = $this->getData($_POST['type']);
            $retval['content'] = $this->load->view('admin/nacenka/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }

    /**
     * Изменение полезности заявки
     * @return <type>
     */
    public function profitOffers()
    {
        $this->load->model('Offer','offer');
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }

        if (!isset($_POST['state']) || !in_array($_POST['state'],$this->offer->getProfitStates())) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }

        try {
            foreach ($_POST['ids'] as $id) {
                $this->offer->load($id)->setData('profit_state',$_POST['state'])->save();
            }
            $retval['status'] = "OK";
            $data = $this->getData($_POST['type']);
            $retval['content'] = $this->load->view('admin/offer/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){}
    }

    public function saveStockStatus()
    {
        $this->load->model('Offer','offer');
        $retval = array();
        $ids = $this->input->post('ids');
        if (!$ids || !count($ids)) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }

        try {
            $status = $this->input->post('status');
            foreach ($ids as $id) {
                $this->offer->load($id)->setData('sold_out',$status)->save();
            }
            $retval['status'] = "OK";
            $data = $this->getData($this->input->post('type'));
            $retval['content'] = $this->load->view('admin/offer/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){}

        $retval['status'] = "ERROR";
        echo json_encode($retval);
        return;
    }

    /**
     * Ручная отправка устаревших заявок в архив
     */
    public function toArhive()
    {
        $this->load->model('Offer','offer');
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }

	if (!isset($_POST['status']) || trim($_POST['status'])=='') {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }

        try {
            foreach ($_POST['ids'] as $id) {
                $this->offer->load($id)->setData('status',$_POST['status'])->save();
            }
            $retval['status'] = "OK";
            $data = $this->getData($_POST['type']);
            $retval['content'] = $this->load->view('admin/offer/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }

    /**
     * Подготовка списка ордеров к выводу
     *
     * @param string $type
     * @param array $ids
     * @return array
     */
    public function getData($type, $ids =array())
    {
        $data = array();
        $this->load->model('Offer','request');
        $offers_order = $this->session->userdata('order_offer');

        $filters = $this->getFilter($type);

        if (!is_array($offers_order)) {
            $offers_order = array('order'=>'date', 'dir'=>'desc');
        }

        if (isset($filters[$type])) {
            $this->request->applyFilters($filters[$type]);
        }
        if (count($ids)) {
            $this->request->addFilterByField('main_table.pk_id',$ids);
        }
        $data['items'] = $this->request
                        ->addOrderByField($offers_order['order'],$offers_order['dir'])
                        ->addFilterByField('type',$type)
                        //->addFilterByField('main_table.status',Offer::STATUS_ACTIVE)
                        ->getCollection();

        $data['type']  = $type;
        $data['orders'] = array($type=>$offers_order);
        $data['filters'] = $filters;

        return $data;
    }

    /**
     * Получение данных сортировки
     *
     * @param string $type
     * @return string
     */
    protected function getOrder($type)
    {
        $order = $this->session->userdata($type.'_offer');

        if (!is_array($order)) {
            $order = array('order'=>'date', 'dir'=>'desc');
        }

        return $order;
    }

    /**
     * Получение активных фильтров
     *
     * @param string $type
     * @return array
     */
    protected function getFilter($type)
    {
        $filters = $this->session->userdata('filter');
        if (!isset($filters[$type])) {
            $filters[$type] = array(
                'date' => array('from'=>date('m/d/Y',time()-3600*24*7),'to'=>""),
                'status' => Offer::STATUS_ACTIVE
            );
        }

        return $filters;
    }

    public function getEditOfferForm()
    {
        if (!isset($_POST['id']) || !(int)$_POST['id'])
        {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        $this->load->model('Offer', 'offer');
        $data['offer'] = $this->offer->load((int)$_POST['id']);
        $this->load->model('Attribute_value','av');
        foreach($this->av->getCodesList() as $code) {
            $this->load->model('Attribute_value',$code);
            $items = $this->$code->setAttributeCode($code)->getCollection();
            $retval = array();
            foreach ($items as $item) {
                $retval[$item->getId()] = array(
                                    'label' => $item->getData('value'),
                                    'value' => $item->getData('value'),
                                    'id'    => $item->getId()
                            );
            }
            $data['attrs'][$code] = $retval;
        }
        $this->load->model('Requires','req');
        $retval['attrs'] = $this->req->prepareAttrs($data['attrs']);

        $this->load->model('Company','company');
        $retval['attrs']['company']['require'] = false;
        foreach($this->company->getCollection() as $company) {
            $retval['attrs']['company']['data'][] = array('id'=>$company->getId(),
                                                    'value'=>$company->getData('name'),
                                                    'label'=>$company->getData('name'));
        }

        $this->load->model('Staff','staff');
        $retval['attrs']['staff']['require'] = false;
        foreach($this->staff->getCollection() as $staff) {
            $retval['attrs']['staff']['data'][$staff->getData('fk_company')][] = array('id'=>$staff->getId(),
                                                    'value'=>$staff->getData('email'),
                                                    'label'=>$staff->getData('email'));
        }

        $retval['content'] = $this->load->view('admin/offer/edit',$data,true);
        echo json_encode($retval);
    }

    public function getContactData()
    {
        if (!isset($_POST['id']) || !(int)$_POST['id'])
        {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        $id = $_POST['id'];
        $this->load->model("Offer","offer");
        $this->offer->load($id);
        $this->load->model("Staff",'staff');
        $this->staff->load($this->offer->getData('fk_staff'));
        $data = array("staff"=>$this->staff);
        $retval['content'] = $this->load->view('admin/offer/contact',$data,true);
        echo json_encode($retval);
    }

    public function getPriceHistory()
    {
        if (!(int)$this->input->post("id")) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        $id = $this->input->post("id");
		
		
		
        $history = Core::getModel("price_history")->addFilterByField("offer_id", $id)->getCollection();
        $data = array("history" => $history);
        $retval['content'] = $this->load->view('admin/offer/price/history',$data,true);
        echo json_encode($retval);
    }

    public function saveOffer()
    {
        $retval = array('success'=>0,'errors'=>0,'duble'=>0);
		$retval['message'] = 'In the database already contains rows ';
		$i = 1;
        foreach($_POST['row'] as $row) {
            $row['staff_id'] = $_POST['staff_id'];
            $row['type'] = 'offer'; //$_POST['type'];
			$row['type_flag'] = $_POST['type'];
            try{
                $id = $this->_saveOffer($row);
				if ($id) {$retval['success']++;}
				else {$retval['duble']++; $retval['message'] = $retval['message'].$i.', ';}
            }
            catch (Exception $e){
                $retval['errors']++;
            }
			$i++;
        }

        $retval['status'] = 'OK';

        echo json_encode($retval);
    }

    protected function _saveOffer($data)
    {
	$not_required = array('color', 'spec');
        foreach ($data['attribute'] as $code => $value)
        {
            if ($value['id']){
                $data['fk_'.$code] = $value['id'];
            }
            else {
        	if (!in_array($code, $not_required))
        	{
            	    throw new Exception('Отсутствует обязательный параметр');
            	}
			elseif ($value['id']=="")  $data['fk_'.$code] = 0;
            }
        }
        if (isset($data['price'])) {$data['price'] = floatval($data['price']);}

        /**
         * Вычисляем начало текущего дня. Нужно чтобы все записи в течение дня имели одинаковую временную метку.
         */
        $time = time();
        //$data['date'] = $time-$time%(3600*24);
        $data['date'] = $time;
        $data['status'] = 'active';
        $this->load->model('Phone','phone');
        $this->phone->setData($data)->save(true);
        $data['fk_phone'] = $this->phone->getId();
		$data['fk_staff'] = $data['staff_id'];
        $this->load->model('Offer','offer');
	/*	if (!(isset($data['offer_id']) && $data['offer_id'])){
			$flag_duble =$this->offer
					->addFilterByField('type', 'offer')
					->addFilterByField('fk_staff', $data['fk_staff'])
					->addFilterByField('fk_phone', $data['fk_phone'])
					->getCollection();
			if (count($flag_duble)) {return false;}
		
		}*/
		////////////////////////////////////////////////////////  proverka cen
		if ($data['type_flag']=='add'){
		$model_id = $this->phone->getData('fk_model');
		$manufacturer_id = $this->phone->getData('fk_manufacturer');
		$new_price =  $data['price'];
		$this->load->model('Offer','offer1');
		$model_colect = $this->offer1
					->addFilterByField('price <=', $new_price)
					->addFilterByField('p.fk_model', $model_id)
					->addFilterByField('main_table.status', 'active')
					->addFilterByField('p.fk_manufacturer', $manufacturer_id)
					->addOrderByField('price', 'asc')
					->getCollection();
		if (count($model_colect)) {
			//$data['price'] = $model_colect[0]->getData('price');
			$this->load->model('Price_history','price');
			$this->price->setData('offer_id',$model_colect[0]->getId())
						->setData('currency_id',$model_colect[0]->getData('fk_currency'))
						->setData('price',$new_price)
						->setData('created_at',$time)
						->setData('fk_staff',$model_colect[0]->getData('fk_staff'))
						->save();	
			$data['status'] = 'arhive';
		}
		else
		{
			$this->load->model('Offer','offers');
			$model_colect = $this->offers
					->addFilterByField('p.fk_model', $model_id)
					->addFilterByField('main_table.status', 'active')
					->addFilterByField('p.fk_manufacturer', $manufacturer_id)
					->addOrderByField('price', 'asc')
					->getCollection();
			/*foreach ($colect as $col)
			{
				$this->load->model('Offer','offer2');
				$this->offer2->load($col->getData('pk_id'))->setData('status', 'arhive')->save(true);			
			}*/
			if (count($model_colect)) {
			$model_colect[0]->setData('price', $new_price)->setData('fk_staff',$data['fk_staff'])->save();
			$data['status'] = 'arhive';
			}
		}
		}
		////////////////////////////////////////////////////////
        
        if (isset($data['offer_id']) && $data['offer_id']) {
            $this->offer->load($data['offer_id']);
        }
        $this->offer->setData($data);
        if (isset($data['offer_id']) && $data['offer_id']) {
            $this->offer->setId($data['offer_id']);
        }

        /**
         * Параметр указывает необходимо ли проверять уникальность записи перед добавелнием.
         * По-умолчанию используется false (не проверять)
         */
        $this->offer->save(true);
		$this->load->model('Offer','offers');
		if (count($model_colect)) {$this->offers->load($model_colect[0]->getId())->setData('status', 'active')->save();}
        return $this->offer->getId();
    }

    public function prepareOutput($data)
    {
        $retval = array();
        foreach ($data as $item) {
            $retval[] = json_encode($item);
        }

        return "[".implode(',',$retval)."]";
    }

    public function exportOffers()
    {
        /**
         * Массив соответствий "название поля в файле" => "название свойства объекта"
         */
        $headers = array(
                "type"=>"type",
                "manufacturer"=>"manufacturer",
                "model"=>"model",
                "price"=>"price",
                "currency"=>"currency_code",
                "USD price"=>"base_price",
                "color"=>"color",
                "spec"=>"spec",
                "notes"=>"notes",
                "company"=>"company_name",
                "staff"=>"email",
				"Offer price"=>"offer_price",
                "date"=>"date");

        /**
         * Обработка входных данных:
         *    - получение экспортируемого типа запросов
         *    - получение списка интересующих запросов (если имеется)
         */
        $get = $this->uri->uri_to_assoc(4);
        if ($get['ids'])
            $get['ids'] = explode('_',$get['ids']);
        else
            $get['ids'] = array();
		
		$nacenka = $get['nac'];
//        $get['ids'] = $this->input->post("ids");
//        $get['type'] = $this->input->post("type");

        /**
         * Получение самих запросов по заданным условиям
         */
        $data = $this->getData($get['type'], $get['ids']);

        /**
         * формирование csv файла во временном файле
         */
        $csv = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
        fputcsv($csv, array_keys($headers));
        foreach ($data['items'] as $item) {
            $row = array();
            $phone = $item->getPhone();
            $item->setData('date',date('Y/m/d',$item->getData('date')));
            foreach ($headers as $field)
            {
                if ($item->getData($field) && $field=="offer_price"){
                    $row[] = $item->getData($field) + $item->getData($field)*$nacenka/100;
                }
				elseif ($item->getData($field)){
                    $row[] = $item->getData($field);
                }
                elseif ($phone->getData($field)) {
                    $row[] = $phone->getData($field);
                }
                else {
                    $row[]="";
                }
            }

            fputcsv($csv, $row);
        }
        rewind($csv);
        $output = stream_get_contents($csv);
        //var_dump($output);

        $this->_sendUploadResponse($get['type'].'s('.date('d-M-Y').').csv', $output);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/force-download')
    {
        header('HTTP/1.1 200 OK');
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //header('Last-Modified', date('r'));
        header('Accept-Ranges: bytes');
        header('Content-Length: '. strlen($content));
        header('Content-Type: '. $contentType);
        header('Content-Disposition: attachment; filename="'.$fileName.'"'."\n\n");
        echo $content;
        
        die;
    }

}
