<?= $this->extend('admin/layout/index') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?= $this->section('content') ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Edit Courir Info</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('3rdcourier') ?>">Data Courir</a></li>
                    <li class="breadcrumb-item active">Edit Courir Info</li>
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
                    <form action="<?= base_url('3rdcourier/api/edit/' . $courir['id']) ?>" method="post">
                        <div class="card-body">
                            <!-- CSRF Token -->
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="courier_name">Courir Name</label>
                                 <div class="col-sm-4">
                                    <input type="text" name="courier_name" class="form-control" id="courier_name" value="<?= old('courier_name', esc($courir['courier_name'])) ?>" required>
                                 </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Courir</button>
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
