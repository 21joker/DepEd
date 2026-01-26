<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Students table</h3>
            <div class="card-tools">
                <button id="add" type="button" class="btn btn-tool">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="students-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Lastname</th>
                    <th>Firstname</th>
                    <th>Middlename</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="students-modal">
    <div class="modal-dialog  modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create($student,['id'=>'students-form'])?>
                <div class="row">
                    <div class="col-md-6">
                        <label>Lastname</label>
                        <?= $this->Form->control('student.lastname',['class'=>'form-control','placeholder'=>'Lastname',
                            'label'=>false]) ?>
                        <label>Firstname</label>
                        <?= $this->Form->control('student.firstname',['class'=>'form-control','placeholder'=>'Firstname',
                            'label'=>false]) ?>
                        <label>Middlename</label>
                        <?= $this->Form->control('student.middlename',['class'=>'form-control','placeholder'=>'Middlename',
                            'label'=>false]) ?>
                        <label>Email</label>
                        <?= $this->Form->control('student.email',['class'=>'form-control','placeholder'=>'Email',
                            'label'=>false]) ?>
                    </div>
                    <div class="col-md-6">
                        <label>Username</label>
                        <?= $this->Form->control('username',['class'=>'form-control','placeholder'=>'Username',
                            'label'=>false]) ?>
                        <label>Password</label>
                        <?= $this->Form->control('password',['class'=>'form-control','placeholder'=>'Password',
                            'label'=>false]) ?>
                        <label>Role</label>
                        <?= $this->Form->control('role',['class'=>'form-control','placeholder'=>'Username',
                            'label'=>false,'options'=>$this->Options->role()]) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false])?>
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

<div class="modal fade" id="message-modal">
    <div class="modal-dialog  modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Send Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null,['id'=>'message-form'])?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('subject',['class'=>'form-control','style'=>'resize:none;'])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('message',['type'=>'textarea','class'=>'form-control','style'=>'resize:none;'])?>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <?= $this->Form->control('student_id',['type'=>'hidden','label'=>false])?>
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