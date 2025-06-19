<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function index()
    {
        //
         if (session()->get('user')) {
            return redirect()->to('/dashboard');
        }

        return view('admin/pages/login/index'); 
    }
}
