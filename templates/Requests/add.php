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
    font-size: 14px;
    line-height: 1.4;
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
.schedule-calendar-title {
    font-weight: 700;
    text-align: center;
    margin-bottom: 6px;
}
.activity-schedule-calendar {
    border: 1px solid #cfcfcf;
    background: #fff;
}
.activity-schedule-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #d9d9d9;
    padding: 6px 8px;
    background: #f8f8f8;
}
.activity-schedule-month {
    font-weight: 700;
    font-size: 14px;
}
.activity-schedule-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
}
.activity-schedule-weekday {
    font-weight: 700;
    text-align: center;
    padding: 4px 0;
    border-right: 1px solid #d9d9d9;
    border-bottom: 1px solid #d9d9d9;
    background: #f3f3f3;
}
.activity-schedule-weekday:nth-child(7n) {
    border-right: none;
}
.activity-schedule-day {
    min-height: 108px;
    border-right: 1px solid #d9d9d9;
    border-bottom: 1px solid #d9d9d9;
    padding: 4px;
    position: relative;
    background: #fff;
}
.activity-schedule-day:nth-child(7n) {
    border-right: none;
}
.activity-schedule-day.is-outside {
    background: #f7f7f7;
}
.activity-schedule-day-number {
    position: absolute;
    top: 4px;
    right: 6px;
    font-size: 12px;
    color: #666;
}
.activity-schedule-slot-list {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.activity-schedule-slot {
    border: 1px solid #17a2d8;
    background: #17a2d8;
    color: #fff;
    border-radius: 4px;
    text-align: center;
    line-height: 1.1;
    padding: 5px 4px;
    width: 100%;
}
.activity-schedule-slot small {
    display: block;
    font-size: 11px;
}
.activity-schedule-slot.is-selected {
    background: #117fa8;
    border-color: #117fa8;
}
.activity-schedule-slot:disabled {
    background: #dc3545;
    border-color: #dc3545;
    cursor: not-allowed;
}
.activity-schedule-selected {
    border: 1px solid #d9d9d9;
    background: #fafafa;
    padding: 8px;
    font-size: 12px;
    min-height: 38px;
}
@media (max-width: 767.98px) {
    .activity-schedule-day {
        min-height: 90px;
    }
}
.target-participants-grid {
    border: 1px solid #2b2b2b;
}
.target-participants-grid .tp-cell {
    border-left: 1px solid #2b2b2b;
}
.target-participants-grid .tp-cell:first-child {
    border-left: none;
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
            <?php
                $userCounts = $userCounts ?? ['total' => 0, 'approved' => 0, 'pending' => 0];
            ?>
            <div class="row mb-3">
                <div class="col-md-4 col-sm-12 mb-2">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= (int)$userCounts['total'] ?></h3>
                            <p>Total Requests</p>
                        </div>
                        <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= (int)$userCounts['approved'] ?></h3>
                            <p>Total Approved Requests</p>
                        </div>
                        <div class="icon"><i class="fas fa-check"></i></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= (int)$userCounts['pending'] ?></h3>
                            <p>Total Pending Requests</p>
                        </div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                </div>
            </div>
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
                            <th>WFP</th>
                            <th>AR</th>
                            <th>ATC</th>
                            <th>List of Participants</th>
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
                                $wfpCode = trim((string)($summary['wfp_code'] ?? ''));
                                $ar = trim((string)($summary['attachment_ar'] ?? ''));
                                $acAttach = trim((string)($summary['attachment_ac'] ?? ''));
                                $participantsList = trim((string)($summary['attachment_list_participants'] ?? ''));
                                $requestStatusById = $requestStatusById ?? [];
                                $declinedRequestIds = $declinedRequestIds ?? [];
                                $reviewedByRequest = $reviewedByRequest ?? [];
                                $reviewedLatestByRequest = $reviewedLatestByRequest ?? [];
                                $declinedLookup = !empty($declinedRequestIds)
                                    ? array_fill_keys($declinedRequestIds, true)
                                    : [];
                                $approvalLatestByRequest = $approvalLatestByRequest ?? [];
                                $rowStatus = $requestStatusById[(int)$request->id] ?? ($request->status ?? 'pending');
                                if (isset($declinedLookup[(int)$request->id]) || !empty($reviewedByRequest[(int)$request->id])) {
                                    $rowStatus = 'declined';
                                }
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
                                $userUpdatedAt = $request->user_updated_at ?? null;
                                $createdAt = $request->created_at ?? $request->created ?? null;
                                $userUpdatedTs = null;
                                if ($userUpdatedAt instanceof \DateTimeInterface) {
                                    $userUpdatedTs = $userUpdatedAt->getTimestamp();
                                } elseif (!empty($userUpdatedAt)) {
                                    $ts = strtotime((string)$userUpdatedAt);
                                    $userUpdatedTs = $ts === false ? null : $ts;
                                }
                                $createdTs = null;
                                if ($createdAt instanceof \DateTimeInterface) {
                                    $createdTs = $createdAt->getTimestamp();
                                } elseif (!empty($createdAt)) {
                                    $ts = strtotime((string)$createdAt);
                                    $createdTs = $ts === false ? null : $ts;
                                }
                                $reviewedLatest = $reviewedLatestByRequest[(int)$request->id] ?? null;
                                $reviewedTs = !empty($reviewedLatest['ts']) ? (int)$reviewedLatest['ts'] : null;
                                $isUpdated = false;
                                if ($userUpdatedTs !== null) {
                                    if ($reviewedTs !== null) {
                                        $isUpdated = $userUpdatedTs > ($reviewedTs + 5);
                                    } elseif ($createdTs !== null) {
                                        $isUpdated = $userUpdatedTs > ($createdTs + 5);
                                    } else {
                                        $isUpdated = true;
                                    }
                                }
                                if ($isApprovedRow) {
                                    $statusLabel = 'Fully Approved';
                                    $statusClass = 'success';
                                } elseif ($isUpdated) {
                                    $statusLabel = 'Updated';
                                    $statusClass = 'warning';
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
                                <td><?= $wfpCode !== '' ? h($wfpCode) : 'N/A' ?></td>
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
                                      <?php if ($participantsList !== '' && ($link = $buildFileLink($participantsList))): ?>
                                          <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener">
                                              <?= h($link['name']) ?>
                                          </a>
                                      <?php else: ?>
                                          <?= $participantsList !== '' ? h($participantsList) : 'N/A' ?>
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
                    '' => 'Modality',
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

                $targetRaw = trim((string)($requestEntity->target_participants ?? ''));
                $targetParticipant = '';
                $targetTotal = '';
                $targetMale = '';
                $targetFemale = '';
                if ($targetRaw !== '') {
                    if (preg_match('/Participant\\s*:\\s*([^|]+)/i', $targetRaw, $match)) {
                        $targetParticipant = trim((string)($match[1] ?? ''));
                    }
                    if (preg_match('/Total\\s*:\\s*([^|]+)/i', $targetRaw, $match)) {
                        $targetTotal = trim((string)($match[1] ?? ''));
                    }
                    if (preg_match('/Male\\s*:\\s*([^|]+)/i', $targetRaw, $match)) {
                        $targetMale = trim((string)($match[1] ?? ''));
                    }
                    if (preg_match('/Female\\s*:\\s*([^|]+)/i', $targetRaw, $match)) {
                        $targetFemale = trim((string)($match[1] ?? ''));
                    }
                    if ($targetParticipant === '' && $targetTotal === '' && $targetMale === '' && $targetFemale === '') {
                        $targetParticipant = $targetRaw;
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

            <?= $this->Form->create($requestEntity, ['type' => 'file', 'id' => 'proposal-form']) ?>
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
                        <td colspan="2"><?= $this->Form->text('proponents', [
                            'class' => 'form-control',
                            'value' => $requestEntity->proponents ?? ($authDisplayName ?? ($auth['username'] ?? '')),
                            'readonly' => true,
                        ]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Activity Schedule:
                          </td>
                          <td colspan="2">
                              <?php
                                  $scheduleRows = $requestEntity->get('activity_schedule_rows');
                                  if (!is_array($scheduleRows) || empty($scheduleRows)) {
                                      $scheduleRows = [[
                                          'date' => (string)($requestEntity->get('activity_schedule_dates') ?? ''),
                                          'time_from' => (string)($requestEntity->get('activity_schedule_time_from') ?? ''),
                                          'time_to' => (string)($requestEntity->get('activity_schedule_time_to') ?? ''),
                                      ]];
                                  }
                                  $scheduleRowsForJs = array_values(array_map(function ($item) {
                                      return [
                                          'date' => (string)($item['date'] ?? ''),
                                          'time_from' => (string)($item['time_from'] ?? ''),
                                          'time_to' => (string)($item['time_to'] ?? ''),
                                      ];
                                  }, $scheduleRows));
                              ?>
                              <div class="form-row align-items-end">
                                  <div class="col-md-4">
                                      <label for="manual-schedule-date" class="mb-1">Date</label>
                                      <input type="date" id="manual-schedule-date" class="form-control">
                                  </div>
                                  <div class="col-md-3">
                                      <label for="manual-schedule-from" class="mb-1">Time From</label>
                                      <input type="time" id="manual-schedule-from" class="form-control">
                                  </div>
                                  <div class="col-md-3">
                                      <label for="manual-schedule-to" class="mb-1">Time To</label>
                                      <input type="time" id="manual-schedule-to" class="form-control">
                                  </div>
                                  <div class="col-md-2">
                                      <button type="button" class="btn btn-outline-primary btn-sm w-100" id="manual-schedule-add">
                                          Add
                                      </button>
                                  </div>
                              </div>
                              <div class="manual-schedule-list mt-2" id="manual-schedule-list"></div>
                              <div class="activity-schedule-selected mt-2" id="activity-schedule-selected"></div>
                              <div id="schedule-rows" class="d-none"></div>
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
                                    <div class="col-3">
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary btn-sm w-100"
                                            data-toggle="modal"
                                            data-target="#mphScheduleModal"
                                        >
                                            VIEW MPH SCHEDULE
                                        </button>
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
                        <td class="label">Target Participant:</td>
                          <td colspan="2">
                              <div class="form-row text-center target-participants-grid">
                                  <div class="col-md-3 font-weight-bold tp-cell">Participant:</div>
                                  <div class="col-md-3 font-weight-bold tp-cell">Male:</div>
                                  <div class="col-md-3 font-weight-bold tp-cell">Female:</div>
                                  <div class="col-md-3 font-weight-bold tp-cell">Total:</div>
                              </div>
                              <div class="form-row target-participants-grid">
                                  <div class="col-md-3 tp-cell">
                                      <?= $this->Form->text('target_participants_label', [
                                          'class' => 'form-control',
                                          'value' => $targetParticipant,
                                      ]) ?>
                                  </div>
                                  <div class="col-md-3 tp-cell">
                                      <?= $this->Form->text('target_participants_male', [
                                          'class' => 'form-control numeric-only',
                                          'inputmode' => 'numeric',
                                          'pattern' => '[0-9]*',
                                          'value' => $targetMale,
                                      ]) ?>
                                  </div>
                                  <div class="col-md-3 tp-cell">
                                      <?= $this->Form->text('target_participants_female', [
                                          'class' => 'form-control numeric-only',
                                          'inputmode' => 'numeric',
                                          'pattern' => '[0-9]*',
                                          'value' => $targetFemale,
                                      ]) ?>
                                  </div>
                                  <div class="col-md-3 tp-cell">
                                      <?= $this->Form->text('target_participants_total', [
                                          'class' => 'form-control',
                                          'value' => $targetTotal,
                                          'readonly' => true,
                                    ]) ?>
                                </div>
                            </div>
                        </td>
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
                                : 'SHIRLYN R. MACASPAC PhD / ARCADIO L. MODUMO Jr.',
                            'readonly' => true,
                        ]) ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="modal fade" id="mphScheduleModal" tabindex="-1" role="dialog" aria-labelledby="mphScheduleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mphScheduleModalLabel">VIEW MPH SCHEDULE</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="activity-schedule-calendar">
                                <div class="activity-schedule-toolbar">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="activity-schedule-prev-month">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <div class="activity-schedule-month" id="activity-schedule-month"></div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="activity-schedule-next-month">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="activity-schedule-grid" id="activity-schedule-weekdays">
                                    <div class="activity-schedule-weekday">Sun</div>
                                    <div class="activity-schedule-weekday">Mon</div>
                                    <div class="activity-schedule-weekday">Tue</div>
                                    <div class="activity-schedule-weekday">Wed</div>
                                    <div class="activity-schedule-weekday">Thu</div>
                                    <div class="activity-schedule-weekday">Fri</div>
                                    <div class="activity-schedule-weekday">Sat</div>
                                </div>
                                <div class="activity-schedule-grid" id="activity-schedule-grid"></div>
                            </div>
                            <small class="text-muted">Red slot means booked.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table proposal-table">
                <thead>
                    <tr>
                        <th class="section" colspan="2">Part II. Financial Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Budget Requirement:</td>
                        <td><?= $this->Form->text('budget_requirement', ['class' => 'form-control peso-format', 'readonly' => true]) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Source of Fund:</td>
                        <td>
                            <?php
                            $fundOptions = ['GASS-MOOE', 'HRTD', 'CMF', 'PSF', 'Other Sources'];
                            $fundAmounts = $fundAmounts ?? [];
                            ?>
                            <table class="table budget-table mb-0" id="source-of-fund-table">
                                <thead>
                                    <tr>
                                        <?php foreach ($fundOptions as $option): ?>
                                            <th class="text-center"><?= h($option) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php foreach ($fundOptions as $option): ?>
                                            <?php
                                                $id = 'source-of-fund-' . strtolower(str_replace([' ', '-'], '', $option));
                                                $value = isset($fundAmounts[$option]) ? (string)$fundAmounts[$option] : '';
                                            ?>
                                            <td class="text-center">
                                                <input
                                                    type="text"
                                                    id="<?= h($id) ?>"
                                                    name="source_of_fund[<?= h($option) ?>]"
                                                    value="<?= h($value) ?>"
                                                    class="form-control peso-format text-center decimal-only"
                                                    inputmode="decimal"
                                                    placeholder="0.00"
                                                >
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Expenditure Matrix:</td>
                        <td>
                            <table class="table budget-table mb-0" id="expenditure-matrix">
                                <thead>
                                    <tr>
                                        <th>Nature of expenditure</th>
                                        <th>No./Quantity</th>
                                        <th>Amount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $natureList = (array)($requestEntity->get('expenditure_nature') ?? []);
                                        $countList = (array)($requestEntity->get('expenditure_no') ?? []);
                                        $amountList = (array)($requestEntity->get('expenditure_amount') ?? []);
                                        $totalList = (array)($requestEntity->get('expenditure_total') ?? []);
                                        $rowCount = max(count($natureList), count($countList), count($amountList), count($totalList), 1);
                                    ?>
                                    <?php for ($i = 0; $i < $rowCount; $i++): ?>
                                        <tr>
                                            <td><?= $this->Form->text("expenditure_nature.$i", [
                                                'class' => 'form-control',
                                                'value' => $natureList[$i] ?? '',
                                            ]) ?></td>
                                            <td><?= $this->Form->text("expenditure_no.$i", [
                                                'class' => 'form-control matrix-no numeric-only',
                                                'inputmode' => 'numeric',
                                                'pattern' => '[0-9]*',
                                                'value' => $countList[$i] ?? '',
                                            ]) ?></td>
                                            <td><?= $this->Form->text("expenditure_amount.$i", [
                                                'class' => 'form-control peso-format matrix-amount decimal-only',
                                                'inputmode' => 'decimal',
                                                'value' => $amountList[$i] ?? '',
                                            ]) ?></td>
                                            <td><?= $this->Form->text("expenditure_total.$i", [
                                                'class' => 'form-control peso-format matrix-total',
                                                'value' => $totalList[$i] ?? '',
                                                'readonly' => true,
                                            ]) ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                            <div class="form-row justify-content-end mt-2">
                                <div class="col-2 col-sm-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="remove-matrix-row" aria-label="Remove row">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <div class="col-2 col-sm-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="add-matrix-row" aria-label="Add row">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Grand Total</td>
                        <td>
                            <?= $this->Form->text('grand_total', ['class' => 'form-control peso-format', 'readonly' => true]) ?>
                            <div id="budget-status" class="alert mt-2 mb-0" style="display: none;"></div>
                        </td>
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
                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label for="attachment-sfwp">SWFP</label>
                                <?= $this->Form->control('attachment_sfwp', [
                                    'type' => 'file',
                                    'label' => false,
                                    'id' => 'attachment-sfwp',
                                    'class' => 'form-control',
                                    'accept' => '.pdf,application/pdf'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <label for="wfp-code">WFP</label>
                                <input
                                    type="text"
                                    id="wfp-code"
                                    name="wfp_code"
                                    class="form-control"
                                    placeholder="Enter Tracking Code"
                                    value="<?= h($requestEntity->wfp_code ?? '') ?>"
                                >
                            </div>
                        </div>
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
                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label for="attachment-ac">ATC</label>
                                <?= $this->Form->control('attachment_ac', [
                                    'type' => 'file',
                                    'label' => false,
                                    'id' => 'attachment-ac',
                                    'class' => 'form-control',
                                    'accept' => '.pdf,application/pdf'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <label for="attachment-pd-proposal">PD PROPOSAL ATTACHMENT</label>
                                <?= $this->Form->control('attachment_pd_proposal', [
                                    'type' => 'file',
                                    'label' => false,
                                    'id' => 'attachment-pd-proposal',
                                    'class' => 'form-control',
                                    'accept' => '.pdf,application/pdf'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3"> <small class="text-muted">PDF files only.</small><br><br>
                        <label for="attachment-list-participants">List of Participants</label>
                        <?= $this->Form->control('attachment_list_participants', [
                            'type' => 'file',
                            'label' => false,
                            'id' => 'attachment-list-participants',
                            'class' => 'form-control',
                            'accept' => '.xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ]) ?>
                        <small class="text-muted">
                            <a href="<?= $this->Url->build('/img/Participant List Template.xlsx') ?>" download>
                                Excel file: Participant List Template
                            </a>
                        </small>
                    </div>
                    <div class="col-md-6 mb-3"></div>
                </div>
                
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
    var bookedDatesUrl = <?= json_encode($this->Url->build('/request/booked-dates')) ?>;
    var initialScheduleRows = <?= json_encode($scheduleRowsForJs ?? []) ?>;
    var bookedSlots = {};
    var scheduleMonth = new Date();
    scheduleMonth.setDate(1);
    var slotRanges = {
        am: { from: '08:00', to: '12:00' },
        pm: { from: '13:00', to: '17:00' }
    };
    var selectedSlots = {};
    var manualScheduleRows = [];
    var manualRowCounter = 0;

    function normalizeDate(value) {
        if (!value) {
            return null;
        }
        var trimmed = value.toString().trim();
        if (trimmed === '') {
            return null;
        }
        if (/^\d{4}-\d{2}-\d{2}$/.test(trimmed)) {
            return trimmed;
        }
        var parts = trimmed.split('/');
        if (parts.length === 3) {
            var month = parseInt(parts[0], 10);
            var day = parseInt(parts[1], 10);
            var year = parseInt(parts[2], 10);
            if (!isNaN(month) && !isNaN(day) && !isNaN(year)) {
                return year + '-' + pad(month) + '-' + pad(day);
            }
        }
        return null;
    }

    function fetchBookedDates() {
        if (!bookedDatesUrl) {
            return;
        }
        var handleData = function (data) {
            if (!data || !Array.isArray(data.slots)) {
                return;
            }
            bookedSlots = {};
            data.slots.forEach(function (value) {
                if (typeof value === 'string' && value.indexOf('__') !== -1) {
                    bookedSlots[value] = true;
                }
            });
            renderActivityScheduleCalendar();
        };
        if (window.fetch) {
            fetch(bookedDatesUrl, { credentials: 'same-origin' })
                .then(function (response) {
                    if (!response.ok) {
                        return null;
                    }
                    return response.json();
                })
                .then(handleData)
                .catch(function () {});
            return;
        }
        if (window.jQuery) {
            $.getJSON(bookedDatesUrl).done(handleData);
        }
    }

    function pad(value) {
        return value < 10 ? '0' + value : String(value);
    }

    function toYmd(dateObj) {
        return dateObj.getFullYear() + '-' + pad(dateObj.getMonth() + 1) + '-' + pad(dateObj.getDate());
    }

    function buildKey(dateValue, slot) {
        return dateValue + '__' + slot;
    }

    function isBookedSlot(dateValue, slot) {
        var key = buildKey(dateValue, slot);
        return Object.prototype.hasOwnProperty.call(bookedSlots, key);
    }

    function getSlotFromTime(value) {
        var hour = parseInt((value || '').split(':')[0], 10);
        if (isNaN(hour)) {
            return null;
        }
        return hour < 12 ? 'am' : 'pm';
    }

    function normalizeTime(value) {
        if (!value) {
            return '';
        }
        var trimmed = value.toString().trim();
        if (trimmed === '') {
            return '';
        }
        var match = trimmed.match(/^(\d{1,2}):(\d{2})/);
        if (!match) {
            return '';
        }
        return pad(parseInt(match[1], 10)) + ':' + match[2];
    }

    function timeToMinutes(value) {
        var normalized = normalizeTime(value);
        if (!normalized) {
            return null;
        }
        var parts = normalized.split(':');
        if (parts.length !== 2) {
            return null;
        }
        var hours = parseInt(parts[0], 10);
        var mins = parseInt(parts[1], 10);
        if (isNaN(hours) || isNaN(mins)) {
            return null;
        }
        return (hours * 60) + mins;
    }

    function rangeOverlaps(aStart, aEnd, bStart, bEnd) {
        if (aStart === null || aEnd === null || bStart === null || bEnd === null) {
            return false;
        }
        return aStart < bEnd && bStart < aEnd;
    }

    function isRangeBlocked(dateValue, timeFrom, timeTo) {
        var start = timeToMinutes(timeFrom);
        var end = timeToMinutes(timeTo);
        if (start === null || end === null || start >= end) {
            return { blocked: false };
        }
        var amKey = buildKey(dateValue, 'am');
        var pmKey = buildKey(dateValue, 'pm');
        if (bookedSlots[amKey]) {
            var amStart = timeToMinutes(slotRanges.am.from);
            var amEnd = timeToMinutes(slotRanges.am.to);
            if (rangeOverlaps(start, end, amStart, amEnd)) {
                return { blocked: true, label: '8:00 AM to 12:00 PM' };
            }
        }
        if (bookedSlots[pmKey]) {
            var pmStart = timeToMinutes(slotRanges.pm.from);
            var pmEnd = timeToMinutes(slotRanges.pm.to);
            if (rangeOverlaps(start, end, pmStart, pmEnd)) {
                return { blocked: true, label: '1:00 PM to 5:00 PM' };
            }
        }
        return { blocked: false };
    }

    function isSameRange(a, b) {
        return a && b && a.date === b.date && a.from === b.from && a.to === b.to;
    }

    function addManualRow(dateValue, timeFrom, timeTo) {
        var normalizedDate = normalizeDate(dateValue);
        var normalizedFrom = normalizeTime(timeFrom);
        var normalizedTo = normalizeTime(timeTo);
        if (!normalizedDate || !normalizedFrom || !normalizedTo) {
            return false;
        }
        var blockedCheck = isRangeBlocked(normalizedDate, normalizedFrom, normalizedTo);
        if (blockedCheck.blocked) {
            alert('Not available: ' + blockedCheck.label + ' is already booked.');
            return false;
        }
        var row = {
            id: 'm' + (manualRowCounter++),
            date: normalizedDate,
            from: normalizedFrom,
            to: normalizedTo
        };
        for (var i = 0; i < manualScheduleRows.length; i += 1) {
            if (isSameRange(manualScheduleRows[i], row)) {
                return false;
            }
        }
        manualScheduleRows.push(row);
        return true;
    }

    function renderManualScheduleList() {
        var list = document.getElementById('manual-schedule-list');
        if (!list) {
            return;
        }
        list.innerHTML = '';
        if (!manualScheduleRows.length) {
            return;
        }
        manualScheduleRows.forEach(function (row) {
            var item = document.createElement('div');
            item.className = 'd-flex align-items-center justify-content-between border rounded px-2 py-1 mb-1';
            item.innerHTML =
                '<div>' + row.date + ' (' + row.from + '-' + row.to + ')</div>' +
                '<button type="button" class="btn btn-sm btn-outline-danger" data-id="' + row.id + '">Remove</button>';
            list.appendChild(item);
        });
    }

    function hydrateInitialScheduleSelection() {
        if (!Array.isArray(initialScheduleRows)) {
            return;
        }
        initialScheduleRows.forEach(function (row) {
            var dateValue = normalizeDate(row.date || '');
            if (!dateValue) {
                return;
            }
            var timeFrom = normalizeTime(row.time_from || '');
            var timeTo = normalizeTime(row.time_to || '');
            var slot = getSlotFromTime(timeFrom);
            if (slot && slotRanges[slot]) {
                var range = slotRanges[slot];
                if (range.from === timeFrom && range.to === timeTo) {
                    selectedSlots[buildKey(dateValue, slot)] = {
                        date: dateValue,
                        slot: slot
                    };
                    return;
                }
            }
            if (timeFrom && timeTo) {
                addManualRow(dateValue, timeFrom, timeTo);
            }
        });
    }

    function syncScheduleRowsToInputs() {
        var container = document.getElementById('schedule-rows');
        var summary = document.getElementById('activity-schedule-selected');
        if (!container) {
            return;
        }
        container.innerHTML = '';
        var keys = Object.keys(selectedSlots).sort();
        if (summary) {
            summary.textContent = '';
        }
        if (!keys.length) {
            if (!manualScheduleRows.length) {
                if (summary) {
                    summary.textContent = 'No schedule selected.';
                }
                return;
            }
        }
        var summaryItems = [];
        keys.forEach(function (key) {
            var item = selectedSlots[key];
            var range = slotRanges[item.slot];
            if (!item || !range) {
                return;
            }
            var row = document.createElement('div');
            row.className = 'schedule-row';
            row.innerHTML =
                '<input type="hidden" name="activity_schedule_date[]" value="' + item.date + '">' +
                '<input type="hidden" name="activity_schedule_time_from[]" value="' + range.from + '">' +
                '<input type="hidden" name="activity_schedule_time_to[]" value="' + range.to + '">';
            container.appendChild(row);
            summaryItems.push(item.date + ' (' + item.slot.toUpperCase() + ' ' + range.from + '-' + range.to + ')');
        });
        manualScheduleRows.forEach(function (row) {
            var manualRow = document.createElement('div');
            manualRow.className = 'schedule-row';
            manualRow.innerHTML =
                '<input type="hidden" name="activity_schedule_date[]" value="' + row.date + '">' +
                '<input type="hidden" name="activity_schedule_time_from[]" value="' + row.from + '">' +
                '<input type="hidden" name="activity_schedule_time_to[]" value="' + row.to + '">';
            container.appendChild(manualRow);
            summaryItems.push(row.date + ' (' + row.from + '-' + row.to + ')');
        });
        if (summary) {
            summary.textContent = summaryItems.join(' | ');
        }
    }

    function renderActivityScheduleCalendar() {
        var monthLabel = document.getElementById('activity-schedule-month');
        var grid = document.getElementById('activity-schedule-grid');
        if (!monthLabel || !grid) {
            return;
        }

        var firstOfMonth = new Date(scheduleMonth.getFullYear(), scheduleMonth.getMonth(), 1);
        var start = new Date(firstOfMonth);
        start.setDate(firstOfMonth.getDate() - firstOfMonth.getDay());

        var monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        monthLabel.textContent = monthNames[scheduleMonth.getMonth()] + ' ' + scheduleMonth.getFullYear();
        grid.innerHTML = '';

        for (var i = 0; i < 42; i += 1) {
            var dayDate = new Date(start);
            dayDate.setDate(start.getDate() + i);
            var dateValue = toYmd(dayDate);
            var isOutside = dayDate.getMonth() !== scheduleMonth.getMonth();
            var isAmBooked = isBookedSlot(dateValue, 'am');
            var isPmBooked = isBookedSlot(dateValue, 'pm');
            var amKey = buildKey(dateValue, 'am');
            var pmKey = buildKey(dateValue, 'pm');
            var amClass = selectedSlots[amKey] ? ' is-selected' : '';
            var pmClass = selectedSlots[pmKey] ? ' is-selected' : '';

            var cell = document.createElement('div');
            cell.className = 'activity-schedule-day' + (isOutside ? ' is-outside' : '');
            cell.innerHTML =
                '<div class="activity-schedule-day-number">' + dayDate.getDate() + '</div>' +
                '<div class="activity-schedule-slot-list">' +
                    '<button type="button" class="activity-schedule-slot' + amClass + '" data-date="' + dateValue + '" data-slot="am"' + (isAmBooked ? ' disabled' : '') + '>' +
                        '<strong>AM</strong><small>' + (isAmBooked ? 'Booked' : 'Select') + '</small>' +
                    '</button>' +
                    '<button type="button" class="activity-schedule-slot' + pmClass + '" data-date="' + dateValue + '" data-slot="pm"' + (isPmBooked ? ' disabled' : '') + '>' +
                        '<strong>PM</strong><small>' + (isPmBooked ? 'Booked' : 'Select') + '</small>' +
                    '</button>' +
                '</div>';
            grid.appendChild(cell);
        }
    }

    var prevMonthBtn = document.getElementById('activity-schedule-prev-month');
    var nextMonthBtn = document.getElementById('activity-schedule-next-month');
    var scheduleGrid = document.getElementById('activity-schedule-grid');

    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', function () {
            scheduleMonth = new Date(scheduleMonth.getFullYear(), scheduleMonth.getMonth() - 1, 1);
            renderActivityScheduleCalendar();
        });
    }
    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', function () {
            scheduleMonth = new Date(scheduleMonth.getFullYear(), scheduleMonth.getMonth() + 1, 1);
            renderActivityScheduleCalendar();
        });
    }
    if (scheduleGrid) {
        scheduleGrid.addEventListener('click', function (event) {
            var btn = event.target.closest('.activity-schedule-slot');
            if (!btn || btn.disabled) {
                return;
            }
            var dateValue = btn.getAttribute('data-date');
            var slot = btn.getAttribute('data-slot');
            if (!dateValue || !slotRanges[slot]) {
                return;
            }
            var key = buildKey(dateValue, slot);
            if (selectedSlots[key]) {
                delete selectedSlots[key];
            } else {
                selectedSlots[key] = { date: dateValue, slot: slot };
            }
            syncScheduleRowsToInputs();
            renderActivityScheduleCalendar();
        });
    }

    var manualAddBtn = document.getElementById('manual-schedule-add');
    if (manualAddBtn) {
        manualAddBtn.addEventListener('click', function () {
            var dateInput = document.getElementById('manual-schedule-date');
            var fromInput = document.getElementById('manual-schedule-from');
            var toInput = document.getElementById('manual-schedule-to');
            var dateValue = dateInput ? dateInput.value : '';
            var timeFrom = fromInput ? fromInput.value : '';
            var timeTo = toInput ? toInput.value : '';
            var added = addManualRow(dateValue, timeFrom, timeTo);
            if (!added) {
                return;
            }
            if (dateInput) {
                dateInput.value = '';
            }
            if (fromInput) {
                fromInput.value = '';
            }
            if (toInput) {
                toInput.value = '';
            }
            renderManualScheduleList();
            syncScheduleRowsToInputs();
        });
    }

    var manualList = document.getElementById('manual-schedule-list');
    if (manualList) {
        manualList.addEventListener('click', function (event) {
            var btn = event.target.closest('button[data-id]');
            if (!btn) {
                return;
            }
            var rowId = btn.getAttribute('data-id');
            manualScheduleRows = manualScheduleRows.filter(function (row) {
                return row.id !== rowId;
            });
            renderManualScheduleList();
            syncScheduleRowsToInputs();
        });
    }

    hydrateInitialScheduleSelection();
    renderManualScheduleList();
    syncScheduleRowsToInputs();
    renderActivityScheduleCalendar();

    fetchBookedDates();
    setInterval(fetchBookedDates, 10000);

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

    function sanitizeIntInput(input) {
        if (!input) {
            return;
        }
        input.value = (input.value || '').toString().replace(/[^\d]/g, '');
    }

    function sanitizeDecimalInput(input) {
        if (!input) {
            return;
        }
        var raw = (input.value || '').toString().replace(/[^\d.]/g, '');
        var parts = raw.split('.');
        if (parts.length > 2) {
            raw = parts.shift() + '.' + parts.join('');
        }
        input.value = raw;
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
            var sourceTotal = getSourceFundTotal();
            budgetInput.value = sourceTotal ? formatPeso(sourceTotal.toFixed(2)) : '';
        }
        updateBudgetStatus();
    }

    function getSourceFundTotal() {
        var sum = 0;
        document.querySelectorAll('#source-of-fund-table input[type="text"]').forEach(function (input) {
            var value = Number(normalizeNumber(input.value || ''));
            if (!Number.isNaN(value)) {
                sum += value;
            }
        });
        return sum;
    }

    function updateBudgetStatus() {
        var budgetInput = document.querySelector('input[name="budget_requirement"]');
        var grandInput = document.querySelector('input[name="grand_total"]');
        var status = document.getElementById('budget-status');
        if (!budgetInput || !grandInput || !status) {
            return;
        }
        var budgetValue = Number(normalizeNumber(budgetInput.value || ''));
        var grandValue = Number(normalizeNumber(grandInput.value || ''));
        if (!Number.isFinite(budgetValue)) {
            budgetValue = 0;
        }
        if (!Number.isFinite(grandValue)) {
            grandValue = 0;
        }
        if (!budgetInput.value && !grandInput.value) {
            status.style.display = 'none';
            status.textContent = '';
            status.className = 'alert mt-2 mb-0';
            return;
        }
        if (grandValue > budgetValue) {
            status.textContent = 'MISMATCH';
            status.className = 'alert alert-danger mt-2 mb-0';
            status.style.display = 'block';
            return;
        }
        if (budgetValue > grandValue) {
            var remaining = budgetValue - grandValue;
            status.textContent = 'REMAINING BALANCE: ' + formatPeso(remaining.toFixed(2));
            status.className = 'alert alert-warning mt-2 mb-0';
            status.style.display = 'block';
            return;
        }
        status.style.display = 'none';
        status.textContent = '';
        status.className = 'alert mt-2 mb-0';
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
        if (event.target.classList.contains('numeric-only')) {
            sanitizeIntInput(event.target);
        } else if (event.target.classList.contains('decimal-only')) {
            sanitizeDecimalInput(event.target);
        }
        if (event.target.classList.contains('matrix-no') || event.target.classList.contains('matrix-amount')) {
            updateMatrixRowTotal(event.target.closest('tr'));
            updateGrandTotal();
        } else if (event.target.classList.contains('matrix-total')) {
            updateGrandTotal();
        } else if (event.target.closest && event.target.closest('#source-of-fund-table')) {
            updateGrandTotal();
        } else if (event.target.name === 'budget_requirement' || event.target.name === 'grand_total') {
            updateBudgetStatus();
        }
    });

    document.querySelectorAll('#expenditure-matrix tbody tr').forEach(function (row) {
        updateMatrixRowTotal(row);
    });
    updateGrandTotal();
    updateBudgetStatus();

    var tpTotal = document.querySelector('input[name="target_participants_total"]');
    var tpMale = document.querySelector('input[name="target_participants_male"]');
    var tpFemale = document.querySelector('input[name="target_participants_female"]');
    function normalizeInt(value) {
        var cleaned = (value || '').toString().replace(/[^\d]/g, '');
        return cleaned === '' ? 0 : parseInt(cleaned, 10);
    }
    function updateTargetTotal() {
        if (!tpTotal || !tpMale || !tpFemale) {
            return;
        }
        var maleVal = normalizeInt(tpMale.value);
        var femaleVal = normalizeInt(tpFemale.value);
        var total = maleVal + femaleVal;
        tpTotal.value = total ? String(total) : '';
    }
    if (tpMale && tpFemale && tpTotal) {
        tpMale.addEventListener('input', updateTargetTotal);
        tpFemale.addEventListener('input', updateTargetTotal);
        updateTargetTotal();
    }

    var proposalForm = document.getElementById('proposal-form');
    if (proposalForm) {
        proposalForm.addEventListener('submit', function (event) {
            var budgetInput = document.querySelector('input[name="budget_requirement"]');
            var grandInput = document.querySelector('input[name="grand_total"]');
            var budgetValue = Number(normalizeNumber(budgetInput ? budgetInput.value : ''));
            var grandValue = Number(normalizeNumber(grandInput ? grandInput.value : ''));
            if (!Number.isFinite(budgetValue)) {
                budgetValue = 0;
            }
            if (!Number.isFinite(grandValue)) {
                grandValue = 0;
            }
            if (grandValue > budgetValue) {
                event.preventDefault();
                return;
            }
            if (budgetValue > grandValue) {
                var remaining = budgetValue - grandValue;
                event.preventDefault();
                return;
            }
        });
    }
});
</script>
