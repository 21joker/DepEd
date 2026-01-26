<?php
/**
 * templates/Requests/view.php
 *
 * Expected variables:
 * - $requestEntity
 * - $approvals
 * - $pageTitle
 */

$requestEntity = $requestEntity ?? null;
$approvals = $approvals ?? [];
$pageTitle = $pageTitle ?? 'Request Details';
$admins = $admins ?? [];
$approvalStatuses = $approvalStatuses ?? [];

$detailsText = trim((string)($requestEntity->details ?? $requestEntity->message ?? ''));
$fields = [];
$matrix = [];
$inMatrix = false;

if ($detailsText !== '') {
    $lines = preg_split("/\\r?\\n/", $detailsText);
    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line === '') {
            continue;
        }
        if (stripos($line, 'Expenditure Matrix:') === 0) {
            $inMatrix = true;
            continue;
        }
        if ($inMatrix) {
            if (strpos($line, '- ') === 0) {
                $row = substr($line, 2);
                $parts = array_map('trim', explode('|', $row));
                $matrix[] = [
                    'nature' => $parts[0] ?? '',
                    'no' => isset($parts[1]) ? trim(str_ireplace('No:', '', $parts[1])) : '',
                    'amount' => isset($parts[2]) ? trim(str_ireplace('Amount:', '', $parts[2])) : '',
                    'total' => isset($parts[3]) ? trim(str_ireplace('Total:', '', $parts[3])) : '',
                ];
                continue;
            }
            $inMatrix = false;
        }

        $pos = strpos($line, ':');
        if ($pos !== false) {
            $label = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));
            if ($label !== '') {
                $fields[$label] = $value;
            }
        }
    }
}

function _req_field(array $fields, string $label): string
{
    return $fields[$label] ?? '';
}

function _approval_badge_class(?string $status): string
{
    return match ($status) {
        'approved' => 'badge-success',
        'declined' => 'badge-warning',
        default => 'badge-secondary',
    };
}

function _approval_label(?string $status): string
{
    return $status === 'approved' ? 'Approved' : ($status === 'declined' ? 'Review' : 'Pending');
}
?>

<?php if (!$requestEntity): ?>
  <div class="alert alert-warning mb-0">Request not found.</div>
  <?php return; ?>
<?php endif; ?>

<style>
.proposal-title {
    font-family: "Times New Roman", Times, serif;
    text-align: center;
    line-height: 1.2;
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
.proposal-value {
    min-height: 18px;
    white-space: pre-wrap;
}
.budget-table th,
.budget-table td {
    border: 1px solid #2b2b2b;
    padding: 4px;
    font-size: 12px;
}
</style>

<div class="container-fluid p-0">
  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
          <div class="proposal-title mb-3">
            <div class="seal">
              <?= $this->Html->image('sdo.jpeg', [
                  'pathPrefix' => 'dist/img/',
                  'alt' => 'Seal',
                  'class' => 'img-fluid'
              ]) ?>
            </div>
            <div class="small">Republic of the Philippines</div>
            <div class="sub">Department of Education</div>
            <div class="small">Region II - Cagayan Valley</div>
            <div class="sub">Schools Division of Santiago City</div>
            <div class="small mt-2">Enclosure 1</div>
            <div class="main">Activity Proposal</div>
            <div class="small">For GAS-MOOE and Centrally Managed and Funded Activities</div>
          </div>

          <table class="table proposal-table">
            <thead>
              <tr>
                <th class="section" colspan="2">Part I. Activity Details</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="label">PMIS Activity Code (AC):</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'PMIS Activity Code')) ?></td>
              </tr>
              <tr>
                <td class="label">Title of Activity:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Title of Activity')) ?></td>
              </tr>
              <tr>
                <td class="label">Proponent/s:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Proponent/s')) ?></td>
              </tr>
              <tr>
                <td class="label">Activity Schedule:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Activity Schedule')) ?></td>
              </tr>
              <tr>
                <td class="label">Venue/Modality:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Venue/Modality')) ?></td>
              </tr>
              <tr>
                <td class="label">Target Participants:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Target Participants')) ?></td>
              </tr>
              <tr>
                <td class="label">Activity Description (Justification):</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Activity Description (Justification)')) ?></td>
              </tr>
              <tr>
                <td class="label">Activity Objectives:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Activity Objectives')) ?></td>
              </tr>
              <tr>
                <td class="label">Expected Output:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Expected Output')) ?></td>
              </tr>
              <tr>
                <td class="label">Monitoring & Evaluation:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Monitoring & Evaluation')) ?></td>
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
                <td class="proposal-value"><?= h(_req_field($fields, 'Budget Requirement')) ?></td>
              </tr>
              <tr>
                <td class="label">Source of Fund:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Source of Fund')) ?></td>
              </tr>
              <tr>
                <td class="label">Expenditure Matrix:</td>
                <td>
                  <table class="table budget-table mb-0">
                    <thead>
                      <tr>
                        <th>Nature of expenditure</th>
                        <th>No.</th>
                        <th>Amount</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($matrix as $row): ?>
                        <tr>
                          <td><?= h($row['nature'] ?? '') ?></td>
                          <td><?= h($row['no'] ?? '') ?></td>
                          <td><?= h($row['amount'] ?? '') ?></td>
                          <td><?= h($row['total'] ?? '') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="label">Grand Total</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Grand Total')) ?></td>
              </tr>
            </tbody>
          </table>

          <?php if ($detailsText === ''): ?>
            <div class="alert alert-warning mb-0">No details saved for this request.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Approvals</h3>
        </div>
        <div class="card-body">
          <?php if (empty($admins)): ?>
            <p class="text-muted mb-0">No approvers found.</p>
          <?php else: ?>
            <?php foreach ($admins as $admin): ?>
              <?php
                $status = $approvalStatuses[$admin->id] ?? null;
                $badge = _approval_badge_class($status);
                $label = _approval_label($status);
              ?>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div><?= h($admin->username) ?></div>
                <span class="badge <?= h($badge) ?>"><?= h($label) ?></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
