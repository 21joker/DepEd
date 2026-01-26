<style>
.proposal-title {
    font-family: "Times New Roman", Times, serif;
    text-align: center;
    line-height: 1.2;
}
.proposal-title .headline {
    font-family: "Old English Text MT", "UnifrakturCook", "Times New Roman", serif;
    font-size: 24px;
    font-weight: 700;
    margin: 6px 0;
}
.proposal-title .seal {
    max-width: 90px;
    margin: 0 auto 8px;
}
.proposal-title .sub {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.proposal-title .main {
    font-size: 18px;
    font-weight: 700;
    text-transform: uppercase;
    margin: 6px 0;
}
.proposal-title .small {
    font-size: 12px;
}
.proposal-table th,
.proposal-table td {
    border: 1px solid #2b2b2b;
    padding: 6px;
    vertical-align: top;
}
.proposal-table th.section {
    background: #e6e6e6;
    font-weight: 700;
}
.proposal-table .label {
    width: 26%;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
}
.proposal-table input.form-control,
.proposal-table textarea.form-control {
    border: none;
    padding: 0;
    background: transparent;
    box-shadow: none;
}
.proposal-table textarea.form-control {
    resize: vertical;
}
.proposal-actions {
    margin-top: 12px;
    text-align: right;
}
.budget-table th,
.budget-table td {
    border: 1px solid #2b2b2b;
    padding: 4px;
    font-size: 12px;
}
.budget-table input.form-control {
    border: none;
    padding: 0;
    background: transparent;
    box-shadow: none;
}
.proposal-footer {
    margin-top: 10px;
    border-top: 1px solid #2b2b2b;
    padding-top: 6px;
}
.proposal-footer img {
    width: 100%;
    height: auto;
    display: block;
}
</style>

<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <?= $this->Flash->render() ?>

            <div class="proposal-title mb-3">
                <div class="seal">
                    <?= $this->Html->image('deped.png', [
                        'alt' => 'Seal',
                        'class' => 'img-fluid'
                    ]) ?>
                </div>
                <div class="headline">Republic of the Philippines</div>
                <div class="headline">Department of Education</div>
                <div class="small">Region II - Cagayan Valley</div>
                <div class="sub">SCHOOLS DIVISION OF SANTIAGO CITY</div>
                <div class="small mt-2">Enclosure 1</div>
                <div class="main">Activity Proposal</div>
                <div class="small">For GAS-MOOE and Centrally Managed and Funded Activities</div>
            </div>

            <?= $this->Form->create($requestEntity, ['type' => 'file']) ?>
            <table class="table proposal-table">
                <thead>
                    <tr>
                        <th class="section" colspan="2">Part I. Activity Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">PMIS Activity Code (AC):</td>
                        <td><?= $this->Form->text('pmis_activity_code', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Title of Activity:</td>
                        <td><?= $this->Form->text('title_of_activity', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Proponent/s:</td>
                        <td><?= $this->Form->text('proponents', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Activity Schedule:</td>
                        <td>
                            <div class="form-row align-items-center">
                                <div class="col-auto pr-2">
                                    <label class="mb-0 font-weight-bold">From:</label>
                                </div>
                                <div class="col">
                                    <?= $this->Form->control('activity_schedule_from', [
                                        'type' => 'date',
                                        'label' => false,
                                        'class' => 'form-control'
                                    ]) ?>
                                </div>
                                <div class="col-auto px-2">
                                    <label class="mb-0 font-weight-bold">To:</label>
                                </div>
                                <div class="col">
                                    <?= $this->Form->control('activity_schedule_to', [
                                        'type' => 'date',
                                        'label' => false,
                                        'class' => 'form-control'
                                    ]) ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Venue/Modality:</td>
                        <td><?= $this->Form->text('venue_modality', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Target Participants:</td>
                        <td><?= $this->Form->text('target_participants', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Activity Description (Justification):</td>
                        <td><?= $this->Form->textarea('activity_description', ['class' => 'form-control', 'rows' => 3]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Activity Objectives:</td>
                        <td><?= $this->Form->textarea('activity_objectives', ['class' => 'form-control', 'rows' => 3]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Expected Output:</td>
                        <td><?= $this->Form->textarea('expected_output', ['class' => 'form-control', 'rows' => 3]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Monitoring & Evaluation:</td>
                        <td><?= $this->Form->text('monitoring_evaluation', ['class' => 'form-control']) ?></td>
                    </tr>
                </tbody>
            </table>

            <table class="table proposal-table">
                <thead>
                    <tr>
                        <th class="section" colspan="2">Part II. Financial Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Budget Requirement:</td>
                        <td><?= $this->Form->text('budget_requirement', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Source of Fund:</td>
                        <td>
                            <div class="d-flex flex-wrap">
                                <?php
                                    $fundOptions = ['OSDS', 'GASS-MOOE', 'CMF', 'PSF'];
                                    foreach ($fundOptions as $option):
                                        $id = 'source-of-fund-' . strtolower(str_replace([' ', '-'], '', $option));
                                ?>
                                    <?php
                                        $selectedFunds = $selectedFunds ?? [];
                                        $isChecked = in_array($option, $selectedFunds, true);
                                    ?>
                                    <div class="custom-control custom-checkbox mr-3 mb-2">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="<?= h($id) ?>"
                                            name="source_of_fund[]"
                                            value="<?= h($option) ?>"
                                            <?= $isChecked ? 'checked' : '' ?>
                                        >
                                        <label class="custom-control-label" for="<?= h($id) ?>"><?= h($option) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Expenditure Matrix:</td>
                        <td>
                            <table class="table budget-table mb-0" id="expenditure-matrix">
                                <thead>
                                    <tr>
                                        <th>Nature of expenditure</th>
                                        <th>No.</th>
                                        <th>Amount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $this->Form->text('expenditure_nature.0', ['class' => 'form-control']) ?></td>
                                        <td><?= $this->Form->text('expenditure_no.0', ['class' => 'form-control']) ?></td>
                                        <td><?= $this->Form->text('expenditure_amount.0', ['class' => 'form-control']) ?></td>
                                        <td><?= $this->Form->text('expenditure_total.0', ['class' => 'form-control']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-right mt-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="add-matrix-row">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Grand Total</td>
                        <td><?= $this->Form->text('grand_total', ['class' => 'form-control']) ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group mt-3">
                <label><strong>Attachment (PDF)</strong></label>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="attachment-sub-aro">SUB-ARO</label>
                        <?= $this->Form->control('attachment_sub_aro', [
                            'type' => 'file',
                            'label' => false,
                            'id' => 'attachment-sub-aro',
                            'class' => 'form-control',
                            'accept' => '.pdf,application/pdf'
                        ]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attachment-sfwp">SFWP</label>
                        <?= $this->Form->control('attachment_sfwp', [
                            'type' => 'file',
                            'label' => false,
                            'id' => 'attachment-sfwp',
                            'class' => 'form-control',
                            'accept' => '.pdf,application/pdf'
                        ]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attachment-ar">AR</label>
                        <?= $this->Form->control('attachment_ar', [
                            'type' => 'file',
                            'label' => false,
                            'id' => 'attachment-ar',
                            'class' => 'form-control',
                            'accept' => '.pdf,application/pdf'
                        ]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attachment-ac">AC</label>
                        <?= $this->Form->control('attachment_ac', [
                            'type' => 'file',
                            'label' => false,
                            'id' => 'attachment-ac',
                            'class' => 'form-control',
                            'accept' => '.pdf,application/pdf'
                        ]) ?>
                    </div>
                </div>
                <small class="text-muted">PDF files only.</small>
            </div>

            <div class="proposal-actions d-flex align-items-center justify-content-end">
                <div class="text-muted mr-3">
                    <strong>Requested By:</strong> <?= h($requestEntity->name ?? '') ?>
                </div>
                <?= $this->Form->button('Submit Request', ['class' => 'btn btn-primary']) ?>
            </div>
            <?= $this->Form->end() ?>

            <div class="proposal-footer">
                <?= $this->Html->image('footer.jpg', [
                    'alt' => 'Footer',
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Request Status</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($lastRequest) && !empty($admins)): ?>
                <div class="mb-3">
                    <div class="text-uppercase text-muted small mb-2">Form Details</div>
                    <div><strong>Title:</strong> <?= h($lastRequest->title) ?></div>
                    <div><strong>Name:</strong> <?= h($lastRequest->name ?: 'N/A') ?></div>
                    <div><strong>Submitted:</strong>
                        <?php if ($lastRequest->created_at): ?>
                            <?= h($lastRequest->created_at->i18nFormat('MM/dd/yyyy h:mm a')) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                    <?php if ((int)($lastRequest->approvals_count ?? 0) === 0): ?>
                        <div class="mt-2">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'edit', $lastRequest->id]) ?>">
                                Edit Request
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-uppercase text-muted small mb-2">Status</div>
                <?php foreach ($admins as $admin): ?>
                    <?php
                        $status = $approvalStatuses[$admin->id] ?? null;
                        if ($status === 'approved') {
                            $badgeClass = 'success';
                        } elseif ($status === 'declined') {
                            $badgeClass = 'danger';
                        } else {
                            $badgeClass = 'warning';
                        }
                    ?>
                    <span class="badge badge-<?= $badgeClass ?> p-2 mr-1 mb-1">
                        <?= h($admin->username) ?>
                    </span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted mb-0">Submit a request to see approval status.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var addBtn = document.getElementById('add-matrix-row');
    var table = document.getElementById('expenditure-matrix');
    if (!addBtn || !table) {
        return;
    }

    addBtn.addEventListener('click', function () {
        var tbody = table.querySelector('tbody');
        if (!tbody) {
            return;
        }
        var rows = tbody.querySelectorAll('tr');
        var index = rows.length;
        var lastRow = rows[rows.length - 1];
        if (!lastRow) {
            return;
        }
        var newRow = lastRow.cloneNode(true);
        newRow.querySelectorAll('input').forEach(function (input) {
            var name = input.getAttribute('name') || '';
            if (name) {
                input.setAttribute('name', name.replace(/\.\d+$/, '.' + index));
            }
            input.value = '';
        });
        tbody.appendChild(newRow);
    });
});
</script>
