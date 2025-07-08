<?= $this->extend('admin/layout/index') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data User</li>
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
                                    <button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='<?= base_url('users/register') ?>'">
                                        <i class="fas fa-plus"></i> Add Users
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Employee ID</th>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Administrator</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $id = 1; ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?= $id++ ?></td>
                                                <td><?= esc($user['employee_id']) ?></td>
                                                <td><?= esc($user['full_name']) ?></td>
                                                <td><?= esc($user['username']) ?></td>
                                                <td>
                                                    <?php
                                                        switch ($user['role']) {
                                                            case 1:
                                                                echo 'Admin';
                                                                break;
                                                            case 2:
                                                                echo 'Finance';
                                                                break;
                                                            case 3:
                                                                echo 'Staff / Operation Batam';
                                                                break;
                                                            case 4:
                                                                echo 'Staff / Operation Jakarta';
                                                                break;
                                                            case 0:
                                                            default:
                                                                echo 'Not Yet Assigned';
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= !empty($user['created_at']) ? date('H:i:s A d/m/Y', strtotime($user['created_at'])) :'-' ?>
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