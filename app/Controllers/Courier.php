<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCCourier;

class Courier extends BaseController
{
    public function index()
    {
        //
        $model = new MNCCourier();
        $data['couriers'] = $model->findAll();
        
        return view('admin/pages/3rdcourier/index', $data);
    }

    public function form_add()
    {
        //
        return view('admin/pages/3rdcourier/create/index');
    }

    public function create()
    {
        $courierModel = new MNCCourier();

        $data = [
            'courier_name'      => $this->request->getPost('courierName')
        ];

        $courierModel->save($data);

        return redirect()->to('/3rdcourier')->with('message', 'Customer created successfully!');
    }
}
