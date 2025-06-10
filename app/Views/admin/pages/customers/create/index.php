<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Add Customer</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('customers') ?>">Data Customers</a></li>
                    <li class="breadcrumb-item active">Add New Customer</li>
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
                          <label for="inputMarkingCode" class="col-sm-2 col-form-label">Marking Code</label>
                          <div class="col-sm-4">
                            <input type="text" name="markingCode" class="form-control" id="inputMarkingCode" placeholder="Marking Code" required disabled>
                            <input type="hidden" name="markingCodeHidden" id="inputMarkingCodeHidden">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputCustomerName" class="col-sm-2 col-form-label">Customer Name</label>
                          <div class="col-sm-4">
                            <input type="text" name="customerName" class="form-control" id="inputCustomerName" placeholder="Customer Name" onkeyup="generateMarkingCode()" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPhoneNumber" class="col-sm-2 col-form-label">Phone Number</label>
                          <div class="col-sm-4">
                            <input type="text" name="phoneNumber" class="form-control" id="inputPhoneNumber" placeholder="Phone Number" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="InputAddress" class="col-sm-2 col-form-label">Address</label>
                          <div class="col-sm-4">
                            <input type="text" name="address" class="form-control" id="InputAddress" placeholder="Address" required>
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
