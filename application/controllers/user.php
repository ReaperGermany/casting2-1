<?php
 
class User extends Controller
{	
	var $language = array ();
	var $is_loaded = '';
	
    public function login()
    {	
		$posts = $this->uri->uri_to_assoc();
		if (isset($posts['lang'])) 
		{$lang = $posts['lang'];}
		else
		{$lang = 'english';}
		$data['lang'] = $lang;
		$error = CI_Language::load('front_login', $lang);
		
		$type = 2;
        if (($login = $this->input->post('login')) && ($pass = $this->input->post('password'))) {
            $this->load->model('userf_model', 'userf');
            if ($this->userf->loadByLogin($login)->getData('password') == $pass) {
                $this->session->set_userdata('login',$login);
				$this->session->set_userdata('type',$type);
                redirect("account","refresh");
            }
        }

        $this->load->view('userf/login', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('login');
		$this->session->unset_userdata('type');
        redirect("user/login","refresh");
    }
	
	function forgot()
	{
		$retval2 = array();
		if(($login = $this->input->post('login')) && ($email = $this->input->post('email')))
		{
			$this->load->model('userf_model', 'user');
			$colect = $this->user->addFilterByField('login', $login)
							->addFilterByField('email', $email)
							->getCollection();
			if (count($colect)) {
				$user = array();
				$user['login'] = $colect[0]->getData('login');
				$user['pass'] = $colect[0]->getData('password');
				$user['email'] = $colect[0]->getData('email');
				$this->load->model('userf_model', 'user');
				if ($this->user->sendPass($user)) {
						$retval2['status'] = "OK";
						$retval2['message'] = "Password sent to your email.";
						echo json_encode($retval2);
						}
				else {
					$retval2['status'] = "ERROR";
					$retval2['message'] = "Error sending mail.";
					echo json_encode($retval2);
				}
			}
			else{
				$retval2['status'] = "ERROR";
				$retval2['message'] = "The combination of Email and Login to be not found. Try again.";
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
		
	public function registration()
    {
		if (($login = $this->input->post('login')) && ($pass = $this->input->post('password')))
		{
		$user = $this->input->post('login');
		$this->load->model('role');
		$role = $this->role->loadByCode('verifying');
		
		
			$this->load->model('userf_model', 'user');
			$col = $this->user->addFilterByField('login',$user)->getCollection();
			if(!count($col))
			{
				if($this->input->post('wire_capab')=='Yes') {$wire_capab=true;} else {$wire_capab=false;}
				if($this->input->post('financil_avab')=='Yes') {$financil_avab=true;} else {$financil_avab=false;}
				if($this->input->post('pay_cc')=='true') {$pay_cc=true;} else {$pay_cc=false;}
				if($this->input->post('pay_trans')=='true') {$pay_trans=true;} else {$pay_trans=false;}
				if($this->input->post('pay_cert')=='true') {$pay_cert=true;} else {$pay_cert=false;}
				if($this->input->post('pay_money')=='true') {$pay_money=true;} else {$pay_money=false;}
				
				$this->load->model('userf_model', 'user');
				$this->user->setData(
				array(
				'login'=>$this->input->post('login'), 
				'password'=>$this->input->post('password'), 
				'role_id'=> $role->getData('pk_id'),
				'name_bus'=>$this->input->post('name_bus'), 
				'name_cor'=>$this->input->post('name_cor'), 
				'aim'=>$this->input->post('aim'), 
				'msn'=>$this->input->post('msn'), 
				'skype'=>$this->input->post('skype'), 
				'email'=>$this->input->post('email'), 
				'cor_id'=>$this->input->post('cor_id'), 
				'data_open'=>$this->input->post('data_open'),
				'fed_tax_id'=>$this->input->post('fed_tax_id'), 
				'state_reg'=>$this->input->post('state_reg'), 
				'type_cor'=>$this->input->post('type_cor'), 
				'type_bus'=>$this->input->post('type_bus'), 
				'type_premise'=>$this->input->post('type_premise'), 
				'wire_capab'=> $wire_capab, 
				'credit_limit'=>$this->input->post('credit_limit'),
				'financil_avab'=>$financil_avab,
				'pay_cc'=>$pay_cc,
				'pay_trans'=>$pay_trans,
				'pay_cert'=>$pay_cert,
				'pay_money'=>$pay_money
				)
				)->save();
				$this->load->model('userf_model', 'user');
				$id = $this->user->loadByLogin($user)->getData('pk_id');
				$this->load->model('user_address', 'addr');
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
				
				$this->load->model('user_bank', 'bank');
				$this->bank->setData(
				array(
				'user_id'=>$id , 
				'contact'=>$this->input->post('contact'),
				'name'=>$this->input->post('name'),				
				'adress'=>$this->input->post('bank_adress'), 
				'city'=>$this->input->post('bank_city'), 
				'state'=>$this->input->post('bank_state'), 
				'zip'=>$this->input->post('bank_zip'), 
				'phone'=>$this->input->post('bank_phone'), 
				'fax'=>$this->input->post('bank_fax'),
				'check_acc'=>$this->input->post('check_acct'),
				'line_credit'=>$this->input->post('line_credit')
				)
				)->save();
				
				$this->load->model('user_reference', 'ref');
				$this->ref->setData(
				array(
				'user_id'=>$id , 
				'accaunt'=>$this->input->post('accaunt1'),
				'supplies'=>$this->input->post('supplier1'),				
				'adress'=>$this->input->post('trade_adress1'), 
				'city'=>$this->input->post('trade_city1'), 
				'state'=>$this->input->post('trade_state1'), 
				'zip'=>$this->input->post('trade_zip1'), 
				'phone'=>$this->input->post('trade_phone1'), 
				'fax'=>$this->input->post('trade_fax1'),
				'contact_name'=>$this->input->post('contact_name1')
				)
				)->save();
				
				$this->ref->setData(
				array(
				'user_id'=>$id , 
				'accaunt'=>$this->input->post('accaunt2'),
				'supplies'=>$this->input->post('supplier2'),				
				'adress'=>$this->input->post('trade_adress2'), 
				'city'=>$this->input->post('trade_city2'), 
				'state'=>$this->input->post('trade_state2'), 
				'zip'=>$this->input->post('trade_zip2'), 
				'phone'=>$this->input->post('trade_phone2'), 
				'fax'=>$this->input->post('trade_fax2'),
				'contact_name'=>$this->input->post('contact_name2')
				)
				)->save();
				
				$this->ref->setData(
				array(
				'user_id'=>$id , 
				'accaunt'=>$this->input->post('accaunt3'),
				'supplies'=>$this->input->post('supplier3'),				
				'adress'=>$this->input->post('trade_adress3'), 
				'city'=>$this->input->post('trade_city3'), 
				'state'=>$this->input->post('trade_state3'), 
				'zip'=>$this->input->post('trade_zip3'), 
				'phone'=>$this->input->post('trade_phone3'), 
				'fax'=>$this->input->post('trade_fax3'),
				'contact_name'=>$this->input->post('contact_name3')
				)
				)->save();
				
				$this->ref->setData(
				array(
				'user_id'=>$id , 
				'accaunt'=>$this->input->post('accaunt4'),
				'supplies'=>$this->input->post('supplier4'),				
				'adress'=>$this->input->post('trade_adress4'), 
				'city'=>$this->input->post('trade_city4'), 
				'state'=>$this->input->post('trade_state4'), 
				'zip'=>$this->input->post('trade_zip4'), 
				'phone'=>$this->input->post('trade_phone4'), 
				'fax'=>$this->input->post('trade_fax4'),
				'contact_name'=>$this->input->post('contact_name4')
				)
				)->save();
				$retval['status'] = "OK";
			}
			else
			{
				$retval['status'] = "ERROR";
				$retval['message'] = "This login is already in use.";
			}
	        //$retval['content'] = ""; //$this->load->view('admin/visible/attr',$data,true);
	        echo json_encode($retval);
		}
		else
		{
			$this->load->view('userf/registration');
		}
    }
	
}