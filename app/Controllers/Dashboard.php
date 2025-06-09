<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        //
        return view('admin/pages/dashboard/index', $this->data, [
            'title' => 'Dashboard'
        ]);
    }
}
