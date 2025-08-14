<?php

namespace App\Controllers;
require_once APPPATH . 'Config/Constants.php';


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShipment;
use App\Models\MNCShipmentPackage;
use App\Models\MNCShipmentLog;
use App\Models\MNCCustomer;
use App\Models\MNCCustomerPrice;
use App\Models\MNCUser;
use App\Models\MNCCourier;
use App\Models\MNCDelivery;
use App\Models\MNCDeliveryImage;

class Shipment extends BaseController
{
    public function index()
    {
        return view('admin/pages/shipment/index');
    }

    public function form_add()
    {
        //
        $customerModel      = new MNCCustomer();
        $data['customers']  = $customerModel->where('status', 1)->findAll();

        return view('admin/pages/shipment/create/index', $data);
    }

    public function add()
    {
        // dd($this->request->getPost());die;
        $customerModel         = new MNCCustomer();
        $shipmentModel         = new MNCShipment();
        $shipmentPackageModel  = new MNCShipmentPackage();
        $shipmentLogModel      = new MNCShipmentLog();

        $db = \Config\Database::connect();
        $db->transStart();


        try {
            $markingCode = $customerModel->where('id', $this->request->getPost('customer_id'))
                             ->get()
                             ->getRow()->marking_code;
            // Prepare shipping data
            $dataShipment = [
                'customer_id'      => $this->request->getPost('customer_id'),
                'marking_code'      => $markingCode,
                'price_id'          => $this->request->getPost('price_id'),
                'price_kg'          => $this->request->getPost('price_kg'),
                'special_case'      => $this->request->getPost('override_total') ? 1 : 0,
                'total_price'       => $this->request->getPost('total_price'),
                'total_weight'      => $this->request->getPost('total_weight'),
                'consolidation'     => $this->request->getPost('consolidation') ? 0 : 1,
                'package_json'      => $this->request->getPost('packages_json'),
                'status_tracking'   => NEW_DATA_SHIPMENT,
                'status_finance'    => WAITING_FOR_PAYMENT,
                'status'            => INITIATE, // Canceled status data shipment
                'created_by'        => session('user')['id'],
            ];


            // Insert main shipping row
            $shipmentModel->insert($dataShipment);
            $shippingId = $shipmentModel->getInsertID();

            if (!$shippingId) {
                throw new \Exception("Shipping insert failed");
            }

            // dd(session('user')['id']);die;

            // Insert each package
            $packages = json_decode($this->request->getPost('packages_json'), true);
            foreach ($packages as $pkg) {
                $shipmentPackageModel->insert([
                    'shipment_id'  => $shippingId,
                    'description'   => $pkg['description'],
                    'dimension_p'   => $pkg['p'],
                    'dimension_l'   => $pkg['l'],
                    'dimension_t'   => $pkg['t'],
                    'dimension_v'   => $pkg['volume'],
                    'real_weight'   => $pkg['real_weight'],
                    'used_weight'   => $pkg['used_weight']
                ]);
            }

            // dd($shipmentPackageModel);die;


            // Prepare shipping log data
            $dataShipmentlog = [
                'shipment_id'      => $shippingId,
                'user_id'           => session('user')['id'],
                'description'       => 'NEW DATA SHIPMENT [NEW DATA]',
            ];

            // Insert shipping log
            $shipmentLogModel->insert($dataShipmentlog);


            $db->transComplete(); // COMMIT TRANSACTION

            if ($db->transStatus() === false) {
                log_message('error', print_r($dataShipment, true));
                log_message('error', print_r($shipmentModel->errors(), true));
                throw new \Exception("Transaction failed");
            }

            return redirect()->to('/shipment')->with('success', 'Shipping created successfully.');
        } catch (\Exception $e) {
                $db->transRollback(); // ROLLBACK if anything fails
                return redirect()->back()->with('error', 'Save failed: ' . $e->getMessage());
        }
    }

    public function process($id)
    {
        $shipmentModel         = new MNCShipment();
        $shipmentPackageModel  = new MNCShipmentPackage();
        $shipmentLogModel      = new MNCShipmentLog();
        $userModel             = new MNCUser();
        $courierData           = new MNCCourier();
        $couriers              = $courierData->findAll();

        $shipment = $shipmentModel
            ->select('
                mnc_shipment.id, 
                mnc_shipment.marking_code, 
                mnc_shipment.consolidation, 
                mnc_shipment.status_tracking, 
                mnc_shipment.status_finance, 
                mnc_shipment.total_price, 
                mnc_shipment.total_weight, 
                mnc_shipment.created_by, 
                mnc_shipment.created_at, 
                mnc_customers_price.price_code
            ')
            ->join('mnc_customers_price', 'mnc_customers_price.id = mnc_shipment.price_id')
            ->find($id);
        if (!$shipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Shipment ID $id tidak ditemukan");
        }

        $packages   = $shipmentPackageModel->where('shipment_id', $id)->findAll();
        $total_packages   = count($packages); // or ->where()->countAllResults() if not fetching all
        $users      = $userModel->where('id', $shipment['created_by'])->first();
        
        $logs = $shipmentLogModel
                ->select('mnc_shipment_logs.*, mnc_users.full_name')
                ->join('mnc_users', 'mnc_users.id = mnc_shipment_logs.user_id', 'left')
                ->where('shipment_id', $id)
                ->findAll();

        return view('admin/pages/shipment/process/index', [
            'shipment' => $shipment,
            'packages' => $packages,
            'total_packages' => $total_packages,
            'users' => $users,
            'logs' => $logs,
            'couriers' => $couriers
        ]);
    }

    public function setPaid($id)
    {
        $db = \Config\Database::connect();
        $shipmentModel = new MNCShipment();
        $shipmentLogModel = new MNCShipmentLog();

        // Begin transaction
        $db->transStart();

        // Update shipment status to "Paid"
        $shipmentModel->update($id, ['status_finance' => PAID]);

        // Insert shipment log
        $shipmentLogModel->insert([
            'shipment_id' => $id,
            'user_id'       => session('user')['id'],
            'description'  => 'INVOICE MARKED AS PAID BY ' . session('user')['fullname'],
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // Complete the transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            // Rollback occurred
            // dd($db->transStatus());die;
            return redirect()->back()->with('error', 'Failed to mark invoice as paid.');
        }

        // Success
        return redirect()->back()->with('success', 'Invoice marked as paid.');
    }

    public function setArrived($id)
    {
        $db = \Config\Database::connect();
        $shipmentModel = new MNCShipment();
        $shipmentLogModel = new MNCShipmentLog();

        // Begin transaction
        $db->transStart();

        // Update shipment status to "Arrived at Warehouse"
        $shipmentModel->update($id, ['status_tracking' => ARRIVED_AT_WAREHOUSE]);

        // Insert shipment log
        $shipmentLogModel->insert([
            'shipment_id' => $id,
            'user_id'       => session('user')['id'],
            'description'  => 'SHIPMENT ARRIVED At Warehouse Jakarta, STATUS UPDATE BY ' . session('user')['fullname'],
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // Complete the transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception("Transaction failed");
        }

        // Success
        return redirect()->back()->with('success', 'Updated shipment status to Arrived.');
    }

    public function setDelivery($id)
    {
        $db = \Config\Database::connect();
        $shipmentModel = new MNCShipment();
        $shipmentLogModel = new MNCShipmentLog();

        // Begin transaction
        $db->transStart();

        // Update shipment status to "Delivered to Customer"
        $shipmentModel->update($id, ['status_tracking' => DELIVERED_TO_CUSTOMER]);

        // Insert shipment log
        $shipmentLogModel->insert([
            'shipment_id' => $id,
            'user_id'       => session('user')['id'],
            'description'  => 'SHIPMENT DELIVERY TO CUSTOMER BY ' . session('user')['fullname'],
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // Complete the transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception("Transaction failed");
        }

        // Success
        return redirect()->back()->with('success', 'Updated shipment status to Arrived.');
    }

    public function saveDeliveryCustomer($id)
    {
        helper(['text', 'filesystem']);
        $db = \Config\Database::connect();
        $db->transBegin();

        $trackingNumber = $this->request->getPost('trackingNumber');
        $courierId      = $this->request->getPost('courier');
        $images         = $this->request->getFiles()['images'] ?? [];

        if (count($images) > 4) {
            return redirect()->back()->with('error', 'You can only upload up to 4 images.');
        }

        // Insert delivery record
        $deliveryModel = new MNCDelivery();
        $deliveryId = $deliveryModel->insert([
            'shipment_id'    => $id,
            'tracking_number' => $trackingNumber,
            'courier_id'      => $courierId
        ]);

       $uploadPath = FCPATH . 'delivery';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        if (!is_writable($uploadPath)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Upload path is not writable: ' . $uploadPath);
        }

        $allowedTypes = ['jpg', 'jpeg', 'png'];
        $maxSizeBytes = 2 * 1024 * 1024; // 2MB
        $imageModel = new MNCDeliveryImage();

        foreach ($images as $image) {
            if (!$image->isValid()) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Invalid image upload.');
            }

            $ext = strtolower($image->getExtension());
            if (!in_array($ext, $allowedTypes)) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Only JPG, JPEG, PNG are allowed.');
            }

            if ($image->getSize() > $maxSizeBytes) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Each image must be under 2MB.');
            }

            // Save the image as-is with a unique name
            $uid = uniqid();
            $finalName = $uid . '.' . $ext;

            try {
                if (!$image->move($uploadPath, $finalName)) {
                    $db->transRollback();
                    return redirect()->back()->with('error', 'Image move failed (no internal error reported).');
                }
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Move threw exception: ' . $e->getMessage());
            }



            $imageModel->insert([
                'shipment_delivery_id' => $deliveryId,
                'path' => $uploadPath . '/' . $finalName,
                'image' => $finalName
            ]);
        }

        $shipmentLogModel = new MNCShipmentLog();
        $shipmentLogModel->insert([
            'shipment_id' => $id,
            'user_id'      => session('user')['id'],
            'description'  => 'DELIVERY TO CUSTOMER WITH TRACKING NUMBER ' . $trackingNumber . ' BY ' . session('user')['fullname']
        ]);

        $shipmentModel = new MNCShipment();
        $shipmentModel->update($id, ['status_tracking' => DELIVERED_TO_CUSTOMER]); // Update status to Delivered

        $db->transCommit();
        return redirect()->back()->with('success', 'Delivery and images saved successfully.');
    }

    // This method handles the route '/shipment/api/getCustomerPrice/{customer_id}'
    public function getCustomerPrice($customer_id)
    {
        // Load the model (assuming you have a model called MNCCustomerPrice)
        $customerPriceModel = new MNCCustomerPrice();

        // Fetch prices based on the customer_id (you can adjust this to your database structure)
        $prices = $customerPriceModel->where('customer_id', $customer_id)->findAll();

        // Return the data as a JSON response
        return $this->response->setJSON($prices);
    }

    public function form_edit($id)
    {
        // dd($this->request->getPost());exit; // Debugging line, remove in production
        $customerModel         = new MNCCustomer();
        $shipmentModel         = new MNCShipment();
        $shipmentPackageModel  = new MNCShipmentPackage();
        $shipmentLogModel      = new MNCShipmentLog();
        
        $data['shipment'] = $shipmentModel
            ->select('
                mnc_shipment.id, 
                mnc_shipment.customer_id, 
                mnc_shipment.marking_code, 
                mnc_shipment.price_id, 
                mnc_shipment.price_kg, 
                mnc_shipment.consolidation, 
                mnc_shipment.package_json, 
                mnc_shipment.status_tracking, 
                mnc_shipment.status_finance, 
                mnc_shipment.total_price, 
                mnc_shipment.total_weight, 
                mnc_shipment.created_by, 
                mnc_shipment.created_at, 
                mnc_customers_price.price_code
            ')
            ->join('mnc_customers_price', 'mnc_customers_price.id = mnc_shipment.price_id')
            ->find($id);
        if (!$data['shipment']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Shipment ID $id tidak ditemukan");
        }

        $data['customers']  = $customerModel->where('status', 1)->findAll();

        // dd($data);exit;
        
        return view('admin/pages/shipment/edit/index', $data);
    }

    public function edit($id)
    {
        // dd($this->request->getPost());exit; // Debugging line, remove in production
        
        $shipmentModel         = new MNCShipment();
        $shipmentPackageModel  = new MNCShipmentPackage();
        $shipmentLogModel      = new MNCShipmentLog();
        $customerModel         = new MNCCustomer();

        $db = \Config\Database::connect();
        $db->transStart();

        try {
             $markingCode = $customerModel->where('id', $this->request->getPost('customer_id'))
                             ->get()
                             ->getRow()->marking_code;

            // Prepare shipping data
            $dataShipment = [
                'customer_id'      => $this->request->getPost('customer_id'),
                'marking_code'     => $markingCode,
                'price_id'         => $this->request->getPost('price_id'),
                'price_kg'         => $this->request->getPost('defaultPriceKg'),
                'special_case'     => $this->request->getPost('override_total') ? 1 : 0,
                'total_price'      => $this->request->getPost('total_price'),
                'total_weight'     => $this->request->getPost('total_weight'),
                'consolidation'    => $this->request->getPost('consolidation') ? 0 : 1,
                'package_json'     => $this->request->getPost('packages_json'),
            ];

            // Update main shipping row
            $shipmentModel->update($id, $dataShipment);

            // Delete existing packages
            $shipmentPackageModel->where('shipment_id', $id)->delete();

            // Insert each package
            $packages = json_decode($this->request->getPost('packages_json'), true);
            foreach ($packages as $pkg) {
                $shipmentPackageModel->insert([
                    'shipment_id'  => $id,
                    'description'   => $pkg['description'],
                    'dimension_p'   => $pkg['p'],
                    'dimension_l'   => $pkg['l'],
                    'dimension_t'   => $pkg['t'],
                    'dimension_v'   => $pkg['volume'],
                    'real_weight'   => $pkg['real_weight'],
                    'used_weight'   => $pkg['used_weight']
                ]);
            }

            // Prepare shipping log data
            $dataShipmentlog = [
                'shipment_id'      => $id,
                'user_id'           => session('user')['id'],
                'description'       => 'EDIT DATA SHIPMENT [EDIT DATA]',
            ];  
            // Insert shipping log
            $shipmentLogModel->insert($dataShipmentlog);    
            $db->transComplete(); // COMMIT TRANSACTION
            if ($db->transStatus() === false) {
                log_message('error', print_r($dataShipment, true));
                log_message('error', print_r($shipmentModel->errors(), true));
                throw new \Exception("Transaction failed");
            }
            return redirect()->to('/shipment')->with('success', 'Shipping updated successfully.');
        } catch (\Exception $e) {
            $db->transRollback(); // ROLLBACK if anything fails
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    
    public function bulkSending()
    {
        $request = service('request');

        $ids = $request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'No shipment IDs provided']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $shipmentModel = new MNCShipment;
            $shipmentLogModel = new MNCShipmentLog();


            $shipmentModel->set('status_tracking', ON_PROGRESS)
                        ->whereIn('id', $ids)
                        ->update();

            foreach ($ids as $id) {
                $shipmentLogModel->insert([
                    'shipment_id'      => $id,
                    'user_id'           => session('user')['id'],
                    'description'       => 'Shipment Sent [Item Sending] to Warehouse Jakarta',
                ]);
            }


            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                // Transaction failed
                return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Shipments updated successfully']);
        } catch (\Exception $e) {
            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function list()
    {
        $shipmentModel = new MNCShipment();
        $user = session()->get('user');
        $role = $user['role'] ?? null;

        $shipments = $shipmentModel
            ->select('
                mnc_shipment.id, 
                mnc_shipment.marking_code, 
                mnc_shipment.consolidation, 
                mnc_shipment.status_tracking, 
                mnc_shipment.status_finance, 
                mnc_shipment.created_at, 
                mnc_customers_price.price_code
            ')
            ->join('mnc_customers_price', 'mnc_customers_price.id = mnc_shipment.price_id')
             ->where('mnc_shipment.status', 1);

        if ($role == 1) {
            // Admin or Super Admin: Show all shipments
            $shipments->where('mnc_shipment.status_tracking !=', 0);
        } else if ($role == 2) {
            // Finance: Show shipments that are paid or in unpaid status
            $shipments->whereIn('mnc_shipment.status_tracking', [2, 3]);
        } else if ($role == 3) {
           $shipmentModel->where('mnc_shipment.status_tracking', 1);
        } else if ($role == 4) {
            $shipmentModel->whereIn('mnc_shipment.status_tracking', [2, 3]);
        }

        $shipments = $shipments->get()->getResultArray(); 

        $data = [];

        foreach ($shipments as $shipment) {
            $statusTracking = (int) $shipment['status_tracking'];

             $canShowAction = in_array($role, [1, 2, 5]) || (in_array($role, [2, 3, 4]) && $statusTracking === 1);

            $data[] = [
                'id' => $shipment['id'],
                'marking_code' => '<a href="' . base_url('shipment/process/' . $shipment['id']) . '">' . esc($shipment['marking_code']) . '</a>',
                'price_code' => esc($shipment['price_code']),
                'consolidation' => $shipment['consolidation'] == 1 ? 'Yes' : 'No',
                'status_tracking' => $this->getStatusTrackingBadge($shipment['status_tracking']),
                'status_finance' => $this->getStatusFinanceBadge($shipment['status_finance']),
                'created_at' => !empty($shipment['created_at']) ? date('H:i:s A d/m/Y', strtotime($shipment['created_at'])) : '-',
                
                
                 // Action button (based on $canShowAction)
                'action' => $canShowAction
                            ? '<a href="' . base_url('shipment/edit/' . $shipment['id']) . '" class="btn btn-primary btn-sm">Edit</a>
                            <a href="' . base_url('shipment/api/delete/' . $shipment['id']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this shipment?\')">Delete</a>'
                            : '',


                'checkbox' => '<input type="checkbox" class="rowCheckbox" value="' . $shipment['id'] . '">'
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    // Helper functions to build badges:
    private function getStatusTrackingBadge($status)
    {
        switch ($status) {
            case 1: return '<span class="badge badge-warning">NEW DATA SHIPMENT</span>';
            case 2: return '<span class="badge badge-secondary">ON PROGRESS</span>';
            case 3: return '<span class="badge badge-success">ARRIVED AT WAREHOUSE</span>';
            case 4: return '<span class="badge badge-success">DELIVERED TO CUSTOMER</span>';
            default: return '<span class="badge badge-secondary">PENDING</span>';
        }
    }

    private function getStatusFinanceBadge($status)
    {
        switch ($status) {
            case 0: return '<span class="badge badge-warning">UN PAID</span>';
            case 1: return '<span class="badge badge-success">PAID</span>';
            default: return '<span class="badge badge-secondary">Pending</span>';
        }
    }

    public function delete($id)
    {
        $shipmentModel = new MNCShipment();
        $shipmentLogModel = new MNCShipmentLog();

        // Check if the shipment exists
        $shipment = $shipmentModel->find($id);
        if (!$shipment) {
            return redirect()->back()->with('error', 'Shipment not found.');
        }

        // Begin transaction
        $db = \Config\Database::connect();
        $db->transStart();

        $shipmentModel->update($id, ['status' => SOFT_DELETE]); // Manual soft delete

        // Log the deletion
        $shipmentLogModel->insert([
            'shipment_id' => $id,
            'user_id'      => session('user')['id'],
            'description'  => 'SHIPMENT DELETED BY ' . session('user')['fullname']
        ]);

        // Complete the transaction
        $db->transComplete();
        if ($db->transStatus() === false) {
            // Rollback occurred
            return redirect()->back()->with('error', 'Failed to delete shipment.');
        } else {
            // Success
            return redirect()->back()->with('success', 'Shipment deleted successfully.');
        }
    }

}
