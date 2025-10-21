<?= 
// dd(APPPATH . 'Views/admin/pages/waybill/view/jakarta_view.php', file_exists(APPPATH . 'Views/admin/pages/waybill/view/jakarta_view.php'));
    $this->extend('admin/layout/index');

    $segment_second = service('uri')->getSegment(2);

    if ($segment_second === 'waybillbatam') {
        echo view('admin/pages/waybill/view/batam_view', ['data' => $waybills_batam]);
    } elseif ($segment_second === 'waybilljakarta') {
        echo view('admin/pages/waybill/view/jakarta_view', ['data' => $waybills_jakarta]);
    }
?>
