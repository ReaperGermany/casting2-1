<?php
/**
 * Управление клиентами
 *
 * Содержит методы, позволяющие добавлять, удалять и редактировать данные компаний и сотрудников.
 */
class Clients extends Controller
{
    private $retval = array();

    function __construct()
    {
        parent::Controller();
    }

    function index()
    {
        $this->load->model('Company','company');
        $data['companies'] = $this->company->addOrderByField('name')->getCollection();
        $this->load->view('admin/clients/container', $data);
    }

    /**
     * Вывод списка имеющихся компаний
     */
    public function ajaxCompanyList()
    {
        $data['companies'] = $this->company->addOrderByField('name')->getCollection();
        $data['session'] = $this->session;
        $retvar = array();
        $this->retval['status'] = "OK";
        $this->retval['content'] = $this->load->view('admin/clients/companies',$data,true);

        echo json_encode($this->retval);
    }

    /**
     * Сохранение данных компании (используется и для создания новых компаний, и для редактирования уже имеющихся)
     */
    public function ajaxCompanySave()
    {
        $this->load->model('Company', 'company');

        $this->company->setData($_POST);
        if ($_POST['id']) $this->company->setId($_POST['id']);

        $this->company->save();

        $this->retval['status'] = "OK";
        $this->load->model('Staff', 'staff');
        $staffs = $this->staff->addFilterByField('fk_company', $this->company->getId())->getCollection();
        $this->retval['staffs'] = count($staffs);
        $this->retval['id'] = $this->company->getId();

        $this->ajaxCompanyList();
    }

    /**
     * Удаление компании
     */
    function ajaxCompanyDelete()
    {
        $this->load->model('Company', 'company');

        $this->company->setData($_POST);
        if ($_POST['id']) $this->company->delete($_POST['id']);

        $this->ajaxCompanyList();
    }

    /**
     * Получение списка сотрудников компании
     */
    public function ajaxStaffList()
    {
        $this->load->model('Staff', 'staff');

        if (isset($_POST['company_id'])) {
            $this->session->set_userdata('company_id',$_POST['company_id']);
        }

        if ($company_id = $this->session->userdata('company_id')){
            $this->staff->addFilterByField('fk_company',$company_id);
        }

        $data['staff_list'] = $this->staff->addOrderByField('appeal')->getCollection();
        $retvar = array();
        $retvar['status'] = "OK";
        $retvar['content'] = $this->load->view('admin/clients/staff_list',$data,true);

        echo json_encode($retvar);
    }

    /**
     * Получение данных по определенному сотруднику
     */
    public function ajaxStaffData()
    {
        $this->load->model('Staff', 'staff');

        $retvar = array();
        if (!$_POST['staff_id']) {
            $retvar['status'] = 'ERROR';
        }
        else {
            $data['staff'] = $this->staff->load($_POST['staff_id']);
            $retvar['status'] = "OK";
            $retvar['content'] = $this->load->view('admin/clients/staff_data',$data,true);
        }

        echo json_encode($retvar);
    }

    /**
     * Сохранение данных сотрудника (используется и для создания новых сотрудников, и для редактирования имеющихся)
     */
    function ajaxStaffSave()
    {
        $this->load->model("Staff","staff");
        $this->staff->setData($_POST);
        //$this->staff->setData('fk_company',(int)$_POST['company_id']);
        if (isset($_POST['staff_id']) && (int)$_POST['staff_id']) {
            $this->staff->setId((int)$_POST['staff_id']);
        }
        $this->staff->save();

        $this->ajaxStaffList();
    }

    /**
     * Удаление сотрудника
     */
    function ajaxStaffDelete()
    {
        $this->load->model("Staff","staff");
        if (isset($_POST['id']) && (int)$_POST['id']) {
            $this->staff->delete((int)$_POST['id']);
        }

        $this->ajaxStaffList();
    }
}
?>
