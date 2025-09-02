<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Edit Price Customer</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('customers') ?>">Data Customer</a></li>
                    <li class="breadcrumb-item active">Edit Price Customer</li>
                  </ol>
                </div>
              </div>
            </div><!-- /.container-fluid -->
          </section>

          <!-- Customer Info Display Section -->
          <div class="alert alert-info">
              <h4>Customer Information</h4>
              <p><strong>Marking Code:</strong> <?= esc($customers['marking_code']) ?></p>
              <p><strong>Customer Name:</strong> <?= esc($customers['customer_name']) ?></p>
              <p><strong>Phone Number:</strong> <?= esc($customers['phone_number']) ?></p>
              <p><strong>Address:</strong> <?= esc($customers['address']) ?></p>
          </div>

          <!-- Main content -->
          <section class="content">
            <div class="container-fluid">
              <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="form-group row">
                      <label for="inputFrom" class="col-sm-2 col-form-label">From</label>
                      <div class="col-sm-4">
                        <select name="from" class="form-control select2" style="width: 100%;" required>
                          <option value="" selected disabled>---SELECT COUNTRY---</option>
                          <?php foreach ($countries as $country): ?>
                              <option value="<?= esc($country['iso_code']) ?>">
                                <?= esc($country['country']) . ' - ' . esc($country['iso_code']) ?>
                              </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputTo" class="col-sm-2 col-form-label">To</label>
                      <div class="col-sm-4">
                        <select name="to" class="form-control select2" style="width: 100%;" required>
                          <option value="" selected disabled>---SELECT COUNTRY---</option>
                          <?php foreach ($citys as $city): ?>
                              <option value="<?= esc($city['iso_code']) ?>">
                                <?= esc($city['city_name']) . ' - ' . esc($city['iso_code']) ?>
                              </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputService" class="col-sm-2 col-form-label">Service</label>
                        <div class="col-sm-4">
                            <select name="service" class="form-control select2" style="width: 100%;" required>
                                <option value="" selected disabled>---SELECT SERVICE---</option>
                                <option value="1">Air</option>
                                <option value="2">Sea</option>
                                <option value="3">LCL</option>
                                <option value="4">Cargo Service</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPrice" class="col-sm-2 col-form-label">Price</label>
                      <div class="col-sm-4">
                        <input type="text" name="price" class="form-control" id="inputPrice" placeholder="Price" required>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-sm-6">
                        <button type="button" class="btn btn-primary float-right" id="addEditPricing">Add</button>
                      </div>
                    </div>
                    <!-- form start -->
                     <form class="form-horizontal" action="/customers/api/edit/pricing" method="post" required>
                      <input type="hidden" name="id" value="<?= esc($customers['id']) ?>">
                      <div class="card-body">
                        <table id="editTablePricing" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        <input type="hidden" name="editpricingData" id="editpricingData" value="<?= esc($pricing) ?>">
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer">
                        <button type="button" id="EditcreateButton" class="btn btn-info float-right">Create</button>
                        <!-- <button type="submit" class="btn btn-default float-right">Cancel</button> -->
                      </div>
                      <!-- /.card-footer -->
                    </form>
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
