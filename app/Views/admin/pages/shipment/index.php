<?= $this->extend('admin/layout/index') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Shipment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Shipment</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php $session = session(); ?>
                        <?php $role = session()->get('user')['role'] ?? null; ?>
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-shipment-tab" data-toggle="pill" href="#custom-tabs-one-shipment" role="tab" aria-controls="custom-tabs-one-shipment" aria-selected="true">Shipment</a>
                                    </li>
                                    <?php if (in_array($role, [1, 5])): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-data-shipment-tab" data-toggle="pill" href="#custom-tabs-one-data-shipment" role="tab" aria-controls="custom-tabs-one-data-shipment" aria-selected="false">Data Shipment Paid & Arrived Jakarta</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-shipment" role="tabpanel" aria-labelledby="custom-tabs-one-shipment-tab">
                                        <div class="col-2">
                                            <?php if ($session->get('user')['role'] === '3'): ?>
                                                <button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='<?= base_url('shipment/add') ?>'">
                                                    <i class="fas fa-plus"></i> Add Shipment
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <br>
                                        <?php $role = session()->get('user')['role'] ?? null; ?>
                                        <table id="shipmenttable" class="table table-bordered table-hover">
                                            <thead>
                                                    <tr>
                                                    <?php if (session()->get('user')['role'] == 3): ?>
                                                        <th><input type="checkbox" id="selectAll"></th>
                                                    <?php endif; ?> 
                                                    <th>No</th>
                                                    <th>Marking Code</th>
                                                    <th>Destination</th>
                                                    <th>Consolidation</th>
                                                    <th>Status Tracking</th>
                                                    <th>Status Finance</th>
                                                    <th>Created Time</th>
                                                    <?php if (in_array($role, [1, 2, 3, 5])): ?>
                                                        <th>Action</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-one-data-shipment" role="tabpanel" aria-labelledby="custom-tabs-one-data-shipment-tab">
                                        <table id="datashipmenttable" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Marking Code</th>
                                                    <th>Destination</th>
                                                    <th>Consolidation</th>
                                                    <th>Status Tracking</th>
                                                    <th>Status Finance</th>
                                                    <th>Created Time</th>
                                                    <?php if (in_array($role, [1, 2, 3, 5])): ?>
                                                        <th>Action</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    <?= $this->endSection() ?>
</div>
<!-- /.content-wrapper -->