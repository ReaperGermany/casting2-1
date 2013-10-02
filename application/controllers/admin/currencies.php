<?php

class Currencies extends Controller
{

    function __construct()
    {
        parent::Controller();
    }

    function edit()
    {
        $this->load->model('Rate','rate');
        $data['rates'] = $this->rate->getCollection();
        $this->load->view('admin/currency/rates',$data);
    }

    function save()
    {
        $this->load->model('Rate','rate');
        foreach($_POST['currency'] as $k => $data)
        {
            $data['fk_currency'] = $k;
            $this->rate->setData($data);
            if ((int)$data['id']) {
                $this->rate->setId($data['id']);
            }
            $this->rate->save();
        }
        header("Location: ".site_url("admin/currencies/edit"));
    }
}