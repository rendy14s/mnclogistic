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
                                <?php $role = session()->get('user')['role'] ?? null; ?>
                                <table id="example1" class="table table-bordered table-hover">
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
                                             <?php if (in_array($role, [1, 3, 5])): ?>
                                                <th>Action</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Will be loaded by DataTables ajax -->
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