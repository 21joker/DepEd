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

$cakeDescription = 'PROJECT OARAS';
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

    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #111827;
            --sidebar-bg-alt: #0b1220;
            --sidebar-border: rgba(148, 163, 184, 0.18);
            --sidebar-text: #e2e8f0;
            --sidebar-muted: #94a3b8;
            --sidebar-accent: #38bdf8;
            --sidebar-accent-strong: #0ea5e9;
        }
        .sidebar-mini.sidebar-collapse {
            --sidebar-width: 4.6rem;
        }
        @media (max-width: 991.98px) {
            :root {
                --sidebar-width: 0px;
            }
        }
        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1 0 auto;
            padding-bottom: 52px;
        }
        .main-sidebar .user-panel {
            align-items: flex-start;
        }
        .main-sidebar .user-panel .image {
            margin-top: 2px;
        }
        .main-sidebar .user-panel .info {
            padding-top: 2px;
        }
        .main-sidebar .user-panel .info {
            max-width: calc(100% - 54px);
            overflow: hidden;
        }
        .main-sidebar .user-panel .info a {
            display: block;
            overflow: visible;
            white-space: normal;
            word-break: break-word;
            line-height: 1.2;
        }
        .main-sidebar .user-panel .info a span {
            display: block;
        }
        .main-sidebar .user-panel .info a span + span {
            font-size: 11px;
            opacity: 0.8;
        }
        .main-sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-bg-alt) 100%);
            border-right: 1px solid var(--sidebar-border);
        }
        .main-sidebar .brand-link {
            background: transparent;
            border-bottom: 1px solid var(--sidebar-border);
            color: var(--sidebar-text);
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .main-sidebar .brand-link .brand-text {
            color: var(--sidebar-text);
        }
        .main-sidebar .brand-link .brand-image {
            border: 2px solid rgba(56, 189, 248, 0.35);
            padding: 2px;
            background: rgba(15, 23, 42, 0.4);
        }
        .main-sidebar .user-panel {
            border-bottom: 1px solid var(--sidebar-border);
        }
        .main-sidebar .user-panel .info a {
            color: var(--sidebar-text);
        }
        .main-sidebar .user-panel .info a span + span {
            color: var(--sidebar-muted);
            opacity: 1;
        }
        .main-sidebar .nav-sidebar .nav-link {
            color: var(--sidebar-text);
            border-radius: 10px;
            margin: 4px 10px;
            padding: 10px 12px;
            transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }
        .main-sidebar .nav-sidebar .nav-link .nav-icon {
            color: var(--sidebar-muted);
            margin-right: 8px;
        }
        .main-sidebar .nav-sidebar .nav-link:hover {
            background: rgba(56, 189, 248, 0.12);
            color: #ffffff;
        }
        .main-sidebar .nav-sidebar .nav-link:hover .nav-icon {
            color: var(--sidebar-accent);
        }
        .main-sidebar .nav-sidebar .nav-link.active {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.25), rgba(14, 165, 233, 0.15));
            color: #ffffff;
            box-shadow: inset 0 0 0 1px rgba(56, 189, 248, 0.3), 0 10px 18px rgba(2, 132, 199, 0.2);
        }
        .main-sidebar .nav-sidebar .nav-link.active .nav-icon {
            color: var(--sidebar-accent-strong);
        }
        .main-sidebar .nav-sidebar .nav-header {
            color: var(--sidebar-muted);
            letter-spacing: 0.6px;
        }
        .main-sidebar .sidebar-custom {
            border-top: 1px solid var(--sidebar-border);
        }
        .main-sidebar .sidebar-custom .btn {
            border-color: rgba(148, 163, 184, 0.4);
            color: var(--sidebar-text);
        }
        .main-sidebar .sidebar-custom .btn:hover {
            background: rgba(56, 189, 248, 0.18);
            color: #ffffff;
            border-color: rgba(56, 189, 248, 0.6);
        }
        table.dataTable > thead .sorting:before,
        table.dataTable > thead .sorting_asc:before,
        table.dataTable > thead .sorting_desc:before,
        table.dataTable > thead .sorting_asc_disabled:before,
        table.dataTable > thead .sorting_desc_disabled:before {
            content: "\2191";
        }
        table.dataTable > thead .sorting:after,
        table.dataTable > thead .sorting_asc:after,
        table.dataTable > thead .sorting_desc:after,
        table.dataTable > thead .sorting_asc_disabled:after,
        table.dataTable > thead .sorting_desc_disabled:after {
            content: "\2193";
        }
        .main-footer {
            background: linear-gradient(90deg, #1e3a8a 0%, #1e3a8a 50%, #b91c1c 50%, #b91c1c 100%);
            background-size: 100vw 100%;
            background-position: 0 0;
            background-repeat: no-repeat;
            border-top: 1px solid #e2e8f0;
            color: #ffffff;
            font-size: 14px;
            padding: 12px 16px 12px calc(var(--sidebar-width) + 16px);
            margin-left: 0 !important;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100vw;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        .main-footer small {
            display: block;
            letter-spacing: 0.2px;
            color: #ffffff;
            font-size: 14px;
            line-height: 1.2;
        }
        .main-footer .footer-inner {
            text-align: left;
            width: 100%;
        }
        .main-header.navbar {
            position: relative;
            background: linear-gradient(90deg, #1e3a8a 0%, #1e3a8a 50%, #b91c1c 50%, #b91c1c 100%);
            background-size: 100vw 100%;
            background-position: calc(-1 * var(--sidebar-width)) 0;
            background-repeat: no-repeat;
            border-bottom: 1px solid #e2e8f0;
        }
        .main-header.navbar::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 12px;
            background: #facc15;
            z-index: 1;
        }
        .main-header.navbar > * {
            position: relative;
            z-index: 2;
        }
        .main-header.navbar .nav-link,
        .main-header.navbar .nav-link i,
        .main-header.navbar .navbar-nav .nav-link {
            color: #ffffff;
        }
        .btn {
            border-radius: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            border-width: 1px;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.12);
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.16);
            filter: brightness(1.02);
        }
        .btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.14);
        }
        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
        }
        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.6);
        }
        .btn-outline-light:hover {
            border-color: rgba(255, 255, 255, 0.9);
        }
        .btn-info,
        .btn-success,
        .btn-warning,
        .btn-danger,
        .btn-primary,
        .btn-secondary {
            color: #ffffff;
        }
        .btn-warning {
            color: #1f2937;
        }
        .btn-warning:hover {
            color: #1f2937;
        }
        @media (max-width: 768px) {
            .main-header.navbar {
                background-size: 100% 100%;
                background-position: 0 0;
            }
        }
    </style>


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
                    <?= $this->Html->image('resized-image.png', [
                        'pathPrefix' => 'dist/img/',
                        'class' => 'img-circle elevation-2',
                        'alt' => 'User Image',
                    ]) ?>
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        <span class="d-block"><?= h($authDisplayName ?? ($auth['username'] ?? '')) ?></span>
                        <?php if (!empty($authOfficeLine)): ?>
                            <span class="d-block"><?= h($authOfficeLine) ?></span>
                        <?php endif; ?>
                    </a>
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
                        <?php
                            $isActivityProposal = $controller === 'Requests' && $action === 'add';
                            $isProjectProposal = $controller === 'Requests' && $action === 'project';
                            $role = $auth['role'] ?? null;
                            $showProposalTabs = !in_array($role, ['Administrator', 'Approver', 'Superuser'], true);
                        ?>
                        <?php if ($showProposalTabs): ?>
                            <a href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'add']) ?>"
                               class="nav-link <?= $isActivityProposal ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Activity Proposal</p>
                            </a>
                        <?php endif; ?>
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
    <footer class="main-footer">
        <div class="footer-inner">
            <small>©Copyright 2026 21Joker | All Rights Reserved</small>
        </div>
    </footer>

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
