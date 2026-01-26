<div class="register-box">
    <div class="register-logo">
        <a href="">NC CIT</a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register a superuser</p>
            <?= $this->Form->create()?>
            <div class="form-group mb-3">
                <?= $this->Form->control('username',['class'=>'form-control','placeholder'=>'Username']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('password',['class'=>'form-control','placeholder'=>'Password']) ?>
            </div>
            <div class="row">
                <div class="col-8">

                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div>
                <!-- /.col -->
            </div>
            <?= $this->Form->end() ?>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>