<?php

class Cron2 extends Controller
{
	var $col_cp = array();
	var $col_c = array();
	var $col_m = array();
	var $col_s = array();
	var $col_ma = array();
	var $col_update = array();
	var $id_user = "";	
	var $_cron2 = false;
	private $log_file = null;
    private $log_file_name = null;
	
	public function index()
	{
		$filters = $this->session->userdata('filter');
		$offers_order = $this->session->userdata('order_subscribe');
		if (!is_array($offers_order)) {
            $offers_order = array( 'order' => 'pk_id', 'dir' => 'desc');
        }
		if (!isset($filters['subscribe'])) {
            $filters['subscribe'] = array(
                //'status' => 'Active'
            );
        }
		
		$data = array();		
		$this->load->model('userf_model', 'user');
		$this->user->applyFilters($filters['subscribe']);		
		$collection = $this->user->addOrderByField($offers_order['order'], $offers_order['dir'])->getCollection();
		foreach ($collection as $col)
		{
			$users[$col->getId()] = array(
			'id'=>$col->getId(),
			'login'=>$col->getData('login')
			);
		}
		
		$this->load->model('sends', 'send');
		$subcribe = array();
		foreach ($users as $user)
		{
			if (count($this->send->addFilterByField('status', 1)->addFilterByField('user_id', $user['id'])->getCollection())) 
				{
					$subcribe[$user['id']] = array (
					'id'=>$user['id'],
					'login'=>$user['login'],
					'status'=> 1					
					);				
				}
			elseif (count($this->send->addFilterByField('status', 0)->addFilterByField('user_id', $user['id'])->getCollection()))
				{
					$subcribe[$user['id']] = array (
					'id'=>$user['id'],
					'login'=>$user['login'],
					'status'=> 0					
					);				
				}
		}
		/////////////////////////////////////////////////////
		$this->load->model('sends', 'sends');
		$status = $this->sends
				->addFilterByField('offer_id', 0)
				->addFilterByField('user_id', 0)
				->getCollection();
		if (!count($status)) 
			{
				$this->send->setData(array('user_id'=>0, 'offer_id'=>0, 'status'=>0))->save();
				$status = 0;
			}
		else $status = $status[0]->getData('status');
		
		$data['status'] = $status;
		////////////////////////////////////////////////////
		$data['users'] = $subcribe;
		$this->load->view('admin/cron/index', $data);
	
	}	
	
	
	/*  http://magento.loc/dimex/admin/cron2/sends/h/2000
	where behind 'h' set hour  */
    public function sends()
    {
		$this->_cron2 = false;
		$this->load->model('sends', 'sends');
		$status = $this->sends
				->addFilterByField('offer_id', 0)
				->addFilterByField('user_id', 0)
				->getCollection();
		if (count($status))
			if ($status[0]->getData('status'))
		{

			$request = $this->uri->segment(5, 0);
	        if (isset($request))
			{$from_time = date('Y-m-d H:i:s', time()-$request*3600);}
			else
			{$from_time = date('Y-m-d H:i:s', time()-24*3600);}
			$to_time = date('Y-m-d H:i:s', time());
			
			$users = array ();
			$this->load->model('userf_model', 'user');
			$collection = $this->user->getCollection();
			foreach ($collection as $col)
			{
				$users[$col->getId()] = array(
				'id'=>$col->getId(),
				'login'=>$col->getData('login'),
				'email'=>$col->getData('email')
				);
			
			}
		
			foreach ($users as $user)
			{
				$this->col_cp = array();
				$this->col_c = array();
				$this->col_m = array();
				$this->col_s = array();
				$this->col_ma = array();
				$id_user = "";	
				
				$this->setNacenkaUsers($user['id']);
				$this->load->model('Offer_front', 'offer');
				$filters = array ('dt_from'=>$from_time, 'dt_to'=>$to_time);
				$data = '';
					$this->offer->applyFilters($filters);
					$collection = $this->offer
						->addFilterByField('send.user_id', $user['id'])
						->addFilterByField('send.status', 1)
						->addFilterByField('type', 'offer')
						->getCollection();
					$data = $this->setNacenkaPrice($collection);
				if (count($data)) $this->getSend($user, $data);
			}
		}
    }
	
	// sends updates offers for users
	/*  http://magento.loc/dimex/admin/cron2/sends_updates/h/2000
	where behind 'h' set hour  */
	public function sends_updates()
    {
		$this->_cron2 = true;
		$this->load->model('sends', 'sends');
		$status = $this->sends
				->addFilterByField('offer_id', 0)
				->addFilterByField('user_id', 0)
				->getCollection();
		if (count($status))
			if ($status[0]->getData('status'))
		{			
			$request = $this->uri->segment(5, 0);
	        if (isset($request) && $request>0)
			{$from_time = time()-$request*3600;}
			else
			{$from_time =  time()-24*3600;}
			$to_time = time();
			
			$users = array ();
			$this->load->model('userf_model', 'user');
			$collection = $this->user->getCollection();
			foreach ($collection as $col)
			{
				$users[$col->getId()] = array(
				'id'=>$col->getId(),
				'login'=>$col->getData('login'),
				'email'=>$col->getData('email')
				);
			
			}
		
			foreach ($users as $user)
			{
				$this->col_cp = array();
				$this->col_c = array();
				$this->col_m = array();
				$this->col_s = array();
				$this->col_ma = array();
				$this->col_update = array();
				$id_user = "";	
				
				$this->load->model('sends', 'sends');
				$col = $this->sends
				->addFilterByField('offer_id', 0)
				->addFilterByField('user_id', $user['id'])
				->addFilterByField('status', 1)
				->getCollection();
				if (count($col))
				{
					foreach ($col as $cl)
					{
						$this->col_update[$cl->getId()] = $cl->getData('manufacturer_id');
					}
				

				$this->setNacenkaUsers($user['id']);
				$this->load->model('Offer_front', 'offer');
				$filters = array ('dt_from2'=>$from_time, 'dt_to2'=>$to_time);
				$data = '';
					$this->offer->applyFilters($filters);
					$collection = $this->offer
						->addFilterByField('type', 'offer')
						->getCollection();
					$data = $this->setNacenkaPrice($collection);

				if (count($data)) $this->getSend($user, $data);
				}
			}
		}
    }
	
	// sends updates offers for users
	/*  http://magento.loc/dimex/admin/cron2/sends_updates_all/h/2000
	where behind 'h' set hour  */
	public function sends_updates_all()
    {
		$this->log_file_name = 'logs/cron_all_sends_'.date('Y-m-d_H-i-s').'.log';
        $this->log_file = fopen($this->log_file_name,'a+');
        if ($this->log_file)
            fputs($this->log_file,"============= Cron send all mails ".date('d-M-Y H:i:s')." =============\r\n");
		
		$this->_cron2 = false;
		$this->load->model('sends', 'sends');
		$status = $this->sends
				->addFilterByField('offer_id', 0)
				->addFilterByField('user_id', 0)
				->getCollection();
		if (count($status))
			if ($status[0]->getData('status'))
		{			
			$request = $this->uri->segment(5, 0);
	        if (isset($request) && $request>0)
			{$from_time = time()-$request*3600;}
			else
			{$from_time =  time()-24*3600;}
			$to_time = time();
			
			$users = array ();
			$this->load->model('userf_model', 'user');
			$collection = $this->user->getCollection();
			foreach ($collection as $col)
			{
				$users[$col->getId()] = array(
				'id'=>$col->getId(),
				'login'=>$col->getData('login'),
				'email'=>$col->getData('email')
				);
			
			}
		
			foreach ($users as $user)
			{
				$this->col_cp = array();
				$this->col_c = array();
				$this->col_m = array();
				$this->col_s = array();
				$this->col_ma = array();
				$this->col_update = array();
				$id_user = "";	
				
				$this->load->model('sends', 'sends');
				$col = $this->sends
				->addFilterByField('offer_id', 0)
				->addFilterByField('manufacturer_id', 0)
				->addFilterByField('user_id', $user['id'])
				->addFilterByField('status', 1)
				->getCollection();
				if (count($col))
				if ($col[0]->getData('status')==1)
				{
				$this->setNacenkaUsers($user['id']);
				$this->load->model('Offer_front', 'offer');
				$filters = array ('dt_from2'=>$from_time, 'dt_to2'=>$to_time);
				$data = '';
					$this->offer->applyFilters($filters);
					$collection = $this->offer
						->addFilterByField('type', 'offer')
						->getCollection();
					$data = $this->setNacenkaPrice($collection);

				if (count($data)) {
					if ($this->getSend($user, $data)){
						if ($this->log_file) {
							fputs($this->log_file,"User: ".$user['login']." send successful \r\n");
						}
					}
					else {
						if ($this->log_file) {
							fputs($this->log_file,"User: ".$user['login']." send error \r\n");
						}
					
					}
				}
				}
			}
		}
    }
	
	function getSend($user, $data)
	{
		$subject = "Dimex updating"; 
		$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?='; 
		$table = '<table>'
				.'<thead>'
				.'<tr>'
				//.'<th>'
				//.'ID'
				//.'</th>'
				.'<th>'
				.'Manufacturer'
				.'</th>'
				.'<th>'
				.'Model'
				.'</th>'
				//.'<th>'
				//.'Color'
				//.'</th>'
				//.'<th>'
				//.'Spec'
				//.'</th>'
				//.'<th>'
				//.'Company name'
				//.'</th>'
				/*.'<th>'
				.'Brand'
				.'</th>'
				.'<th>'
				.'Location'
				.'</th>'*/
				.'<th>'
				.'Updated at'
				.'</th>'
				.'<th>'
				.'Offer Price'
				.'</th>'
				.'</tr>'
				.'</thead>';
		$table .= '<tbody>';
		foreach ($data as $dat)
		{
			$table .= '<tr>'
				//.'<td>'
				//.$dat->getData('pk_id')
				//.'</td>'
				.'<td>'
				.$dat->getData('manufacturer')
				.'</td>'
				.'<td>'
				.$dat->getData('model')
				.'</td>'
				//.'<td>'
				//.$dat->getData('color')
				//.'</td>'
				//.'<td>'
				//.$dat->getData('spec')
				//.'</td>'
				//.'<td>'
				//.company_code($dat->getData('fk_staff'))
				//.'</td>'
			/*	.'<td>'
				.$dat->getData('brand')
				.'</td>'
				.'<td>'
				.$dat->getData('location')
				.'</td>'*/
				.'<td>'
				.date('m/d/Y',strtotime($dat->getData('updated_at')))
				.'</td>'
				.'<td>'
				.round($dat->getData('price'),2)
				.'</td>'
				.'</tr>';		
		}
		$table .= '</tbody></table>';
		$message = ' 
		<html> 
		    <head> 
		        <title>Dimex updating</title> 
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
	
	function setNacenkaUsers($id)
	{
		$this->id_user = $id;
		$this->load->model('visible_model', 'vis');
		$Color = $this->vis->addFilterByField('user_id', $id)
				->getCollection();
		foreach($Color as $color) 
		{
			if ($color->getData('code_atr')=='color') 
				{$this->col_c[$color->getId()]=$color->getData('value');} 
			if ($color->getData('code_atr')=='model') 
				{$this->col_m[$color->getId()]=$color->getData('value');} 
			if ($color->getData('code_atr')=='spec') 
				{$this->col_s[$color->getId()]=$color->getData('value');} 
			if ($color->getData('code_atr')=='manufacturer') 
				{$this->col_ma[$color->getId()]=$color->getData('value');} 
			if ($color->getData('code_atr')=='company_name') 
				{$this->col_cp[$color->getId()]=$color->getData('value');} 
		}		
		
	}
	
	function setNacenkaPrice($collection)
    {
        $this->load->model('nacenka_model', 'nac');
		$colect = $this->nac
					->addFilterByField('user_id', 0)
					->getCollection();
		$above_m = 0;
		$above = 0;
		$below_m = 0;
		$below = 0;
		foreach ($colect as $col)
		{
			if ($col->getData('code_atr')=='above') 
			{
				$above_m = $col->getData('mnog');
				$above = $col->getData('value');
			}
			if ($col->getData('code_atr')=='below') 
			{
				$below_m = $col->getData('mnog');
				$below = $col->getData('value');
			}
		}
		
		$nacenka = 0;
		$colect = $this->nac
					->addFilterByField('user_id', $this->id_user)
					->getCollection();
		if (count($colect)) {$nacenka = $colect[0]->getData('mnog');}
		
		foreach($collection as $col)
		{
			$col->setData('price', $col->getData('price')+$col->getData('price')*$nacenka/100);			
			if ($col->getData('price')<= $below) 	
				{
					$col->setData('price', $col->getData('price')+$col->getData('price')*$below_m/100);				
				}
			elseif ($col->getData('price')>= $above)
				{
					$col->setData('price', $col->getData('price')+$col->getData('price')*$above_m/100);				
				}
		
		}		
		return $collection;
    }
	
	function unsubscribe()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
            $this->load->model('Sends','send');
			foreach ($_POST['ids'] as $item)
			{
				$pk_id = $this->send->addFilterByField('user_id', $item)->getCollection();
				if (count($pk_id))
				{
				foreach ($pk_id as $id)
					{
					$this->send->load($id->getId())->setData('status', 0)->save();
					}
				}
			
			}
            $retval['status'] = "OK";
	        $this->load->model('userf_model', 'user');
			$collection = $this->user->getCollection();
			foreach ($collection as $col)
			{
				$users[$col->getId()] = array(
				'id'=>$col->getId(),
				'login'=>$col->getData('login')
				);
			}
			
			$this->load->model('sends', 'send');
			$subcribe = array();
			foreach ($users as $user)
			{
				if (count($this->send->addFilterByField('status', 1)->addFilterByField('user_id', $user['id'])->getCollection())) 
					{
						$subcribe[$user['id']] = array (
						'id'=>$user['id'],
						'login'=>$user['login'],
						'status'=> 1					
						);				
					}
				elseif (count($this->send->addFilterByField('status', 0)->addFilterByField('user_id', $user['id'])->getCollection()))
					{
						$subcribe[$user['id']] = array (
						'id'=>$user['id'],
						'login'=>$user['login'],
						'status'=> 0					
						);				
					}
			}
			$data['items'] = $subcribe;
			$data['type'] = $_POST['type'];
            $retval['content'] = $this->load->view('admin/cron/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
	
	function subscribe()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }
        try {
            $this->load->model('Sends','send');
			foreach ($_POST['ids'] as $item)
			{
				$pk_id = $this->send->addFilterByField('user_id', $item)->getCollection();
				if (count($pk_id))
				{
				foreach ($pk_id as $id)
					{
					$this->send->load($id->getId())->setData('status', 1)->save();
					}
				}
			
			}
            $retval['status'] = "OK";
	        $this->load->model('userf_model', 'user');
			$collection = $this->user->getCollection();
			foreach ($collection as $col)
			{
				$users[$col->getId()] = array(
				'id'=>$col->getId(),
				'login'=>$col->getData('login')
				);
			}
			
			$this->load->model('sends', 'send');
			$subcribe = array();
			foreach ($users as $user)
			{
				if (count($this->send->addFilterByField('status', 1)->addFilterByField('user_id', $user['id'])->getCollection())) 
					{
						$subcribe[$user['id']] = array (
						'id'=>$user['id'],
						'login'=>$user['login'],
						'status'=> 1					
						);				
					}
				elseif (count($this->send->addFilterByField('status', 0)->addFilterByField('user_id', $user['id'])->getCollection()))
					{
						$subcribe[$user['id']] = array (
						'id'=>$user['id'],
						'login'=>$user['login'],
						'status'=> 0					
						);				
					}
			}
			$data['items'] = $subcribe;
			$data['type'] = $_POST['type'];
            $retval['content'] = $this->load->view('admin/cron/table',$data,true);
            echo json_encode($retval);
            return;
        }
        catch(Exception $e){

        }
    }
	
	function status()
	{
		$retval = array();
		if (!isset($_POST['stat']) || !count($_POST['stat'])) {
            $retval['stat'] = "ERROR";
            echo json_encode($retval);
            return;
        }
		
		$this->load->model('sends','send');
		$id = $this->send
				->addFilterByField('offer_id', 0)
				->addFilterByField('user_id', 0)
				->getCollection();
		$this->send->load($id[0]->getId())->setData('status', $_POST['stat'])->save();
		$retval['status'] = "OK";
		echo json_encode($retval);
        return;
	
	}
	
	function statusFront()
	{
		$retval = array();
		if ((!isset($_POST['stat']) || !count($_POST['stat'])) && (!isset($_POST['login']) || !count($_POST['login']))) {
            $retval['stat'] = "ERROR";
            echo json_encode($retval);
            return;
        }
		$this->load->model('userf_model','user');
		$user_id = $this->user->loadByLogin($_POST['login'])->getData('pk_id');
		
		
		$this->load->model('sends','send');
		$id = $this->send
				->addFilterByField('offer_id', 0)
				->addFilterByField('manufacturer_id', 0)
				->addFilterByField('user_id', $user_id)
				->getCollection();
		if (count($id)) {
		$this->send->load($id[0]->getId())->setData('status', $_POST['stat'])->save();
		}
		else
		{
		$this->send->setData('offer_id', 0)
					->setData('manufacturer_id', 0)
					->setData('user_id', $user_id)
					->setData('status', $_POST['stat'])->save();
		}
		$retval['status'] = "OK";
		echo json_encode($retval);
        return;
	
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
     * —брос фильтра
     */
    function reset()
    {
        $filters = $this->session->userdata('filter');
        $filters[$_POST['type']] = array();
        $this->session->set_userdata('filter', $filters);
    }
}
 
