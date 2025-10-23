
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?= $this->section('content') ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Way Bill Jakarta</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Way Bill Jakarta</li>
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

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="jakartawaybilltable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Marking Code</th>
                                            <th>Arrived Date</th>
                                            <th>Generate PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $id = 1; ?>
                                        <?php
                                        foreach ($waybills_jakarta as $waybill): ?>
                                        <tr>
                                            <td><?= $id++ ?></td>
                                            <td><?= $waybill['marking_code'] ?></td>
                                            <td><?= date('l, d F Y', strtotime($waybill['date_reports'])) ?></td>
                                            <td>
                                                <form action="<?= site_url('waybill/api/generatewaybilljakarta') ?>" method="post" target="_blank">
                                                    <input type="hidden" name="shipment_id" value="<?= esc($waybill['shipment_id']) ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm">PDF</button>
                                                </form>
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