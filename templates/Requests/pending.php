<?php
/**
 * templates/Requests/pending.php
 *
 * Expected variables:
 * - $requests
 * - $counts : ['pending'=>0,'approved'=>0,'rejected'=>0,'deleted'=>0]
 * - $recentLogs (optional)
 */

$counts = $counts ?? ['pending'=>0,'approved'=>0,'rejected'=>0,'deleted'=>0];
$recentLogs = $recentLogs ?? [];
$requests = $requests ?? [];
$pageTitle = $pageTitle ?? 'Pending Requests';
$headerBadge = $headerBadge ?? 'Needs all admin approvals';
$viewType = $viewType ?? 'pending';
$inModal = $inModal ?? false;
$showActions = $viewType === 'pending' && ($auth['role'] ?? '') !== 'Superuser';
$showView = true;
$showStatus = $viewType === 'pending' && ($auth['role'] ?? '') !== 'Superuser';
$adminApprovalStatus = $adminApprovalStatus ?? [];
$adminApprovalMeta = $adminApprovalMeta ?? [];
$csrfToken = $this->getRequest()->getAttribute('csrfToken');

function _approval_badge($status) {
  return match ($status) {
    'approved' => 'badge-success',
    'declined' => 'badge-danger',
    default => 'badge-secondary',
  };
}

function _ux_badge_class($action) {
    return match ($action) {
        'approved' => 'badge-success',
        'rejected' => 'badge-secondary',
        'deleted'  => 'badge-danger',
        'created'  => 'badge-info',
        default    => 'badge-light',
    };
}

function _format_status_time($value): string {
  if ($value instanceof \Cake\I18n\FrozenTime) {
    return $value->i18nFormat('MM/dd/yy h:mm a');
  }
  if ($value instanceof \DateTimeInterface) {
    return $value->format('m/d/y h:i a');
  }
  return trim((string)$value);
}

function _time_to_ts($value): ?int {
  if ($value instanceof \DateTimeInterface) {
    return $value->getTimestamp();
  }
  $raw = trim((string)$value);
  if ($raw === '') {
    return null;
  }
  $ts = strtotime($raw);
  return $ts === false ? null : $ts;
}

$hasRequests = is_countable($requests) ? count($requests) > 0 : !empty($requests);
?>

<style>
.dashboard-row {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 12px;
}
.dashboard-row .small-box {
  flex: 1 1 220px;
  min-width: 220px;
  border-radius: 14px;
}
.dashboard-row a.small-box {
  color: inherit;
  text-decoration: none;
}
.dashboard-row a.small-box:hover {
  color: inherit;
  text-decoration: none;
}
.table-responsive { overflow-x: auto; }
.subject-truncate {
  max-width: 360px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
@media (max-width: 768px) {
  .subject-truncate { max-width: 220px; }
}
.table-sm td,
.table-sm th {
  white-space: nowrap;
}
.table-sm td.wrap {
  white-space: normal;
  min-width: 220px;
}
.action-wrap {
  display: inline-flex;
  gap: 8px;
  flex-wrap: wrap;
}
.action-wrap .btn { border-radius: 10px; }
</style>

<!-- ===== Stats Cards ===== -->
<?php if (!$inModal && ($auth['role'] ?? '') !== 'Superuser'): ?>
<div class="dashboard-row">
  <div class="small-box bg-success">
    <div class="inner">
      <h3><?= (int)($counts['approved'] ?? 0) ?></h3>
      <p>Approved</p>
    </div>
    <div class="icon"><i class="fas fa-check"></i></div>
  </div>

  <div class="small-box bg-secondary">
    <div class="inner">
      <h3><?= (int)($counts['pending'] ?? 0) ?></h3>
      <p>Review</p>
    </div>
    <div class="icon"><i class="fas fa-ban"></i></div>
  </div>

</div>
<?php endif; ?>

<?php if (!$inModal && ($auth['role'] ?? '') === 'Superuser'): ?>
<div class="dashboard-row">
  <div class="small-box bg-info">
    <div class="inner">
      <h3><?= (int)($totalSubmitted ?? 0) ?></h3>
      <p>Total Submitted Activities</p>
    </div>
    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
  </div>

  <div class="small-box bg-success">
    <div class="inner">
      <h3><?= (int)($counts['approved'] ?? 0) ?></h3>
      <p>Total Approved Activities</p>
    </div>
    <div class="icon"><i class="fas fa-check"></i></div>
  </div>

  <div class="small-box bg-warning">
    <div class="inner">
      <h3><?= (int)($counts['pending'] ?? 0) ?></h3>
      <p>Total Pending Activities</p>
    </div>
    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
  </div>

  <div class="small-box bg-secondary">
    <div class="inner">
      <h3><?= (int)($counts['rejected'] ?? 0) ?></h3>
      <p>Total Review Activities</p>
    </div>
    <div class="icon"><i class="fas fa-search"></i></div>
  </div>
</div>
<?php endif; ?>

<!-- ===== Pending Requests ===== -->
<div class="col-12 p-0">
  <div class="card">
    <div class="card-header">
      <?php
        $exportMode = (string)($this->request->getQuery('export_mode') ?? 'month');
        $exportDate = (string)($this->request->getQuery('export_date') ?? '');
        if ($exportMode === '') {
            $exportMode = 'month';
        }
      ?>
      <div class="d-flex align-items-center">
        <div class="d-flex align-items-center mr-3">
          <select id="export-mode" class="form-control form-control-sm mr-2" style="width: 110px;">
            <option value="day" <?= $exportMode === 'day' ? 'selected' : '' ?>>Day</option>
            <option value="month" <?= $exportMode === 'month' ? 'selected' : '' ?>>Month</option>
            <option value="year" <?= $exportMode === 'year' ? 'selected' : '' ?>>Year</option>
          </select>
          <input id="export-date" class="form-control form-control-sm" value="<?= h($exportDate) ?>" style="width: 160px;" placeholder="Select date">
        </div>
        <a class="btn btn-danger btn-sm mr-2" target="_blank" rel="noopener"
           href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'exportAllPdf'], ['fullBase' => true]) ?>"
           id="export-pdf-btn">
          Export PDF
        </a>
        <a class="btn btn-outline-success btn-sm mr-3"
           href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'exportAllExcel'], ['fullBase' => true]) ?>"
           id="export-excel-btn">
          Export Excel
        </a>
      </div>
      <h3 class="card-title">&nbsp;</h3>
      <div class="card-tools d-flex align-items-center">
        <?php if (!empty($headerBadge)): ?>
          <span class="badge badge-warning mr-2"><?= h($headerBadge) ?></span>
        <?php endif; ?>
        <?php if (($auth['role'] ?? '') === 'Superuser' && $viewType === 'pending'): ?>
          <?= $this->Form->postLink(
            'Clear Requests',
            ['controller' => 'Requests', 'action' => 'clearAll'],
            [
              'class' => 'btn btn-danger btn-sm',
              'confirm' => 'Clear all requests and approvals? This cannot be undone.'
            ]
          ) ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="card-body">
      <?= $this->Flash->render() ?>

      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="text-muted small">Search in list</div>
        <div class="input-group input-group-sm" style="max-width: 320px;">
          <input type="text" id="pending-search" class="form-control" placeholder="Search name, subject, status">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" id="pending-search-btn" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
          <thead>
            <tr>
              <th style="width: 14%;">Name</th>
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
              <th style="width: 12%;">Submitted</th>
              <th style="width: 12%;">Last Updated</th>
<?php if ($showStatus): ?>
                <th style="width: 10%;">Status</th>
              <?php endif; ?>
              <th style="width: 12%;">Action</th>
            </tr>
          </thead>

          <tbody>
<?php if (!$hasRequests): ?>
              <tr>
                <td colspan="<?= $showStatus ? 17 : 16 ?>" class="text-center text-muted">No requests found.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($requests as $request): ?>
              <?php
                $summary = $requestSummaries[$request->id] ?? [];
                $ac = trim((string)($summary['pmis_activity_code'] ?? ''));
                $title = trim((string)($summary['title_of_activity'] ?? $request->title ?? ''));
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
                $requestId = (int)($request->id ?? 0);
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
                <td><?= h($request->name ?? '') ?></td>
                <td><?= $ac !== '' ? h($ac) : 'N/A' ?></td>
                <td class="wrap" title="<?= h($title) ?>"><?= $title !== '' ? h($title) : 'N/A' ?></td>
                <td><?= $schedule !== '' ? h($schedule) : 'N/A' ?></td>
                <td><?= $budget !== '' ? h($budget) : 'N/A' ?></td>
                <td><?= $source !== '' ? h($source) : 'N/A' ?></td>
                <td><?= $grand !== '' ? h($grand) : 'N/A' ?></td>
                <td>
                  <?php if ($subAro !== '' && ($link = $buildFileLink($subAro))): ?>
                    <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener"><?= h($link['name']) ?></a>
                  <?php else: ?>
                    <?= $subAro !== '' ? h($subAro) : 'N/A' ?>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($sfwp !== '' && ($link = $buildFileLink($sfwp))): ?>
                    <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener"><?= h($link['name']) ?></a>
                  <?php else: ?>
                    <?= $sfwp !== '' ? h($sfwp) : 'N/A' ?>
                  <?php endif; ?>
                </td>
                <td><?= $wfpCode !== '' ? h($wfpCode) : 'N/A' ?></td>
                <td>
                  <?php if ($ar !== '' && ($link = $buildFileLink($ar))): ?>
                    <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener"><?= h($link['name']) ?></a>
                  <?php else: ?>
                    <?= $ar !== '' ? h($ar) : 'N/A' ?>
                  <?php endif; ?>
                </td>
                <td>
                <?php if ($acAttach !== '' && ($link = $buildFileLink($acAttach))): ?>
                  <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener"><?= h($link['name']) ?></a>
                <?php else: ?>
                  <?= $acAttach !== '' ? h($acAttach) : 'N/A' ?>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($participantsList !== '' && ($link = $buildFileLink($participantsList))): ?>
                  <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener"><?= h($link['name']) ?></a>
                <?php else: ?>
                  <?= $participantsList !== '' ? h($participantsList) : 'N/A' ?>
                <?php endif; ?>
              </td>

              <td><?= h($request->created_at ?? $request->created ?? '') ?></td>
                <td><?= h($request->updated_at ?? $request->modified ?? $request->created_at ?? $request->created ?? '') ?></td>

                <?php if ($showStatus): ?>
                  <td>
                    <?php
                      $status = $adminApprovalStatus[$request->id] ?? 'pending';
                      $meta = $adminApprovalMeta[$request->id] ?? [];
                      $statusAt = $meta['created'] ?? null;
                      $userUpdatedAt = $request->user_updated_at ?? null;
                      $createdAt = $request->created_at ?? $request->created ?? null;
                      $userUpdatedTs = _time_to_ts($userUpdatedAt);
                      $createdTs = _time_to_ts($createdAt);
                      $statusAtTs = _time_to_ts($statusAt);
                      $isUpdated = false;
                      if ($userUpdatedTs !== null) {
                        if ($statusAtTs !== null) {
                          $isUpdated = $userUpdatedTs > ($statusAtTs + 5);
                        } elseif ($createdTs !== null) {
                          $isUpdated = $userUpdatedTs > ($createdTs + 5);
                        } else {
                          $isUpdated = true;
                        }
                      }
                      if ($status === 'approved') {
                        $label = 'Approved';
                        $badge = _approval_badge($status);
                      } elseif ($isUpdated) {
                        $label = 'Updated';
                        $badge = 'badge-warning';
                      } elseif ($status === 'declined') {
                        $label = 'Review';
                        $badge = _approval_badge($status);
                      } else {
                        $label = 'Pending';
                        $badge = _approval_badge($status);
                      }
                      $statusAtLabel = $isUpdated
                        ? _format_status_time($userUpdatedAt)
                        : ($statusAt ? _format_status_time($statusAt) : '');
                      $showStatusTime = ($statusAtLabel !== '' && ($isUpdated || $status !== 'pending'));
                    ?>
                    <span class="badge <?= h($badge) ?>"><?= h($label) ?></span>
                    <?php if ($showStatusTime): ?>
                      <span class="text-muted small ml-2">
                        <?= h($statusAtLabel) ?>
                      </span>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>

                <td>
                  <div class="action-wrap">
                    <?php if ($showView): ?>
                      <a
                        class="btn btn-info btn-sm modal-link"
                        data-title="Request Details"
                        href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'view', $request->id, '?' => ['modal' => 1]], ['fullBase' => true]) ?>"
                      >View</a>
                    <?php endif; ?>

                    <?php
                      $isFullyApproved = in_array($request->status ?? null, ['approved', 'Approved'], true)
                        || ((int)($request->approvals_needed ?? 0) > 0
                          && (int)($request->approvals_count ?? 0) >= (int)($request->approvals_needed ?? 0));
                    ?>
                    <?php if ($isFullyApproved): ?>
                      <a
                        class="btn btn-secondary btn-sm"
                        target="_blank"
                        rel="noopener"
                        href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'exportPdf', $request->id]) ?>"
                      >Export PDF</a>
                    <?php endif; ?>

                      <?php if ($showActions && !$isFullyApproved): ?>
                        <?= $this->Form->postLink(
                          'Approve',
                          ['controller' => 'Requests', 'action' => 'approve', $request->id],
                          ['class' => 'btn btn-success btn-sm', 'confirm' => 'Approve this request?']
                        ) ?>

                        <button
                          type="button"
                          class="btn btn-warning btn-sm review-btn"
                          data-request-id="<?= (int)$request->id ?>"
                          data-request-title="<?= h($request->title ?? '') ?>"
                        >Review</button>
                      <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>

      <small class="text-muted d-block mt-2">
    Tip: On mobile, the table scrolls horizontally. Subject is truncated to keep layout clean.
  </small>
    </div>
  </div>
</div>

<script>
  (function () {
    var modeSelect = document.getElementById('export-mode');
    var dateInput = document.getElementById('export-date');
    var pdfBtn = document.getElementById('export-pdf-btn');
    var excelBtn = document.getElementById('export-excel-btn');

    function updateInputType() {
      var mode = modeSelect.value;
      if (mode === 'day') {
        dateInput.type = 'date';
        dateInput.placeholder = 'YYYY-MM-DD';
      } else if (mode === 'month') {
        dateInput.type = 'month';
        dateInput.placeholder = 'YYYY-MM';
      } else {
        dateInput.type = 'number';
        dateInput.placeholder = 'YYYY';
        dateInput.min = '2000';
        dateInput.max = '2100';
      }
    }

    function buildUrl(baseHref) {
      var url = new URL(baseHref, window.location.origin);
      var mode = modeSelect.value || 'month';
      var date = dateInput.value || '';
      if (mode) {
        url.searchParams.set('export_mode', mode);
      }
      if (date) {
        url.searchParams.set('export_date', date);
      }
      return url.toString();
    }

    function updateLinks() {
      pdfBtn.href = buildUrl(pdfBtn.href.split('?')[0]);
      excelBtn.href = buildUrl(excelBtn.href.split('?')[0]);
    }

    updateInputType();
    updateLinks();

    modeSelect.addEventListener('change', function () {
      updateInputType();
      dateInput.value = '';
      updateLinks();
    });
    dateInput.addEventListener('change', updateLinks);
    dateInput.addEventListener('keyup', updateLinks);
  })();
</script>

<div class="modal fade" id="review-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send Review Feedback</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="review-form" method="post">
        <div class="modal-body">
          <input type="hidden" name="_csrfToken" value="<?= h($csrfToken) ?>">
          <div class="text-muted small mb-2" id="review-request-title"></div>
          <div class="form-group mb-0">
            <label for="review-remarks" class="font-weight-bold">Remarks</label>
            <textarea
              class="form-control"
              id="review-remarks"
              name="remarks"
              rows="4"
              placeholder="Type your feedback for the requester..."
              required
            ></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Send for Review</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('pending-search');
  var btn = document.getElementById('pending-search-btn');
  var table = document.querySelector('.table-responsive table');
  if (!input || !btn || !table) {
    return;
  }
  function filterRows() {
    var term = (input.value || '').toLowerCase();
    var rows = table.querySelectorAll('tbody tr');
    rows.forEach(function (row) {
      var text = (row.textContent || '').toLowerCase();
      row.style.display = text.indexOf(term) !== -1 ? '' : 'none';
    });
  }
  input.addEventListener('input', filterRows);
  btn.addEventListener('click', filterRows);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = $('#review-modal');
  var form = document.getElementById('review-form');
  var remarks = document.getElementById('review-remarks');
  var title = document.getElementById('review-request-title');
  if (!form || !remarks || !title) {
    return;
  }

  document.querySelectorAll('.review-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var requestId = btn.getAttribute('data-request-id');
      var requestTitle = btn.getAttribute('data-request-title') || '';
      form.action = <?= json_encode($this->Url->build(['controller' => 'Requests', 'action' => 'decline'])) ?> + '/' + requestId;
      title.textContent = requestTitle ? 'Subject: ' + requestTitle : '';
      remarks.value = '';
      modal.modal('show');
    });
  });
});
</script>

<!-- ===== Recent Activity Logs (optional) ===== -->
<?php if (!empty($recentLogs)): ?>
<div class="col-12 p-0">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Recent Activity Logs</h3>
      <div class="card-tools">
        <span class="badge badge-info">Audit trail</span>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
          <thead>
            <tr>
              <th style="width: 20%;">When</th>
              <th style="width: 20%;">Admin</th>
              <th style="width: 15%;">Action</th>
              <th>Request</th>
              <th style="width: 15%;">IP</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentLogs as $log): ?>
              <?php
                $action = $log->action ?? '';
                $badge = _ux_badge_class($action);
                $adminName = $log->admin->name ?? $log->actor_name ?? '—';
                $reqTitle  = $log->request->title ?? $log->request_title ?? '—';
                $ip        = $log->ip ?? '—';
                $when      = $log->created ?? $log->created_at ?? '—';
              ?>
              <tr>
                <td><?= h($when) ?></td>
                <td><?= h($adminName) ?></td>
                <td><span class="badge <?= h($badge) ?>"><?= h($action ?: '—') ?></span></td>
                <td class="subject-truncate" title="<?= h($reqTitle) ?>"><?= h($reqTitle) ?></td>
                <td><?= h($ip) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
