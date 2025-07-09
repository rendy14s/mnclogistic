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
        $userModel = new MNCUser();
        // Only select active users (status = 1)
        $data['users'] = $userModel->where('status', 1)->findAll();
        
        return view('admin/pages/users/index', $data);
    }

    public function form_add()
    {
        //
        return view('admin/pages/users/create/index');
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
            'role'        => $this->request->getPost('role'),
            'status'        => '1', // Default status set to '1' (active)
        ];

        $userModel->save($data);

        return redirect()->to('/users')->with('message', 'User created successfully!');
    }

    public function edit($id) {
        $userModel = new MNCUser();
        $user = $userModel->find($id); // Fetch user by ID

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found.");
        }

        if ($this->request->getMethod() === 'POST') {
            // Handle form submission

            $firstname = $this->request->getPost('first_name');
            $lastname  = $this->request->getPost('last_name');

            $data = [
                'first_name'    => $this->request->getPost('first_name'),
                'last_name'     => $this->request->getPost('last_name'),
                'full_name'     => $firstname . ' ' . $lastname,
                'username'      => $this->request->getPost('username'),
                'role'          => $this->request->getPost('role'),
            ];

            // Debug: Check data before update
            log_message('debug', 'Form Data: ' . json_encode($data));

            // Update the user data
            if ($userModel->update($id, $data)) {
                log_message('debug', 'User updated successfully.');
                return redirect()->to('/users'); // Redirect to the users list page
            } else {
                // If the update fails, log the error
                log_message('error', 'Failed to update user with ID: ' . $id);
            }
        }

        // Pass user data to the view
        return view('admin/pages/users/edit/index', ['user' => $user]);
    }

    public function changePassword($id)
    {
        $userModel = new MNCUser();
        $user = $userModel->find($id); // Get user data

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User not found.");
        }

        // Check if form is submitted (POST)
        if ($this->request->getMethod() === 'POST') {

            // Get the new password
            $newPassword = $this->request->getPost('password');

            // Hash the password before saving it to the database
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $data = [
                'password' => $hashedPassword,
            ];

            if ($userModel->update($id, $data)) {
                // Redirect with success message
                return redirect()->to('/users')->with('message', 'Password changed successfully.');
            } else {
                // If update failed
                return redirect()->back()->with('error', 'Failed to change password.');
            }
        }

        // If the request is GET, show the change password form
        return view('admin/pages/users/change_password/index', ['user' => $user]);
    }

    public function softDelete($id)
    {
        // Get the user model
        $userModel = new MNCUser();

        // Find the user
        $user = $userModel->find($id);

        // Check if the user exists
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        // Update the user's status to 0 (soft delete)
        $data = [
            'status' => 0
        ];

        if ($userModel->update($id, $data)) {
            // Redirect to users list with a success message
            return redirect()->to('/users')->with('message', 'User soft-deleted successfully.');
        } else {
            // If update fails
            return redirect()->back()->with('error', 'Failed to soft delete the user.');
        }
    }

}
