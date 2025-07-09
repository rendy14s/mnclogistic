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
        $data['customers'] = $customerModel->where('status', 1)->findAll();
        
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
            'address'           => $this->request->getPost('address'),
            'status'            => '1'
        ];

        $customerModel->save($data);

        return redirect()->to('/customers')->with('message', 'Customer created successfully!');
    }

    public function edit($id) {
        $customerModel = new MNCCustomer();
        $customers = $customerModel->find($id); // Fetch customers by ID

        if (!$customers) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("customers not found.");
        }

        if ($this->request->getMethod() === 'POST') {
            // Handle form submission

            $data = [
                'marking_code'    => $this->request->getPost('marking_code'),
                'customer_name'     => $this->request->getPost('customer_name'),
                'phone_number'     => $this->request->getPost('phone_number'),
                'address'      => $this->request->getPost('address')
            ];

            // Debug: Check data before update
            log_message('debug', 'Form Data: ' . json_encode($data));

            // Update the customers data
            if ($customerModel->update($id, $data)) {
                log_message('debug', 'customers updated successfully.');
                return redirect()->to('/customers'); // Redirect to the customerss list page
            } else {
                // If the update fails, log the error
                log_message('error', 'Failed to update customers with ID: ' . $id);
            }
        }

        // Pass customers data to the view
        return view('admin/pages/customers/edit/index', ['customer' => $customers]);
    }


    public function softDelete($id)
    {
        // Get the customer model
        $customerModel = new MNCCustomer();

        // Find the customer
        $customer = $customerModel->find($id);

        // Check if the customer exists
        if (!$customer) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('customer not found');
        }

        // Update the customer's status to 0 (soft delete)
        $data = [
            'status' => 0
        ];

        if ($customerModel->update($id, $data)) {
            // Redirect to customers list with a success message
            return redirect()->to('/customers')->with('message', 'customer soft-deleted successfully.');
        } else {
            // If update fails
            return redirect()->back()->with('error', 'Failed to soft delete the customer.');
        }
    }
}
