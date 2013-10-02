<?php

class Nacenka_price extends Controller
{

    function __construct()
    {
        parent::Controller();
    }

    /**
     * Вывод списка всех предложений
     */
    function index()
    {
		$filters = $this->session->userdata('filter');
		$offers_order = $this->session->userdata('order_nacenka');
		//echo 	$filter['pk_id'].' '.$filter['user_id'];
		//echo $offers_order['order'].' '. $offers_order['dir'];
		
		$this->load->model('userf_model', 'user');
        $data['data_user'] = $this->user->getCollection();
		$this->load->model('attribute_value', 'attr');
        $data['data_attr'] = $this->attr;
        
		$this->load->model('nacenka_model', 'nacenka');
		//////////////////filter
			if (!is_array($offers_order)) {
            $offers_order = array( 'order' => 'value', 'dir' => 'asc');
        }
        if (!isset($filters['nacenka'])) {
            $filters['nacenka'] = array(
                //'status' => 'Active'
            );
        }
			///////////////////////////	
		$this->nacenka->applyFilters($filters['nacenka']);			
        $data['nacenka'] = $this->nacenka
						->addOrderByField($offers_order['order'], $offers_order['dir'])
						->addFilterByField('user_id', 0)
						//->addFilterByField('status', 'active')
						->getCollection();
        $this->load->view('admin/nacenka_price/nacenka_list', $data);
    }
	
	function addNacenka()
    {
        if (isset($_POST['user']) && count(trim($_POST['user'])) && isset($_POST['code']) && count(trim($_POST['code'])) && isset($_POST['id_code']) && count(trim($_POST['id_code']))&& isset($_POST['nacn']) && count(trim($_POST['nacn']))) {
            $this->load->model('nacenka_model', 'nac');
			

					$above =$this->nac
						->addFilterByField('code_atr', '0')
						->addFilterByField('user_id', 0)
						->addFilterByField('main_table.value', trim($_POST['id_code']))
						->getcollection();
				$id ='';
				if ( count($above)) 
						{
							$id = $above[0]->getId();
						}
				
	            $this->nac->Load($id)
						->setData('user_id', 0)
						->setData('code_atr', '0')
						->setData('value', trim($_POST['id_code'])) 
						->setData('mnog', trim($_POST['nacn']))
						->save();
	            $retval['status'] = "OK";
				echo json_encode($retval);
			
        }
		else 
		{
		$retval['status'] = "ERROR";
		$retval['message'] = "Error input data.";
		echo json_encode($retval);
		}
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
     * Сброс фильтра
     */
    function reset()
    {
        $filters = $this->session->userdata('filter');
        $filters[$_POST['type']] = array('date' => array('from' => date('m/d/Y', time() - 3600 * 24 * 7), 'to' => ""));
        $this->session->set_userdata('filter', $filters);
    }
}
