<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Process Shippment</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('shippment') ?>">Data Shippment</a></li>
                    <li class="breadcrumb-item active">Proccess Shippment</li>
                  </ol>
                </div>
              </div>
            </div><!-- /.container-fluid -->
          </section>

          <!-- Main content -->
          <section class="content">
            <div class="container-fluid">
              <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="card-body">
                        <p><strong>Marking Code:</strong> <?= esc($shippment['marking_code']) ?></p>
                        <p><strong>Price Code:</strong> <?= esc($shippment['price_code']) ?></p>
                        <p><strong>Total Price:</strong> Rp <?= number_format($shippment['total_price'], 0, ',', '.') ?></p>
                        <p><strong>Status:</strong> <?= $shippment['status'] == 1 ? 'On Progress' : 'Completed' ?></p>
                        <p><strong>Created By:</strong> <?= $users['full_name']?></p>
                        <hr>

                        <h5>Packages:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>P x L x T</th>
                                    <th>Volume</th>
                                    <th>Real Weight</th>
                                    <th>Used Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($packages as $pkg): ?>
                                    <tr>
                                        <td><?= esc($pkg['description']) ?></td>
                                        <td><?= esc("{$pkg['dimension_p']} x {$pkg['dimension_l']} x {$pkg['dimension_t']}") ?></td>
                                        <td><?= esc($pkg['dimension_v']) ?></td>
                                        <td><?= esc($pkg['real_weight']) ?></td>
                                        <td><?= esc($pkg['used_weight']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <hr>

                        <h5>Log Activites:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>User</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= esc($log['description']) ?></td>
                                        <td><?= esc($log['full_name']) ?></td>
                                        <td>
                                            <?= !empty($log['created_at']) ? date('H:i:s A d/m/Y', strtotime($log['created_at'])) :'-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
              </div>
              <!-- /.row -->
            </div><!-- /.container-fluid -->
          </section>
          <!-- /.content -->
        <?= $this->endSection() ?>
      </div>
      <!-- /.content-wrapper -->
