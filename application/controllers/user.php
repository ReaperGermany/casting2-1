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
				$this->load->model('userf_model', 'user');
				$this->user->setData(
				array(
				'login'=>$this->input->post('login'), 
				'password'=>$this->input->post('password'), 
				'role_id'=> $role->getData('pk_id'),
				'skype'=>$this->input->post('skype'), 
				'email'=>$this->input->post('email'), 
				'family'=>$this->input->post('family'), 
				'name'=>$this->input->post('name'), 
				'syrname'=>$this->input->post('syrname'), 
				'gander'=>$this->input->post('gander'), 
				'birthday'=> $this->input->post('birthday')
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