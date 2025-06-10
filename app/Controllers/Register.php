<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCUser;

class Register extends BaseController
{
    public function index()
    {
        //
        return view('admin/pages/forms/register/index');
    }

    public function create()
    {
        $userModel = new MNCUser();

        // Get the last employee ID
        $lastUser = $userModel->orderBy('id', 'DESC')->first();
        $lastEmployeeID = $lastUser ? $lastUser['employee_id'] : null;

        // Extract numeric part and increment
        if ($lastEmployeeID && preg_match('/^MNC(\d+)$/', $lastEmployeeID, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }

        // Generate new ID with padding
        $newEmployeeID = 'MNC' . str_pad($number, 8, '0', STR_PAD_LEFT);

        $firstname = $this->request->getPost('firstName');
        $lastname  = $this->request->getPost('lastName');

        $data = [
            'employee_id' => $newEmployeeID,
            'first_name'  => $this->request->getPost('firstName'),
            'last_name'   => $this->request->getPost('lastName'),
            'full_name'   => $firstname . ' ' . $lastname,
            'username'    => $this->request->getPost('username'),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_admin'    => $this->request->getPost('isAdmin') ? 1 : 0,
        ];

        $userModel->save($data);

        return redirect()->to('/users')->with('message', 'User created successfully!');
    }
}
