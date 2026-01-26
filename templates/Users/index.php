<?php $isEnroll = (string)$this->getRequest()->getQuery('enroll') === '1'; ?>
<style>
<?php if ($isEnroll): ?>
#users-table .edit,
#users-table .delete,
#users-table th:last-child,
#users-table td:last-child {
    display: none !important;
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

<?php $this->Html->script('/js/Users.js', ['block' => 'scriptBottom']); ?>
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
                        'label' => false
                    ]) ?>
                    <label>First name</label>
                    <?= $this->Form->control('first_name', [
                        'class' => 'form-control',
                        'placeholder' => 'First name',
                        'label' => false
                    ]) ?>
                    <label>M.I</label>
                    <?= $this->Form->control('middle_initial', [
                        'class' => 'form-control',
                        'placeholder' => 'M.I',
                        'label' => false
                    ]) ?>
                    <label>Last name</label>
                    <?= $this->Form->control('last_name', [
                        'class' => 'form-control',
                        'placeholder' => 'Last name',
                        'label' => false
                    ]) ?>
                    <label>Email Address</label>
                    <?= $this->Form->control('email_address', [
                        'class' => 'form-control',
                        'placeholder' => 'Email Address',
                        'label' => false,
                        'type' => 'email'
                    ]) ?>
                    <label>Level of governance</label>
                    <?= $this->Form->control('level_of_governance', [
                        'class' => 'form-control',
                        'label' => false,
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
                        'type' => 'password'
                    ]) ?>
                    <label>Retype Password</label>
                    <?= $this->Form->control('retype_password', [
                        'class' => 'form-control',
                        'placeholder' => 'Retype Password',
                        'label' => false,
                        'type' => 'password'
                    ]) ?>
                    <?= $this->Form->control('username', [
                        'type' => 'hidden',
                        'label' => false
                    ]) ?>
                    <?= $this->Form->control('role', [
                        'type' => 'hidden',
                        'label' => false
                    ]) ?>
                <?php else: ?>
                    <label>Username</label>
                    <?= $this->Form->control('username', [
                        'class' => 'form-control',
                        'placeholder' => 'Username',
                        'label' => false
                    ]) ?>
                    <label>First name</label>
                    <?= $this->Form->control('first_name', [
                        'class' => 'form-control',
                        'placeholder' => 'First name',
                        'label' => false
                    ]) ?>
                    <label>M.I</label>
                    <?= $this->Form->control('middle_initial', [
                        'class' => 'form-control',
                        'placeholder' => 'M.I',
                        'label' => false
                    ]) ?>
                    <label>Last name</label>
                    <?= $this->Form->control('last_name', [
                        'class' => 'form-control',
                        'placeholder' => 'Last name',
                        'label' => false
                    ]) ?>
                    <label>Email Address</label>
                    <?= $this->Form->control('email_address', [
                        'class' => 'form-control',
                        'placeholder' => 'Email Address',
                        'label' => false,
                        'type' => 'email'
                    ]) ?>
                    <label>Level of governance</label>
                    <?= $this->Form->control('level_of_governance', [
                        'class' => 'form-control',
                        'label' => false,
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
                        'options' => [
                            'User' => 'User',
                            'Administrator' => 'Administrator',
                            'Approver' => 'Approver',
                            'Superuser' => 'Superuser',
                        ]
                    ]) ?>
                    <div id="manage-password-fields">
                        <label>Old Password</label>
                        <?= $this->Form->control('old_password', [
                            'class' => 'form-control',
                            'placeholder' => 'Old Password',
                            'label' => false,
                            'type' => 'password'
                        ]) ?>
                        <label>New Password</label>
                        <?= $this->Form->control('new_password', [
                            'class' => 'form-control',
                            'placeholder' => 'New Password',
                            'label' => false,
                            'type' => 'password'
                        ]) ?>
                        <?= $this->Form->control('password', [
                            'type' => 'hidden',
                            'label' => false
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer justify-content-between">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false])?>
                <?= $this->Form->control('original_username',['type'=>'hidden','label'=>false])?>
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
