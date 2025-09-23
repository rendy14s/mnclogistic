<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCWayBill;
use App\Models\MNCShipment;
use App\Models\MNCShipmentPackage;

class Reports extends BaseController
{
    public function index()
    {
        //
    }

    public function Waybill()
    {
        $waybill = new MNCWayBill();
        $data['waybills'] = $waybill
                                ->select('DATE(date_reports) as date_reports, MIN(id) as id') // ambil id terkecil sebagai wakil
                                ->where('date_reports <=', date('Y-m-d 23:59:59'))
                                ->groupBy('DATE(date_reports)')
                                ->orderBy('date_reports', 'DESC')
                                ->findAll();

        return view('admin/pages/waybill/index', $data);
    }

    public function generateWayBill()
    {
        helper('pdf');

        $dateReports = $this->request->getPost('date_reports');

        $waybillModel         = new MNCWayBill();
        $shipmentModel        = new MNCShipment();
        $shipmentPackageModel = new MNCShipmentPackage();

        // Ambil shipment_id dari waybill berdasarkan tanggal
        $waybillIds = $waybillModel
            ->select('shipment_id')
            ->where('DATE(date_reports)', $dateReports)
            ->findAll();

        $ids = array_column($waybillIds, 'shipment_id');

        if (empty($ids)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tidak ada shipment pada tanggal {$dateReports}");
        }

        // Ambil detail shipment
        $shipments = $shipmentModel
            ->select('
                mnc_shipment.id,
                mnc_shipment.consolidation,
                mnc_shipment.created_by,
                mnc_shipment.created_at,
                mnc_customers.marking_code,
                mnc_customers_price.price_code,
                mnc_users.full_name as created_by_name
            ')
            ->join('mnc_customers', 'mnc_customers.id = mnc_shipment.customer_id', 'left')
            ->join('mnc_customers_price', 'mnc_customers_price.id = mnc_shipment.price_id', 'left')
            ->join('mnc_users', 'mnc_users.id = mnc_shipment.created_by', 'left')
            ->whereIn('mnc_shipment.id', $ids)
            ->findAll();

        if (!$shipments) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Detail shipment tidak ditemukan untuk ID: " . implode(',', $ids));
        }

        // Ambil package per shipment
        $shipmentPackages = $shipmentPackageModel
            ->whereIn('shipment_id', $ids)
            ->findAll();

        // Susun array packages per shipment_id (lebih rapi dipakai di view)
        $packagesByShipment = [];
        foreach ($shipmentPackages as $pkg) {
            $packagesByShipment[$pkg['shipment_id']][] = $pkg;
        }

        // Buat HTML dari view
        $html = view('admin/pages/waybill/generate_way_note', [
            'shipments'   => $shipments,
            'packages'    => $packagesByShipment,
            'dateReports' => $dateReports,
        ]);

        // Generate PDF via helper
        $filename = "waybill-" . date('Y-m-d', strtotime($dateReports));
        generate_pdf($html, $filename . ".pdf");
    }
}
