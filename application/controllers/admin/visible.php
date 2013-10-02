<?php

class Visible extends Controller
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
		$offers_order = $this->session->userdata('order_visible');
		//echo 	$filter['pk_id'].' '.$filter['user_id'];
		
		$this->load->model('userf_model', 'user');
        $data['data_user'] = $this->user->getCollection();
		$this->load->model('attribute_value', 'attr');
        $data['data_attr'] = $this->attr;
        
		$this->load->model('visible_model', 'visible');
		//////////////////filter
			if (!is_array($offers_order)) {
            $offers_order = array( 'order' => 'pk_id', 'dir' => 'desc');
        }
        if (!isset($filters['visible'])) {
            $filters['visible'] = array(
                'status' => 'Active'
            );
        }
			///////////////////////////	
		$this->visible->applyFilters($filters['visible']);			
        $data['visibl'] = $this->visible
						->addOrderByField($offers_order['order'], $offers_order['dir'])
						//->addFilterByField('status', 'active')
						->getCollection();
        $this->load->view('admin/visible/visible_list', $data);
    }
	
	function addUnvisible()
    {
        if (isset($_POST['user']) && count(trim($_POST['user'])) && isset($_POST['code']) && count(trim($_POST['code'])) && isset($_POST['id_code']) && count(trim($_POST['id_code']))) {
            $this->load->model('visible_model', 'vis');
			
			if (!count($this->vis
							->addFilterByField('user_id', trim($_POST['user']))
							->addFilterByField('code_atr', trim($_POST['code']))
							->addFilterByField('main_table.value', trim($_POST['id_code']))
							->getCollection())							
				)
			{
            $this->vis->setData(array('user_id'=>trim($_POST['user']), 'code_atr'=>trim($_POST['code']), 'value'=>trim($_POST['id_code']), 'status'=>'Active'))->save();
            $retval['status'] = "OK";
			echo json_encode($retval);
			}
			else 
			{
			$retval['status'] = "ERROR";
			$retval['message'] = "This is already there.";
			echo json_encode($retval);
			}
			
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
