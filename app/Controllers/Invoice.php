<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShipment;
use App\Models\MNCShipmentPackage;
use App\Models\MNCCustomer;
use App\Models\MNCCustomerPrice;

class Invoice extends BaseController
{
    public function index()
    {
        //
    }

    public function exportPdf($id)
    {
        helper('pdf');

        $customer = new MNCCustomer();
        $shipmentModel = new MNCShipment();
        $detailModel = new MNCShipmentPackage();
        $customer_price = new MNCCustomerPrice();

        $shipment = $shipmentModel->find($id);
        $details = $detailModel->where('shipment_id', $id)->findAll();
        $total_packages   = count($details); // or ->where()->countAllResults() if not fetching all

        $customerData = $customer->where('marking_code', $shipment['marking_code'])->first();
        $customerPrice = $customer_price
                        ->where('id', $shipment['price_id'])
                        ->where('customer_id', $shipment['customer_id'])->first();

        if (!$shipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Shipment not found');
        }

        $html = view('admin/pages/invoice/invoice_pdf_template', [
            'detail_shipment' => [
                'company_logo'     => base_url('assets/admin/dist/img/AdminLTELogo.png'),
                'sender_name'      => 'MNC Logistics',
                'sender_address'   => 'Jakarta, Indonesia',
            ],
            'customer' => $customerData,
            'shipment' => $shipment,
            'total_packages' => $total_packages,
            'details' => $details,
            'price' => $customerPrice,
        ]);

        generate_pdf($html, 'invoice_' . '#' . $shipment['id'] . '.pdf');
    }
}
