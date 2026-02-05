<?php
/**
 * templates/Requests/export_all_pdf.php
 *
 * Exports the pending list table to PDF (browser print).
 *
 * Variables:
 * - $requests
 * - $requestSummaries
 * - $adminApprovalStatus
 * - $pageTitle
 */
$requests = $requests ?? [];
$requestSummaries = $requestSummaries ?? [];
$adminApprovalStatus = $adminApprovalStatus ?? [];
$pageTitle = $pageTitle ?? 'All Requests';
?>
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
    width: min(1100px, 100%);
    margin: 16px auto;
    padding: 18px 20px 120px;
    background: #fff;
    border: 1px solid #2b2b2b;
  }
  .print-footer {
    display: block;
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    padding: 6px 28px 0;
    background: #fff;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }
  th, td {
    border: 1px solid #2b2b2b;
    padding: 4px 6px;
    vertical-align: top;
  }
  th {
    background: #e6e6e6;
    font-weight: 700;
  }
  .wrap {
    word-break: break-word;
  }
  @media print {
    body { background: #fff; }
    .toolbar { display: none; }
    .sheet { margin: 0; border: none; width: 100%; padding-bottom: 120px; }
  }
</style>

<div class="toolbar">
  <h1><?= h($pageTitle) ?></h1>
  <button class="btn" type="button" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="sheet">
  <table>
    <thead>
      <tr>
        <th style="width: 12%;">Name</th>
        <th>AC</th>
        <th>Title of Activity</th>
        <th>Activity Schedule</th>
        <th>Budget Requirement</th>
        <th>Source of Fund</th>
        <th>Grand Total</th>
        <th>SUB-ARO</th>
        <th>S/WFP</th>
        <th>AR</th>
        <th>ATC</th>
        <th>Participants</th>
        <th>Submitted</th>
        <th>Last Updated</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $request): ?>
        <?php
          $summary = $requestSummaries[$request->id] ?? [];
          $name = (string)($request->name ?? '');
          if ($name === '') {
            $name = (string)($request->display_name ?? $request->email ?? '');
          }
          $status = $adminApprovalStatus[$request->id] ?? 'pending';
          $label = $status === 'approved' ? 'Approved' : ($status === 'declined' ? 'Review' : 'Pending');
        ?>
        <tr>
          <td class="wrap"><?= h($name) ?></td>
          <td class="wrap"><?= h($summary['pmis_activity_code'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['title_of_activity'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['activity_schedule'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['budget_requirement'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['source_of_fund'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['grand_total'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['attachment_sub_aro'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['attachment_sfwp'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['attachment_ar'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['attachment_ac'] ?? '') ?></td>
          <td class="wrap"><?= h($summary['attachment_list_participants'] ?? '') ?></td>
          <td class="wrap"><?= h($request->created_at ?? $request->created ?? '') ?></td>
          <td class="wrap"><?= h($request->updated_at ?? $request->modified ?? $request->created_at ?? $request->created ?? '') ?></td>
          <td class="wrap"><?= h($label) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

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
