<?= $this->extend('admin/layout/index') ?>
    <?php $session = session(); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Process Shipment</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('shipment') ?>">Data Shipment</a></li>
                    <li class="breadcrumb-item active">Proccess Shipment</li>
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
                        <p><strong>Marking Code:</strong> <?= esc($shipment['marking_code']) ?></p>
                        <p><strong>Price Code:</strong> <?= esc($shipment['price_code']) ?></p>
                        <p class="<?= in_array($session->get('user')['role'], [3, 4]) ? 'd-none' : '' ?>">
                            <strong>Total Price:</strong> Rp <?= number_format($shipment['total_price'], 0, ',', '.') ?>
                        </p>
                        <p><strong>Status Shipment:</strong> 
                            <?=
                                $statusText = '';
                                switch ($shipment['status_tracking']) {
                                    case 1:
                                        $statusText = 'NEW DATA SHIPMENT';
                                        break;
                                    case 2:
                                        $statusText = 'ON PROGRESS';
                                        break;
                                    case 3:
                                        $statusText = 'ARRIVED AT WAREHOUSE JAKARTA';
                                        break;
                                    case 4:
                                        $statusText = 'DELIVERY TO CUSTOMER';
                                        break;
                                    case 14:
                                        $statusText = 'PARTIAL DELIVERY TO CUSTOMER';
                                        break;
                                    case 5:
                                        $statusText = 'SUCCESS DELIVERY';
                                        break;
                                    case 6:
                                        $statusText = 'FAILED DELIVERY';
                                        break;
                                    default:
                                        $statusText = 'UNKNOWN STATUS';
                                } 
                            ?>
                            <?= esc($statusText) ?>
                        </p>
                        <p><strong>Status Finance:</strong> <?= $shipment['status_finance'] == 0 ? 'UN PAID' : 'PAID' ?></p>
                        <p><strong>Created By:</strong> <?= $users['full_name']?></p>
                        <hr>

                        <h5>Packages:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Description</th>
                                    <th>P x L x T</th>
                                    <th>Volume</th>
                                    <th>Real Weight</th>
                                    <th>Used Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1; ?>
                                <?php foreach ($packages as $pkg): ?>
                                    <tr>
                                        <td><?= $id++ ?></td>
                                        <td><?= esc($pkg['description']) ?></td>
                                        <td><?= esc("{$pkg['dimension_p']} x {$pkg['dimension_l']} x {$pkg['dimension_t']}") ?></td>
                                        <td><?= esc($pkg['dimension_v']) ?></td>
                                        <td><?= esc($pkg['real_weight']) ?></td>
                                        <td><?= esc($pkg['used_weight']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Consolidation</th>
                                    <th colspan="1" ><?= esc($shipment['consolidation']) ? 'YES' : 'NO' ?></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Total Weight</th>
                                    <td colspan="1"><?= esc($shipment['total_weight']) ?> Kg</td>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Total Packages / Box</th>
                                    <td colspan="1"><?= esc($total_packages) ?></td>
                                </tr>
                            </tfoot>
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
                                <a href="<?= base_url('invoice/pdf/' . $shipment['id']) ?>" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>

                                <!-- Mark as Paid Button -->
                                <?php if ($shipment['status_finance'] != '1'): ?>
                                  <form action="<?= base_url('shipment/paid/' . $shipment['id']) ?>" method="get" onsubmit="return confirm('Mark this invoice as paid?')" class="ml-2">
                                      <?= csrf_field() ?>
                                      <button type="submit" class="btn btn-sm btn-success">
                                        Mark as Paid
                                      </button>
                                  </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($session->get('user')['role'] === '4'): ?>
                          <?php if ($shipment['status_tracking'] == '2'): ?>
                            <form action="<?= base_url('shipment/arrived/' . $shipment['id']) ?>" method="get" onsubmit="return confirm('Mark this arrived?')" class="ml-2">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-success">
                                  Arrived Shipment
                                </button>
                            </form>
                          <?php endif; ?>
                         <?php if (in_array($shipment['status_tracking'], ['3', '14'])): ?>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#partialdeliveryModal">
                                  Partial Delivery to Customer
                              </button>
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deliveryModal">
                                  Delivery to Customer
                              </button>
                          <?php endif; ?>
                          <div class="d-flex gap-2">
                            <?php if ($shipment['status_tracking'] == '4'): ?>
                                <form action="<?= base_url('shipment/delivery/success/' . $shipment['id']) ?>" method="post" onsubmit="return confirm('Mark this Success Delivery Shipment?')" class="ml-2">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                    Success Delivery Shipment
                                    </button>
                                </form>
                                <form action="<?= base_url('shipment/delivery/failed/' . $shipment['id']) ?>" method="post" onsubmit="return confirm('Mark this Failed Delivery Shipment?')" class="ml-2">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                    Failed Delivery Shipment
                                    </button>
                                </form>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                    </div>
                  </div>

                  <!-- /.modal delivery-->
                    <div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" aria-labelledby="deliveryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title">Form Delivery Customer</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <!-- Add enctype for file uploads -->
                                <form id="deliveryForm" method="post" enctype="multipart/form-data" action="<?= site_url('shipment/deliverycustomer/' . $shipment['id']) ?>" >
                                    <?= csrf_field() ?>
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <label for="inputTrackingNumber" class="col-sm-4 col-form-label">Tracking Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="trackingNumber" class="form-control" id="inputTrackingNumber" placeholder="Tracking Number" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputCourier" class="col-sm-4 col-form-label">Courier</label>
                                            <div class="col-sm-8">
                                                <select name="courier" class="form-control" id="inputCourier" required>
                                                    <option value="">Select Courier</option>
                                                    <?php foreach ($couriers as $courier): ?>
                                                        <option value="<?= esc($courier['id']) ?>"><?= esc($courier['courier_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Modified image input -->
                                        <div class="form-group row">
                                            <label for="inputImages" class="col-sm-4 col-form-label">Upload Images</label>
                                            <div class="col-sm-8">
                                                <div class="custom-file">
                                                    <input type="file" name="images[]" class="custom-file-input" id="inputImages" accept="image/*" multiple required>
                                                    <label class="custom-file-label" for="inputImages">Choose up to 4 images</label>
                                                </div>
                                                <small class="form-text text-muted">Max 4 images, JPG/PNG only.</small>
                                                <div id="imagePreview" class="mt-3 d-flex flex-wrap"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.end modal delivery-->

                    <!-- /.modal partial delivery-->
                    <div class="modal fade" id="partialdeliveryModal" tabindex="-1" role="dialog" aria-labelledby="PartialdeliveryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title">Form Partial Delivery Customer</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <!-- Add enctype for file uploads -->
                                <form id="partialdeliveryForm" method="post" enctype="multipart/form-data" action="<?= site_url('shipment/partialdeliverycustomer/' . $shipment['id']) ?>" >
                                    <?= csrf_field() ?>
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <label for="inputTrackingNumber" class="col-sm-4 col-form-label">Tracking Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="trackingNumber" class="form-control" id="inputTrackingNumber" placeholder="Tracking Number" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputCourier" class="col-sm-4 col-form-label">Courier</label>
                                            <div class="col-sm-8">
                                                <select name="courier" class="form-control" id="inputCourier" required>
                                                    <option value="">Select Courier</option>
                                                    <?php foreach ($couriers as $courier): ?>
                                                        <option value="<?= esc($courier['id']) ?>"><?= esc($courier['courier_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Modified image input -->
                                        <div class="form-group row">
                                            <label for="inputImages" class="col-sm-4 col-form-label">Upload Images</label>
                                            <div class="col-sm-8">
                                                <div class="custom-file">
                                                    <input type="file" name="images[]" class="custom-file-input" id="PartialimageInput" accept="image/*" multiple required>
                                                    <label class="custom-file-label" for="PartialimageInput">Choose up to 4 images</label>
                                                </div>
                                                <small class="form-text text-muted">Max 4 images, JPG/PNG only.</small>
                                                <div id="PartialimagePreview" class="mt-3 d-flex flex-wrap"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.end modal partial delivery-->

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




