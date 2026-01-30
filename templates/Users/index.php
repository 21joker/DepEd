<?php $isEnroll = (string)$this->getRequest()->getQuery('enroll') === '1'; ?>
<style>
<?php if ($isEnroll): ?>
#users-table .edit,
#users-table .delete,
#users-table th:last-child,
#users-table td:last-child {
    display: none !important;
}
#add.btn.btn-tool {
    background: #1f5fbf;
    color: #fff;
    border-radius: 999px;
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #1a4f9d;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.18);
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}
#add.btn.btn-tool:hover {
    background: #1a54aa;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.22);
    transform: translateY(-1px);
}
#add.btn.btn-tool:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(31, 95, 191, 0.3);
}
<?php endif; ?>
</style>

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users table</h3>
            <?php if ($isEnroll): ?>
            <div class="card-tools">
                <button id="add" type="button" class="btn btn-tool">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table id="users-table" class="table table-bordered table-striped" data-enroll="<?= $isEnroll ? '1' : '0' ?>">
                <thead>
                <tr>
                    <?php if ($isEnroll): ?>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Modified</th>
                    <?php else: ?>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Level of Governance</th>
                        <th>Edit Account</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->Html->script('/js/Users.js?v=' . filemtime(WWW_ROOT . 'js/Users.js'), ['block' => 'scriptBottom']); ?>
<div class="modal fade" id="users-modal">
    <div class="modal-dialog  modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create($user,['id'=>'users-form'])?>
                <?php if ($isEnroll): ?>
                    <label>Username</label>
                    <?= $this->Form->control('username_display', [
                        'class' => 'form-control',
                        'placeholder' => 'Username',
                        'label' => false,
                        'id' => 'username-display-enroll'
                    ]) ?>
                    <label>First name</label>
                    <?= $this->Form->control('first_name', [
                        'class' => 'form-control',
                        'placeholder' => 'First name',
                        'label' => false,
                        'id' => 'first-name-enroll'
                    ]) ?>
                    <label>M.I</label>
                    <?= $this->Form->control('middle_initial', [
                        'class' => 'form-control',
                        'placeholder' => 'M.I',
                        'label' => false,
                        'id' => 'middle-initial-enroll'
                    ]) ?>
                    <label>Last name</label>
                    <?= $this->Form->control('last_name', [
                        'class' => 'form-control',
                        'placeholder' => 'Last name',
                        'label' => false,
                        'id' => 'last-name-enroll'
                    ]) ?>
                    <label>Suffix</label>
                    <?= $this->Form->control('suffix', [
                        'class' => 'form-control',
                        'placeholder' => 'Suffix (e.g., Jr., III)',
                        'label' => false,
                        'id' => 'suffix-enroll'
                    ]) ?>
                    <label>Degree</label>
                    <?= $this->Form->control('degree', [
                        'class' => 'form-control',
                        'placeholder' => 'Degree (e.g., PhD, MAEd)',
                        'label' => false,
                        'id' => 'degree-enroll'
                    ]) ?>
                    <label>Rank</label>
                    <?= $this->Form->control('rank', [
                        'class' => 'form-control',
                        'placeholder' => 'Rank',
                        'label' => false,
                        'id' => 'rank-enroll'
                    ]) ?>
                    <label>Position</label>
                    <?= $this->Form->control('position', [
                        'class' => 'form-control',
                        'placeholder' => 'Position',
                        'label' => false,
                        'id' => 'position-enroll'
                    ]) ?>
                    <label>Email Address</label>
                    <?= $this->Form->control('email_address', [
                        'class' => 'form-control',
                        'placeholder' => 'Email Address',
                        'label' => false,
                        'type' => 'email',
                        'id' => 'email-address-enroll'
                    ]) ?>
                    <label>Level of governance</label>
                    <?= $this->Form->control('level_of_governance', [
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'level-of-governance-enroll',
                        'options' => [
                            'SGOD' => 'SGOD',
                            'CID' => 'CID',
                            'OSDS'=> 'OSDS',
                        ]
                    ]) ?>
                    <label>Role</label>
                    <?= $this->Form->control('role_display', [
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'role-display-enroll',
                        'options' => [
                            'User' => 'User',
                            'Approver' => 'Approver',
                        ]
                    ]) ?>
                    <label>Password</label>
                    <?= $this->Form->control('password', [
                        'class' => 'form-control',
                        'placeholder' => 'Password',
                        'label' => false,
                        'type' => 'password',
                        'id' => 'password-enroll'
                    ]) ?>
                    <label>Retype Password</label>
                    <?= $this->Form->control('retype_password', [
                        'class' => 'form-control',
                        'placeholder' => 'Retype Password',
                        'label' => false,
                        'type' => 'password',
                        'id' => 'retype-password-enroll'
                    ]) ?>
                    <?= $this->Form->control('username', [
                        'type' => 'hidden',
                        'label' => false,
                        'id' => 'username'
                    ]) ?>
                    <?= $this->Form->control('role', [
                        'type' => 'hidden',
                        'label' => false,
                        'id' => 'role'
                    ]) ?>
                <?php else: ?>
                    <label>Username</label>
                    <?= $this->Form->control('username', [
                        'class' => 'form-control',
                        'placeholder' => 'Username',
                        'label' => false,
                        'id' => 'username-manage'
                    ]) ?>
                    <label>First name</label>
                    <?= $this->Form->control('first_name', [
                        'class' => 'form-control',
                        'placeholder' => 'First name',
                        'label' => false,
                        'id' => 'first-name-manage'
                    ]) ?>
                    <label>M.I</label>
                    <?= $this->Form->control('middle_initial', [
                        'class' => 'form-control',
                        'placeholder' => 'M.I',
                        'label' => false,
                        'id' => 'middle-initial-manage'
                    ]) ?>
                    <label>Last name</label>
                    <?= $this->Form->control('last_name', [
                        'class' => 'form-control',
                        'placeholder' => 'Last name',
                        'label' => false,
                        'id' => 'last-name-manage'
                    ]) ?>
                    <label>Suffix</label>
                    <?= $this->Form->control('suffix', [
                        'class' => 'form-control',
                        'placeholder' => 'Suffix (e.g., Jr., III)',
                        'label' => false,
                        'id' => 'suffix-manage'
                    ]) ?>
                    <label>Degree</label>
                    <?= $this->Form->control('degree', [
                        'class' => 'form-control',
                        'placeholder' => 'Degree (e.g., PhD, MAEd)',
                        'label' => false,
                        'id' => 'degree-manage'
                    ]) ?>
                    <label>Rank</label>
                    <?= $this->Form->control('rank', [
                        'class' => 'form-control',
                        'placeholder' => 'Rank',
                        'label' => false,
                        'id' => 'rank-manage'
                    ]) ?>
                    <label>Position</label>
                    <?= $this->Form->control('position', [
                        'class' => 'form-control',
                        'placeholder' => 'Position',
                        'label' => false,
                        'id' => 'position-manage'
                    ]) ?>
                    <label>Email Address</label>
                    <?= $this->Form->control('email_address', [
                        'class' => 'form-control',
                        'placeholder' => 'Email Address',
                        'label' => false,
                        'type' => 'email',
                        'id' => 'email-address-manage'
                    ]) ?>
                    <label>Level of governance</label>
                    <?= $this->Form->control('level_of_governance', [
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'level-of-governance-manage',
                        'options' => [
                            'SGOD' => 'SGOD',
                            'CID' => 'CID',
                            'OSDS' => 'OSDS',
                        ]
                    ]) ?>
                    <label>Role</label>
                    <?= $this->Form->control('role', [
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'role-manage',
                        'options' => [
                            'User' => 'User',
                            'Administrator' => 'Administrator',
                            'Approver' => 'Approver',
                            'Superuser' => 'Superuser',
                        ]
                    ]) ?>
                    <div id="manage-password-fields">
                        <label>Email Address</label>
                        <?= $this->Form->control('reset_email', [
                            'class' => 'form-control',
                            'placeholder' => 'Email Address',
                            'label' => false,
                            'type' => 'email',
                            'id' => 'reset-email'
                        ]) ?>
                        <small class="text-muted d-block mb-2">
                            Enter the user email to generate a new 8-digit password.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer justify-content-between">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false])?>
                <?= $this->Form->control('original_username',['type'=>'hidden','label'=>false])?>
                <?= $this->Form->control('reset_mode', ['type' => 'hidden', 'label' => false, 'value' => '0']) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            <?= $this->Form->end()?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="users-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2"><strong>Username:</strong> <span id="view-username">—</span></div>
                <div class="mb-2"><strong>Full Name:</strong> <span id="view-fullname">—</span></div>
                <div class="mb-2"><strong>Suffix:</strong> <span id="view-suffix">—</span></div>
                <div class="mb-2"><strong>Degree:</strong> <span id="view-degree">—</span></div>
                <div class="mb-2"><strong>Rank:</strong> <span id="view-rank">—</span></div>
                <div class="mb-2"><strong>Position:</strong> <span id="view-position">—</span></div>
                <div class="mb-2"><strong>Email Address:</strong> <span id="view-email">—</span></div>
                <div class="mb-2"><strong>Level of Governance:</strong> <span id="view-level">—</span></div>
                <div class="mb-2"><strong>Role:</strong> <span id="view-role">—</span></div>
                <div class="mb-2"><strong>Created:</strong> <span id="view-created">—</span></div>
                <div class="mb-2"><strong>Modified:</strong> <span id="view-modified">—</span></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
