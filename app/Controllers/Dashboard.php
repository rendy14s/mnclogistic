<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShipment;
use App\Models\MNCUser;
use App\Models\MNCCustomer;

class Dashboard extends BaseController
{
    public function index()
    {
        //
        $countShipmentData = new MNCShipment();

        // $countShipmentDataSuccess = new MNCDeliveryImage();

        $countUsers = new MNCUser();

        $countCustomers = new MNCCustomer();

        $totalRowsShipment          = $countShipmentData
                        ->where('status', 1)
                        ->countAllResults();

        $totalRowsShipmentSuccess = $countShipmentData
                        ->whereIn('status_tracking', [3, 4])
                        ->where('status_finance', 1)
                        ->where('status', 1)
                        ->countAllResults();
        
        $totalRowsUsers = $countUsers
                        ->where('status', 1)
                        ->countAllResults();

        $totalRowsCustomers = $countCustomers
                        ->where('status', 1)
                        ->countAllResults();



        return view('admin/pages/dashboard/index', [
            'title' => 'Dashboard',
            'totalRowsShipment' => $totalRowsShipment,
            'totalRowsShipmentSuccess' => $totalRowsShipmentSuccess,
            'totalRowsUsers' => $totalRowsUsers,
            'totalRowsCustomers' => $totalRowsCustomers
        ]);
    }
}
