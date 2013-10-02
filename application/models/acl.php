<?php

class Acl extends Model
{
    protected $_rights = array(
                            "admin" => array("*"),
                            "salesman" => array(
                                "offers/index",
                                "offers/add",
                                "offers/setOrder",
                                "offers/filter",
                                "offers/reset",
                                "offers/savePost",
                                "ajax/getStaffList",
                                "ajax/saveOffer",
                                "matches/index",
                                "user/login",
                                "user/logout"),
							"customer" => array(
								"user/login",
								"account/setOrder",
								"account/addRequest",
								"account/reset",
								"account/filter",
								"account/filter2",
								"account/subscribe",
								"account/unsubscribe",
								"account/index",
								"subscribe/setOrder",
								"subscribe/addRequest",
								"subscribe/reset",
								"subscribe/filter",
								"subscribe/subs",
								"subscribe/unsubs",
								"subscribe/index")
                         );
    protected $_allowed_for_all = array(
           // "*",              // allow all pages for all users
			"user/login",
			"user/logout",
			"user/registration",
			"cron2/sends",
			"cron2/sends_updates",
			"cron2/sends_updates_all",
			"cron2/statusFront",
			"user/forgot",
            "admin/cron/dump",
            "admin/cron/updateCurrency"
    );

    public function isAllow($user, $key)
    {
        if (in_array($key, $this->_allowed_for_all) || in_array("*", $this->_allowed_for_all)) {
            return true;
        }
        if (!$user || !$user->getId()) return false;
		$role = $user->getRole()->getData('code');
		if (isset($this->_rights['customer']) && in_array($key, $this->_rights['customer']) && Core::app()->session->userdata('type')==1 && $role=='admin')
		{
			return false;
		}
        if (isset($this->_rights[$role]) && (in_array($key, $this->_rights[$role]) ||  in_array("*", $this->_rights[$role]))) {
            return true;
        }
        return false;
    }
}
