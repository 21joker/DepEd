<?php
/**
 * templates/Requests/deleted.php
 *
 * Expected variables:
 * - $counts : ['pending'=>0,'approved'=>0,'rejected'=>0,'deleted'=>0]
 * - $deleteLogs
 * - $pageTitle
 * - $headerBadge
 */

$counts = $counts ?? ['pending'=>0,'approved'=>0,'rejected'=>0,'deleted'=>0];
$deleteLogs = $deleteLogs ?? [];
$pageTitle = $pageTitle ?? 'Deleted Requests';
$headerBadge = $headerBadge ?? 'Deleted by you';
$inModal = $inModal ?? false;
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
</style>

<!-- ===== Stats Cards ===== -->
<?php if (!$inModal): ?>
<div class="dashboard-row">
  <a class="small-box bg-warning dashboard-card" data-title="Pending Requests" href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'pending', '?' => ['modal' => 1]]) ?>">
    <div class="inner">
      <h3><?= (int)($counts['pending'] ?? 0) ?></h3>
      <p>Pending</p>
    </div>
    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
  </a>

  <a class="small-box bg-success dashboard-card" data-title="Approved Requests" href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'approved', '?' => ['modal' => 1]]) ?>">
    <div class="inner">
      <h3><?= (int)($counts['approved'] ?? 0) ?></h3>
      <p>Approved</p>
    </div>
    <div class="icon"><i class="fas fa-check"></i></div>
  </a>

  <a class="small-box bg-secondary dashboard-card" data-title="Rejected Requests" href="<?= $this->Url->build(['controller' => 'Requests', 'action' => 'rejected', '?' => ['modal' => 1]]) ?>">
    <div class="inner">
      <h3><?= (int)($counts['rejected'] ?? 0) ?></h3>
      <p>Rejected</p>
    </div>
    <div class="icon"><i class="fas fa-ban"></i></div>
  </a>
</div>
<?php endif; ?>

<!-- ===== Deleted Requests ===== -->
<div class="col-12 p-0">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?= h($pageTitle) ?></h3>
      <div class="card-tools">
        <?php if (!empty($headerBadge)): ?>
          <span class="badge badge-danger"><?= h($headerBadge) ?></span>
        <?php endif; ?>
      </div>
    </div>

    <div class="card-body">
      <?= $this->Flash->render() ?>

      <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
          <thead>
            <tr>
              <th style="width: 18%;">Deleted At</th>
              <th style="width: 12%;">Mode</th>
              <th>Title</th>
              <th style="width: 14%;">Prev Status</th>
              <th style="width: 12%;">Request ID</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($deleteLogs)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No deleted requests.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($deleteLogs as $log): ?>
              <tr>
                <td><?= h($log->deleted_at ?? '') ?></td>
                <td><?= h($log->delete_mode ?? '') ?></td>
                <td class="subject-truncate" title="<?= h($log->request_title ?? '') ?>">
                  <?= h($log->request_title ?? '') ?>
                </td>
                <td><?= h($log->request_status ?? '') ?></td>
                <td><?= h($log->request_id ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
