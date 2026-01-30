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
.proposal-form-container {
    display: none;
}
.proposal-form-container.is-visible {
    display: block;
}
.proposal-list-table th,
.proposal-list-table td {
    white-space: nowrap;
    font-size: 13px;
}
.proposal-list-table td {
    vertical-align: middle;
}
.proposal-list-table .actions {
    white-space: nowrap;
}
.proposal-list-table .action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    letter-spacing: 0.2px;
}
.proposal-list-table .action-btn i {
    font-size: 12px;
}
.proposal-list-table .status-badge {
    font-size: 12px;
    letter-spacing: 0.3px;
    text-transform: uppercase;
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
    margin-top: 14px;
    border-top: 1px solid #2b2b2b;
    padding-top: 10px;
    display: flex;
    justify-content: center;
}
.proposal-footer img {
    width: 100%;
    max-width: 520px;
    height: auto;
    display: block;
}
</style>

<div class="col-12">
    <div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h3 class="card-title mb-0">My Proposal Forms</h3>
                <input
                    type="text"
                    class="form-control form-control-sm ml-3"
                    id="proposal-search"
                    placeholder="Search..."
                    style="max-width: 240px;"
                >
                <button type="button" class="btn btn-primary btn-sm ml-auto" id="toggle-proposal-form">
                    <i class="fas fa-plus"></i>
                    Create Proposal
                </button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <?php if (!empty($userRequests)): ?>
                <table class="table table-bordered table-striped proposal-list-table" id="proposal-forms-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Logs</th>
                            <th>AC</th>
                            <th>Title of Activity</th>
                            <th>Activity Schedule</th>
                            <th>Budget Requirement</th>
                            <th>Source of Fund</th>
                            <th>Grand Total</th>
                            <th>SUB-ARO</th>
                            <th>S/WFP</th>
                            <th>AR</th>
                            <th>AC</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rowIndex = 1; ?>
                        <?php foreach ($userRequests as $request): ?>
                            <?php
                                $summary = $requestSummaries[$request->id] ?? [];
                                $ac = trim((string)($summary['pmis_activity_code'] ?? ''));
                                $title = trim((string)($summary['title_of_activity'] ?? ''));
                                $schedule = trim((string)($summary['activity_schedule'] ?? ''));
                                $budget = trim((string)($summary['budget_requirement'] ?? ''));
                                $source = trim((string)($summary['source_of_fund'] ?? ''));
                                $grand = trim((string)($summary['grand_total'] ?? ''));
                                $subAro = trim((string)($summary['attachment_sub_aro'] ?? ''));
                                $sfwp = trim((string)($summary['attachment_sfwp'] ?? ''));
                                $ar = trim((string)($summary['attachment_ar'] ?? ''));
                                $acAttach = trim((string)($summary['attachment_ac'] ?? ''));
                                $requestStatusById = $requestStatusById ?? [];
                                $rowStatus = $requestStatusById[(int)$request->id] ?? ($request->status ?? 'pending');
                                $isDeclined = $rowStatus === 'declined';
                                $isApprovedRow = $rowStatus === 'approved';
                                $isLocked = $isApprovedRow
                                    || ((int)($request->approvals_count ?? 0) > 0 && !$isDeclined);
                                $logTime = $request->updated_at ?? $request->created_at ?? null;
                                $logLabel = 'N/A';
                                if ($logTime instanceof \Cake\I18n\FrozenTime) {
                                    $logLabel = $logTime->i18nFormat('MM/dd/yyyy h:mm a');
                                } elseif ($logTime instanceof \DateTimeInterface) {
                                    $logLabel = $logTime->format('m/d/Y h:i a');
                                } elseif (!empty($logTime)) {
                                    $logLabel = trim((string)$logTime);
                                }
                                $isApproved = (int)($request->approvals_needed ?? 0) > 0
                                    && (int)($request->approvals_count ?? 0) >= (int)($request->approvals_needed ?? 0);
                                if ($isApprovedRow) {
                                    $statusLabel = 'Fully Approved';
                                    $statusClass = 'success';
                                } elseif ($isDeclined) {
                                    $statusLabel = 'Review';
                                    $statusClass = 'danger';
                                } else {
                                    $statusLabel = 'Pending';
                                    $statusClass = 'secondary';
                                }
                                $requestId = (int)$request->id;
                                $buildFileLink = function (string $filename) use ($requestId) {
                                    $safeName = basename($filename);
                                    if ($safeName === '') {
                                        return null;
                                    }
                                    return [
                                        'name' => $safeName,
                                        'url' => $this->Url->build('/uploads/requests/' . $requestId . '/' . $safeName),
                                    ];
                                };
                            ?>
                            <tr>
                                <td><?= $rowIndex ?></td>
                                <td><?= (int)$request->id ?></td>
                                <td><?= h($logLabel) ?></td>
                                <td><?= $ac !== '' ? h($ac) : 'N/A' ?></td>
                                <td><?= $title !== '' ? h($title) : 'N/A' ?></td>
                                <td><?= $schedule !== '' ? h($schedule) : 'N/A' ?></td>
                                <td><?= $budget !== '' ? h($budget) : 'N/A' ?></td>
                                <td><?= $source !== '' ? h($source) : 'N/A' ?></td>
                                <td><?= $grand !== '' ? h($grand) : 'N/A' ?></td>
                                <td>
                                    <?php if ($subAro !== '' && ($link = $buildFileLink($subAro))): ?>
                                        <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener">
                                            <?= h($link['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= $subAro !== '' ? h($subAro) : 'N/A' ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($sfwp !== '' && ($link = $buildFileLink($sfwp))): ?>
                                        <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener">
                                            <?= h($link['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= $sfwp !== '' ? h($sfwp) : 'N/A' ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($ar !== '' && ($link = $buildFileLink($ar))): ?>
                                        <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener">
                                            <?= h($link['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= $ar !== '' ? h($ar) : 'N/A' ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($acAttach !== '' && ($link = $buildFileLink($acAttach))): ?>
                                        <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener">
                                            <?= h($link['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= $acAttach !== '' ? h($acAttach) : 'N/A' ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= h($statusClass) ?> status-badge">
                                        <?= h($statusLabel) ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a
                                        class="btn btn-sm btn-primary action-btn modal-link"
                                        href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'view', $request->id, '?' => ['modal' => 1]]) ?>"
                                        data-title="View Request"
                                    >
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <?php if ($isLocked): ?>
                                        <span class="btn btn-sm btn-secondary action-btn disabled">
                                            <i class="fas fa-lock"></i>
                                            Edit
                                        </span>
                                    <?php else: ?>
                                        <a
                                            class="btn btn-sm btn-outline-secondary action-btn"
                                            href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'edit', $request->id]) ?>"
                                        >
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($isApproved) || in_array($request->status ?? null, ['approved', 'Approved'], true)): ?>
                                        <a
                                            class="btn btn-sm btn-danger action-btn"
                                            href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'exportPdf', $request->id]) ?>"
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            <i class="fas fa-file-pdf"></i>
                                            Export PDF
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $rowIndex++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted mb-0">No proposal forms submitted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="col-12 proposal-form-container <?= !empty($showForm) ? 'is-visible' : '' ?>" id="proposal-form-container">
    <div class="card">
        <div class="card-body">
            <?= $this->Flash->render() ?>

            <?php
                $venueOptions = [
                    '' => 'Select...',
                    'ONLINE' => 'ONLINE',
                    'FaceToFace' => 'FaceToFace',
                    'Hybrid' => 'Hybrid',
                ];
                $venueValue = trim((string)($requestEntity->venue_modality ?? ''));
                $venueChoice = '';
                $venueDetails = '';
                if ($venueValue !== '') {
                    foreach (['ONLINE', 'FaceToFace', 'Hybrid'] as $option) {
                        if (stripos($venueValue, $option) === 0) {
                            $venueChoice = $option;
                            $venueDetails = trim((string)preg_replace('/^' . preg_quote($option, '/') . '\\s*[-:]?\\s*/i', '', $venueValue));
                            break;
                        }
                    }
                    if ($venueChoice === '') {
                        $venueDetails = $venueValue;
                    }
                }
            ?>

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
                        <th class="section" colspan="3">Part I. Activity Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">PMIS Activity Code (AC):</td>
                        <td><?= $this->Form->text('pmis_activity_code', [
                            'class' => 'form-control',
                            'value' => !empty($requestEntity->pmis_activity_code) ? $requestEntity->pmis_activity_code : 'AC-',
                        ]) ?></td>
                        <td>
                            <?php
                                $officeOptions = [
                                    'CID' => 'CID',
                                    'OSDS' => 'OSDS',
                                    'SGOD' => 'SGOD',
                                ];
                            ?>
                            <?= $this->Form->select('pmis_activity_office', $officeOptions, [
                                'class' => 'form-control',
                            ]) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Title of Activity:</td>
                        <td colspan="2"><?= $this->Form->text('title_of_activity', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Proponent/s:</td>
                        <td colspan="2"><?= $this->Form->text('proponents', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Activity Schedule:</td>
                        <td colspan="2">
                            <div class="form-row align-items-center">
                                <div class="col-auto pr-2">
                                    <label class="mb-0 font-weight-bold">From:</label>
                                </div>
                                <div class="col">
                                    <?= $this->Form->control('activity_schedule_from', [
                                        'type' => 'date',
                                        'label' => false,
                                        'class' => 'form-control',
                                        'id' => 'activity-schedule-from'
                                    ]) ?>
                                </div>
                                <div class="col-auto px-2">
                                    <label class="mb-0 font-weight-bold">To:</label>
                                </div>
                                <div class="col">
                                    <?= $this->Form->control('activity_schedule_to', [
                                        'type' => 'date',
                                        'label' => false,
                                        'class' => 'form-control',
                                        'id' => 'activity-schedule-to'
                                    ]) ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Venue/Modality:</td>
                        <td colspan="2">
                            <div class="form-row align-items-center">
                                <div class="col">
                                    <?= $this->Form->text('venue_modality_details', [
                                        'class' => 'form-control',
                                        'label' => false,
                                        'value' => $venueDetails,
                                        'placeholder' => 'venue',
                                    ]) ?>
                                </div>
                                <div class="col-4">
                                    <?= $this->Form->select('venue_modality_choice', $venueOptions, [
                                        'class' => 'form-control',
                                        'label' => false,
                                        'value' => $venueChoice,
                                    ]) ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Target Participants:</td>
                        <td colspan="2"><?= $this->Form->text('target_participants', ['class' => 'form-control']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Activity Description (Justification):</td>
                        <td colspan="2"><?= $this->Form->textarea('activity_description', ['class' => 'form-control', 'rows' => 3]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Activity Objectives:</td>
                        <td colspan="2"><?= $this->Form->textarea('activity_objectives', ['class' => 'form-control', 'rows' => 3]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Expected Output:</td>
                        <td colspan="2"><?= $this->Form->textarea('expected_output', ['class' => 'form-control', 'rows' => 3]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Monitoring & Evaluation:</td>
                        <td colspan="2"><?= $this->Form->text('monitoring_evaluation', [
                            'class' => 'form-control',
                            'value' => !empty($requestEntity->monitoring_evaluation)
                                ? $requestEntity->monitoring_evaluation
                                : 'SHIRLYN R. MACASPAC PhD / ARCADIO L. MODUMO',
                        ]) ?></td>
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
                        <td><?= $this->Form->text('budget_requirement', ['class' => 'form-control peso-format']) ?></td>
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
                                        <td><?= $this->Form->text('expenditure_no.0', ['class' => 'form-control matrix-no']) ?></td>
                                        <td><?= $this->Form->text('expenditure_amount.0', ['class' => 'form-control peso-format matrix-amount']) ?></td>
                                        <td><?= $this->Form->text('expenditure_total.0', ['class' => 'form-control peso-format matrix-total']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-right mt-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="remove-matrix-row">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="add-matrix-row">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Grand Total</td>
                        <td><?= $this->Form->text('grand_total', ['class' => 'form-control peso-format']) ?></td>
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
                        <label for="attachment-sfwp">S/WFP</label>
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
                <?php if (!empty($isEdit) && !empty($requestEntity->updated_at)): ?>
                    <div class="text-muted mr-3 small">
                        <strong>Last Updated:</strong>
                        <?= h($requestEntity->updated_at->i18nFormat('MM/dd/yyyy h:mm a')) ?>
                    </div>
                <?php endif; ?>
                <?= $this->Form->button(!empty($isEdit) ? 'Update Request' : 'Submit Request', ['class' => 'btn btn-primary']) ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var scheduleFrom = document.getElementById('activity-schedule-from');
    var scheduleTo = document.getElementById('activity-schedule-to');
    if (scheduleFrom && scheduleTo) {
        var syncScheduleRange = function () {
            if (scheduleFrom.value) {
                scheduleTo.min = scheduleFrom.value;
                if (scheduleTo.value && scheduleTo.value < scheduleFrom.value) {
                    scheduleTo.value = scheduleFrom.value;
                }
            } else {
                scheduleTo.min = '';
            }
        };
        scheduleFrom.addEventListener('change', syncScheduleRange);
        scheduleTo.addEventListener('change', syncScheduleRange);
        syncScheduleRange();
    }

    var toggleButton = document.getElementById('toggle-proposal-form');
    var formContainer = document.getElementById('proposal-form-container');
    if (toggleButton && formContainer) {
        var updateLabel = function () {
            if (formContainer.classList.contains('is-visible')) {
                toggleButton.innerHTML = '<i class="fas fa-minus"></i> Hide Form';
            } else {
                toggleButton.innerHTML = '<i class="fas fa-plus"></i> Create Proposal';
            }
        };
        updateLabel();
        toggleButton.addEventListener('click', function () {
            formContainer.classList.toggle('is-visible');
            updateLabel();
            if (formContainer.classList.contains('is-visible')) {
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    var addBtn = document.getElementById('add-matrix-row');
    var removeBtn = document.getElementById('remove-matrix-row');
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
                if (/\[\d+\]$/.test(name)) {
                    input.setAttribute('name', name.replace(/\[\d+\]$/, '[' + index + ']'));
                } else {
                    input.setAttribute('name', name.replace(/\.\d+$/, '.' + index));
                }
            }
            input.value = '';
        });
        tbody.appendChild(newRow);
    });

    if (removeBtn) {
        removeBtn.addEventListener('click', function () {
            var tbody = table.querySelector('tbody');
            if (!tbody) {
                return;
            }
            var rows = tbody.querySelectorAll('tr');
            if (rows.length <= 1) {
                rows.forEach(function (row) {
                    row.querySelectorAll('input').forEach(function (input) {
                        input.value = '';
                    });
                });
                return;
            }
            tbody.removeChild(rows[rows.length - 1]);
        });
    }

    var searchInput = document.getElementById('proposal-search');
    var proposalTable = document.getElementById('proposal-forms-table');
    if (searchInput && proposalTable) {
        searchInput.addEventListener('input', function () {
            var query = searchInput.value.toLowerCase();
            proposalTable.querySelectorAll('tbody tr').forEach(function (row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(query) !== -1 ? '' : 'none';
            });
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var pesoInputs = document.querySelectorAll('.peso-format');
    if (!pesoInputs.length) {
        return;
    }

    function normalizeNumber(value) {
        return (value || '').toString().replace(/[^\d.-]/g, '');
    }

    function formatPeso(value) {
        var normalized = normalizeNumber(value);
        if (normalized === '' || normalized === '-' || normalized === '.') {
            return value;
        }
        var number = Number(normalized);
        if (Number.isNaN(number)) {
            return value;
        }
        if (typeof Intl !== 'undefined' && Intl.NumberFormat) {
            return new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }
        return 'PHP ' + number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function updateMatrixRowTotal(row) {
        if (!row) {
            return;
        }
        var countInput = row.querySelector('.matrix-no');
        var amountInput = row.querySelector('.matrix-amount');
        var totalInput = row.querySelector('.matrix-total');
        if (!countInput || !amountInput || !totalInput) {
            return;
        }
        var countValue = Number(normalizeNumber(countInput.value || ''));
        var amountValue = Number(normalizeNumber(amountInput.value || ''));
        if (Number.isNaN(countValue) || Number.isNaN(amountValue)) {
            totalInput.value = '';
            return;
        }
        var total = countValue * amountValue;
        if (!Number.isFinite(total)) {
            totalInput.value = '';
            return;
        }
        totalInput.value = formatPeso(total.toFixed(2));
    }

    function updateGrandTotal() {
        var total = 0;
        document.querySelectorAll('.matrix-total').forEach(function (input) {
            var value = Number(normalizeNumber(input.value || ''));
            if (!Number.isNaN(value)) {
                total += value;
            }
        });
        var grandInput = document.querySelector('input[name="grand_total"]');
        var budgetInput = document.querySelector('input[name="budget_requirement"]');
        if (grandInput) {
            grandInput.value = total ? formatPeso(total.toFixed(2)) : '';
        }
        if (budgetInput) {
            budgetInput.value = total ? formatPeso(total.toFixed(2)) : '';
        }
    }

    function handleFocus(event) {
        var input = event.target;
        input.value = normalizeNumber(input.value);
    }

    function handleBlur(event) {
        var input = event.target;
        if (input.value.trim() === '') {
            return;
        }
        input.value = formatPeso(input.value);
    }

    pesoInputs.forEach(function (input) {
        if (input.value.trim() !== '') {
            input.value = formatPeso(input.value);
        }
        input.addEventListener('focus', handleFocus);
        input.addEventListener('blur', handleBlur);
    });

    document.addEventListener('focusin', function (event) {
        if (event.target && event.target.classList && event.target.classList.contains('peso-format')) {
            handleFocus(event);
        }
    });
    document.addEventListener('focusout', function (event) {
        if (event.target && event.target.classList && event.target.classList.contains('peso-format')) {
            handleBlur(event);
        }
    });

    document.addEventListener('input', function (event) {
        if (!event.target) {
            return;
        }
        if (event.target.classList.contains('matrix-no') || event.target.classList.contains('matrix-amount')) {
            updateMatrixRowTotal(event.target.closest('tr'));
            updateGrandTotal();
        } else if (event.target.classList.contains('matrix-total')) {
            updateGrandTotal();
        }
    });

    document.querySelectorAll('#expenditure-matrix tbody tr').forEach(function (row) {
        updateMatrixRowTotal(row);
    });
    updateGrandTotal();
});
</script>
