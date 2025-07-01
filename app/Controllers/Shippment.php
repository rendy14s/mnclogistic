<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShippment;
use App\Models\MNCShippmentPackage;
use App\Models\MNCShippmentLog;
use App\Models\MNCCustomer;
use App\Models\MNCUser;
use App\Models\MNCPrice;

class Shippment extends BaseController
{
    public function index()
    {
        //
        $shippmentModel         = new MNCShippment();
        $data['shippments']     = $shippmentModel->findAll();
        
        return view('admin/pages/shippment/index', $data);
    }

    public function form_add()
    {
        //
        $customerModel      = new MNCCustomer();
        $priceModel         = new MNCPrice();
        $data['customers']  = $customerModel->findAll();
        $data['prices']     = $priceModel->findAll();

        return view('admin/pages/shippment/create/index', $data);
    }

    public function add()
    {
        // dd($this->request->getPost());die;
        $shippmentModel         = new MNCShippment();
        $shippmentPackageModel  = new MNCShippmentPackage();
        $shippmentLogModel      = new MNCShippmentLog();

        $db = \Config\Database::connect();
        $db->transStart();


        try {
            // Prepare shipping data
            $dataShippment = [
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
            $shippmentModel->insert($dataShippment);
            $shippingId = $shippmentModel->getInsertID();

            if (!$shippingId) {
                throw new \Exception("Shipping insert failed");
            }

            // dd(session('user')['id']);die;

            // Insert each package
            $packages = json_decode($this->request->getPost('packages_json'), true);
            foreach ($packages as $pkg) {
                $shippmentPackageModel->insert([
                    'shippment_id'  => $shippingId,
                    'description'   => $pkg['description'],
                    'dimension_p'   => $pkg['p'],
                    'dimension_l'   => $pkg['l'],
                    'dimension_t'   => $pkg['t'],
                    'dimension_v'   => $pkg['volume'],
                    'real_weight'   => $pkg['real_weight'],
                    'used_weight'   => $pkg['used_weight']
                ]);
            }

            // dd($shippmentPackageModel);die;


            // // Prepare shipping log data
            $dataShippmentlog = [
                'shippment_id'      => $shippingId,
                'user_id'           => session('user')['id'],
                'description'       => 'NEW DATA INSERTED [ON PROGRESS]',
            ];

            // Insert shipping log
            $shippmentLogModel->insert($dataShippmentlog);


            $db->transComplete(); // COMMIT TRANSACTION

            if ($db->transStatus() === false) {
                log_message('error', print_r($dataShippment, true));
                log_message('error', print_r($shippmentModel->errors(), true));
                throw new \Exception("Transaction failed");
            }

            return redirect()->to('/shippment')->with('success', 'Shipping created successfully.');
        } catch (\Exception $e) {
                $db->transRollback(); // ROLLBACK if anything fails
                return redirect()->back()->with('error', 'Save failed: ' . $e->getMessage());
        }
    }

    public function process($id)
    {
        $shippmentModel         = new MNCShippment();
        $shippmentPackageModel  = new MNCShippmentPackage();
        $shippmentLogModel      = new MNCShippmentLog();
        $userModel              = new MNCUser();

        $shippment = $shippmentModel->find($id);
        if (!$shippment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Shippment ID $id tidak ditemukan");
        }

        $packages   = $shippmentPackageModel->where('shippment_id', $id)->findAll();
        $users      = $userModel->where('id', $shippment['created_by'])->first();
        
        $logs = $shippmentLogModel
                ->select('mnc_shippment_logs.*, mnc_users.full_name')
                ->join('mnc_users', 'mnc_users.id = mnc_shippment_logs.user_id', 'left')
                ->where('shippment_id', $id)
                ->findAll();

        return view('admin/pages/shippment/process/index', [
            'shippment' => $shippment,
            'packages' => $packages,
            'users' => $users,
            'logs' => $logs
        ]);
    }

    public function setPaid($id)
    {
        $db = \Config\Database::connect();
        $shipmentModel = new MNCShippment();
        $shipmentLogModel = new MNCShippmentLog();

        // Begin transaction
        $db->transStart();

        // Update shipment status to "Paid" (status = 2)
        $shipmentModel->update($id, ['status_finance' => '1']);

        // Insert shipment log
        $shipmentLogModel->insert([
            'shippment_id' => $id,
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
        $shipmentModel = new MNCShippment();
        $shipmentLogModel = new MNCShippmentLog();

        // Begin transaction
        $db->transStart();

        // Update shipment status to "Paid" (status = 2)
        $shipmentModel->update($id, ['status_tracking' => '2']);

        // Insert shipment log
        $shipmentLogModel->insert([
            'shippment_id' => $id,
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
}
