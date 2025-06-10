<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Add User Access</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('users') ?>">Data User</a></li>
                    <li class="breadcrumb-item active">Add New User</li>
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
                     <form class="form-horizontal" action="api/user/register" method="post" required>
                      <div class="card-body">
                        <div class="form-group row">
                          <label for="inputFirstName" class="col-sm-2 col-form-label">First Name</label>
                          <div class="col-sm-4">
                            <input type="text" name="firstName" class="form-control" id="inputFirstName" placeholder="First Name" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputLastName" class="col-sm-2 col-form-label">Last Name</label>
                          <div class="col-sm-4">
                            <input type="text" name="lastName" class="form-control" id="inputLastName" placeholder="Last Name" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                          <div class="col-sm-4">
                            <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Username" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                          <div class="col-sm-4">
                            <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-2 col-form-label">Is Admin ?</label>
                          <div class="col-sm-4">
                            <div class="form-check">
                              <input type="checkbox" name="isAdmin" class="form-check-input" id="inputIsAdmin" required>
                            </div>
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
