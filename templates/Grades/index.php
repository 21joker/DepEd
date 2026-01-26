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

            <!-- Chart -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Line Chart</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <canvas id="lineChart" style="height:250px"></canvas>
                </div>
            </div>

            <!-- Table -->
            <table id="grades-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>English</th>
                    <th>Science</th>
                    <th>Math</th>
                    <th>Filipino</th>
                    <th>Mapeh</th>
                    <th>Average</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="grades-modal">
    <div class="modal-dialog  modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create($grade,['id'=>'grades-form'])?>
                <div class="row">
                    <div class="col-md-6">
                        <label>Student</label>
                        <?= $this->Form->control('student_id',['class'=>'form-control','options'=>$students,
                            'label'=>false]) ?>
                        <label>English</label>
                        <?= $this->Form->control('english',['class'=>'form-control','placeholder'=>'English',
                            'label'=>false]) ?>
                        <label>Science</label>
                        <?= $this->Form->control('science',['class'=>'form-control','placeholder'=>'Science',
                            'label'=>false]) ?>
                        <label>Math</label>
                        <?= $this->Form->control('math',['class'=>'form-control','placeholder'=>'Math',
                            'label'=>false]) ?>
                        <label>Filipino</label>
                        <?= $this->Form->control('filipino',['class'=>'form-control','placeholder'=>'Filipino',
                            'label'=>false]) ?>
                        <label>Mapeh</label>
                        <?= $this->Form->control('mapeh',['class'=>'form-control','placeholder'=>'Mapeh',
                            'label'=>false]) ?>
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
    </div>
</div>