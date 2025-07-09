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
                    <form action="<?= base_url('users/api/edit/' . $user['id']) ?>" method="post">
                        <div class="card-body">
                            <!-- CSRF Token -->
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control" id="first_name" value="<?= old('first_name', esc($user['first_name'])) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" value="<?= old('last_name', esc($user['last_name'])) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" value="<?= old('username', esc($user['username'])) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" id="role">
                                    <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin</option>
                                    <option value="2" <?= $user['role'] == 2 ? 'selected' : '' ?>>Finance</option>
                                    <option value="3" <?= $user['role'] == 3 ? 'selected' : '' ?>>Staff Operational Batam</option>
                                    <option value="4" <?= $user['role'] == 4 ? 'selected' : '' ?>>Staff Operational Jakarta</option>
                                    <option value="5" <?= $user['role'] == 5 ? 'selected' : '' ?>>Direktur</option>
                                    <option value="6" <?= $user['role'] == 6 ? 'selected' : '' ?>>Marketing</option>
                                </select>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update User</button>
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
