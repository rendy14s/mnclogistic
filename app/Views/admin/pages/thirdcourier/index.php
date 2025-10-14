<?= $this->extend('admin/layout/index') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Courier</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Courier</li>
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
                                    <button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='<?= base_url('thirdcourier/register') ?>'">
                                        <i class="fas fa-plus"></i> Add 3rd Couriers
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Courier Name</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php if (empty($couriers)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No data available in table</td>
                                            </tr>
                                        <?php else: ?>

                                        <?php $id = 1; ?>
                                        <?php foreach ($couriers as $courier): ?>
                                            <tr>
                                                <td><?= $id++ ?></td>
                                                <td><?= esc($courier['courier_name']) ?></td>
                                                <td>
                                                    <?= !empty($courier['created_at']) ? date('H:i:s A d/m/Y', strtotime($courier['created_at'])) :'-' ?>
                                                </td>
                                                <td> <!-- Action Buttons -->
                                                    <a href="<?= base_url('thirdcourier/edit/' . $courier['id']) ?>" class="btn btn-primary btn-sm">Edit</a>
                                                    <a href="<?= base_url('thirdcourier/softDelete/' . $courier['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
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