<?php
 
class Userf_model extends Main_model
{
    protected $role = null;
    public function __construct()
    {
        parent::__construct();
        $this->_init('userf');
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
	
	public function getUsers()
    {
        $items = $this->getCollection();
        if (count($items)) {
            return $items;
		}
		else return NULL;
    }
	
	public function getCount()
    {
        return $this->db->count_all_results('userf');
    }
	
	public function applyFilters($filters)
    {
        if (!is_array($filters)) return $this;
       // if (isset($filters['pk_id']) && $filters['pk_id'] != "")
      //          $this->addFilterByField('pk_id =', $filters['pk_id']);

        if (isset($filters['login']) && $filters['login'] != "")
                $this->addFilterByField('login LIKE', '%' . $filters['login'] . '%');

        if (isset($filters['email']) && $filters['email'] != "")
                $this->addFilterByField('email LIKE', '%' . $filters['email'] . '%');

        if (isset($filters['role_id']) && $filters['role_id'] != "")
            if ($filters['role_id']=='Active') 
				{
					$this->db->where('role_id', 3);
				}
			elseif ($filters['role_id']=='Disactive') 
				{
					$this->db->where('role_id', 4);
				}
		
		if (isset($filters['limit']) && $filters['limit'] != "")
               $this->db->limit($filters['limit'], $filters['limit_from']);	
			
    }
	
	public function sendPass($user, $admin=false)
	{
		$subject = "Dimex recovery password"; 
		$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?='; 
		if ($admin) {
		$table = '
		<h2>Dear '.$user['login'].'</h2>
		<div>
		At your request, the password to your account has been changed by the site administrator Dimex.
		</br>
		Login: '.$user['login'].'</br>
		Password: '.$user['pass'].'</br></br>
		This letter expelled automatically at him not need to answer.
		</div>
		';}
		else {
		$table = '
		<h2>Dear '.$user['login'].'</h2>
		<div>
		You with a form of restoration of the password was asked for a password to your account at Dimex.
		</br>
		Login: '.$user['login'].'</br>
		Password: '.$user['pass'].'</br></br>
		This letter expelled automatically at him not need to answer.
		</div>
		';
		}
		$message = ' 
		<html> 
		    <head> 
		        <title>Dimex recovery password</title> 
		    </head> 
		    <body>'
				.$table.
			'</body> 
		</html>'; 

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=windows-1251\r\n";
		$headers .= "To: <".$user['email'].">\r\n";
		$headers .= "From: Dimex <xxx@xx.ru>\r\n";
		$to  = $user['login']." <".$user['email'].">, " ; 
		//$headers  = "Content-type: text/html; charset=windows-1251 \r\n"; 
		//$headers .= "From: Dimex updating\r\n"; 

		return mail($to, $subject, $message, $headers); 
	}
	
}