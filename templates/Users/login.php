<div class="login-shell">
    <div class="card login-card">
        <div class="card-body">
            <div class="login-brand">
                <video class="login-logo-video" autoplay muted loop playsinline>
                    <source src="img/sdo.mp4" type="video/mp4">
                </video>
                <h1 class="login-title">ONLINE ACTIVITY PROPOSAL AND APPROVAL SYSTEM</h1>
                <p class="login-subtitle">Sign in to continue</p>
            </div>

            <div class="mb-3"><?= $this->Flash->render() ?></div>

            <?= $this->Form->create()?>
            <div class="form-group mb-3">
                <?= $this->Form->control('username', [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your username'
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('password', [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your password'
                ]) ?>
            </div>
            <div class="privacy-trigger mb-3 text-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#privacy-modal">
                    View Privacy Notice
                </button>
            </div>
            <div class="login-action">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
            <?= $this->Form->end() ?>

            <div class="login-forgot">
                <a href="#" data-toggle="modal" data-target="#forgot-modal">Forgot Password?</a>
            </div>
            <div class="login-signup text-center mt-2">
                Don't have an account? <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']) ?>">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="privacy-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Notice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="privacy-text mb-3">
                    The Department of Education (DepEd) collects, processes, and stores personal data from students, personnel, and stakeholders to manage educational services and programs, adhering strictly to the Data Privacy Act of 2012 (RA 10173). Data is used for school operations,, policy formulation, and research, with access limited to authorized personnel. Information is secured, retained for specific periods, and not shared without consent.
                </p>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="privacy-agree" required>
                    <label class="custom-control-label" for="privacy-agree">I agree</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="privacy-accept">Accept</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forgot Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" id="forgot-username" placeholder="Username">
                </div>
                <div class="form-group mb-0">
                    <label>Email Address/Contact Number</label>
                    <input type="text" class="form-control" id="forgot-contact" placeholder="Email or mobile">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="forgot-submit">Send Request</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('forgot-submit');
    var privacyAccept = document.getElementById('privacy-accept');
    var privacyAgree = document.getElementById('privacy-agree');
    var loginBtn = document.querySelector('.login-action button[type="submit"]');
    if (loginBtn) {
        loginBtn.disabled = true;
    }
    if (window.jQuery && $('#privacy-modal').length) {
        $('#privacy-modal').modal('show');
    }
    if (privacyAccept) {
        privacyAccept.addEventListener('click', function () {
            if (privacyAgree && privacyAgree.checked) {
                if (loginBtn) {
                    loginBtn.disabled = false;
                }
                $('#privacy-modal').modal('hide');
            } else {
                alert('Please accept the Privacy Notice to continue.');
            }
        });
    }
    if (privacyAgree) {
        privacyAgree.addEventListener('change', function () {
            if (loginBtn) {
                loginBtn.disabled = !privacyAgree.checked;
            }
        });
    }
    if (!btn) {
        return;
    }
    btn.addEventListener('click', function () {
        var username = document.getElementById('forgot-username').value || '';
        var contact = document.getElementById('forgot-contact').value || '';
        var csrf = document.querySelector('meta[name="csrf-token"]');
        var token = csrf ? csrf.getAttribute('content') : '';

        fetch('<?= $this->Url->build(['controller' => 'Users', 'action' => 'forgotPassword']) ?>', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': token
            },
            body: JSON.stringify({ username: username, contact: contact })
        })
            .then(function (resp) { return resp.json(); })
            .then(function (data) {
                alert(data.message || 'Request sent.');
                if (data.status === 'success') {
                    $('#forgot-modal').modal('hide');
                    document.getElementById('forgot-username').value = '';
                    document.getElementById('forgot-contact').value = '';
                }
            })
            .catch(function () {
                alert('Failed to send request.');
            });
    });
});
</script>
