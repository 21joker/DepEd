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

function _approval_badge($status) {
  return match ($status) {
    'approved' => 'badge-success',
    'declined' => 'badge-warning',
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
  max-width: 520px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
@media (max-width: 768px) {
  .subject-truncate { max-width: 260px; }
}
.action-wrap {
  display: inline-flex;
  gap: 8px;
  flex-wrap: wrap;
}
.action-wrap .btn { border-radius: 10px; }
</style>

<!-- ===== Stats Cards ===== -->
<?php if (!$inModal): ?>
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
      <h3><?= (int)($counts['rejected'] ?? 0) ?></h3>
      <p>Review</p>
    </div>
    <div class="icon"><i class="fas fa-ban"></i></div>
  </div>

</div>
<?php endif; ?>

<!-- ===== Pending Requests ===== -->
<div class="col-12 p-0">
  <div class="card">
    <div class="card-header">
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
              <th style="width: 18%;">Name</th>
              <th>Subject</th>
              <th style="width: 18%;">Submitted</th>
              <th style="width: 12%;">Approvals</th>
<?php if ($showStatus): ?>
                <th style="width: 12%;">Status</th>
              <?php endif; ?>
              <th style="width: 18%;">Action</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!$hasRequests): ?>
              <tr>
                <td colspan="<?= $showStatus ? 6 : 5 ?>" class="text-center text-muted">No requests found.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($requests as $request): ?>
              <tr>
                <td><?= h($request->name ?? '') ?></td>

                <td class="subject-truncate" title="<?= h($request->title ?? '') ?>">
                  <?= h($request->title ?? '') ?>
                </td>

                <td><?= h($request->created_at ?? $request->created ?? '') ?></td>

                <td>
                  <?= (int)($request->approvals_count ?? 0) ?> / <?= (int)($request->approvals_needed ?? 0) ?>
                </td>

                <?php if ($showStatus): ?>
                  <td>
                    <?php
                      $status = $adminApprovalStatus[$request->id] ?? 'pending';
                      $label = $status === 'approved' ? 'Approved' : ($status === 'declined' ? 'Review' : 'Pending');
                      $badge = _approval_badge($status);
                    ?>
                    <span class="badge <?= h($badge) ?>"><?= h($label) ?></span>
                  </td>
                <?php endif; ?>

                <td>
                  <div class="action-wrap">
                    <?php if ($showView): ?>
                      <a
                        class="btn btn-info btn-sm modal-link"
                        data-title="Request Details"
                        href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'view', $request->id, '?' => ['modal' => 1]]) ?>"
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

                    <?php if ($showActions): ?>
                      <?= $this->Form->postLink(
                        'Approve',
                        ['controller' => 'Requests', 'action' => 'approve', $request->id],
                        ['class' => 'btn btn-success btn-sm', 'confirm' => 'Approve this request?']
                      ) ?>

                      <?= $this->Form->postLink(
                        'Review',
                        ['controller' => 'Requests', 'action' => 'decline', $request->id],
                        ['class' => 'btn btn-warning btn-sm', 'confirm' => 'Mark this request for review?']
                      ) ?>
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
      var text = row.textContent.toLowerCase();
      row.style.display = text.indexOf(term) !== -1 ? '' : 'none';
    });
  }
  input.addEventListener('input', filterRows);
  btn.addEventListener('click', filterRows);
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
