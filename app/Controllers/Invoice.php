<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCShipment;
use App\Models\MNCShipmentPackage;
use App\Models\MNCCustomer;

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

        $shipment = $shipmentModel->find($id);
        $details = $detailModel->where('shipment_id', $id)->findAll();
        $customerData = $customer->where('marking_code', $shipment['marking_code'])->first();

        if (!$shipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Shipment not found');
        }

        $html = view('admin/pages/invoice/invoice_pdf_template', [
            'detail_shipment' => [
                'company_logo'     => base_url('assets/admin/dist/img/AdminLTELogo.png'),
                'sender_name'      => 'PT. MNC Logistics',
                'sender_address'   => 'Batam, Indonesia',
                'sender_phone'     => '+62 821 2264 4927',
            ],
            'customer' => $customerData,
            'shipment' => $shipment,
            'details' => $details
        ]);

        generate_pdf($html, 'invoice_' . '#' . $shipment['id'] . '.pdf');
    }
}
