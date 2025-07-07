<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCCustomer;

class Customers extends BaseController
{
    public function index()
    {
        //
        $customerModel = new MNCCustomer();
        $data['customers'] = $customerModel->findAll();
        
        return view('admin/pages/customers/index', $data);
    }

    public function form_add()
    {
        //

        return view('admin/pages/customers/create/index');
    }

    public function create()
    {
        $customerModel = new MNCCustomer();

        $data = [
            'marking_code'      => $this->request->getPost('markingCode'),
            'customer_name'     => $this->request->getPost('customerName'),
            'phone_number'      => $this->request->getPost('phoneNumber'),
            'address'           => $this->request->getPost('address')
        ];

        $customerModel->save($data);

        return redirect()->to('/customers')->with('message', 'Customer created successfully!');
    }
}
