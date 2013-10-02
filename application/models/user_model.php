<?php
 
class User_model extends Main_model
{
    protected $role = null;
    public function __construct()
    {
        parent::__construct();
        $this->_init('users');
    }

    public function loadByLogin($login)
    {
        $items = $this->addFilterByField('login', $login)->getCollection();
        if (count($items)) {
            return reset($items);
        }
        else {
            return $this;
        }
    }

    public function getRole()
    {
        if (!$this->getData('role_id')) {
            return false;
        }

        if (!$this->role) {
            $this->role = Core::getModel('role')->load($this->getData('role_id'));
        }

        return $this->role;
    }
}