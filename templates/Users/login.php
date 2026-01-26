<div class="login-shell">
    <div class="card login-card">
        <div class="card-body">
            <div class="login-brand">
                <video class="login-logo-video" autoplay muted loop playsinline>
                    <source src="img/sdo.mp4" type="video/mp4">
                </video>
                <h1 class="login-title">SDO Activity</h1>
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
            <div class="login-action">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
            <?= $this->Form->end() ?>

            <div class="login-forgot">
                <a href="#" data-toggle="modal" data-target="#forgot-modal">Forgot Password?</a>
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
                    <label>Contact (optional)</label>
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
