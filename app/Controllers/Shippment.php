<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShippment;
use App\Models\MNCShippmentPackage;
use App\Models\MNCCustomer;
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
        $shippmentModelPackage  = new MNCShippmentPackage();

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
                'status'            => 1
            ];

            // Insert main shipping row
            $shippmentModel->insert($dataShippment);
            $shippingId = $shippmentModel->getInsertID();

            if (!$shippingId) {
                throw new \Exception("Shipping insert failed");
            }

            // dd($shippingId);die;

            // Insert each package
            $packages = json_decode($this->request->getPost('packages_json'), true);
            foreach ($packages as $pkg) {
                $shippmentModelPackage->insert([
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

            $db->transComplete(); // COMMIT TRANSACTION

            if ($db->transStatus() === false) {
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
        $shippmentModelPackage  = new MNCShippmentPackage();

        $shippment = $shippmentModel->find($id);
        if (!$shippment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Shippment ID $id tidak ditemukan");
        }

        $packages = $shippmentModelPackage->where('shippment_id', $id)->findAll();

        return view('admin/pages/shippment/process/index', [
            'shippment' => $shippment,
            'packages' => $packages
        ]);
    }
}
