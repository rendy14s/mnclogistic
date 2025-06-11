<?= $this->extend('admin/layout/index') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Price</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Price</li>
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
                                    <button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='<?= base_url('prices/register') ?>'">
                                        <i class="fas fa-plus"></i> Add Prices
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Price Code</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Price</th>
                                            <th>Services</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $id = 1; ?>
                                        <?php foreach ($prices as $price): ?>
                                            <tr>
                                                <td><?= $id++ ?></td>
                                                <td><?= esc($price['price_code']) ?></td>
                                                <td><?= esc($price['from']) ?></td>
                                                <td><?= esc($price['to']) ?></td>
                                                <td><?= esc($price['price']) ?></td>
                                                <td><?= esc($price['service']) ?></td>
                                                <td>
                                                    <?= !empty($price['created_at']) ? date('H:i:s A d/m/Y', strtotime($price['created_at'])) :'-' ?>
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