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
        $model = new MNCCustomer();
        $data['customers'] = $model->findAll();
        
        return view('admin/pages/customers/index', $data);
    }
}
