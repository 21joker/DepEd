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
  .print-header {
    text-align: center;
    margin-bottom: 12px;
  }
  .print-header .seal {
    max-width: 80px;
    margin: 0 auto 4px;
  }
  .print-header .headline {
    font-family: "Old English Text MT", "UnifrakturCook", "Times New Roman", serif;
    font-size: 18px;
    font-weight: 700;
    margin: 2px 0;
  }
  .print-header .sub {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .print-header .small {
    font-size: 10px;
  }
  .print-header .divider {
    border: 0;
    border-top: 2px solid #111;
    margin: 8px auto 0;
    width: 92%;
  }
  .print-footer {
    display: block;
    width: 100%;
    padding: 6px 0 0;
    background: #fff;
    margin-top: 10px;
  }
  .footer-content {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    justify-content: center;
    width: 760px;
    margin: 50px auto 0;
  }
  .footer-logos {
    flex: 0 0 260px;
  }
  .footer-logos img {
    width: 100%;
    height: auto;
    display: block;
  }
  .footer-text {
    font-size: 12px;
    line-height: 1.2;
    text-align: left;
  }
  .footer-text a {
    color: #0b5ed7;
    text-decoration: underline;
  }
  .footer-text .info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
  }
  .footer-text .info-row img {
    width: 14px;
    height: 14px;
    margin-top: 0;
  }
  .footer-text .info-row > div {
    white-space: nowrap;
    display: inline-block;
  }
  .footer-text .info-row a {
    white-space: nowrap;
    display: inline-block;
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
    .sheet { margin: 0; border: none; width: 100%; padding-bottom: 24px; }
    .print-header { position: static; }
  }
</style>

<div class="toolbar">
  <h1><?= h($pageTitle) ?></h1>
  <button class="btn" type="button" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="sheet">
  <div class="print-header">
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
    <hr class="divider">
  </div>
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
        <th>WFP</th>
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
          <td class="wrap"><?= h($summary['wfp_code'] ?? '') ?></td>
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

  <div class="print-footer">
    <hr style="border:0; border-top:1px solid #2b2b2b; margin:0 0 6px;">
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
        <div class="info-row">
          <?= $this->Html->image('number.png', ['alt' => 'Phone']) ?>
          <div>(078) 682-0156</div>
        </div>
      <div class="info-row inline-row">
        <?= $this->Html->image('email.png', ['alt' => 'Email']) ?>
        <div><a href="mailto:santiago.city@deped.gov.ph">santiago.city@deped.gov.ph</a></div>
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
