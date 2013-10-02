<?php

class Manager extends Controller
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
		$offers_order = $this->session->userdata('users_sort');
			if (!is_array($offers_order)) {
            $offers_order = array( 'order' => 'pk_id', 'dir' => 'desc');
        }
        if (!isset($filters['users']['limit']) || isset($filters['users']['limit']))
		{
            $filters['users'] = array(
                'limit'=>'20',
				'limit_from'=>'0'
            );
        }
		
		$request = $this->uri->segment(5, 0);
	    if (isset($request) && $request>0) 
		{
			$filters['users']['limit_from'] = $filters['users']['limit']*($request-1);
			$data['page'] = $request;
		}
		else $data['page'] = 1;
		
				
		$this->load->model('userf_model', 'user');
		$data['count'] = $this->user->getCount();
		$this->user->applyFilters($filters['users']);	
        $data['users'] = $this->user
						->addOrderByField($offers_order['order'], $offers_order['dir'])
						->getCollection();
		
		
		$data['filters'] = $filters;
        $this->load->view('admin/manager/manager', $data);
    }
	
	function ajaxLoadInfo()
    {		
		if($id = $this->input->post('id'))
		{
			$retval = array();
			$data = array();
			$this->load->model('userf_model', 'user');
			$us = $this->user->addFilterByField('pk_id', $id)->getCollection();
			$data['user'] = $us[0];
			$this->load->model('user_bank', 'bank');
			$bn = $this->bank->addFilterByField('user_id', $id)->getCollection();
			if (count($bn)) $data['bank'] = $bn[0];
			$this->load->model('user_reference', 'ref');
			$rf = $this->ref->addFilterByField('user_id', $id)->getCollection();
			if (count($rf)) $data['ref'] = $rf;
			$this->load->model('user_address', 'addr');
			$ads = $this->addr
									->addFilterByField('user_id', $id)
									->addFilterByField('type', 'shipping')
									->getCollection();
			if (count($ads)) $data['addr_s'] = $ads[0];
			$adb = $this->addr
									->addFilterByField('user_id', $id)
									->addFilterByField('type', 'billing')
									->getCollection();
			if (count($adb)) $data['addr_b'] = $adb[0];
			$retval['status'] = "OK";
		    $retval['content'] = $this->load->view('admin/manager/info',$data,true);
		    echo json_encode($retval);
		}
		else
		{
			$retval['status'] = "ERROR";
		    $retval['message'] = "Error I/O.";
		    echo json_encode($retval);
		}
		
    }
	
	function ajaxApprove()
	{
		$retval2 = array();
		if($id = $this->input->post('id'))
		{
			$this->load->model('role');
			$role = $this->role->loadByCode('customer');
			$this->load->model('userf_model', 'user');
			$this->user->load($id)->setData('role_id', $role->getData('pk_id'))->save();
			$retval2['status'] = "OK";
			$retval2['message'] = "Approve successful";
		    echo json_encode($retval2);
		}
		else
		{
			$retval2['status'] = "ERROR";
		    $retval2['message'] = "Error I/O.";
		    echo json_encode($retval2);
		}
	}
	
	function ajaxChangePass()
	{
		$retval2 = array();
		if($id = $this->input->post('id'))
		{
			$this->load->model('userf_model', 'user');
			$this->user->load($id)->setData('password', $this->input->post('pass'))->save();
			
			$this->load->model('userf_model', 'user2');
			$colect = $this->user2->addFilterByField('pk_id', $id)->getCollection();
			$user['login'] = $colect[0]->getData('login');
			$user['pass'] = $colect[0]->getData('password');
			$user['email'] = $colect[0]->getData('email');
			$this->load->model('userf_model', 'user3');
			if ($this->user3->sendPass($user, true)) {
				$retval2['status'] = "OK";
				$retval2['message'] = "Password sent to ".$user['email'].".";
				echo json_encode($retval2);
			}
			else {
				$retval2['status'] = "ERROR";
				$retval2['message'] = "Error sending mail.";
				echo json_encode($retval2);
			}
		}
		else
		{
			$retval2['status'] = "ERROR";
		    $retval2['message'] = "Error I/O.";
		    echo json_encode($retval2);
		}
	}
	
	function ajaxDecline()
	{
		$retval2 = array();
		if($id = $this->input->post('id'))
		{
			$this->load->model('role');
			$role = $this->role->loadByCode('verifying');
			$this->load->model('userf_model', 'user');
			$this->user->load($id)->setData('role_id', $role->getData('pk_id'))->save();
			$retval2['status'] = "OK";
		    echo json_encode($retval2);
		}
		else
		{
			$retval2['status'] = "ERROR";
		    $retval2['message'] = "Error I/O.";
		    echo json_encode($retval2);
		}
	}
	
	function ajaxEditing()
	{
		$retval2 = array();
		if($id = $this->input->post('pk_id'))
		{
			if($this->input->post('wire_capab')=='Yes') {$wire_capab=true;} else {$wire_capab=false;}
			if($this->input->post('financil_avab')=='Yes') {$financil_avab=true;} else {$financil_avab=false;}
			if($this->input->post('pay_cc')=='true') {$pay_cc=true;} else {$pay_cc=false;}
			if($this->input->post('pay_trans')=='true') {$pay_trans=true;} else {$pay_trans=false;}
			if($this->input->post('pay_cert')=='true') {$pay_cert=true;} else {$pay_cert=false;}
			if($this->input->post('pay_money')=='true') {$pay_money=true;} else {$pay_money=false;}
				
				$this->load->model('userf_model', 'user');
				$this->user->load($id)
				->setData('name_bus', $this->input->post('name_bus'))
				->setData('name_cor', $this->input->post('name_cor'))
				->setData('aim', $this->input->post('aim'))
				->setData('msn', $this->input->post('msn'))
				->setData('skype', $this->input->post('skype'))
				->setData('email', $this->input->post('email'))
				->setData('cor_id', $this->input->post('cor_id'))
				->setData('data_open', $this->input->post('data_open'))
				->setData('fed_tax_id', $this->input->post('fed_tax_id'))
				->setData('state_reg', $this->input->post('state_reg'))
				->setData('type_cor', $this->input->post('type_cor'))
				->setData('type_bus', $this->input->post('type_bus'))
				->setData('type_premise', $this->input->post('type_premise'))
				->setData('wire_capab', $wire_capab)
				->setData('credit_limit', $this->input->post('credit_limit'))
				->setData('financil_avab', $financil_avab)
				->setData('pay_cc', $pay_cc)
				->setData('pay_trans', $pay_trans)
				->setData('pay_cert', $pay_cert)
				->setData('pay_money', $pay_money)
				->save();
				
				$this->load->model('user_address', 'addr');
				$adr1 = $this->addr
						->addFilterByField('user_id', $id )
						->addFilterByField('type', 'billing')
						->getCollection();
				if(count($adr1)>0) 
				{
				$id_s1 = $adr1[0]->getData('pk_id');
				$this->addr->load($id_s1)
				->setData('user_id', $id) 
				->setData('type', 'billing')
				->setData('adress', $this->input->post('billink_adress')) 
				->setData('email', $this->input->post('billing_email')) 
				->setData('city', $this->input->post('billing_city')) 
				->setData('state', $this->input->post('billing_state')) 
				->setData('zip', $this->input->post('billing_zip')) 
				->setData('phone', $this->input->post('billing_phone')) 
				->setData('fax', $this->input->post('billing_fax')) 
				->save();
				}
				else
				{
					$this->addr->setData(
					array(
					'user_id'=>$id , 
					'type'=>'billing', 
					'adress'=>$this->input->post('billink_adress'), 
					'email'=>$this->input->post('billing_email'), 
					'city'=>$this->input->post('billing_city'), 
					'state'=>$this->input->post('billing_state'), 
					'zip'=>$this->input->post('billing_zip'), 
					'phone'=>$this->input->post('billing_phone'), 
					'fax'=>$this->input->post('billing_fax')
					)
					)->save();
				};
				
				$adr = $this->addr
						->addFilterByField('user_id', $id )
						->addFilterByField('type', 'shipping')
						->getCollection();
				if(count($adr)>0) 
				{
				$id_s = $adr[0]->getData('pk_id');
				$this->addr->load($id_s)
				->setData('user_id', $id) 
				->setData('type', 'shipping')
				->setData('adress', $this->input->post('shipping_adress'))
				->setData('email', $this->input->post('shipping_email'))
				->setData('city', $this->input->post('shipping_city')) 
				->setData('state', $this->input->post('shipping_state')) 
				->setData('zip', $this->input->post('shipping_zip')) 
				->setData('phone', $this->input->post('shipping_phone')) 
				->setData('fax', $this->input->post('shipping_fax')) 
				->save();
				}
				else
				{
				$this->addr->setData(
				array(
				'user_id'=>$id , 
				'type'=>'shipping', 
				'adress'=>$this->input->post('shipping_adress'), 
				'email'=>$this->input->post('shipping_email'), 
				'city'=>$this->input->post('shipping_city'), 
				'state'=>$this->input->post('shipping_state'), 
				'zip'=>$this->input->post('shipping_zip'), 
				'phone'=>$this->input->post('shipping_phone'), 
				'fax'=>$this->input->post('shipping_fax')
				)
				)->save();			
				};
				
				$this->load->model('user_bank', 'bank');
				$adr = $this->bank
						->addFilterByField('user_id', $id )
							->getCollection();
				if(count($adr)) $id_s = $adr[0]->getData('pk_id');
				$this->bank->load($id_s)
				->setData('user_id', $id) 
				->setData('contact', $this->input->post('contact'))
				->setData('name', $this->input->post('name'))
				->setData('adress', $this->input->post('bank_adress'))
				->setData('city', $this->input->post('bank_city'))
				->setData('state', $this->input->post('bank_state'))
				->setData('zip', $this->input->post('bank_zip'))
				->setData('fax', $this->input->post('bank_fax'))
				->setData('phone', $this->input->post('bank_phone'))
				->setData('check_acc', $this->input->post('check_acct'))
				->setData('line_credit', $this->input->post('line_credit'))
				->save();
				
				$this->load->model('user_reference', 'ref');
				//$adr = $this->ref
				//		->addFilterByField('user_id', $id )
				//		->getCollection();
				//if(count($adr)) $id_s = $adr[0]->getData('pk_id');
				for ($i=1; $i<5; $i++)
				{
				$this->ref->load($this->input->post('pk_id'.$i))
				->setData('user_id', $id) 
				->setData('accaunt', $this->input->post('accaunt'.$i))
				->setData('supplies', $this->input->post('supplier'.$i))
				->setData('adress', $this->input->post('trade_adress'.$i))
				->setData('city', $this->input->post('trade_city'.$i))
				->setData('state', $this->input->post('trade_state'.$i))
				->setData('zip', $this->input->post('trade_zip'.$i))
				->setData('phone', $this->input->post('trade_phone'.$i))
				->setData('fax', $this->input->post('trade_fax'.$i))
				->setData('contact_name', $this->input->post('contact_name'.$i))
				->save();
				}
		
		
			$retval2['status'] = "OK";
		    echo json_encode($retval2);
		}
		else
		{
			$retval2['status'] = "ERROR";
		    $retval2['message'] = "Error I/O.";
		    echo json_encode($retval2);
		}
	}
	
	function setUsers()
    {
        //$request = $this->uri->uri_to_assoc();
		$request['type'] = $_POST['type'];
		if ($_POST['attr']=='mail') {$request['order'] = 'email';}
		else {$request['order'] = $_POST['attr'];}
		$request['dir'] = $_POST['dir'];
        if (isset($request['type']) && isset($request['order'])) {
            if (!isset($request['dir'])) $request['dir'] = 'asc';
            $this->session->set_userdata('users_sort', $request);
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
        $filters[$_POST['type']] = array('date' => array('from' => "", 'to' => ""));
        $this->session->set_userdata('filter', $filters);
    }
	
}
