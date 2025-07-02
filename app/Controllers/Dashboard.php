<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShippment;
use App\Models\MNCUser;
use App\Models\MNCCustomer;

class Dashboard extends BaseController
{
    public function index()
    {
        //
        $countShipmentData = new MNCShippment();

        // $countShipmentDataSuccess = new MNCDeliveryImage();

        $countUsers = new MNCUser();

        $countCustomers = new MNCCustomer();

        $totalRowsShipment          = $countShipmentData->countAll();

        $totalRowsShipmentSuccess   = $countShipmentData->where('status_tracking', 3)->countAllResults();
        
        $totalRowsUsers             = $countUsers->countAll();

        $totalRowsCustomers         = $countCustomers->countAll();


        return view('admin/pages/dashboard/index', [
            'title' => 'Dashboard',
            'totalRowsShipment' => $totalRowsShipment,
            'totalRowsShipmentSuccess' => $totalRowsShipmentSuccess,
            'totalRowsUsers' => $totalRowsUsers,
            'totalRowsCustomers' => $totalRowsCustomers
        ]);
    }
}
