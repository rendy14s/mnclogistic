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
                        <div class="card">
                            <div class="card-header">
                                <div class="col-2">
                                    <?php $session = session(); ?>
                                    <?php if ($session->get('user')['role'] === '3'): ?>
                                        <button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='<?= base_url('shipment/add') ?>'">
                                            <i class="fas fa-plus"></i> Add Shipment
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Marking Code</th>
                                            <th>Destination</th>
                                            <th>Consolidation</th>
                                            <th>Status Tracking</th>
                                            <th>Status Finance</th>
                                            <th>Created Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $id = 1; ?>
                                        <?php foreach ($shipments as $shipment): ?>
                                            <tr>
                                                <td><?= $id++ ?></td>
                                                <td>
                                                    <a href="<?= base_url('shipment/process/' . esc($shipment['id']) ) ?>">
                                                        <?= esc($shipment['marking_code']) ?>
                                                    </a>
                                                </td>
                                                <td><?= esc($shipment['price_code']) ?></td>
                                                <td>
                                                    <?= $shipment['consolidation'] == 1 ? 'Yes' : 'No' ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        switch ($shipment['status_tracking']) {
                                                            case 1:
                                                                echo '<span class="badge badge-warning">NEW DATA SHIPMENT</span>';
                                                                break;
                                                            case 2:
                                                                echo '<span class="badge badge-secondary">ON PROGRESS</span>';
                                                                break;
                                                            case 3:
                                                                echo '<span class="badge badge-success">ARRIVED AT WAREHOUSE</span>';
                                                                break;
                                                            case 4:
                                                                echo '<span class="badge badge-success">DELIVERED TO CUSTOMER</span>';
                                                                break;
                                                            case 0:
                                                            default:
                                                                echo '<span class="badge badge-secondary">PENDING</span>';
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        switch ($shipment['status_finance']) {
                                                            case 0:
                                                                echo '<span class="badge badge-warning">UN PAID</span>';
                                                                break;
                                                            case 1:
                                                                echo '<span class="badge badge-success">PAID</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge badge-secondary">Pending</span>';
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= !empty($shipment['created_at']) ? date('H:i:s A d/m/Y', strtotime($shipment['created_at'])) :'-' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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