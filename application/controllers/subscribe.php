<?php
 
class Subscribe extends Controller
{	
	var $col_cp = array();
	var $col_c = array();
	var $col_m = array();
	var $col_s = array();
	var $col_ma = array();
	var $id_user = "";
	var $_subscribe = true;

    function __construct()
    {
        parent::Controller();
    }
	
	function index()
    {
		$offers_order = $this->session->userdata('order_offer_scribe');
		if (!is_array($offers_order)) {
            $offers_order = array('order' => 'code', 'dir' => 'desc');
        }
		
		$login = $this->session->userdata('login');
		$this->load->model('userf_model', 'user');
		$Id = $this->user->addFilterByField('login', $login)->getCollection();
		$this->id_user = $Id[0]->getId();
		
		$filters = $this->session->userdata('filter');
		if (!isset($filters['offer_scribe'])) {
            $filters['offer_scribe']['value']= '';
        }

		$this->load->model('Attribute_value', 'attr');
		if ($filters['offer_scribe']['value']!='') 
		{
			$data['manufacturer'] = $this->attr
								->addFilterByField('code', 'manufacturer')
								->addOrderByField($offers_order['order'], $offers_order['dir'])
								->addFilterByField('value LIKE', '%'.$filters['offer_scribe']['value'].'%')
								->getCollection();
		}
		else
		{
			$data['manufacturer'] = $this->attr
								->addFilterByField('code', 'manufacturer')
								->addOrderByField($offers_order['order'], $offers_order['dir'])
								->getCollection();
		
		}
		$data['filters'] = $filters;
		$data['orders']['offer_scribe'] = $offers_order;
        $this->load->view('subscribe', $data);
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

    /**
     * —брос фильтра
     */
    function reset()
    {
        $filters = $this->session->userdata('filter');
        $filters[$_POST['type']]['value'] = "";
        $this->session->set_userdata('filter', $filters);
    }
	
	function unsubs()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
			$offers_order = $this->session->userdata('order_offer_scribe');
			if (!is_array($offers_order)) {
	            $offers_order = array('order' => 'value', 'dir' => 'desc');
	        }
			$data['orders']['offer_scribe'] = $offers_order;
			
            $login = $this->session->userdata('login');
			$this->load->model('userf_model', 'user');
			$Id = $this->user->addFilterByField('login', $login)->getCollection();
			$this->id_user = $Id[0]->getId();
            $this->load->model('Sends','send');
			foreach ($_POST['ids'] as $item)
			{
				$pk_id = $this->send->addFilterByField('user_id', $this->id_user)->addFilterByField('offer_id', 0)->addFilterByField('manufacturer_id', $item)->getCollection();
				if (count($pk_id))
				{
				$this->send->delete($pk_id[0]->getId());
				}
			
			}
            $retval['status'] = "OK";
            $data['type'] = $_POST['type'];
			$this->load->model('Attribute_value', 'attr');
			$data['items'] = $this->attr->addFilterByField('code', 'manufacturer')->getCollection();
			$data['type'] = $_POST['type'];
            $retval['content'] = $this->load->view('subscribe/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
	
	function subs()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
			$offers_order = $this->session->userdata('order_offer_scribe');
			if (!is_array($offers_order)) {
	            $offers_order = array('order' => 'value', 'dir' => 'desc');
	        }
			$data['orders']['offer_scribe'] = $offers_order;
		
			$login = $this->session->userdata('login');
			$this->load->model('userf_model', 'user');
			$Id = $this->user->addFilterByField('login', $login)->getCollection();
			$this->id_user = $Id[0]->getId();
            $this->load->model('Sends','send');
			foreach ($_POST['ids'] as $item)
			{
				if (!count($this->send->addFilterByField('user_id', $this->id_user)->addFilterByField('manufacturer_id', $item)->addFilterByField('offer_id', 0)->getCollection()))
				{
				$this->send->setData(array('user_id'=> $this->id_user, 'offer_id'=>0, 'manufacturer_id'=>$item))->save();}			
			}
            $retval['status'] = "OK";
            
			$data['type'] = $_POST['type'];
			$this->load->model('Attribute_value', 'attr');
			$data['items'] = $this->attr->addFilterByField('code', 'manufacturer')->getCollection();
            $retval['content'] = $this->load->view('subscribe/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
}