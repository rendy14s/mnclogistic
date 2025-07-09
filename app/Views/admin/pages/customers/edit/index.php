<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Edit customer Info</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('customers') ?>">Data customer</a></li>
                    <li class="breadcrumb-item active">Edit customer Info</li>
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
                    <form action="<?= base_url('customers/api/edit/' . $customer['id']) ?>" method="post">
                        <div class="card-body">
                            <!-- CSRF Token -->
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="marking_code">Marking Code</label>
                                <input type="text" name="marking_code" class="form-control" id="marking_code" value="<?= old('marking_code', esc($customer['marking_code'])) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" id="customer_name" value="<?= old('customer_name', esc($customer['customer_name'])) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" id="phone_number" value="<?= old('phone_number', esc($customer['phone_number'])) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" id="address" value="<?= old('address', esc($customer['address'])) ?>" required>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Customer</button>
                        </div>
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
