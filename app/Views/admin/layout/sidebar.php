
<?php
  $uri = service('uri'); // CI 4 URI service

  $segment_first = $uri->getSegment(1); // e.g. 'users'
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?= base_url('assets/admin/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">MNC Logistic</span>
    </a>

    <!-- Sidebar -->
    <?php $session = session(); ?>
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?= base_url('assets/admin/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= esc($session->get('user')['fullname']) ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">General</li>
          <li class="nav-item <?= ($segment_first === 'dashboard') ? 'menu-open' : '' ?>">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <?php if (in_array($session->get('user')['role'], ['1', '5', '6'])): ?>
          <li class="nav-item <?= ($segment_first === 'customers') ? 'menu-open' : '' ?>">
            <a href="<?= base_url('customers') ?>" class="nav-link">
              <i class="nav-icon fa fa-fw fa-list-alt"></i>
              <p>Customer</p>
            </a>
          </li>
        <?php endif; ?>

          <?php if ($session->get('user')['role'] !== '6'): ?>
          <li class="nav-item <?= ($segment_first === 'shipment') ? 'menu-open' : '' ?>">
            <a href="<?= base_url('shipment') ?>" class="nav-link">
              <i class="nav-icon fa fa-fw fa-list-alt"></i>
              <p>
                Shipment
              </p>
            </a>
          </li>
        <?php endif; ?>

          <?php if (in_array($session->get('user')['role'], ['1', '5'])): ?>
            <li class="nav-header">System</li>
            <li class="nav-item <?= ($segment_first === 'users') ? 'menu-open' : '' ?>">
              <a href="<?= base_url('users') ?>" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>User</p>
              </a>
            </li>

            <li class="nav-item <?= ($segment_first === 'thirdcourier') ? 'menu-open' : '' ?>">
              <a href="<?= base_url('thirdcourier') ?>" class="nav-link">
                <i class="nav-icon fas ion-android-car"></i>
                <p>3rd Courier</p>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
