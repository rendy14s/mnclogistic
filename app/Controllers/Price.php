<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCPrice;
use App\Models\MNCConstantCountry;


class Price extends BaseController
{
    public function index()
    {
        //
        $priceModel     = new MNCPrice();
        $data['prices']     = $priceModel->findAll();
        
        return view('admin/pages/price/index', $data);
    }

    public function form_add()
    {
        //
        $countryModel   = new MNCConstantCountry();
        $data['countries']  = $countryModel->findAll();

        return view('admin/pages/price/create/index', $data);
    }

    public function create()
    {
        $priceModel = new MNCPrice();

        $from       = $this->request->getPost('from');
        $to         = $this->request->getPost('to');
        $service    = $this->request->getPost('service');

        $data = [
            'price_code'      => 'MNC-' . $from . '-' . $to . '-' . $service,
            'from'            => $from,
            'to'              => $to,
            'service'         => $service,
            'price'           => $this->request->getPost('price')
        ];

        $priceModel->save($data);

        return redirect()->to('/prices');
    }
}
