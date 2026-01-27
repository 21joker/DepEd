<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'SDO ACTIVTITY';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', 'sdo.ico') ?>
    <meta name="csrf-token" content="<?= h($this->request->getAttribute('csrfToken')) ?>">


    <?= $this->Html->css('home') ?>
    <!-- <?= $this->Html->css([]) ?> -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <?= $this->Html->css('/plugins/fontawesome-free/css/all.min.css') ?>
    <?= $this->Html->css('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->css('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
    <?= $this->Html->css('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>
    <?= $this->Html->css('/dist/css/adminlte.min.css') ?>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <?= $this->Html->image('sdo.jpeg', [
            'pathPrefix' => 'dist/img/',
            'alt' => 'AdminLTELogo',
            'class' => 'animation__shake',
            'height' => '60',
            'width' => '60',
        ]) ?>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build('/Users/logout') ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
            <?php if ($this->getRequest()->getParam('controller') !== 'Requests' || $this->getRequest()->getParam('action') !== 'add'): ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            
            <?php endif; ?>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="" class="brand-link">
            <?= $this->Html->image('sdo.jpeg', [
                'alt' => 'SDO Santiago Logo',
                'class' => 'brand-image img-circle elevation-3',
                'style' => 'opacity: .8',
            ]) ?>
            <span class="brand-text font-weight-light">SDO Santiago</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?= $this->Html->image('user2-160x160.jpg', [
                        'pathPrefix' => 'dist/img/',
                        'class' => 'img-circle elevation-2',
                        'alt' => 'User Image',
                    ]) ?>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= h($authDisplayName ?? ($auth['username'] ?? '')) ?></a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <!-- <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div> -->

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <?php
                    $controller = $this->getRequest()->getParam('controller');
                    $action = $this->getRequest()->getParam('action');
                    $isRequestsPending = $controller === 'Requests' && $action === 'pending';
                    $isLogs = $controller === 'Logs' && $action === 'index';
                    $isUsersIndex = $controller === 'Users' && $action === 'index';
                    $isUsersEnroll = $isUsersIndex && (string)$this->getRequest()->getQuery('enroll') === '1';
                    $isUsersManage = $isUsersIndex && !$isUsersEnroll;
                    if ($isUsersEnroll) {
                        $pageHeading = 'Create User Account';
                    } elseif ($isUsersManage) {
                        $pageHeading = 'Manage Users';
                    } else {
                        $pageHeading = 'Dashboard';
                    }
                ?>
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item menu-open">
                        <?php if (in_array($auth['role'] ?? null, ['Superuser', 'Administrator', 'Approver'], true)): ?>
                        <a href="/usermngt/requests/pending" class="nav-link <?= $isRequestsPending ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>
                                Proposal
                            </p>
                        </a>
                        <?php endif; ?>

                        <?php if (in_array($auth['role'] ?? null, ['Superuser'], true)): ?>
                        <a href="/usermngt/logs" class="nav-link <?= $isLogs ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Logs
                            </p>
                        </a>
                        <?php endif; ?>

                        <?php if (
                            ($this->getRequest()->getParam('controller') !== 'Requests' || $this->getRequest()->getParam('action') !== 'add')
                            && in_array($auth['role'] ?? null, ['Superuser', 'Administrator'], true)
                        ): ?>
                        <a href="/usermngt/users?enroll=1" class="nav-link <?= $isUsersEnroll ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Add new user
                            </p>
                        </a>
                        <?php endif; ?>
                        <?php if (in_array($auth['role'] ?? null, ['Superuser', 'Administrator'], true)): ?>
                        <a href="/usermngt/users" class="nav-link <?= $isUsersManage ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Manage user
                            </p>
                        </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
        <div class="sidebar-custom">
            <a href="<?= $this->Url->build('/Users/logout') ?>" class="btn btn-outline-light btn-sm btn-block">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= h($pageHeading) ?></h1>
                        
    <div class="header-right">

                    </div><!-- /.col -->
                    <div class="col-sm-6">
                       
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <?= $this->fetch('content') ?>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
   

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <div class="ml-2">
            <?= $this->Html->link('Logout','/Users/logout') ?>
        </div>
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?= $this->Html->script([
    '/plugins/jquery/jquery.min.js',
    '/plugins/bootstrap/js/bootstrap.bundle.min.js',
    '/plugins/datatables/jquery.dataTables.min.js',
    '/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
    '/plugins/datatables-responsive/js/dataTables.responsive.min.js',
    '/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
    '/plugins/datatables-buttons/js/dataTables.buttons.min.js',
    '/plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
    '/plugins/jszip/jszip.min.js',
    '/plugins/pdfmake/pdfmake.min.js',
    '/plugins/pdfmake/vfs_fonts.js',
    '/plugins/datatables-buttons/js/buttons.html5.min.js',
    '/plugins/datatables-buttons/js/buttons.print.min.js',
    '/plugins/datatables-buttons/js/buttons.colVis.min.js',
    '/plugins/chart.js/Chart.min.js',
    '/dist/js/adminlte.min.js',
]) ?>
<?= $this->fetch('scriptBottom') ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        form.setAttribute('autocomplete', 'off');
    });
    document.querySelectorAll('input, textarea, select').forEach(function (field) {
        field.setAttribute('autocomplete', 'off');
    });
});
</script>

<div class="modal fade" id="dashboard-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dashboard-modal-title">Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="dashboard-modal-body">
                <div class="text-muted">Loading...</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function openDashboardModal(url, title) {
        var modalTitle = document.getElementById('dashboard-modal-title');
        var modalBody = document.getElementById('dashboard-modal-body');
        if (!modalTitle || !modalBody) {
            return;
        }
        modalTitle.textContent = title || 'Details';
        modalBody.innerHTML = '<div class="text-muted">Loading...</div>';
        $('#dashboard-modal').modal('show');
        var loginUrl = <?= json_encode($this->Url->build(['controller' => 'Users', 'action' => 'login'])) ?>;
        var webroot = <?= json_encode($this->request->getAttribute('webroot')) ?> || '/';
        var resolvedUrl = url;
        if (url && !/^https?:\/\//i.test(url)) {
            var base = window.location.origin + (webroot || '/');
            if (base.charAt(base.length - 1) !== '/') {
                base += '/';
            }
            try {
                resolvedUrl = new URL(url, base).toString();
            } catch (e) {
                resolvedUrl = url;
            }
        }
        fetch(resolvedUrl, {
            credentials: 'include',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (resp) {
                if (resp.status === 401 || resp.status === 403) {
                    modalBody.innerHTML = '<div class="text-danger">Session expired. Please refresh the page.</div>';
                    return '';
                }
                return resp.text();
            })
            .then(function (html) {
                if (!html) {
                    return;
                }
                if (html.indexOf('login-card') !== -1 || html.indexOf('Sign in to continue') !== -1) {
                    modalBody.innerHTML = '<div class="text-danger">Session expired. Please refresh the page.</div>';
                    return;
                }
                modalBody.innerHTML = html;
                bindModalLinks();
            })
            .catch(function () {
                modalBody.innerHTML = '<div class="text-danger">Failed to load.</div>';
            });
    }

    function bindModalLinks() {
        document.querySelectorAll('a.dashboard-card, a.modal-link').forEach(function (card) {
            if (card.dataset.bound === '1') {
                return;
            }
            card.dataset.bound = '1';
            card.addEventListener('click', function (e) {
                e.preventDefault();
                var url = card.getAttribute('href');
                var title = card.getAttribute('data-title') || 'Details';
                openDashboardModal(url, title);
            });
        });
    }

    bindModalLinks();
});
</script>

</body>
</html>
