<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    public function index()
    {
        //
        return view('admin/pages/users/index');
    }
}
