<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Add Shippment</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('shippment') ?>">Data Shippment</a></li>
                    <li class="breadcrumb-item active">Add New Shippment</li>
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
                    <!-- form start -->
                    <form id="shippingForm" class="form-horizontal" action="api/add" method="post" required>
                      <div class="card-body">
                        <div class="form-group row">
                          <label for="inputMarkingCode" class="col-sm-2 col-form-label">Marking Code</label>
                          <div class="col-sm-4">
                            <select name="marking_code" class="form-control select2" style="width: 100%;" required>
                              <option value="" selected disabled>---SELECT MARKING CODE---</option>
                              <?php foreach ($customers as $customer): ?>
                                  <option value="<?= esc($customer['marking_code']) ?>">
                                    <?= esc($customer['marking_code']) ?>
                                  </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputTo" class="col-sm-2 col-form-label">Original Shippment</label>
                          <div class="col-sm-4">
                            <select id="shippingPrice" name="price_code" class="form-control select2" style="width: 100%;" required>
                              <option value=""  selected disabled>---SELECT SHIPPMENT---</option>
                              <?php foreach ($prices as $price): ?>
                                  <option value="<?= esc($price['price_code']) ?>" data-price="<?= esc($price['price']) ?>">
                                    <?= esc($price['price_code']) ?>
                                  </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>


                        <div class="form-group row">
                            <label for="inputTo" class="col-sm-2 col-form-label">Packages Data Box</label>
                            <input type="hidden" name="packages_json" id="packages_json">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#packageModal">Add Package</button>
                        </div>

                        <div class="form-group row">
                        <table id="packagesTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Description</th>
                                    <th>P x L x T</th>
                                    <th>Volume</th>
                                    <th>Real Weight</th>
                                    <th>Used Weight (Kg)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="no-data">
                                    <td colspan="7" class="text-center text-muted">No data available in table</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th colspan="5" class="text-right" >Consolidation</th>
                                  <th colspan="2">
                                      <!-- <input name="consolidation" type="checkbox" id="consolidationCheckbox"> -->
                                      <input type="hidden" name="consolidation" value="0">
                                      <input type="checkbox" id="consolidationCheckbox" name="consolidation" value="1">
                                  </th>
                                </tr>
                                <tr>
                                  <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                  <td colspan="2">
                                    <span id="totalPrice">Rp 0</span>
                                    <input type="number" id="totalInput" class="form-control d-none mt-1" min="0">
                                    <button id="editTotalBtn" type="button" class="btn btn-sm btn-outline-primary mt-1">Edit</button>

                                    <input type="hidden" name="override_total" id="override_total" value="0">

                                  </td>
                                </tr>
                              </tfoot>
                              <input type="hidden" name="total_price" id="total_price">
                        </table>
                    </div>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer">
                        <!-- <pre id="jsonViewer" style="background:#eee; padding:10px;"></pre> -->
                        <button type="submit" class="btn btn-info float-right">Create</button>
                        <!-- <button type="submit" class="btn btn-default float-right">Cancel</button> -->
                      </div>
                      <!-- /.card-footer -->
                    </form>

                    

                    <!-- /.modal -->
                    <div class="modal fade" id="packageModal" tabindex="-1" role="dialog" aria-labelledby="packageModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Form Data Box</h4>
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
