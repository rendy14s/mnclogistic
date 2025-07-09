<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Edit User Info</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('users') ?>">Data User</a></li>
                    <li class="breadcrumb-item active">Edit User Info</li>
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
                     <form action="<?= base_url('users/api/changePassword/' . $user['id']) ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="card-body">
                                <!-- Display validation errors -->
                                <?php if (session('errors')): ?>
                                    <div class="alert alert-danger">
                                        <?= implode('<br>', session('errors')) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter new password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirm">Confirm Password</label>
                                    <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Confirm password" required>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                                <a href="<?= base_url('users') ?>" class="btn btn-secondary">Cancel</a>
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
