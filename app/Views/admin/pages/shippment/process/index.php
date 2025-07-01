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
                        <p><strong>Status Shipment:</strong> <?= $shippment['status_tracking'] == 1 ? 'On Progress' : 'Completed' ?></p>
                        <p><strong>Status Finance:</strong> <?= $shippment['status_finance'] == 0 ? 'UN PAID' : 'PAID' ?></p>
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
                        <?php $session = session(); ?>

                        <!-- Only show this button if the user is an finance -->
                        <br />
                        <?php if ($session->get('user')['role'] === '2'): ?>
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?= base_url('invoice/pdf/' . $shippment['id']) ?>" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>

                                <!-- Mark as Paid Button -->
                                <?php if ($shippment['status_finance'] != '1'): ?>
                                  <form action="<?= base_url('shippment/paid/' . $shippment['id']) ?>" method="get" onsubmit="return confirm('Mark this invoice as paid?')" class="ml-2">
                                      <?= csrf_field() ?>
                                      <button type="submit" class="btn btn-sm btn-success">
                                        Mark as Paid
                                      </button>
                                  </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($session->get('user')['role'] === '3'): ?>
                          <?php if ($shippment['status_tracking'] == '1'): ?>
                            <form action="<?= base_url('shippment/arrived/' . $shippment['id']) ?>" method="get" onsubmit="return confirm('Mark this arrived?')" class="ml-2">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-success">
                                  Arrived Shipment
                                </button>
                            </form>
                          <?php endif; ?>
                         <?php if ($shippment['status_tracking'] == '2' && $shippment['status_finance'] == '1'): ?>
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deliveryModal">
                                  Delivery to Customer
                              </button>
                          <?php endif; ?>
                        <?php endif; ?>
                    </div>
                  </div>

                  <!-- /.modal -->
                    <div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" aria-labelledby="deliveryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Form Delivery Customer</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="packageForm">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="inputDescription" class="col-sm-3 col-form-label">Description</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="description" class="form-control" id="inputDescription" placeholder="Description" onblur="formatTextInput(this)" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputDimensionP" class="col-sm-3 col-form-label">Dimension P</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="dimension_p" class="form-control" id="dimension_p" placeholder="Dimension P" step="any" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputDimensionL" class="col-sm-3 col-form-label">Dimension L</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="dimension_l" class="form-control" id="dimension_l" placeholder="Dimension L" step="any" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputDimensionT" class="col-sm-3 col-form-label">Dimension T</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="dimension_t" class="form-control" id="dimension_t" placeholder="Dimension T" step="any" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputDimensionV" class="col-sm-3 col-form-label">Volume</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="dimension_v" class="form-control" id="dimension_v" step="any" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputRealWeight" class="col-sm-3 col-form-label">Real Weight</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="realWeight" class="form-control" id="realWeight" placeholder="Real Weight" step="any" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
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
