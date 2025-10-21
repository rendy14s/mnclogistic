<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MNC Logistic | Dashboard</title>


  <!-- jQuery -->
  <script src="<?= base_url('assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?= base_url('assets/admin/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Select2 -->
  <script src="<?= base_url('assets/admin/plugins/select2/js/select2.full.min.js') ?>"></script>
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/jqvmap/jqvmap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/dist/css/adminlte.min.css') ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/daterangepicker/daterangepicker.css') ?>">
  <!-- summernote -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/summernote/summernote-bs4.min.css') ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
 
  <script>   
    window.formatTextInput = function (el) {
        el.value = el.value
            .toLowerCase()
            .replace(/\b\w/g, char => char.toUpperCase())
            .trim()
            .replace(/\s+/g, ' ');
    };
  </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?= $this->include('admin/layout/header') ?>
    <?= $this->include('admin/layout/sidebar') ?>

     <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                <!-- GLOBAL ALERT SECTION -->
                <div id="alertBox" class="alert d-none" role="alert"></div>

                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <?= $this->include('admin/layout/footer') ?>
</div>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- ChartJS -->
<script src="<?= base_url('assets/admin/plugins/chart.js/Chart.min.js') ?>"></script>
<!-- Sparkline -->
<script src="<?= base_url('assets/admin/plugins/sparklines/sparkline.js') ?>"></script>
<!-- JQVMap -->
<script src="<?= base_url('assets/admin/plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/jqvmap/maps/jquery.vmap.usa.js') ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url('assets/admin/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
<!-- daterangepicker -->
<script src="<?= base_url('assets/admin/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<!-- Summernote -->
<script src="<?= base_url('assets/admin/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/admin/dist/js/adminlte.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('assets/admin/dist/js/demo.js') ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url('assets/admin/dist/js/pages/dashboard.js') ?>"></script>
<!-- DataTables  & Plugins -->
 
<script src="<?= base_url('assets/admin/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<!-- Page specific script Table-->
<script>
  const userRole = <?= session()->get('user')['role'] ?? 'null' ?>;

  $(function () {
    const columns = [];

    if (userRole === 3) {
      columns.push({ data: "checkbox", orderable: false, searchable: false });
    }

    

    columns.push(
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      { data: "marking_code" },
      { data: "price_code" },
      { data: "consolidation" },
      { data: "status_tracking" },
      { data: "status_finance" },
      { data: "created_at" }
      
    );

    if ([1, 2, 3, 5].includes(userRole)) {
      columns.push({ data: "action", orderable: false, searchable: false }); // Hidden Action column
    }

    const buttons = [];

    if (userRole === 3) {
      buttons.push({
        text: 'Item Send',
        className: 'btn btn-warning',
        action: function (e, dt, node, config) {
          const selectedIds = $('.rowCheckbox:checked').map(function () {
            return this.value;
          }).get();

          if (selectedIds.length === 0) {
            alert('Please select at least one item.');
            return;
          }

          if (confirm(`Are you sure for Sending ${selectedIds.length} selected item(s)?`)) {
            $.post('/shipment/api/bulkSending', { ids: selectedIds }, function (response) {
              alert('Selected shipments status sending successfully!');
              dt.ajax.reload(null, false);
            }).fail(function () {
              alert('Error occurred while updating status.');
            });
          }
        }
      });
    }

    const table = $("#shipmenttable").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/list",
      columns: columns,
      order: [[0, 'asc']],
      buttons: buttons,
      initComplete: function () {
        if (userRole === 3) {
          this.api().buttons().container()
            .appendTo('#shipmenttable_wrapper .col-md-6:eq(0)');
        }
      }
    });

    const tableongoing = $("#shipmenttableongoing").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/listongoing",
      columns: columns,
      order: [[0, 'asc']]
    });

    const tablearrived = $("#shipmenttablearrived").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/listarrived",
      columns: columns,
      order: [[0, 'asc']]
    });

    const tableoutfordelivery = $("#shipmenttableoutfordelivery").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/listoutfordelivery",
      columns: columns,
      order: [[0, 'asc']]
    });

    const tabledelivered = $("#shipmenttabledelivered").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/listdelivered",
      columns: columns,
      order: [[0, 'asc']]
    });

    const tablefaileddelivered = $("#shipmenttablefaileddelivered").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/listfaileddelivered",
      columns: columns,
      order: [[0, 'asc']]
    });

    const tablefinish = $("#shipmenttablefinished").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/listfaileddelivered",
      columns: columns,
      order: [[0, 'asc']]
    });

  });

  $(function () {
    const columns = [];
    

    columns.push(
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      { data: "marking_code" },
      { data: "price_code" },
      { data: "consolidation" },
      { data: "status_tracking" },
      { data: "status_finance" },
      { data: "created_at" }
      
    );

    if ([1, 2, 3, 5].includes(userRole)) {
      columns.push({ data: "action", orderable: false, searchable: false }); // Hidden Action column
    }

    const table = $("#datashipmenttable").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      ajax: "/shipment/api/datashipment",
      columns: columns,
      order: [[0, 'asc']]
    });
  });

  const table_jakarta_waybill = $("#jakartawaybilltable").DataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    ordering: true,
    paging: true,
    info: true,
    order: [[0, 'asc']]
  });

  const table_customer = $("#table_customer").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: true,
      paging: true,
      info: true,
      order: [[0, 'asc']]
    });
</script>



<script>
function showAlert(message, type = 'success') {
    const alertBox = document.getElementById('alertBox');
    alertBox.className = `alert alert-${type}`;
    alertBox.textContent = message;
    alertBox.classList.remove('d-none');

    // Auto-dismiss after 5 seconds (optional)
    setTimeout(() => {
        alertBox.classList.add('d-none');
        alertBox.textContent = '';
    }, 3000);
}
</script>

<!-- Core JS Shipment -->
<script src="<?= base_url('assets/admin/corejs/shipment.js') ?>"></script>

<!-- Core JS Customer Pricing -->
 <script src="<?= base_url('assets/admin/corejs/pricing_customer.js') ?>"></script>

<!-- Core JS Customer Add -->
<script src="<?= base_url('assets/admin/corejs/customer_add.js') ?>"></script>

<!-- Core JS Edit Pricing Customer -->
<script src="<?= base_url('assets/admin/corejs/edit_pricing_customer.js') ?>"></script>

<!-- Core JS Price Add Shipment -->
<script src="<?= base_url('assets/admin/corejs/price_add_shipment.js') ?>"></script>

<!-- Core JS Shipment Process -->
<script src="<?= base_url('assets/admin/corejs/plugin_custom/primary_table_index_shipment.js') ?>"></script>

<!-- Image Preview Script -->
<script>
    const imageInput = document.getElementById('inputImages');
    const imagePreview = document.getElementById('imagePreview');
    const fileLabel = document.querySelector('label[for="inputImages"]');

    imageInput.addEventListener('change', function () {
        imagePreview.innerHTML = '';
        const files = Array.from(this.files);

        if (files.length > 4) {
            alert('You can upload a maximum of 4 images.');
            this.value = '';
            fileLabel.textContent = 'Choose up to 4 images';
            return;
        }

        fileLabel.textContent = files.length + ' image(s) selected';

        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('m-1', 'img-thumbnail');
                img.style.height = '100px';
                img.style.width = '100px';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

</body>
</html>
