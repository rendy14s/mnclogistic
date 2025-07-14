<?php

namespace App\Controllers;

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
        //
        $shipmentModel         = new MNCShipment();
        $data['shipments']     = $shipmentModel->findAll();
        
        return view('admin/pages/shipment/index', $data);
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
        $shipmentModel         = new MNCShipment();
        $shipmentPackageModel  = new MNCShipmentPackage();
        $shipmentLogModel      = new MNCShipmentLog();

        $db = \Config\Database::connect();
        $db->transStart();


        try {
            // Prepare shipping data
            $dataShipment = [
                'marking_code'      => $this->request->getPost('marking_code'),
                'price_code'        => $this->request->getPost('price_code'),
                'special_case'      => $this->request->getPost('override_total') ? 1 : 0,
                'total_price'       => $this->request->getPost('total_price'),
                'consolidation'     => $this->request->getPost('consolidation') ? 1 : 0,
                'package_json'      => $this->request->getPost('packages_json'),
                'status_tracking'   => 1,
                'status_finance'    => 0,
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


            // // Prepare shipping log data
            $dataShipmentlog = [
                'shipment_id'      => $shippingId,
                'user_id'           => session('user')['id'],
                'description'       => 'NEW DATA INSERTED [ON PROGRESS]',
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
        $userModel              = new MNCUser();
        $courierData            = new MNCCourier();
        $couriers               = $courierData->findAll();

        $shipment = $shipmentModel->find($id);
        if (!$shipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Shipment ID $id tidak ditemukan");
        }

        $packages   = $shipmentPackageModel->where('shipment_id', $id)->findAll();
        $users      = $userModel->where('id', $shipment['created_by'])->first();
        
        $logs = $shipmentLogModel
                ->select('mnc_shipment_logs.*, mnc_users.full_name')
                ->join('mnc_users', 'mnc_users.id = mnc_shipment_logs.user_id', 'left')
                ->where('shipment_id', $id)
                ->findAll();

        return view('admin/pages/shipment/process/index', [
            'shipment' => $shipment,
            'packages' => $packages,
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

        // Update shipment status to "Paid" (status = 2)
        $shipmentModel->update($id, ['status_finance' => '1']);

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

        // Update shipment status to "Paid" (status = 2)
        $shipmentModel->update($id, ['status_tracking' => '2']);

        // Insert shipment log
        $shipmentLogModel->insert([
            'shipment_id' => $id,
            'user_id'       => session('user')['id'],
            'description'  => 'SHIPMENT ARRIVED STATUS UPDATE BY ' . session('user')['fullname'],
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

        // Update shipment status to "Paid" (status = 2)
        $shipmentModel->update($id, ['status_tracking' => '3']);

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

            if (!$image->move($uploadPath, $finalName)) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Image upload failed.');
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
        $shipmentModel->update($id, ['status_tracking' => '3']); // Update status to Delivered

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


}
