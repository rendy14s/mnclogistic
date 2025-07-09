<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCCourier;

class Courier extends BaseController
{
    public function index()
    {
        //
        $model = new MNCCourier();
        $data['couriers'] = $model->where('status', 1)->findAll();
        
        return view('admin/pages/3rdcourier/index', $data);
    }

    public function form_add()
    {
        //
        return view('admin/pages/3rdcourier/create/index');
    }

    public function create()
    {
        $courierModel = new MNCCourier();

        $data = [
            'courier_name'      => $this->request->getPost('courierName')
        ];

        $courierModel->save($data);

        return redirect()->to('/3rdcourier')->with('message', 'Customer created successfully!');
    }

    public function edit($id) {
        $courierModel = new MNCCourier();
        $courir = $courierModel->find($id); // Fetch courir by ID

        if (!$courir) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Courir not found.");
        }

        if ($this->request->getMethod() === 'POST') {
            // Handle form submission

            $data = [
                'courier_name'    => $this->request->getPost('courier_name')
            ];

            // Debug: Check data before update
            log_message('debug', 'Form Data: ' . json_encode($data));

            // Update the courir data
            if ($courierModel->update($id, $data)) {
                log_message('debug', 'courir updated successfully.');
                return redirect()->to('/3rdcourier'); // Redirect to the courirs list page
            } else {
                // If the update fails, log the error
                log_message('error', 'Failed to update courir with ID: ' . $id);
            }
        }

        // Pass courir data to the view
        return view('admin/pages/3rdcourier/edit/index', ['courir' => $courir]);
    }

        public function softDelete($id)
    {
        // Get the courier model
        $courierModel = new MNCCourier();

        // Find the courier
        $courier = $courierModel->find($id);

        // Check if the courier exists
        if (!$courier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('courier not found');
        }

        // Update the courier's status to 0 (soft delete)
        $data = [
            'status' => 0
        ];

        if ($courierModel->update($id, $data)) {
            // Redirect to couriers list with a success message
            return redirect()->to('/3rdcourier')->with('message', 'courier soft-deleted successfully.');
        } else {
            // If update fails
            return redirect()->back()->with('error', 'Failed to soft delete the courier.');
        }
    }
}
