<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Add Price</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('price') ?>">Data Price</a></li>
                    <li class="breadcrumb-item active">Add New Price</li>
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
                     <form class="form-horizontal" action="api/register" method="post" required>
                      <div class="card-body">
                        <div class="form-group row">
                          <label for="inputFrom" class="col-sm-2 col-form-label">From</label>
                          <div class="col-sm-4">
                            <select name="from" class="form-control select2" style="width: 100%;">
                              <option selected disabled>---SELECT COUNTRY---</option>
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
                            <select name="to" class="form-control select2" style="width: 100%;">
                              <option selected disabled>---SELECT COUNTRY---</option>
                              <?php foreach ($countries as $country): ?>
                                  <option value="<?= esc($country['iso_code']) ?>">
                                    <?= esc($country['country']) . ' - ' . esc($country['iso_code']) ?>
                                  </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputService" class="col-sm-2 col-form-label">Service</label>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="radio" id="radioPrimary1" name="service" value="AIR" required>
                                  <label for="radioPrimary1">
                                    AIR
                                  </label>
                                </div>

                                <div class="icheck-primary d-inline">
                                  <input type="radio" id="radioPrimary2" name="service" value="SEA" required>
                                  <label for="radioPrimary2">
                                    SEA
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPrice" class="col-sm-2 col-form-label">Price</label>
                          <div class="col-sm-4">
                            <input type="text" name="price" class="form-control" id="inputPrice" placeholder="Price" required>
                          </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer">
                        <button type="submit" class="btn btn-info float-right">Create</button>
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
