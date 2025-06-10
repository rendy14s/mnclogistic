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
        $customerModel = new MNCCustomer();
        $lastUser = $customerModel->orderBy('id', 'DESC')->first();
        $lastNumber = 0;

        if ($lastUser && preg_match('/MNC\s(\d+)\s[A-Z]{2}/', $lastUser['marking_code'] ?? '', $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        return view('admin/pages/customers/create/index', ['nextNumber' => $nextNumber]);
    }

    public function create()
    {
        $customerModel = new MNCCustomer();

        $data = [
            'marking_code'      => $this->request->getPost('markingCodeHidden'),
            'customer_name'     => $this->request->getPost('customerName'),
            'phone_number'      => $this->request->getPost('phoneNumber'),
            'address'           => $this->request->getPost('address')
        ];

        $customerModel->save($data);

        return redirect()->to('/customers')->with('message', 'Customer created successfully!');
    }
}
