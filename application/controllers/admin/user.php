<?php
 
class User extends Controller
{
	public function login()
    {
		$type = 1;
        if (($login = $this->input->post('login')) && ($pass = $this->input->post('password'))) {
            $this->load->model('user_model', 'user');
            if ($this->user->loadByLogin($login)->getData('password') == $pass) {
				$this->session->set_userdata('login',$login);
				$this->session->set_userdata('type',$type);
                redirect("admin/offers/index","refresh");
            }
        }

        $this->load->view('admin/user/login');
    }

    public function logout()
    {
        $this->session->unset_userdata('login');
		$this->session->unset_userdata('type');
        redirect("admin/user/login","refresh");
    }
}