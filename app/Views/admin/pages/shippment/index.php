<?= $this->extend('admin/layout/index') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Shippment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Shippment</li>
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
                                    <button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='<?= base_url('shippment/add') ?>'">
                                        <i class="fas fa-plus"></i> Add Shippment
                                    </button>
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
                                            <th>Status</th>
                                            <th>Created Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $id = 1; ?>
                                        <?php foreach ($shippments as $shippment): ?>
                                            <tr>
                                                <td><?= $id++ ?></td>
                                                <td><?= esc($shippment['marking_code']) ?></td>
                                                <td><?= esc($shippment['price_code']) ?></td>
                                                <td>
                                                    <?= $shippment['consolidation'] == 1 ? 'Yes' : 'No' ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        switch ($shippment['status']) {
                                                            case 1:
                                                                echo '<span class="badge badge-warning">On Progress</span>';
                                                                break;
                                                            case 2:
                                                                echo '<span class="badge badge-success">Completed</span>';
                                                                break;
                                                            case 0:
                                                            default:
                                                                echo '<span class="badge badge-secondary">Pending</span>';
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= !empty($shippment['created_at']) ? date('H:i:s A d/m/Y', strtotime($shippment['created_at'])) :'-' ?>
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