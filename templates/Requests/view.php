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
$statusOnly = $statusOnly ?? false;
$remarksList = $remarksList ?? [];

$detailsSource = $requestEntity->details;
if ($detailsSource === null || trim((string)$detailsSource) === '') {
    $detailsSource = $requestEntity->message ?? '';
}
$detailsText = trim((string)$detailsSource);
$fields = [];
$matrix = [];
$inMatrix = false;
$currentLabel = null;
$knownLabels = [
    'PMIS Activity Code',
    'Title of Activity',
    'Proponent/s',
    'Activity Schedule',
    'Venue/Modality',
    'Target Participants',
    'Activity Description (Justification)',
    'Activity Objectives',
    'Expected Output',
    'Monitoring & Evaluation',
    'Budget Requirement',
    'Source of Fund',
    'Grand Total',
    'Attachment SUB-ARO',
    'Attachment SFWP',
    'Attachment AR',
    'Attachment AC',
];

if ($detailsText !== '') {
    $lines = preg_split("/\\r\\n|\\n|\\r/", $detailsText);
    foreach ($lines as $line) {
        $rawLine = (string)$line;
        $trimLine = trim($rawLine);
        if ($trimLine === '') {
            if ($currentLabel !== null && !$inMatrix) {
                $fields[$currentLabel] = rtrim($fields[$currentLabel] . "\n");
            }
            continue;
        }
        if (stripos($trimLine, 'Expenditure Matrix:') === 0) {
            $inMatrix = true;
            continue;
        }
        if ($inMatrix) {
            if (strpos($trimLine, '- ') === 0) {
                $row = substr($trimLine, 2);
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

        $matchedLabel = null;
        foreach ($knownLabels as $label) {
            $prefix = $label . ':';
            if (stripos($trimLine, $prefix) === 0) {
                $matchedLabel = $label;
                $value = ltrim(substr($trimLine, strlen($prefix)));
                $fields[$label] = $value;
                $currentLabel = $label;
                break;
            }
        }
        if ($matchedLabel !== null) {
            continue;
        }

        if ($currentLabel !== null) {
            $fields[$currentLabel] = rtrim($fields[$currentLabel] . "\n" . $rawLine);
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
        'declined' => 'badge-danger',
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
    height: auto;
    overflow: visible;
    white-space: normal;
}
.proposal-table {
    table-layout: auto;
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
    word-break: break-word;
    overflow-wrap: anywhere;
    display: block;
    width: 100%;
    max-height: none;
    height: auto;
}
.budget-table th,
.budget-table td {
    border: 1px solid #2b2b2b;
    padding: 4px;
    font-size: 12px;
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

<div class="container-fluid p-0">
  <div class="row">
    <?php if (!$statusOnly): ?>
    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
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

          <div class="proposal-footer">
            <?= $this->Html->image('footer.jpg', [
                'alt' => 'Footer',
            ]) ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="col-lg-<?= $statusOnly ? '12' : '5' ?>">
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
      <?php if (empty($remarksList) && !empty($approvals)): ?>
        <?php
          foreach ($approvals as $approval) {
              $remark = trim((string)($approval->remarks ?? ''));
              if ($remark === '') {
                  continue;
              }
              $reviewer = $approval->user->username ?? null;
              $remarksList[] = [
                  'name' => $reviewer ?: 'Reviewer',
                  'remark' => $remark,
                  'created' => $approval->created ?? null,
              ];
          }
        ?>
      <?php endif; ?>
      <?php if (!empty($remarksList)): ?>
        <div class="card mt-3">
          <div class="card-header">
            <h3 class="card-title">Remarks</h3>
          </div>
          <div class="card-body">
            <?php foreach ($remarksList as $item): ?>
              <?php
                $timeLabel = '';
                if ($item['created'] instanceof \Cake\I18n\FrozenTime) {
                    $timeLabel = $item['created']->i18nFormat('MM/dd/yyyy h:mm a');
                } elseif ($item['created'] instanceof \DateTimeInterface) {
                    $timeLabel = $item['created']->format('m/d/Y h:i a');
                } elseif (!empty($item['created'])) {
                    $timeLabel = trim((string)$item['created']);
                }
              ?>
              <div class="mb-2">
                <strong><?= h($item['name']) ?>:</strong>
                <?= nl2br(h($item['remark'])) ?>
                <?php if ($timeLabel !== ''): ?>
                  <span class="text-muted small ml-2">(<?= h($timeLabel) ?>)</span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="card mt-3">
          <div class="card-header">
            <h3 class="card-title">Remarks</h3>
          </div>
          <div class="card-body">
            <p class="text-muted mb-0">No remarks yet.</p>
          </div>
        </div>
      <?php endif; ?>

      <?php
        $attachmentMap = [
            'SUB-ARO' => 'Attachment SUB-ARO',
            'S/WFP' => 'Attachment SFWP',
            'AR' => 'Attachment AR',
            'AC' => 'Attachment AC',
        ];
        $attachmentItems = [];
        foreach ($attachmentMap as $label => $fieldKey) {
            $filename = trim((string)($fields[$fieldKey] ?? ''));
            if ($filename === '') {
                continue;
            }
            $safeName = basename($filename);
            $filePath = WWW_ROOT . 'uploads' . DS . 'requests' . DS . (int)$requestEntity->id . DS . $safeName;
            $fileUrl = $this->Url->build('/uploads/requests/' . (int)$requestEntity->id . '/' . $safeName);
            $attachmentItems[] = [
                'label' => $label,
                'name' => $safeName,
                'url' => $fileUrl,
                'exists' => is_file($filePath),
            ];
        }
      ?>
      <div class="card mt-3">
        <div class="card-header">
          <h3 class="card-title">Attachments (PDF)</h3>
        </div>
        <div class="card-body">
          <?php if (empty($attachmentItems)): ?>
            <p class="text-muted mb-0">No attachments uploaded.</p>
          <?php else: ?>
            <?php foreach ($attachmentItems as $item): ?>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                  <strong><?= h($item['label']) ?>:</strong>
                  <?php if ($item['exists']): ?>
                    <a href="<?= h($item['url']) ?>" target="_blank" rel="noopener">
                      <?= h($item['name']) ?>
                    </a>
                  <?php else: ?>
                    <?= h($item['name']) ?>
                    <span class="text-danger small ml-2">(missing file)</span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
