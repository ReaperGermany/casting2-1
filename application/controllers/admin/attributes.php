<?php

class Attributes extends Controller
{

    function __construct()
    {
        parent::Controller();
    }

    function index()
    {
        $this->load->model('Attribute_value', 'av');
        $data['av_model'] = $this->av;
        $this->load->view('admin/attributes/list',$data);
    }

    public function ajaxAttributeList()
    {
        $order = $this->session->userdata('model_order');
        if (!is_array($order)) {
            $order = array('order'=>'model', 'dir'=>'asc');
        }
        $this->load->model('Attribute_value', 'av');
        $this->av->addFilterByField('code',$_POST['code']);
        $this->av->addOrderByField('value', $order['dir']);
        $data['values'] = $this->av->getCollection();
        $data['code'] = $_POST['code'];
        $data['order'] = $order;
        $data['av_model'] = $this->av;
		$data['type'] = $_POST['code'];
        $retval = array();
        $retval['status'] = "OK";
        $retval['code'] = $_POST['code'];
        if ($_POST['code']=='model==') {
            $data = $this->_prepareModelData($data);
            $retval['content'] = $this->load->view('attributes/model',$data,true);
            $retval = $this->_prepareModelRetval($retval, $data);
        }
        else {
            $retval['content'] = $this->load->view('admin/attributes/container',$data,true);
        }
        echo json_encode($retval);
    }
	
	public function ajaxAttributeList2()
    {
        $this->load->model('Attribute_value', 'av');
        $this->av->addFilterByField('code',$_POST['code']);
        $data['values'] = $this->av->getCollection();
        $retval = array();
        $retval['status'] = "OK";
        $retval['code'] = $_POST['code'];
        $retval['content'] = $this->load->view('admin/visible/attr',$data,true);
        echo json_encode($retval);
    }
	
	public function ajaxAttributeList3()
    {
        //$this->load->model('Company', 'cp');
		$this->load->model('Staff', 'cp');
        $data['values'] = $this->cp->getCollection();
        $retval = array();
        $retval['status'] = "OK";
        $retval['code'] = $_POST['code'];
        $retval['content'] = $this->load->view('admin/visible/attr2',$data,true);
        echo json_encode($retval);
    }
	
	public function ajaxAttributeList4()
    {
        $this->load->model('Offer', 'cp');
        $data['values'] = $this->cp->addFilterByField('type', 'offer')->getCollection();
        $retval = array();
        $retval['status'] = "OK";
        $retval['code'] = $_POST['code'];
        $retval['content'] = $this->load->view('admin/nacenka/attr',$data,true);
        echo json_encode($retval);
    }

	
	public function saveImage()
	{
		if (!is_dir('skin/images/logo')) {mkdir('skin/images/logo');}
		
		if($_FILES["filename"]["size"] > 1024*3*1024)
		{
	     $err_msg ="The file very big.";
	    exit;
		}
		$path = array(".jpg",".png",".gif");
		$flag = false;
		 foreach ($path as $item){
		
		  if($item == strtolower(strrchr($_FILES['filename']['name'], '.'))) {
			$flag = true;
			break;
		  }
		 }
		if ($flag) {
		if($_FILES['filename']['error'] != 0){
		$error = fopen("error/error.dat","wb");
		if(fwrite($error,$_FILES['filename']['error']) == false){
			$err_msg="Error write file.";
			exit();
		}else
		{
			unlink($_FILES['filename']['tmp_name']);
			exit();
		}
		fclose($error);
		}
		
		$name_image = "skin/images/logo/".$_FILES["filename"]["name"];
		if ( move_uploaded_file($_FILES["filename"]["tmp_name"], $name_image)) {
			$err_msg= "File upload.";
	
			$img = base_url().$name_image;
			$img2 = $name_image;
			$height = 30;
			switch ( strtolower(strrchr($img, '.')) ){
			case ".jpg":
				$srcImage = @imagecreatefromjpeg($img);
				break;
			case ".gif":
				$srcImage = @ImageCreateFromGIF($img);
				break;
			case ".png":
				$srcImage = @ImageCreateFromPNG($img);
				break;
			}
		$srcWidth = ImageSX($srcImage);
		$srcHeight = ImageSY($srcImage);
		
		   
			$destHeight = $height;
			$destWidth = $height*$srcWidth/$srcHeight;
			$resImage = ImageCreateTrueColor($destWidth, $destHeight);
			imageAlphaBlending($resImage, false);
			imageSaveAlpha($resImage, true);
			ImageCopyResampled($resImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
			
			switch ( strtolower(strrchr($img2, '.')) ){
			case ".jpg":
				imagejpeg($resImage, $img2); 
				break;
			case ".gif":
				ImageGIF($resImage, $img2);
				break;
			case ".png":
				ImagePNG($resImage, $img2);
				break;
			}
			ImageDestroy($srcImage);
			ImageDestroy($resImage);
		

			if (isset($_POST['id']) && $id = $_POST['id']){
				$this->load->model('Attribute_value', 'av');
				$this->av->load($id)->setData('image', $name_image)->save();
				redirect("admin/attributes","refresh");
			}
		} else {
			$err_msg= "Error file.";
			exit;
		}
		}
		else
		{
			echo "Error type file.";
			exit;
		}
	}

	public function ajaxDeleteImage()
	{
		if (isset($_POST['id']) && $id = $_POST['id']){
			$this->load->model('Attribute_value', 'av');
			$this->av->load($id)->setData('image', null)->save();
			$order = $this->session->userdata('model_order');
	        if (!is_array($order)) {
	            $order = array('order'=>'model', 'dir'=>'asc');
	        }
	        $this->load->model('Attribute_value', 'av');
	        $this->av->addFilterByField('code', 'manufacturer');
	        $this->av->addOrderByField('value', $order['dir']);
	        $data['values'] = $this->av->getCollection();
	        $data['code'] = 'manufacturer';
	        $data['order'] = $order;
	        $data['av_model'] = $this->av;
			$data['type'] = 'manufacturer';
	        $retval = array();
	        $retval['status'] = "OK";
	        $retval['code'] = 'manufacturer';
	        $retval['content'] = $this->load->view('admin/attributes/container',$data,true);
	        echo json_encode($retval);
		
		}
	
	}
    /**
     * Быстрое добавление нового атрибута
     */
    public function ajaxAttributeAdd()
    {
        $this->load->model('Attribute_value', 'av');
        $items = $this->av->addFilterByField('value', trim($_POST['value'], ' '))
                 ->addFilterByField('code', $_POST['code'])
                 ->getCollection();
        if (count($items)) {
            $item = $items[0];
        }
        else {
			$this->av->setData('code', $_POST['code']);
			$this->av->setData('value', trim($_POST['value'], ' '));
            $this->av->save();
            $item = $this->av;
        }

        try {
            $this->load->model('Requires', 'req');
            $this->req->add($item, $_POST['require_code'], $_POST['require_id']);
            $retval = array(
                "status" => "OK",
                "id" => $item->getId(),
                "code" => $item->getData("code"),
                "value" => $item->getData("value")
            );
        }
        catch (Exception $e) {
            $retval = array(
                "status" => "ERROR",
                "message" => $e->getMessage()
            );
        }

        echo json_encode($retval);
    }

    
    public function ajaxAttributeFastSave()
    {
        //var_dump($_POST);exit();
        $this->load->model('Attribute_value', 'av');
        $requires=array();
        if (isset($_POST['manufacturers']))
            $requires[Attribute_value::CODE_MANUFACTURER] = $_POST['manufacturers'];

        if (isset($_POST['colors']))
            $requires[Attribute_value::CODE_COLOR] = $_POST['colors'];

        if (isset($_POST['specs']))
            $requires[Attribute_value::CODE_SPEC] = $_POST['specs'];
        
        $this->av->setData('code', $_POST['code']);
		$this->av->setData('value', trim($_POST['value'], ' '));
        $this->av->setData('requires',$requires);
        if ($_POST['id']) $this->av->setId($_POST['id']);

        $collection = $this->av
                ->addFilterByField('value', trim($_POST['value'], ' '))
                ->addFilterByField('code', $_POST['code'])
                ->getCollection();

        if (count($collection) && $_POST['id']=='') {
            $retval = array(
                'status' => "ERROR",
                "message" => "Value '".trim($_POST['value'], ' ')."' of attribute '".$_POST['code']."' already exists."
                );
            echo json_encode($retval);
            return;
        }
        $this->av->resetFilter();
        $this->av->save();

        $this->load->model('Attribute_value', 'av');
        foreach ($this->av->getCodesList() as $code) {
            $this->load->model('Attribute_value', $code);
            $items = $this->$code->setAttributeCode($code)->getCollection();
            $retval = array();
            foreach ($items as $item) {
                $retval[] = array(
                    'label' => $item->getData('value'),
                    'value' => $item->getData('value'),
					'id' => $item->getId()
                );
            }
            $data['attrs'][$code] = $retval;
        }
        $this->load->model('Company', 'company');
        $items = $this->company->getCollection();
        $retval = array();
        foreach ($items as $item) {
            $retval[] = array(
                'label' => $item->getData('name'),
                'value' => $item->getData('name'),
				'id' => $item->getId()
            );
        }
        $data['attrs']['company_name'] = $retval;

        $this->load->model('Staff', 'staff');
        $items = $this->staff->getCollection();
        $retval = array();
        foreach ($items as $item) {
            $retval[] = array(
                'label' => $item->getData('appeal'),
                'value' => $item->getData('appeal'),
				'id' => $item->getId()
            );
        }
        $data['attrs']['appeal'] = $retval;
		$this->load->model('Requires', 'req');
        $data['attrs'] = $this->req->prepareAttrs_add($data['attrs']);
		
		$retval = array(
                'status' => "OK",
                "attr" => $data['attrs']
                );
            echo json_encode($retval);
            return;
    }
	
/**
     * Сохранение атрубута при редактировании
     */
	public function ajaxAttributeSave()
    {
        //var_dump($_POST);exit();
        $this->load->model('Attribute_value', 'av');
        $requires=array();
        if (isset($_POST['manufacturers']))
            $requires[Attribute_value::CODE_MANUFACTURER] = $_POST['manufacturers'];

        if (isset($_POST['colors']))
            $requires[Attribute_value::CODE_COLOR] = $_POST['colors'];

        if (isset($_POST['specs']))
            $requires[Attribute_value::CODE_SPEC] = $_POST['specs'];
        
        $this->av->setData('code', $_POST['code']);
		$this->av->setData('value', trim($_POST['value'], ' '));
		if (trim($_POST['image'], ' ')!=null) $this->av->setData('image', trim($_POST['image'], ' '));
        $this->av->setData('requires',$requires);
        if ($_POST['id']) $this->av->setId($_POST['id']);

        $collection = $this->av
                ->addFilterByField('value', trim($_POST['value'], ' '))
                ->addFilterByField('code', $_POST['code'])
                ->getCollection();

        if (count($collection) && $_POST['id']=='') {
            $retval = array(
                'status' => "ERROR",
                "message" => "Value '".trim($_POST['value'], ' ')."' of attribute '".$_POST['code']."' already exists."
                );
            echo json_encode($retval);
            return;
        }
        $this->av->resetFilter();

        $this->av->save();

        $this->ajaxAttributeList();
    }

    public function ajaxAttributeDelete()
    {
        $retval = array();
        if (!isset($_POST['ids']) || !count($_POST['ids'])) {
            $retval['status'] = "ERROR";
            echo json_encode($retval);
            return;
        }

        try {
            $this->load->model('Attribute_value', 'av');
            $this->av->delete($_POST['ids']);

			
			//if (!is_array($_POST['ids'])) $ids = array($_POST['ids']);
			//foreach ($ids as $id)
			
            $this->ajaxAttributeList();
            return;
        }
        catch(Exception $e){

        }
    }

    protected function _prepareModelData($data)
    {
        $this->load->model('Attribute_value', 'av_model');

        $data['attrs']['manufacturers'] = $this->_toArray(
                $this->av_model
                ->resetFilter()
                ->addFilterByField('code',Attribute_value::CODE_MANUFACTURER)
                ->addOrderByField('value', $data['order']['dir'])
                ->getCollection()
                );
        $data['attrs']['colors'] = $this->_toArray(
                $this->av_model
                ->resetFilter()
                ->addFilterByField('code',Attribute_value::CODE_COLOR)
                ->addOrderByField('value')
                ->getCollection()
                );
        $data['attrs']['specs'] = $this->_toArray(
                $this->av_model
                ->resetFilter()
                ->addFilterByField('code',Attribute_value::CODE_SPEC)
                ->addOrderByField('value')
                ->getCollection()
                );

        return $data;
    }

    protected function _toArray($data)
    {
        $retval=array();
        foreach ($data as $item)
        {
            $retval[] = array('id'=> $item->getId(), 'label'=>$item->getData('value'));
        }
        return $retval;
    }

    protected function _prepareModelRetval($retval, $data)
    {
        $retval['manufacturers'] = $data['attrs']['manufacturers'];
        $retval['colors'] = $data['attrs']['colors'];
        $retval['specs'] = $data['attrs']['specs'];

        return $retval;
    }

     /**
     * Установка параметров сортировки
     */
    function setAttributeOrder()
    {
        $request = $this->uri->uri_to_assoc();
        if (isset($request['order']))
        {
            if (!isset($request['dir'])) $request['dir']='asc';
            $this->session->set_userdata('model_order',$request);
        }
        $this->ajaxAttributeList();
    }
}
