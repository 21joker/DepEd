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

$cakeDescription = 'Project OARAS';
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

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap">

    <?= $this->Html->css('/plugins/fontawesome-free/css/all.min.css') ?>
    <?= $this->Html->css('/dist/css/adminlte.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="hold-transition register-page login-bg">

<style>
:root {
    --ink: #0f172a;
    --muted: #64748b;
    --panel: rgba(255, 255, 255, 0.92);
    --accent: #0ea5a0;
    --accent-strong: #0b7a77;
    --ring: rgba(14, 165, 160, 0.35);
}

body.login-bg {
    font-family: "Manrope", "Segoe UI", Arial, sans-serif;
    min-height: 100vh;
    background: #0b1020;
    position: relative;
    color: var(--ink);
}

body.login-bg::before {
    content: "";
    position: fixed;
    inset: 0;
    background:
        linear-gradient(120deg, rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.45)),
        url("/usermngt/img/sdobg.jpg") center / cover no-repeat;
    z-index: -2;
}

body.login-bg::after {
    content: "";
    position: fixed;
    inset: 0;
    background: radial-gradient(circle at 15% 20%, rgba(14, 165, 160, 0.18), transparent 45%),
                radial-gradient(circle at 85% 10%, rgba(59, 130, 246, 0.18), transparent 50%);
    z-index: -1;
}

.login-shell {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px 48px;
}

.login-card {
    width: min(440px, 100%);
    border-radius: 22px;
    border: 1px solid rgba(148, 163, 184, 0.25);
    background: var(--panel);
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
    backdrop-filter: blur(12px);
}

.login-card .card-body {
    padding: 32px 30px 28px;
}

.login-brand {
    text-align: center;
    margin-bottom: 24px;
}

.login-logo-video {
    width: 112px;
    height: 112px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.7);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
    background: #fff;
}

.login-title {
    margin: 18px 0 4px;
    font-family: "Playfair Display", "Times New Roman", serif;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: 0.2px;
    color: #0f172a;
}

.login-subtitle {
    margin: 0;
    font-size: 14px;
    color: var(--muted);
}

.login-card .form-group label {
    font-weight: 600;
    color: #1f2937;
}

.login-card .form-control {
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    padding: 10px 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.login-card .form-control:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 4px var(--ring);
    background: #ffffff;
}
.privacy-text {
    font-size: 12px;
    color: #475569;
    line-height: 1.4;
}
.custom-control-label {
    font-weight: 600;
    color: #0f172a;
}

.login-action {
    margin-top: 12px;
}

.login-card .btn-primary {
    background: linear-gradient(135deg, var(--accent), #3b82f6);
    border: none;
    box-shadow: 0 10px 20px rgba(14, 165, 160, 0.35);
    border-radius: 12px;
    padding: 10px 14px;
    font-weight: 600;
}

.login-card .btn-primary:hover {
    background: linear-gradient(135deg, var(--accent-strong), #2563eb);
}

.login-forgot {
    margin-top: 18px;
    text-align: center;
}

.login-forgot a {
    color: #0f766e;
    font-weight: 600;
}

.login-forgot a:hover {
    color: #115e59;
    text-decoration: underline;
}
.login-footer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 12px;
    text-align: center;
    color: #e2e8f0;
    font-size: 12px;
    letter-spacing: 0.2px;
    z-index: 5;
}

/* Privacy modal polish */
#privacy-modal .modal-content {
    border-radius: 16px;
    border: 1px solid rgba(148, 163, 184, 0.25);
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
}
#privacy-modal .modal-header {
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 20px;
}
#privacy-modal .modal-title {
    font-weight: 700;
    font-size: 16px;
    color: #0f172a;
}
#privacy-modal .modal-body {
    padding: 18px 20px 8px;
}
#privacy-modal .modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 12px 20px 16px;
    gap: 8px;
}
#privacy-modal .privacy-text {
    font-size: 13px;
    line-height: 1.5;
    color: #475569;
}
#privacy-modal .custom-control-label {
    font-weight: 600;
    color: #0f172a;
}
#privacy-modal .btn-primary {
    background: linear-gradient(135deg, var(--accent), #3b82f6);
    border: none;
    border-radius: 10px;
    padding: 8px 16px;
    font-weight: 600;
}
#privacy-modal .btn-outline-secondary {
    border-radius: 10px;
    padding: 8px 16px;
}
</style>

    <?= $this->fetch('content') ?>
    <div class="login-footer">©Copyright 2026 21Joker | All Rights Reserved 
       </div>

    <?= $this->Html->script([
            '/plugins/jquery/jquery.min.js',
            '/plugins/bootstrap/js/bootstrap.bundle.min.js',
            '/dist/js/adminlte.min.js'
    ])?>

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
</body>
</html>

