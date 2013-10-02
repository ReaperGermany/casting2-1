<?php

class Offers extends Controller
{
	var $group = false;
	
    function __construct()
    {
        parent::Controller();
    }

    /**
     * Вывод списка всех предложений
     */
    function index()
    {
        $this->group = true;
		$offers_order = $this->session->userdata('order_offer');
        $filters = $this->session->userdata('filter');

        $this->load->model('Offer', 'offer');

        if (!is_array($offers_order)) {
            $offers_order = array('order' => 'date', 'dir' => 'desc');
        }

        if (!isset($filters['offer'])) {
            $filters['offer'] = array(
                'date' => array('from' => "", 'to' => ""), //date('m/d/Y', time() - 3600 * 24 * 7)
                'status' => Offer::STATUS_ACTIVE
            );
        }
        $this->offer->applyFilters($filters['offer']);
        $data['offers'] = $this->offer
                        //->addOrderByField($offers_order['order'], $offers_order['dir'])
						->addOrderByField('manufacturer', 'asc')
						->addOrderByField('model', 'asc')
                        ->addFilterByField('type', 'offer')
                        //->addFilterByField('main_table.status', Offer::STATUS_ACTIVE)
                        ->getCollection();


        $requests_order = $this->session->userdata('order_request');
        $this->load->model('Offer', 'request');
        if (!is_array($requests_order)) {
            $requests_order = array('order' => 'date', 'dir' => 'desc');
        }
        if (!isset($filters['request'])) {
            $filters['request'] = array(
                'date' => array('from' => "", 'to' => ""),
                'status' => Offer::STATUS_ACTIVE
            );
        }

        //$this->request->applyFilters($filters['request']);
        $data['requests'] = $this->request
                        ->addOrderByField($requests_order['order'], $requests_order['dir'])
                        ->addFilterByField('type', 'request')
                        //->addFilterByField('main_table.status', Offer::STATUS_ACTIVE)
                        ->getCollection();

        $this->load->model('Attribute_value', 'av');
        foreach ($this->av->getCodesList() as $code) {
            $this->load->model('Attribute_value', $code);
            $items = $this->$code->setAttributeCode($code)->getCollection();
            $retval = array();
            foreach ($items as $item) {
                $retval[] = array(
                    'label' => $item->getData('value'),
                    'value' => $item->getData('value')
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
                'value' => $item->getData('name')
            );
        }
        $data['attrs']['company_name'] = $retval;

        $this->load->model('Staff', 'staff');
        $items = $this->staff->getCollection();
        $retval = array();
        foreach ($items as $item) {
            $retval[] = array(
                'label' => $item->getData('appeal'),
                'value' => $item->getData('appeal')
            );
        }
        $data['attrs']['appeal'] = $retval;


        $data['orders'] = array('offer' => $offers_order, 'request' => $requests_order);
        $data['filters'] = $filters;
        $this->load->view('admin/offers_list', $data);
    }

    /**
     * Установка параметров сортировки
     */
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

    /**
     * Установка параметров фильтрации
     */
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

    /**
     * Добавление заявки
     */
    function add()
    {
        if ($type = $this->uri->segment(3, 0)) {
			$this->load->model('staff', 'staff');
			$data['types'] = $this->uri->segment(4, 0);
            $emails = $this->staff->getCollection();
            $retval = array();
            foreach ($emails as $email) {
                if (!$email->getData('email')=='') {
				$retval[] = array(
                    'label' => $email->getData('email'),
                    'value' => $email->getData('email'),
					'name' => $email->getData('company_name'),
                    'id' => $email->getData('fk_company'),
					'staff_id'=>$email->getId()				
                );
				}
            }
            $data['emails'] = prepare_autocomplete_list($retval);
			
            $this->load->model('Company', 'company');
            $data['type'] = $type;
			
            $companies = $this->company->getCollection();
            $retval = array();
            foreach ($companies as $company) {
                $retval[] = array(
                    'label' => $company->getData('name'),
                    'value' => $company->getData('name'),
                    'id' => $company->getId()
                );
            }
            $data['companies'] = prepare_autocomplete_list($retval);

            $this->load->model('Attribute_value', 'av');
            foreach ($this->av->getCodesList() as $code) {
                $this->load->model('Attribute_value', $code);
                $items = $this->$code->setAttributeCode($code)->getCollection();
                $retval = array();
                foreach ($items as $item) {
                    $retval[$item->getId()] = array(
                        'label' => $item->getData('value'),
                        'value' => $item->getData('value'),
                        'id' => $item->getId()
                    );
                }
                $data['attrs'][$code] = $retval;
            }
            $this->load->model('Requires', 'req');
            if ($type == 'add') {$data['attrs'] = $this->req->prepareAttrs_add($data['attrs']);}
			else {$data['attrs'] = $this->req->prepareAttrs($data['attrs']);}
			$data['av_model'] = $this->av;
            $this->load->view('admin/offer/add_form', $data);
            return;
        }
        $this->load->view('admin/offers_list');
    }

    function savePost()
    {
        $this->load->view('admin/offers_list');
    }

    function importForm()
    {
        $this->load->view('admin/import/form');
    }

    function import()
    {
        $errors = array();
        $data = array();
        if (!isset($_FILES['import_file'])) $errors[] = "Нет данных о файле";
        elseif ($_FILES['import_file']['error'])
                $errors[] = "Ошибка при загрузке файла";

        $fp = fopen($_FILES['import_file']['tmp_name'], 'rt') or $errors[] = "Ошибка открытия файла";
        $data['errors'] = $errors;
        if (!count($errors)) {
            $this->load->model('Import', 'import');
            $data['result'] = $this->import->parceFile($fp);
            $data['log'] = $this->import->getLogFile();
        }

        $this->load->view('admin/import/result', $data);
    }

}
