<?php
/**
 * Superuser Logs
 *
 * @var \Cake\Datasource\ResultSetInterface $loginLogs
 * @var \Cake\Datasource\ResultSetInterface $userLogs
 * @var string $period
 * @var string $date
 * @var string $month
 * @var string $year
 */
?>

<style>
.logs-toolbar {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
}
.logs-toolbar .export-csv-wrap {
  margin-left: auto;
}
.btn-export-csv {
  border-color: #6c757d;
  color: #6c757d;
}
.btn-export-csv:hover,
.btn-export-csv:focus {
  background-color: #28a745;
  border-color: #28a745;
  color: #fff;
}
.logs-toolbar .form-control {
  min-width: 180px;
}
.logs-card + .logs-card {
  margin-top: 16px;
}
</style>

<div class="col-12 p-0">
  <div class="card logs-card">
    <div class="card-header">
      <h3 class="card-title">Logs</h3>
    </div>
    <div class="card-body">
      <?= $this->Flash->render('logs') ?>
      <form method="get" class="logs-toolbar mb-3">
        <div>
          <label class="small text-muted mb-1">Sort</label>
          <select class="form-control form-control-sm" name="period" id="period-select">
            <option value="all" <?= $period === 'all' ? 'selected' : '' ?>>All</option>
            <option value="daily" <?= $period === 'daily' ? 'selected' : '' ?>>Daily</option>
            <option value="monthly" <?= $period === 'monthly' ? 'selected' : '' ?>>Monthly</option>
            <option value="yearly" <?= $period === 'yearly' ? 'selected' : '' ?>>Yearly</option>
          </select>
        </div>
        <div>
          <label class="small text-muted mb-1">Search (username/role)</label>
          <input class="form-control form-control-sm" type="text" name="q" value="<?= h($search ?? '') ?>" placeholder="e.g. admin, approver">
        </div>
        <div id="filter-date" style="<?= $period === 'daily' ? '' : 'display:none;' ?>">
          <label class="small text-muted mb-1">Date</label>
          <input class="form-control form-control-sm" type="date" name="date" value="<?= h($date) ?>">
        </div>
        <div id="filter-month" style="<?= $period === 'monthly' ? '' : 'display:none;' ?>">
          <label class="small text-muted mb-1">Month</label>
          <input class="form-control form-control-sm" type="month" name="month" value="<?= h($month) ?>">
        </div>
        <div id="filter-year" style="<?= $period === 'yearly' ? '' : 'display:none;' ?>">
          <label class="small text-muted mb-1">Year</label>
          <input class="form-control form-control-sm" type="number" name="year" min="2000" max="2100" value="<?= h($year) ?>">
        </div>
        <div class="pt-3">
          <button class="btn btn-primary btn-sm" type="submit">Apply</button>
        </div>
        <div class="pt-3 export-csv-wrap">
          <?php
            $exportQuery = [
              'period' => $period,
              'date' => $date,
              'month' => $month,
              'year' => $year,
              'q' => $search ?? '',
            ];
          ?>
          <a class="btn btn-outline-secondary btn-sm btn-export-csv" href="<?= $this->Url->build(['controller' => 'Logs', 'action' => 'export', '?' => $exportQuery]) ?>">
            Export CSV
          </a>
        </div>
      </form>
      <div class="mb-3">
        <?= $this->Form->postLink(
          'Clear Login Logs',
          ['controller' => 'Logs', 'action' => 'clearLoginLogs'],
          [
            'class' => 'btn btn-danger btn-sm',
            'confirm' => 'Clear all login logs? This cannot be undone.'
          ]
        ) ?>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Login Logs</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
              <thead>
                <tr>
                  <th>Time</th>
                  <th>User</th>
                  <th>Role</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($loginLogs->isEmpty()): ?>
                  <tr><td colspan="3" class="text-center text-muted">No login logs.</td></tr>
                <?php else: ?>
                  <?php foreach ($loginLogs as $log): ?>
                    <tr>
                      <td><?= h($log->created ?? '') ?></td>
                      <td><?= h($log->username ?? ($log->user->username ?? '')) ?></td>
                      <td><?= h($log->role ?? ($log->user->role ?? '')) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Account Created Logs</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
              <thead>
                <tr>
                  <th>Created</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($userLogs->isEmpty()): ?>
                  <tr><td colspan="4" class="text-center text-muted">No accounts found.</td></tr>
                <?php else: ?>
                  <?php foreach ($userLogs as $user): ?>
                    <tr>
                      <td><?= h($user->created ?? '') ?></td>
                      <td><?= h($user->username ?? '') ?></td>
                      <td><?= h($user->role ?? '') ?></td>
                      <td><?= h(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: '—') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var period = document.getElementById('period-select');
  var date = document.getElementById('filter-date');
  var month = document.getElementById('filter-month');
  var year = document.getElementById('filter-year');
  if (!period) {
    return;
  }
  function syncFilters() {
    var value = period.value;
    if (date) date.style.display = value === 'daily' ? '' : 'none';
    if (month) month.style.display = value === 'monthly' ? '' : 'none';
    if (year) year.style.display = value === 'yearly' ? '' : 'none';
  }
  period.addEventListener('change', syncFilters);
  syncFilters();
});
</script>
