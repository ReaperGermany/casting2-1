<?php

class Matches extends Controller
{
    protected $_default_filter_interval;

    public function __construct()
    {
        parent::Controller();
        $this->_default_filter_interval = 3600*24*7; //одна неделя
    }

    public function index()
    {

        if (isset($_POST['match_value'])){
            $this->session->set_userdata('match_value',$_POST['match_value']);
        }

        $order = $this->session->userdata('order_matches');

        if (!is_array($order)) {
            $order = array('order'=>'model', 'dir'=>'asc');
        }

        $this->load->model('Phone', 'phone');
        $this->load->model('Offer', 'offer');

        $filter = $this->session->userdata('match_filter');
        if (!isset($filter['offer_date'])) {
            $filter['offer_date'] = array('from'=>date('m/d/Y',time()-$this->_default_filter_interval),'to'=>"");
        }
        if (!isset($filter['request_date'])) {
            $filter['request_date'] = array('from'=>date('m/d/Y',time()-$this->_default_filter_interval),'to'=>"");
        }

        $data['filter'] = $filter;

        $data['items'] = $this->phone
                ->applyFilter($filter)
                ->addOrderByField($order['order'],$order['dir'])
                ->getMatches($this->session->userdata('match_value'));
        $attrs = array();
        foreach ($data['items'] as $item) {
    	    if ($item->getData('offer_price') == Offer::TYPE_OFFER_DEFAULT_PRICE) $item->setData('offer_base_price', '');
    	    if ($item->getData('request_price') == Offer::TYPE_REQUEST_DEFAULT_PRICE) $item->setData('request_base_price', '');
            $attrs['manufacturer'][] = $item->getData('manufacturer');
            $attrs['model'][] = $item->getData('model');
            $attrs['offer_company'][] = $item->getData('offer_company');
            $attrs['request_company'][] = $item->getData('request_company');
        }
        $data['match_value'] = $this->session->userdata('match_value');

        foreach ( $attrs as $k => $v) {
            $attrs[$k] = array_unique($v);
        }

        $this->load->model('Attribute_value','av');
        foreach($this->av->getCodesList() as $code) {
            $this->load->model('Attribute_value',$code);
            $items = $this->$code->setAttributeCode($code)->getCollection();
            $retval = array();
            foreach ($items as $item) {
                if (!isset($attrs[$code]) || !in_array($item->getData('value'),$attrs[$code])) continue;
                $retval[$item->getId()] = array(
                                    'id'    => $item->getId(),
                                    'label' => $item->getData('value'),
                                    'value' => $item->getData('value')
                            );
            }
            $data['attrs'][$code] = $retval;
        }
        
	if (isset($attrs['offer_company']) && is_array($attrs['offer_company'])) {
    	    foreach ($attrs['offer_company'] as $company) {
        	$data['attrs']['offer_company'][] = array(
                                    'label' => $company,
                                    'value' => $company);
    	    }
    	}

	if (isset($attrs['request_company']) && is_array($attrs['request_company'])) {
    	    foreach ($attrs['request_company'] as $company) {
        	$data['attrs']['request_company'][] = array(
                                    'label' => $company,
                                    'value' => $company);
    	    }
    	}

        /*$this->load->model('Company','company');
        $items = $this->company->getCollection();
        $retval = array();
        foreach ($items as $item) {
            if (!isset($attrs['company_name']) || !in_array($item->getData('value'),$attrs['company_name'])) continue;
            $retval[$item->getId()] = array(
                                'label' => $item->getData('name'),
                                'value' => $item->getId()
                        );
        }
        $data['attrs']['company_name'] = $retval;
 */
	if (count($data['attrs'])) {
    	    $models = array_values($data['attrs']['model']);
    	    $this->load->model('requires', 're');
    	    $data['attrs'] = $this->re->prepareAttrs($data['attrs']);
    	    $data['attrs']['model']['data']['all'] = $models;
    	}
        $data['order'] = $order;

        $this->load->view('admin/matches/container', $data);
    }

    public function filter()
    {
        if (isset($_POST)) {
            $filter = $_POST;
        }
        else {
            $filter = array();
        }
        $this->session->set_userdata('match_filter', $filter);
    }

    function reset()
    {
        $filters = $this->session->userdata('match_filter');
        $filters = array(
            'offer_date' => array('from'=>date('m/d/Y',time()-3600*24*7),'to'=>""),
            'request_date' => array('from'=>date('m/d/Y',time()-3600*24*7),'to'=>"")
            );
        $this->session->set_userdata('match_filter',$filters);
    }

    public function setOrder()
    {
        $request = $this->uri->uri_to_assoc();
        if (isset($request['order']))
        {
            if (!isset($request['dir'])) $request['dir']='asc';
            $this->session->set_userdata('order_matches',$request);
        }
        $this->index();
    }

    public function ajaxMatchData()
    {
        if (isset($_POST['id'])) {
            $this->load->model('Phone', 'phone');
            $data['items'] = $this->phone->getMatchData($_POST['id'],$this->session->userdata('match_value'));
            $retval['status'] = "OK";
            $retval['content'] = $this->load->view('admin/matches/data', $data, true);
        }
        else {
            $retval['status'] = "ERROR";
        }
        echo json_encode($retval);
    }
}
?>
