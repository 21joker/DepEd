<?php
/**
 * templates/Requests/export_pdf.php
 *
 * Expected variables:
 * - $requestEntity
 * - $pageTitle
 */

$requestEntity = $requestEntity ?? null;
$pageTitle = $pageTitle ?? 'Activity Proposal';
$proponentName = $proponentName ?? '';
$proponentDegree = $proponentDegree ?? '';
$proponentPosition = $proponentPosition ?? '';
$proponentEsignature = $proponentEsignature ?? '';
$approverSignatures = $approverSignatures ?? [];
$proponentEsignatureSrc = '';
if ($proponentEsignature !== '') {
    $proponentEsignatureSrc = str_replace('\\', '/', (string)$proponentEsignature);
    $uploadsPos = strpos($proponentEsignatureSrc, '/uploads/');
    if ($uploadsPos !== false) {
        $proponentEsignatureSrc = ltrim(substr($proponentEsignatureSrc, $uploadsPos), '/');
    }
    $proponentEsignatureSrc = ltrim($proponentEsignatureSrc, '/');
}
$proponentEsignatureUrl = '';
if ($proponentEsignatureSrc !== '') {
    $proponentEsignatureUrl = $this->Url->build('/' . $proponentEsignatureSrc, ['fullBase' => true]);
}
if (!function_exists('_signature_url')) {
    function _signature_url($path, $urlHelper) {
        $src = str_replace('\\', '/', (string)$path);
        $uploadsPos = strpos($src, '/uploads/');
        if ($uploadsPos !== false) {
            $src = ltrim(substr($src, $uploadsPos), '/');
        }
        $src = ltrim($src, '/');
        if ($src === '') {
            return '';
        }
        return $urlHelper->build('/' . $src, ['fullBase' => true]);
    }
}
if (!function_exists('_approver_signature_urls')) {
    function _approver_signature_urls($signatures, $urlHelper) {
        $order = ['po', 'smmne', 'ao', 'budget', 'accountant'];
        $urls = [];
        foreach ($order as $key) {
            if (empty($signatures[$key])) {
                continue;
            }
            $url = _signature_url($signatures[$key], $urlHelper);
            if ($url !== '') {
                $urls[] = $url;
            }
        }
        return $urls;
    }
}

$detailsText = trim((string)($requestEntity->details ?? $requestEntity->message ?? ''));
$fields = [];
$matrix = [];
$inMatrix = false;

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
    'Attachment List of Participants',
];
$labelLookup = array_fill_keys($knownLabels, true);
$currentLabel = null;

if ($detailsText !== '') {
    $lines = preg_split("/\\r?\\n/", $detailsText);
    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line === '') {
            continue;
        }
        if (stripos($line, 'Expenditure Matrix:') === 0) {
            $inMatrix = true;
            $currentLabel = null;
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

        $matchedLabel = null;
        foreach ($knownLabels as $label) {
            $prefix = $label . ':';
            if (stripos($line, $prefix) === 0) {
                $matchedLabel = $label;
                $value = trim(substr($line, strlen($prefix)));
                $fields[$label] = $value;
                $currentLabel = $label;
                break;
            }
        }

        if ($matchedLabel === null && $currentLabel !== null && isset($labelLookup[$currentLabel])) {
            $existing = (string)($fields[$currentLabel] ?? '');
            $fields[$currentLabel] = $existing === '' ? $line : ($existing . "\n" . $line);
        }
    }
}

function _req_field(array $fields, string $label): string
{
    return $fields[$label] ?? '';
}

function _format_peso(string $value): string
{
    $raw = trim($value);
    if ($raw === '') {
        return $raw;
    }
    $normalized = preg_replace('/[^0-9.\-]/', '', $raw);
    if ($normalized === '' || $normalized === '-' || $normalized === '.') {
        return $raw;
    }
    $number = (float)$normalized;
    return '₱ ' . number_format($number, 2, '.', ',');
}
?>

<?php if (!$requestEntity): ?>
  <div style="padding:16px; font-family: Arial, sans-serif;">
    Request not found.
  </div>
  <?php return; ?>
<?php endif; ?>

<style>
* { box-sizing: border-box; }
body {
    margin: 0;
    background: #f5f5f5;
    font-family: "Times New Roman", Times, serif;
    color: #111;
}
.toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #ffffff;
    border-bottom: 1px solid #d4d4d4;
}
.toolbar h1 {
    font-size: 16px;
    margin: 0;
    font-weight: 700;
}
.toolbar .btn {
    appearance: none;
    border: 1px solid #1f2937;
    background: #1f2937;
    color: #fff;
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 6px;
    cursor: pointer;
}
.sheet {
    width: min(900px, 100%);
    margin: 16px auto;
    padding: 24px 28px 140px;
    background: #fff;
    border: 1px solid #2b2b2b;
    position: relative;
}
.proposal-title {
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
.proposal-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12px;
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
.budget-table {
    width: 100%;
    border-collapse: collapse;
}
.budget-table th,
.budget-table td {
    border: 1px solid #2b2b2b;
    padding: 4px;
    font-size: 12px;
}
.signature-block {
    text-align: center;
}
.signature-block .name {
    font-weight: 700;
    text-decoration: underline;
}
.proposal-footer {
    margin-top: 10px;
    padding-top: 6px;
}
.proposal-footer img {
    width: 100%;
    height: auto;
    display: block;
}
.footer-content {
    display: flex;
    gap: 16px;
    align-items: center;
}
.footer-logos {
    flex: 0 0 320px;
}
.footer-logos img {
    width: 100%;
    height: auto;
}
.footer-text {
    font-size: 12px;
    line-height: 1.4;
}
.footer-text a {
    color: #0b5ed7;
    text-decoration: underline;
}
.footer-text .info-row {
    display: inline-flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 4px;
}
.footer-text .info-row img {
    width: 14px;
    height: 14px;
    margin-top: 2px;
}
.footer-text .info-row.inline-row {
    align-items: center;
}
.footer-text .info-row.inline-row img {
    margin-top: 0;
}
.footer-text .info-row.inline-row > div {
    white-space: nowrap;
    display: inline-block;
}
.footer-text .info-row.inline-row a {
    white-space: nowrap;
    display: inline-block;
}
@media print {
    body { background: #fff; }
    .toolbar { display: none; }
    .sheet {
        margin: 0;
        border: none;
        width: 100%;
    }
}
</style>

<div class="toolbar">
    <h1><?= h($pageTitle) ?></h1>
    <button class="btn" type="button" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="sheet">
    <div class="proposal-title mb-3">
        <div class="seal">
            <?= $this->Html->image('deped.png', [
                'alt' => 'Seal',
                'style' => 'max-width:90px;'
            ]) ?>
        </div>
        <div class="headline">Republic of the Philippines</div>
        <div class="headline">Department of Education</div>
        <div class="small">Region II - Cagayan Valley</div>
        <div class="sub">SCHOOLS DIVISION OF SANTIAGO CITY</div>
        <div class="small" style="margin-top:8px;">Enclosure 1</div>
        <div class="main">Activity Proposal</div>
        <div class="small">For GAS-MOOE and Centrally Managed and Funded Activities</div>
    </div>

    <table class="proposal-table">
        <thead>
            <tr>
                <th class="section" colspan="2">Part I. Activity Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">PMIS Activity Code (AC):</td>
                <?php
                    $pmisCode = _req_field($fields, 'PMIS Activity Code');
                    $pmisOffice = _req_field($fields, 'PMIS Activity Office');
                    $pmisLabel = $pmisCode;
                    if ($pmisOffice !== '') {
                        $pmisLabel = trim($pmisLabel . ' (' . $pmisOffice . ')');
                    }
                ?>
                <td class="proposal-value"><?= h($pmisLabel) ?></td>
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
                <?php
                    $monitoringEval = (string)_req_field($fields, 'Monitoring & Evaluation');
                    if ($monitoringEval !== '') {
                        $monitoringEval = str_replace('ARCADIO L. MODUMO', 'ARCADIO L. MODUMO JR.', $monitoringEval);
                    }
                ?>
                <td class="proposal-value"><?= h($monitoringEval) ?></td>
            </tr>
        </tbody>
    </table>

    <table class="proposal-table">
        <thead>
            <tr>
                <th class="section" colspan="2">Part II. Financial Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">Budget Requirement:</td>
                <td class="proposal-value"><?= h(_format_peso(_req_field($fields, 'Budget Requirement'))) ?></td>
            </tr>
            <tr>
                <td class="label">Source of Fund:</td>
                <td class="proposal-value"><?= h(_req_field($fields, 'Source of Fund')) ?></td>
            </tr>
            <tr>
                <td class="label">Expenditure Matrix:</td>
                <td>
                    <table class="budget-table">
                        <thead>
                            <tr>
                                <th>Nature of expenditure</th>
                                <th>No.</th>
                                <th>Amount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($matrix)): ?>
                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($matrix as $row): ?>
                                    <tr>
                                        <td><?= h($row['nature'] ?? '') ?></td>
                                        <td><?= h($row['no'] ?? '') ?></td>
                                        <td><?= h(_format_peso((string)($row['amount'] ?? ''))) ?></td>
                                        <td><?= h(_format_peso((string)($row['total'] ?? ''))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="label">Grand Total</td>
                <td class="proposal-value"><?= h(_format_peso(_req_field($fields, 'Grand Total'))) ?></td>
            </tr>
        </tbody>
    </table>

    <table class="proposal-table">
        <thead>
            <tr>
                <th class="section" colspan="2">Part III. Review and Certification</th>
            </tr>
        </thead>
          <tbody>
              <tr>
                  <td colspan="2">
                      <div>Requested by:</div>
                      <div class="signature-block" style="margin-top: -10px; margin-bottom: 10px;">
                          <?php if ($proponentEsignatureUrl !== ''): ?>
                              <div style="margin-bottom: -20px;">
                                  <?= $this->Html->image($proponentEsignatureUrl, [
                                      'alt' => '',
                                      'style' => 'max-width: 220px; max-height: 90px; height: auto;',
                                  ]) ?>
                              </div>
                          <?php endif; ?>
                          <div class="name"><?= h($proponentName ?: 'FULL NAME OF USER') ?></div>
                          <?php if ($proponentDegree !== ''): ?>
                              <div><?= h($proponentDegree) ?></div>
                          <?php endif; ?>
                          <?php if ($proponentPosition !== ''): ?>
                              <div><?= h($proponentPosition) ?></div>
                          <?php endif; ?>
                      </div>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                      <div>Recommending Approval:</div>
                      <div class="signature-block" style="margin-top: 50px; margin-bottom: 10px;">
                          <div class="name">JACQUELINE S. RAMOS PhD, CESE</div>
                          <div>Assistant Schools Division Superintendent</div>
                          <?php
                              $approverUrls = _approver_signature_urls($approverSignatures, $this->Url);
                          ?>
                          <?php if (!empty($approverUrls)): ?>
                              <div style="margin-top: 6px;">
                                  <?php foreach ($approverUrls as $url): ?>
                                      <img src="<?= h($url) ?>" alt="" style="max-width: 50px; max-height: 60px; height: auto; margin: 2px 4px;">
                                  <?php endforeach; ?>
                              </div>
                          <?php endif; ?>
                      </div>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                      <div>Approved:</div>
                      <div class="signature-block" style="margin-top: 50px; margin-bottom: 10px;">
                          <div class="name">ALFREDO B. GUMARU JR. EdD CESO V</div>
                          <div>Schools Division Superintendent</div>
                      </div>
                  </td>
              </tr>
          </tbody>
    </table>
__________________________________________________________________________________________________________

  <div class="print-footer">
    <div class="proposal-footer" style="margin-top:0; padding-top:0;">
        <div class="footer-content">
            <div class="footer-logos">
                <?= $this->Html->image('footer.jpg', [
                    'alt' => 'Footer',
                ]) ?>
            </div>
            <div class="footer-text">
                <div class="info-row">
                    <?= $this->Html->image('location.png', ['alt' => 'Location']) ?>
                    <div>Childrens Park, Caloocan, Santiago City, 3311</div>
                </div>
                <div>
                </div>
                <div class="info-row">
                    <?= $this->Html->image('number.png', ['alt' => 'Phone']) ?>
                    <div>(078) 682-0156</div>
                </div>
                <div>
                </div>
                <div class="info-row inline-row">
                    <?= $this->Html->image('email.png', ['alt' => 'Email']) ?>
                    <div><a href="mailto:santiago.city@deped.gov.ph">santiago.city@deped.gov.ph</a></div>
                </div>
                <div class="info-row inline-row">
                    <?= $this->Html->image('link.png', ['alt' => 'Website']) ?>
                    <div><a href="https://santiagocity.deped.gov.ph">https://santiagocity.deped.gov.ph</a></div>
                </div>
                <div class="info-row">
                    <?= $this->Html->image('facebook.png', ['alt' => 'Facebook']) ?>
                    <div><a href="https://www.facebook.com/SDOsantiagoCitySCTEx">https://www.facebook.com/SDOsantiagoCitySCTEx</a></div>
                </div>
            </div>
            
        </div>
    </div>
</div>
