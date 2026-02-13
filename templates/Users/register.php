<div class="register-box">
    <div class="register-logo">
        <a href=""></a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register an account</p>
            <?= $this->Form->create(null, ['type' => 'file']) ?>
            <div class="form-group mb-3">
                <?= $this->Form->control('id_number', ['class' => 'form-control', 'placeholder' => 'ID Number']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('username', ['class' => 'form-control', 'placeholder' => 'Username']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('first_name', ['class' => 'form-control', 'placeholder' => 'First Name']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('middle_initial', [
                    'class' => 'form-control',
                    'placeholder' => 'Middle Innitial',
                    'label' => 'Middle Innitial',
                    'maxlength' => 2,
                    'pattern' => '[A-Za-z]\\.?'
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('last_name', ['class' => 'form-control', 'placeholder' => 'Last name']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('suffix', ['class' => 'form-control', 'placeholder' => 'Suffix']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('degree', ['class' => 'form-control', 'placeholder' => 'Degree']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('rank', ['class' => 'form-control', 'placeholder' => 'Rank']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('position', ['class' => 'form-control', 'placeholder' => 'Position']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('email_address', ['class' => 'form-control', 'placeholder' => 'Email Address']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('office', [
                    'class' => 'form-control',
                    'empty' => 'Select Office',
                    'options' => [
                        'SGOD' => 'SGOD',
                        'CID' => 'CID',
                        'OSDS' => 'OSDS',
                    ]
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('section_unit', ['class' => 'form-control', 'placeholder' => 'Section/Unit']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('esignature', [
                    'type' => 'file',
                    'class' => 'form-control',
                    'accept' => '.png,.jpg,.jpeg'
                ]) ?>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <a class="btn btn-link p-0" href="<?= $this->Url->build(['action' => 'login']) ?>">
                        Already have an account?
                    </a>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>

<?php if (!empty($showCredentials)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var username = <?= json_encode($savedUsername ?? '') ?>;
    var password = <?= json_encode($savedPassword ?? '123') ?>;
    var modal = document.getElementById('register-alert-modal');
    var message = document.getElementById('register-alert-message');
    if (!modal || !message) {
        return;
    }
    var html = '<div><strong>Account Created Successful</strong></div>' +
        '<div style="margin-top:8px;">Username: ' + String(username) + '</div>' +
        '<div>Password: ' + String(password) + '</div>';
    message.innerHTML = html;
    modal.setAttribute('data-redirect', <?= json_encode($this->Url->build(['action' => 'login'])) ?>);
    modal.classList.add('show');
});
</script>
<?php endif; ?>

<?php if (!empty($saveErrorMessage)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('register-alert-modal');
    var message = document.getElementById('register-alert-message');
    if (!modal || !message) {
        return;
    }
    var baseMessage = <?= json_encode($saveErrorMessage) ?>;
    var details = <?= json_encode($saveErrorDetails ?? []) ?>;
    var html = '<div>' + baseMessage + '</div>';
    if (details && details.length) {
        html += '<ul style="margin:8px 0 0 18px;">';
        details.forEach(function (item) {
            html += '<li>' + String(item) + '</li>';
        });
        html += '</ul>';
    }
    message.innerHTML = html;
    modal.classList.add('show');
});
</script>
<?php endif; ?>

<div id="register-alert-modal" class="register-alert-modal" aria-hidden="true">
    <div class="register-alert-backdrop"></div>
    <div class="register-alert-dialog" role="dialog" aria-modal="true" aria-labelledby="register-alert-title">
        <div class="register-alert-header">
            <h5 id="register-alert-title">Notice</h5>
            <button type="button" class="register-alert-close" aria-label="Close">×</button>
        </div>
        <div class="register-alert-body" id="register-alert-message"></div>
        <div class="register-alert-footer">
            <button type="button" class="btn btn-primary register-alert-ok">OK</button>
        </div>
    </div>
</div>

<style>
.register-alert-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}
.register-alert-modal.show {
    display: flex;
}
.register-alert-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
}
.register-alert-dialog {
    position: relative;
    background: #fff;
    border-radius: 10px;
    width: min(420px, 92vw);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
    z-index: 1;
    overflow: hidden;
}
.register-alert-header {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e9ecef;
}
.register-alert-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}
.register-alert-close {
    background: transparent;
    border: 0;
    font-size: 20px;
    line-height: 1;
    color: #6c757d;
    cursor: pointer;
}
.register-alert-body {
    padding: 16px;
    color: #343a40;
}
.register-alert-footer {
    padding: 12px 16px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('register-alert-modal');
    if (!modal) {
        return;
    }
    var closeButtons = modal.querySelectorAll('.register-alert-close, .register-alert-ok, .register-alert-backdrop');
    closeButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            modal.classList.remove('show');
            var redirectUrl = modal.getAttribute('data-redirect');
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.querySelector('input[name="middle_initial"]');
    if (!input) {
        return;
    }
    input.addEventListener('input', function () {
        var letter = (input.value || '').replace(/[^a-zA-Z]/g, '').charAt(0);
        input.value = letter ? letter.toUpperCase() : '';
    });
    var form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            var letter = (input.value || '').replace(/[^a-zA-Z]/g, '').charAt(0);
            input.value = letter ? letter.toUpperCase() + '.' : '';
        });
    }
});
</script>
