<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCUser;

class Users extends BaseController
{
    public function index()
    {
        //
        $model = new MNCUser();
        $data['users'] = $model->findAll();
        
        return view('admin/pages/users/index', $data);
    }
}
