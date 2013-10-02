<?php
class Customer
{
    function checkCustomer()
    {
        $controller =& get_instance();
		if (!($controller->uri->rsegments[1] == 'cron2' && $controller->uri->rsegments[2] == 'sends')) {
		
        if (!($controller->uri->rsegments[1] == 'user'
            && $controller->uri->rsegments[2] == 'login'
            || ($controller->session->userdata('login')) 
			|| ($controller->uri->rsegments[1] == 'user'
            && $controller->uri->rsegments[2] == 'registration')
			|| ($controller->uri->rsegments[1] == 'user'
            && $controller->uri->rsegments[2] == 'forgot')
			|| ($controller->uri->rsegments[1] == 'cron2'
            && $controller->uri->rsegments[2] == 'sends_updates_all'))
        ) {
            if (isset($controller->uri->segments[1]) && $controller->uri->segments[1] == "admin") {
                redirect('admin/user/login/', 'refresh');
            }
            else {
                redirect('user/login/', 'refresh');
            }
        }
		}
	}

    public function checkAccess()
    {
        $key = Core::app()->uri->rsegments[1]."/".Core::app()->uri->rsegments[2];
        $user = null;
        $login = Core::app()->session->userdata('login');
        if (Core::app()->session->userdata('type') == 2) {
            $user = Core::getSingleton("userf_model")->loadByLogin($login);
	    }
		elseif (Core::app()->session->userdata('type') == 1) {
		    $user = Core::getSingleton("user_model")->loadByLogin($login);
	    }

        Core::register("current_user", $user);

        $acl = Core::getModel("acl");
        if (!$acl->isAllow($user, $key)) {
		   if (Core::app()->session->userdata('type')==2)
		   {
				show_error2('Please login to your account. <a href="'.base_url().'">Login</a> </br> If you have already entered, then you do not have permission to access this page.');
		   }
		   elseif (Core::app()->session->userdata('type')==1)
		   {
				show_error2('Please login to your account. <a href="'.base_url().'/admin/user/login">Login administrator</a> </br> If you have already entered, then you do not have permission to access this page.');
		   }
		   else
		   {
				show_error2('Please login to your account. <a href="'.base_url().'">My account</a>');
		   }
		   //show_404($key);
        }
    }

}
