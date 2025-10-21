<?= $this->extend('admin/layout/index') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Shipment Finished</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Shipment Finished</li>
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
                        <?php $role = session()->get('user')['role'] ?? null; ?>
                        <div class="card card-primary card-tabs">
                            <div class="card-body">
                                <table id="shipmenttablefinished" class="table table-bordered table-hover">
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