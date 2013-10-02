<?php
 
class Account extends Controller
{	
	var $col_cp = array();
	var $col_c = array();
	var $col_m = array();
	var $col_s = array();
	var $col_ma = array();
	var $id_user = "";

    function __construct()
    {
        parent::Controller();
    }
	
	function index()
    {
		$offers_order = $this->session->userdata('order_offer_front');
		if (!is_array($offers_order)) {
            $offers_order = array('order' => 'manufacturer', 'dir' => 'asc');
        }
		$this->load->model('Offer_front', 'offer');
		
		$filters = $this->session->userdata('filter');
		if (!isset($filters['offer_front'])) {
            $filters['offer_front'] = array(
                'date' => array('from' => "", 'to' => ""),
                'status' => Offer_front::STATUS_ACTIVE
            );
        }
		
		$this->setNacenkaUsers();
		
        $this->load->model('Offer_front', 'offer');
		
		$this->offer->applyFilters($filters['offer_front']);
        //////////////// nacenka price
		
		$collection = $this->offer
						//->addOrderByField($offers_order['order'], $offers_order['dir'])
						->addOrderByField('manufacturer', 'asc')
						->addOrderByField('model', 'asc')
                        ->addFilterByField('type', 'offer')
                        ->addFilterByField('main_table.status', Offer_front::STATUS_ACTIVE)
                        ->getCollection();
		$data['offers'] = $this->setNacenkaPrice($collection);
		$data['filters'] = $filters;
		$data['login'] = $this->session->userdata('login');
		$data['orders']['offer_front'] = $offers_order;
		$this->load->model('userf_model', 'user');
		$user = $this->user->addFilterByField('login', $this->session->userdata('login'))->getCollection();
		$this->load->model('Sends', 'sends');
		$subs = $this->sends->addFilterByField('user_id', $user[0]->getId())
					->addFilterByField('offer_id', 0)
					->addFilterByField('manufacturer_id', 0)
					->getCollection();
		if (count($subs)) {$subs_flag = $subs[0]->getData('status');}
		else {$subs_flag = 0;}
		$data['subs'] = $subs_flag;
		
		$this->load->model('Attribute_value', 'atr');
		$atr = $this->atr
					->addFilterByField('code', 'manufacturer')
					->addOrderByField('value', 'asc')
					->getCollection();
		$data['list'] = $atr; 
        $this->load->view('account', $data);
    }
	
	function setNacenkaUsers()
	{
		$login = $this->session->userdata('login');
		$this->load->model('userf_model', 'user');
		$Id = $this->user->addFilterByField('login', $login)->getCollection();
		$this->id_user = $Id[0]->getId();
		/////////////////////////////////////////////////
		$this->load->model('visible_model', 'vis');
		$Color = $this->vis->addFilterByField('user_id', $Id[0]->getId())
				->addFilterByField('code_atr', 'color')
				->getCollection();
		$i=0;
		foreach($Color as $color) {$this->col_c[$i]=$color->getData('value'); $i++;}
		//////////////////////////////////////////////////
		$Color = $this->vis->addFilterByField('user_id', $Id[0]->getId())
				->addFilterByField('code_atr', 'model')
				->getCollection();
		$i=0;
		foreach($Color as $color) {$this->col_m[$i]=$color->getData('value'); $i++;}
		//////////////////////////////////////////////////
		$Color = $this->vis->addFilterByField('user_id', $Id[0]->getId())
				->addFilterByField('code_atr', 'spec')
				->getCollection();
		$i=0;
		foreach($Color as $color) {$this->col_s[$i]=$color->getData('value'); $i++;}
		//////////////////////////////////////////////////
		$Color = $this->vis->addFilterByField('user_id', $Id[0]->getId())
				->addFilterByField('code_atr', 'manufacturer')
				->getCollection();
		$i=0;
		foreach($Color as $color) {$this->col_ma[$i]=$color->getData('value'); $i++;}
		//////////////////////////////////////////////////

		$Color = $this->vis->addFilterByField('user_id', $Id[0]->getId())
				->addFilterByField('code_atr', 'company_name')
				->getCollection();
		$i=0;
		foreach($Color as $color) {$this->col_cp[$i]=$color->getData('value'); $i++;}
	
	}
	
	function setNacenkaPrice($collection)
    {
		$this->load->model('nacenka_model', 'nac');
		$colect = $this->nac
					->addOrderByField('value', 'asc')
					->addFilterByField('user_id', 0)
					->addFilterByField('code_atr', '0')
					->getCollection();
		$count = count($colect);
		
		foreach($collection as $col)
		{
			$price = $col->getData('price');
			for($i=0; $i<$count; $i++)
			{
				if (($i==0) && ($price <= $colect[$i]->getData('value'))) 
					{$col->setData('price', $price+$price*$colect[$i]->getData('mnog')/100);break;}
				elseif (($i==($count-1)) && ($price >= $colect[$i]->getData('value'))) 
					{$col->setData('price', $price+$price*$colect[$i]->getData('mnog')/100);break;}
				elseif (($i>0) && ($i<($count-1)) && ($price <= $colect[$i]->getData('value')) && ($price > $colect[$i-1]->getData('value'))) 
					{$col->setData('price', $price+$price*$colect[$i]->getData('mnog')/100);break;}
				
			}	
		}		
		return $collection;
    }
	
	function setOrder()
    {
        //$request = $this->uri->uri_to_assoc();
		$request['type'] = $_POST['type'];
		$request['order'] = $_POST['attr'];
		$request['dir'] = $_POST['dir'];
        if (isset($request['type']) && isset($request['order'])) {
            if (!isset($request['dir'])) $request['dir'] = 'asc';
            $this->session->set_userdata('order_' . $request['type'], $request);
        }
		$this->index();
    }
	
	function filter()
    {
        $filters = $this->session->userdata('filter');
        $filters[$_POST['type']] = $_POST;
        $this->session->set_userdata('filter', $filters);
    }
	
	function filter2()
    {
        $filters = $this->session->userdata('filter');
        $filters[$_POST['type']] = $_POST;
		if (isset($_POST['id_man']) && $_POST['id_man']!='all') {
		$this->load->model('Attribute_value', 'atr');
		$atr = $this->atr
					->addFilterByField('pk_id', $_POST['id_man'])
					->getCollection();
		$filters[$_POST['type']]['manufacturer'] = $atr[0]->getData('value');
		}
		else
		{
		$filters[$_POST['type']]['manufacturer'] = '';
		}
        $this->session->set_userdata('filter', $filters);
    }

    /**
     * —брос фильтра
     */
    function reset()
    {
        $filters = $this->session->userdata('filter');
        $filters[$_POST['type']] = "";
        $this->session->set_userdata('filter', $filters);
    }

	function addRequest()
	{
		$id = $_POST['id'];
		$qty = $_POST['qty'];
		
		$login = $this->session->userdata('login');
		$this->load->model('userf_model', 'user');
		$Id = $this->user->addFilterByField('login', $login)->getCollection();
		if(count($Id)) {$user_id = $Id[0]->getId();}
		else {$user_id = 0;}
		
		$this->setNacenkaUsers();
		$this->load->model('Offer_front', 'offer');		
		$collection = $this->offer
                        //->addFilterByField('pk_id ', $id)
                        ->getCollection();
		$collection = $this->setNacenkaPrice($collection);
		foreach ($collection as $col)
		{
			if ($col->getId()==$id) {$collect= $col; break;}
		}
		$this->offer->setData(
				array(
				'type'=>'request',
				'date'=>time(),
				'status'=>'active',
				'qty'=>$qty,
				'user_id'=>$user_id,
				'fk_staff'=>$collect->getData('fk_staff'), 
				'fk_phone'=>$collect->getData('fk_phone'), 
				'price'=>$collect->getData('price'), 
				'fk_currency'=>$collect->getData('fk_currency'), 
				'notes'=>$collect->getData('notes'), 
				'brand'=>$collect->getData('brand'), 
				'location'=>$collect->getData('location'), 
				'offer_price'=>$collect->getData('offer_price')
				)
				)->save();
		
	}
	
	function unsubscribe()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
			$offers_order = $this->session->userdata('order_offer_front');
			if (!is_array($offers_order)) {
	            $offers_order = array('order' => 'date', 'dir' => 'desc');
	        }
			$data['orders']['offer_front'] = $offers_order;
			
            $login = $this->session->userdata('login');
			$this->load->model('userf_model', 'user');
			$Id = $this->user->addFilterByField('login', $login)->getCollection();
			$this->id_user = $Id[0]->getId();
            $this->load->model('Sends','send');
			foreach ($_POST['ids'] as $item)
			{
				$pk_id = $this->send->addFilterByField('user_id', $this->id_user)->addFilterByField('offer_id', $item)->getCollection();
				if (count($pk_id))
				{
				$this->send->delete($pk_id[0]->getId());
				}
			
			}
            $retval['status'] = "OK";
            $this->setNacenkaUsers();
	        $this->load->model('Offer_front', 'offer');
			$collection = $this->offer
	                        ->addFilterByField('type', 'offer')
	                        ->addFilterByField('main_table.status', Offer_front::STATUS_ACTIVE)
	                        ->getCollection();
			$data['items'] = $this->setNacenkaPrice($collection);
			$data['type'] = $_POST['type'];
            $retval['content'] = $this->load->view('account/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
	
	function subscribe()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
			$offers_order = $this->session->userdata('order_offer_front');
			if (!is_array($offers_order)) {
	            $offers_order = array('order' => 'date', 'dir' => 'desc');
	        }
			$data['orders']['offer_front'] = $offers_order;
		
			$login = $this->session->userdata('login');
			$this->load->model('userf_model', 'user');
			$Id = $this->user->addFilterByField('login', $login)->getCollection();
			$this->id_user = $Id[0]->getId();
            $this->load->model('Sends','send');
			foreach ($_POST['ids'] as $item)
			{
				if (!count($this->send->addFilterByField('user_id', $this->id_user)->addFilterByField('offer_id', $item)->getCollection()))
				{
				$this->send->setData(array('user_id'=> $this->id_user, 'offer_id'=>$item))->save();}
			
			}
            $retval['status'] = "OK";
            $this->setNacenkaUsers();
	        $this->load->model('Offer_front', 'offer');
			$collection = $this->offer
	                        ->addFilterByField('type', 'offer')
	                        ->addFilterByField('main_table.status', Offer_front::STATUS_ACTIVE)
	                        ->getCollection();
			$data['items'] = $this->setNacenkaPrice($collection);
			$data['type'] = $_POST['type'];
            $retval['content'] = $this->load->view('account/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
}