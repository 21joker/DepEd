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
:root {
  --logs-bg: #f6f8fb;
  --logs-card: #ffffff;
  --logs-border: #e6e9f0;
  --logs-text: #1f2937;
  --logs-muted: #6b7280;
  --logs-accent: #2563eb;
  --logs-success: #16a34a;
  --logs-warning: #f59e0b;
  --logs-danger: #dc2626;
}
.logs-card {
  border: 1px solid var(--logs-border);
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
  overflow: hidden;
}
.logs-card .card-header {
  background: linear-gradient(180deg, #ffffff, #f7f9fc);
  border-bottom: 1px solid var(--logs-border);
}
.logs-card .card-title {
  font-weight: 700;
  color: var(--logs-text);
  letter-spacing: 0.2px;
}
.logs-toolbar {
  display: flex;
  flex-wrap: wrap;
  gap: 12px 16px;
  align-items: flex-end;
  padding: 8px 0 4px;
}
.logs-toolbar .export-csv-wrap {
  margin-left: auto;
}
.logs-toolbar .form-control {
  min-width: 180px;
  border-radius: 8px;
  border-color: var(--logs-border);
}
.logs-toolbar label {
  font-weight: 600;
  color: var(--logs-muted);
}
.btn-export-csv {
  border-color: var(--logs-success);
  color: var(--logs-success);
  font-weight: 600;
  border-radius: 8px;
}
.btn-export-csv:hover,
.btn-export-csv:focus {
  background-color: var(--logs-success);
  border-color: var(--logs-success);
  color: #fff;
}
.btn-danger.btn-sm {
  border-radius: 8px;
}
.logs-table {
  margin: 0;
}
.logs-table thead th {
  background: #f3f6fb;
  color: var(--logs-text);
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  border-bottom: 1px solid var(--logs-border);
}
.logs-table tbody tr:nth-child(even) {
  background: #fafbfe;
}
.logs-table tbody tr:hover {
  background: #eef2ff;
}
.logs-table td, .logs-table th {
  border-color: var(--logs-border);
  vertical-align: middle;
}
.role-badge {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  border: 1px solid transparent;
  text-transform: capitalize;
}
.role-superuser {
  background: rgba(220, 38, 38, 0.08);
  color: var(--logs-danger);
  border-color: rgba(220, 38, 38, 0.2);
}
.role-approver {
  background: rgba(245, 158, 11, 0.12);
  color: #b45309;
  border-color: rgba(245, 158, 11, 0.25);
}
.role-user {
  background: rgba(37, 99, 235, 0.1);
  color: var(--logs-accent);
  border-color: rgba(37, 99, 235, 0.25);
}
.logs-pagination {
  display: flex;
  align-items: center;
  gap: 8px;
}
.logs-pagination .dataTables_info {
  color: var(--logs-muted);
  font-size: 0.9rem;
}
.pagination .page-link {
  border-radius: 8px;
  margin: 0 2px;
}
.logs-chart-card .card-body {
  padding: 12px 16px 6px;
}
.logs-chart-wrap {
  height: 180px;
}
.logs-chart-meta {
  color: var(--logs-muted);
  font-size: 0.85rem;
  margin: 0 0 8px;
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
        <div class="pt-3">
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
        <div class="pt-3 export-csv-wrap">
          <?= $this->Form->postLink(
            'Clear Login Logs',
            ['controller' => 'Logs', 'action' => 'clearLoginLogs'],
            [
              'class' => 'btn btn-danger btn-sm',
              'confirm' => 'Clear all login logs? This cannot be undone.'
            ]
          ) ?>
        </div>
      </form>

      <div class="card mb-3 logs-chart-card">
        <div class="card-header">
          <h3 class="card-title">Login Attempts Per Day</h3>
        </div>
        <div class="card-body">
          <p class="logs-chart-meta">Showing <?= h($period === 'all' ? 'last 30 days' : $period) ?> based on current filters.</p>
          <div class="logs-chart-wrap">
            <canvas id="loginAttemptsChart"></canvas>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Login Logs</h3>
        </div>
        <div class="card-body p-0">
          <?php $this->Paginator->options(['url' => $this->request->getQueryParams()]); ?>
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0 logs-table">
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
                    <?php
                      $roleValue = (string)($log->role ?? ($log->user->role ?? ''));
                      $roleKey = strtolower(preg_replace('/\s+/', '-', $roleValue));
                    ?>
                    <tr>
                      <td><?= h($log->created ?? '') ?></td>
                      <td><?= h($log->username ?? ($log->user->username ?? '')) ?></td>
                      <td><span class="role-badge role-<?= h($roleKey) ?>"><?= h($roleValue) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php
            $paginationTemplates = [
              'prevActive' => '<li class="page-item"><a class="page-link" rel="prev" href="{{url}}">Previous</a></li>',
              'prevDisabled' => '<li class="page-item disabled"><span class="page-link">Previous</span></li>',
              'nextActive' => '<li class="page-item"><a class="page-link" rel="next" href="{{url}}">Next</a></li>',
              'nextDisabled' => '<li class="page-item disabled"><span class="page-link">Next</span></li>',
              'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
              'current' => '<li class="page-item active"><span class="page-link">{{text}}</span></li>',
              'ellipsis' => '',
            ];
          ?>
          <div class="logs-pagination justify-content-between p-2">
            <div class="dataTables_info">
              <?= $this->Paginator->counter('Showing {{start}} to {{end}} of {{count}} entries') ?>
            </div>
            <nav aria-label="Pagination">
              <ul class="pagination pagination-sm mb-0">
                <?= $this->Paginator->prev('Previous', ['templates' => $paginationTemplates]) ?>
                <?= $this->Paginator->numbers([
                  'templates' => $paginationTemplates,
                  'modulus' => 5,
                ]) ?>
                <?= $this->Paginator->next('Next', ['templates' => $paginationTemplates]) ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Account Created Logs</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0 logs-table">
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
                    <?php
                      $roleValue = (string)($user->role ?? '');
                      $roleKey = strtolower(preg_replace('/\s+/', '-', $roleValue));
                    ?>
                    <tr>
                      <td><?= h($user->created ?? '') ?></td>
                      <td><?= h($user->username ?? '') ?></td>
                      <td><span class="role-badge role-<?= h($roleKey) ?>"><?= h($roleValue) ?></span></td>
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
  var chartCanvas = document.getElementById('loginAttemptsChart');
  if (chartCanvas && typeof Chart !== 'undefined') {
    var labels = <?= json_encode($chartLabels ?? []) ?>;
    var counts = <?= json_encode($chartCounts ?? []) ?>;
    var ctx = chartCanvas.getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Login Attempts',
          data: counts,
          borderColor: '#2563eb',
          backgroundColor: 'rgba(37, 99, 235, 0.15)',
          borderWidth: 2,
          pointRadius: 2,
          pointHoverRadius: 4,
          fill: true,
          tension: 0.35
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            grid: { display: false },
            ticks: { maxTicksLimit: 10 }
          },
          y: {
            beginAtZero: true,
            grid: { color: '#eef2f7' },
            ticks: { precision: 0 }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false }
        }
      }
    });
  }

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
