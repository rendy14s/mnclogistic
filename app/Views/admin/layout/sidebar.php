
<?php
  $uri = service('uri'); // CI 4 URI service

  $segment_first = $uri->getSegment(1); // e.g. 'users'
  $segment_second = $uri->getSegment(2); // e.g. 'edit'
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
          <a href="#" class="d-block"><?= esc($session->get('user')['fullname'] ?? 'Guest') ?></a>
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
          <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '6'])): ?>
          <li class="nav-item <?= ($segment_first === 'customers') ? 'menu-open' : '' ?>">
            <a href="<?= base_url('customers') ?>" class="nav-link">
              <i class="nav-icon fa fa-fw fa-list-alt"></i>
              <p>Customer</p>
            </a>
          </li>
        <?php endif; ?>

          <li class="nav-item has-treeview <?= ($segment_first === 'shipment') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link  <?= ($segment_first === 'shipment') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-table"></i>
                  <p>
                      Shipment
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '6', '3'])): ?>
                  <li class="nav-item">
                      <a href="<?= base_url('shipment/newdata') ?>" class="nav-link <?= ($segment_first === 'shipment' && $segment_second === 'newdata') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>New Data</p>
                      </a>
                  </li>
                <?php endif; ?>
                <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '6', '4'])): ?>
                  <li class="nav-item">
                      <a href="<?= base_url('shipment/ongoing') ?>" class="nav-link <?= ($segment_first === 'shipment' && $segment_second === 'ongoing') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>On Going</p>
                      </a>
                  </li>
                <?php endif; ?>
                <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '2', '5', '6', '4'])): ?>
                  <li class="nav-item">
                      <a href="<?= base_url('shipment/arrived') ?>" class="nav-link <?= ($segment_first === 'shipment' && $segment_second === 'arrived') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Arrived at Warehouse</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="<?= base_url('shipment/outfordelivery') ?>" class="nav-link <?= ($segment_first === 'shipment' && $segment_second === 'outfordelivery') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Out for Delivery</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="<?= base_url('shipment/delivered') ?>" class="nav-link <?= ($segment_first === 'shipment' && $segment_second === 'delivered') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Delivered</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="<?= base_url('shipment/faileddelivered') ?>" class="nav-link <?= ($segment_first === 'shipment' && $segment_second === 'faileddelivered') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Failed Delivery</p>
                      </a>
                  </li>
                <?php endif; ?>
              </ul>
          </li>

        <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '3', '4', '5'])): ?>
          <li class="nav-item has-treeview <?= ($segment_first === 'waybill') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link  <?= ($segment_first === 'waybill') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-table"></i>
                  <p>
                      Way Bill
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '3'])): ?>
                  <li class="nav-item">
                      <a href="<?= base_url('waybill/waybillbatam') ?>" class="nav-link <?= ($segment_first === 'waybill' && $segment_second === 'waybillbatam') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Batam</p>
                      </a>
                  </li>
                <?php endif; ?>
                <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '4'])): ?>
                  <li class="nav-item">
                      <a href="<?= base_url('waybill/waybilljakarta') ?>" class="nav-link <?= ($segment_first === 'waybill' && $segment_second === 'waybilljakarta') ? 'active' : '' ?>">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Jakarta</p>
                      </a>
                  </li>
                <?php endif; ?>
              </ul>
          </li>
        <?php endif; ?>

          <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '2', '6', '4'])): ?>
            <li class="nav-header">System</li>
            <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5'])): ?>
              <li class="nav-item <?= ($segment_first === 'users') ? 'menu-open' : '' ?>">
                <a href="<?= base_url('users') ?>" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>User</p>
                </a>
              </li>
            <?php endif; ?>
            <?php if (($user = $session->get('user')) && in_array($user['role'], ['1', '5', '2', '6', '4'])): ?>
              <li class="nav-item <?= ($segment_first === 'thirdcourier') ? 'menu-open' : '' ?>">
                <a href="<?= base_url('thirdcourier') ?>" class="nav-link">
                  <i class="nav-icon fas ion-android-car"></i>
                  <p>3rd Courier</p>
                </a>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
