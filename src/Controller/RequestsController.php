<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Database\Expression\IdentifierExpression;
use Cake\I18n\FrozenTime;

/**
 * Requests Controller
 *
 * @property \App\Model\Table\RequestsTable $Requests
 */
class RequestsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'exportPdf']);
    }

    public function add()
    {
        $this->viewBuilder()->setLayout($this->Auth->user() ? 'default' : 'login');
        $requestEntity = $this->Requests->newEmptyEntity();
        $this->loadModel('Users');

        if ($this->request->is('post')) {
            $formData = $this->request->getData();
            $venueChoice = trim((string)($formData['venue_modality_choice'] ?? ''));
            $venueDetails = trim((string)($formData['venue_modality_details'] ?? ''));
            $venueCombined = $venueChoice;
            if ($venueDetails !== '') {
                $venueCombined .= ($venueCombined !== '' ? ' - ' : '') . $venueDetails;
            }
            if ($venueCombined !== '') {
                $formData['venue_modality'] = $venueCombined;
            }
            $requestEntity = $this->Requests->patchEntity($requestEntity, $formData);
            $attachmentUploads = $this->prepareAttachmentUploads($formData);
            $rawName = trim((string)$this->request->getData('name'));
            $rawEmail = trim((string)$this->request->getData('email'));
            if ($rawName === '' && !empty($formData['proponents'])) {
                $rawName = trim((string)$formData['proponents']);
            }
            if ($rawName === '') {
                $authId = (int)$this->Auth->user('id');
                if ($authId) {
                    try {
                        $this->loadModel('Students');
                        $student = $this->Students->find()
                            ->select(['firstname', 'middlename', 'lastname', 'email'])
                            ->where(['user_id' => $authId])
                            ->first();
                        if ($student) {
                            $parts = array_filter([
                                $student->firstname ?? null,
                                $student->middlename ?? null,
                                $student->lastname ?? null,
                            ]);
                            $fullName = trim(implode(' ', $parts));
                            if ($fullName !== '') {
                                $rawName = $fullName;
                            }
                            if ($rawEmail === '' && !empty($student->email)) {
                                $rawEmail = (string)$student->email;
                            }
                        }
                    } catch (\Throwable $e) {
                        // Ignore if students table isn't available.
                    }
                }
            }
            $requestEntity->name = $rawName;
            $requestEntity->email = $rawEmail;
            $requestEntity->status = 'pending';
            $requestEntity->approvals_count = 0;

            $title = trim((string)($formData['title_of_activity'] ?? $formData['title'] ?? ''));
            if ($title === '') {
                $title = 'Activity Proposal';
            }
            $requestEntity->title = $title;
            $requestEntity->subject = $title;

            $detailsLines = [];
            $detailsMap = [
                'PMIS Activity Code' => 'pmis_activity_code',
                'Title of Activity' => 'title_of_activity',
                'Proponent/s' => 'proponents',
                'Venue/Modality' => 'venue_modality',
                'Target Participants' => 'target_participants',
                'Activity Description (Justification)' => 'activity_description',
                'Activity Objectives' => 'activity_objectives',
                'Expected Output' => 'expected_output',
                'Monitoring & Evaluation' => 'monitoring_evaluation',
                'Budget Requirement' => 'budget_requirement',
                'Source of Fund' => 'source_of_fund',
                'Grand Total' => 'grand_total',
            ];

            foreach ($detailsMap as $label => $key) {
                $rawValue = $formData[$key] ?? '';
                if (is_array($rawValue)) {
                    $value = trim(implode(', ', array_filter(array_map('trim', $rawValue), 'strlen')));
                } else {
                    $value = trim((string)$rawValue);
                }
                if ($value !== '') {
                    $detailsLines[] = $label . ': ' . $value;
                }
            }

            $scheduleFrom = trim((string)($formData['activity_schedule_from'] ?? ''));
            $scheduleTo = trim((string)($formData['activity_schedule_to'] ?? ''));
            if ($scheduleFrom !== '' || $scheduleTo !== '') {
                $detailsLines[] = 'Activity Schedule: ' . trim($scheduleFrom . ' - ' . $scheduleTo, ' -');
            }
            foreach ($attachmentUploads as $label => $upload) {
                if (!empty($upload['filename'])) {
                    $detailsLines[] = $label . ': ' . $upload['filename'];
                }
            }

            $nature = $formData['expenditure_nature'] ?? [];
            $count = $formData['expenditure_no'] ?? [];
            $amount = $formData['expenditure_amount'] ?? [];
            $total = $formData['expenditure_total'] ?? [];
            $matrixRows = [];
            $rows = max(count((array)$nature), count((array)$count), count((array)$amount), count((array)$total));
            for ($i = 0; $i < $rows; $i++) {
                $rowNature = trim((string)($nature[$i] ?? ''));
                $rowCount = trim((string)($count[$i] ?? ''));
                $rowAmount = trim((string)($amount[$i] ?? ''));
                $rowTotal = trim((string)($total[$i] ?? ''));
                if ($rowTotal === '' && ($rowCount !== '' || $rowAmount !== '')) {
                    $countValue = (float)preg_replace('/[^0-9.\-]/', '', $rowCount);
                    $amountValue = (float)preg_replace('/[^0-9.\-]/', '', $rowAmount);
                    if ($countValue || $amountValue) {
                        $rowTotal = (string)($countValue * $amountValue);
                    }
                }
                if ($rowNature === '' && $rowCount === '' && $rowAmount === '' && $rowTotal === '') {
                    continue;
                }
                $matrixRows[] = sprintf(
                    '- %s | No: %s | Amount: %s | Total: %s',
                    $rowNature,
                    $rowCount,
                    $rowAmount,
                    $rowTotal
                );
            }
            if (!empty($matrixRows)) {
                $detailsLines[] = 'Expenditure Matrix:';
                $detailsLines = array_merge($detailsLines, $matrixRows);
            }

            $detailsText = trim(implode("\n", $detailsLines));
            if ($detailsText !== '') {
                $requestEntity->details = $detailsText;
                $requestEntity->message = $detailsText;
            }
            $authId = (int)$this->Auth->user('id');
            if ($authId) {
                $requestEntity->user_id = $authId;
            }

        $adminCount = $this->Users->find()
            ->where(['role IN' => ['Administrator', 'Approver']])
            ->count();
            $requestEntity->approvals_needed = $adminCount;

            if ($this->Requests->save($requestEntity)) {
                $this->Requests->updateAll(
                    [
                        'status' => 'pending',
                        'approvals_count' => 0,
                        'approvals_needed' => $adminCount,
                        'user_id' => $authId ?: null,
                    ],
                    ['id' => $requestEntity->id]
                );
                $this->storeAttachmentUploads($attachmentUploads, (int)$requestEntity->id);
                if ($rawName !== '' || $rawEmail !== '') {
                    $this->Requests->updateAll(
                        [
                            'name' => $rawName !== '' ? $rawName : null,
                            'email' => $rawEmail !== '' ? $rawEmail : null,
                        ],
                        ['id' => $requestEntity->id]
                    );
                }
                $this->request->getSession()->write('last_request_id', $requestEntity->id);
                $this->notifyAdmins($requestEntity->id);
                $this->Flash->success('Request submitted. Pending approval by all admins.');
                return $this->redirect(['action' => 'add']);
            }

            $this->Flash->error('Failed to submit request.');
        }

        $this->loadModel('Users');
        $this->loadModel('RequestApprovals');

        $admins = $this->Users->find()
            ->where(['role IN' => ['Administrator', 'Approver']])
            ->orderAsc('id')
            ->all();
        $admins = $this->orderApproverHierarchy($admins);

        $lastRequestId = $this->request->getSession()->read('last_request_id');
        $lastRequest = null;
        $approvalStatuses = [];
        $approvalRemarks = [];
        $approvalMeta = [];
        $authId = (int)$this->Auth->user('id');
        $userRequests = [];
        $requestSummaries = [];
        $declinedRequestIds = [];
        $requestStatusById = [];
        $showForm = $this->request->is(['post', 'put', 'patch']) || $requestEntity->hasErrors();
        $isEdit = false;

        if ($authId) {
            $lastRequest = $this->Requests->find()
                ->where(['user_id' => $authId])
                ->orderDesc('created_at')
                ->orderDesc('id')
                ->first();
            $userRequests = $this->Requests->find()
                ->where(['user_id' => $authId])
                ->orderDesc('created_at')
                ->orderDesc('id')
                ->all()
                ->toArray();
            foreach ($userRequests as $request) {
                $detailsSource = $request->details;
                if ($detailsSource === null || trim((string)$detailsSource) === '') {
                    $detailsSource = $request->message ?? '';
                }
                $fields = $this->extractFieldsFromDetails((string)$detailsSource);
                $requestSummaries[$request->id] = [
                    'pmis_activity_code' => $fields['PMIS Activity Code'] ?? '',
                    'title_of_activity' => $fields['Title of Activity'] ?? ($request->title ?? ''),
                    'activity_schedule' => $fields['Activity Schedule'] ?? '',
                    'budget_requirement' => $fields['Budget Requirement'] ?? '',
                    'source_of_fund' => $fields['Source of Fund'] ?? '',
                    'grand_total' => $fields['Grand Total'] ?? '',
                    'attachment_sub_aro' => $fields['Attachment SUB-ARO'] ?? '',
                    'attachment_sfwp' => $fields['Attachment SFWP'] ?? '',
                    'attachment_ar' => $fields['Attachment AR'] ?? '',
                    'attachment_ac' => $fields['Attachment AC'] ?? '',
                ];
            }
            $requestIds = array_map('intval', array_map(function ($request) {
                return $request->id ?? 0;
            }, $userRequests));
            $requestIds = array_values(array_filter($requestIds));
            if (!empty($requestIds)) {
                try {
                    $this->loadModel('RequestApprovals');
                    $declinedRequestIds = [];
                    $approvalRows = $this->RequestApprovals->find()
                        ->select(['request_id', 'status'])
                        ->where(['request_id IN' => $requestIds])
                        ->enableHydration(false)
                        ->all();
                    foreach ($approvalRows as $row) {
                        $status = strtolower(trim((string)($row['status'] ?? '')));
                        if ($status === 'declined') {
                            $declinedRequestIds[] = (int)($row['request_id'] ?? 0);
                        }
                    }
                    $declinedRequestIds = array_values(array_unique(array_filter($declinedRequestIds)));
                } catch (\Throwable $e) {
                    $declinedRequestIds = [];
                }
            }
            $declinedLookup = !empty($declinedRequestIds)
                ? array_fill_keys($declinedRequestIds, true)
                : [];
            foreach ($userRequests as $request) {
                $requestId = (int)($request->id ?? 0);
                if (!$requestId) {
                    continue;
                }
                $isDeclined = isset($declinedLookup[$requestId]);
                $isApproved = in_array($request->status ?? null, ['approved', 'Approved'], true)
                    || ((int)($request->approvals_needed ?? 0) > 0
                        && (int)($request->approvals_count ?? 0) >= (int)($request->approvals_needed ?? 0));
                if ($isDeclined) {
                    $requestStatusById[$requestId] = 'declined';
                } elseif ($isApproved) {
                    $requestStatusById[$requestId] = 'approved';
                } else {
                    $requestStatusById[$requestId] = 'pending';
                }
            }
        } elseif ($lastRequestId) {
            $lastRequest = $this->Requests->find()
                ->where(['id' => $lastRequestId])
                ->first();
        }

        if ($lastRequest) {
            $approvalRows = $this->RequestApprovals->find()
                ->select(['admin_user_id', 'status', 'remarks', 'created'])
                ->where(['request_id' => $lastRequest->id])
                ->enableHydration(false)
                ->all();
            foreach ($approvalRows as $row) {
                $adminKey = (int)($row['admin_user_id'] ?? 0);
                if (!$adminKey) {
                    continue;
                }
                $approvalStatuses[$adminKey] = $row['status'] ?? null;
                if (!empty($row['remarks'])) {
                    $approvalRemarks[$adminKey] = (string)$row['remarks'];
                }
                if (!empty($row['created'])) {
                    $approvalMeta[$adminKey]['created'] = $row['created'];
                }
            }
        }

        $this->set(compact(
            'requestEntity',
            'admins',
            'lastRequest',
            'approvalStatuses',
            'approvalRemarks',
            'approvalMeta',
            'userRequests',
            'requestSummaries',
            'declinedRequestIds',
            'requestStatusById',
            'showForm',
            'isEdit'
        ));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout($this->Auth->user() ? 'default' : 'login');

        if (!$id) {
            $this->Flash->error('Request not found.');
            return $this->redirect(['action' => 'add']);
        }

        $requestEntity = $this->Requests->get($id);
        $authId = (int)$this->Auth->user('id');
        $sessionRequestId = (int)$this->request->getSession()->read('last_request_id');

        if ($authId && (int)$requestEntity->user_id !== $authId) {
            $this->Flash->error('You are not allowed to edit this request.');
            return $this->redirect(['action' => 'add']);
        }
        if (!$authId && $sessionRequestId && (int)$requestEntity->id !== $sessionRequestId) {
            $this->Flash->error('You are not allowed to edit this request.');
            return $this->redirect(['action' => 'add']);
        }

        $isDeclined = ($requestEntity->status ?? '') === 'declined';
        if (!$isDeclined) {
            try {
                $this->loadModel('RequestApprovals');
                $isDeclined = $this->RequestApprovals->find()
                    ->where([
                        'request_id' => $requestEntity->id,
                        'status' => 'declined',
                    ])
                    ->count() > 0;
            } catch (\Throwable $e) {
                // Fallback to status column only.
            }
        }
        $isLocked = in_array($requestEntity->status, ['approved', 'Approved'], true)
            || ((int)$requestEntity->approvals_count > 0 && !$isDeclined);
        if ($isLocked) {
            $this->Flash->error('This request can no longer be edited.');
            return $this->redirect(['action' => 'add']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $formData = $this->request->getData();
            $venueChoice = trim((string)($formData['venue_modality_choice'] ?? ''));
            $venueDetails = trim((string)($formData['venue_modality_details'] ?? ''));
            $venueCombined = $venueChoice;
            if ($venueDetails !== '') {
                $venueCombined .= ($venueCombined !== '' ? ' - ' : '') . $venueDetails;
            }
            if ($venueCombined !== '') {
                $formData['venue_modality'] = $venueCombined;
            }
            $requestEntity = $this->Requests->patchEntity($requestEntity, $formData);
            $attachmentUploads = $this->prepareAttachmentUploads($formData);
            $detailsSource = $requestEntity->details;
            if ($detailsSource === null || trim((string)$detailsSource) === '') {
                $detailsSource = $requestEntity->message ?? '';
            }
            $existingFields = $this->extractFieldsFromDetails((string)$detailsSource);

            $rawName = trim((string)$this->request->getData('name'));
            $rawEmail = trim((string)$this->request->getData('email'));
            if ($rawName === '' && !empty($formData['proponents'])) {
                $rawName = trim((string)$formData['proponents']);
            }
            $requestEntity->name = $rawName;
            $requestEntity->email = $rawEmail;

            $title = trim((string)($formData['title_of_activity'] ?? $formData['title'] ?? ''));
            if ($title === '') {
                $title = 'Activity Proposal';
            }
            $requestEntity->title = $title;
            $requestEntity->subject = $title;

            $detailsLines = [];
            $detailsMap = [
                'PMIS Activity Code' => 'pmis_activity_code',
                'Title of Activity' => 'title_of_activity',
                'Proponent/s' => 'proponents',
                'Venue/Modality' => 'venue_modality',
                'Target Participants' => 'target_participants',
                'Activity Description (Justification)' => 'activity_description',
                'Activity Objectives' => 'activity_objectives',
                'Expected Output' => 'expected_output',
                'Monitoring & Evaluation' => 'monitoring_evaluation',
                'Budget Requirement' => 'budget_requirement',
                'Source of Fund' => 'source_of_fund',
                'Grand Total' => 'grand_total',
            ];

            foreach ($detailsMap as $label => $key) {
                $rawValue = $formData[$key] ?? '';
                if (is_array($rawValue)) {
                    $value = trim(implode(', ', array_filter(array_map('trim', $rawValue), 'strlen')));
                } else {
                    $value = trim((string)$rawValue);
                }
                if ($value !== '') {
                    $detailsLines[] = $label . ': ' . $value;
                }
            }

            $scheduleFrom = trim((string)($formData['activity_schedule_from'] ?? ''));
            $scheduleTo = trim((string)($formData['activity_schedule_to'] ?? ''));
            if ($scheduleFrom !== '' || $scheduleTo !== '') {
                $detailsLines[] = 'Activity Schedule: ' . trim($scheduleFrom . ' - ' . $scheduleTo, ' -');
            }
            $attachmentLabels = [
                'Attachment SUB-ARO',
                'Attachment SFWP',
                'Attachment AR',
                'Attachment AC',
            ];
            foreach ($attachmentLabels as $label) {
                if (!empty($attachmentUploads[$label]['filename'])) {
                    $detailsLines[] = $label . ': ' . $attachmentUploads[$label]['filename'];
                } elseif (!empty($existingFields[$label])) {
                    $detailsLines[] = $label . ': ' . $existingFields[$label];
                }
            }

            $nature = $formData['expenditure_nature'] ?? [];
            $count = $formData['expenditure_no'] ?? [];
            $amount = $formData['expenditure_amount'] ?? [];
            $total = $formData['expenditure_total'] ?? [];
            $matrixRows = [];
            $rows = max(count((array)$nature), count((array)$count), count((array)$amount), count((array)$total));
            for ($i = 0; $i < $rows; $i++) {
                $rowNature = trim((string)($nature[$i] ?? ''));
                $rowCount = trim((string)($count[$i] ?? ''));
                $rowAmount = trim((string)($amount[$i] ?? ''));
                $rowTotal = trim((string)($total[$i] ?? ''));
                if ($rowTotal === '' && ($rowCount !== '' || $rowAmount !== '')) {
                    $countValue = (float)preg_replace('/[^0-9.\-]/', '', $rowCount);
                    $amountValue = (float)preg_replace('/[^0-9.\-]/', '', $rowAmount);
                    if ($countValue || $amountValue) {
                        $rowTotal = (string)($countValue * $amountValue);
                    }
                }
                if ($rowNature === '' && $rowCount === '' && $rowAmount === '' && $rowTotal === '') {
                    continue;
                }
                $matrixRows[] = sprintf(
                    '- %s | No: %s | Amount: %s | Total: %s',
                    $rowNature,
                    $rowCount,
                    $rowAmount,
                    $rowTotal
                );
            }
            if (!empty($matrixRows)) {
                $detailsLines[] = 'Expenditure Matrix:';
                $detailsLines = array_merge($detailsLines, $matrixRows);
            }

            $detailsText = trim(implode("\n", $detailsLines));
            if ($detailsText !== '') {
                $requestEntity->details = $detailsText;
                $requestEntity->message = $detailsText;
            }

            if ($this->Requests->save($requestEntity)) {
                $this->storeAttachmentUploads($attachmentUploads, (int)$requestEntity->id);
                $this->Flash->success('Request updated.');
                return $this->redirect(['action' => 'add']);
            }

            $this->Flash->error('Failed to update request.');
        }

        $detailsSource = $requestEntity->details;
        if ($detailsSource === null || trim((string)$detailsSource) === '') {
            $detailsSource = $requestEntity->message ?? '';
        }
        $detailsText = trim((string)$detailsSource);
        $fields = [];
        $matrix = [];
        $inMatrix = false;
        if ($detailsText !== '') {
            $lines = preg_split("/\\r\\n|\\n|\\r/", $detailsText);
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

        $requestEntity->set('pmis_activity_code', $fields['PMIS Activity Code'] ?? '');
        $requestEntity->set('title_of_activity', $fields['Title of Activity'] ?? '');
        $requestEntity->set('proponents', $fields['Proponent/s'] ?? '');
        $requestEntity->set('venue_modality', $fields['Venue/Modality'] ?? '');
        $requestEntity->set('target_participants', $fields['Target Participants'] ?? '');
        $requestEntity->set('activity_description', $fields['Activity Description (Justification)'] ?? '');
        $requestEntity->set('activity_objectives', $fields['Activity Objectives'] ?? '');
        $requestEntity->set('expected_output', $fields['Expected Output'] ?? '');
        $requestEntity->set('monitoring_evaluation', $fields['Monitoring & Evaluation'] ?? '');
        $requestEntity->set('budget_requirement', $fields['Budget Requirement'] ?? '');
        $requestEntity->set('grand_total', $fields['Grand Total'] ?? '');

        $schedule = $fields['Activity Schedule'] ?? '';
        if ($schedule !== '') {
            $parts = array_map('trim', explode(' - ', $schedule));
            $requestEntity->set('activity_schedule_from', $parts[0] ?? '');
            $requestEntity->set('activity_schedule_to', $parts[1] ?? '');
        }

        $funds = $fields['Source of Fund'] ?? '';
        $selectedFunds = array_filter(array_map('trim', explode(',', $funds)), 'strlen');

        $requestEntity->set('expenditure_nature', array_column($matrix, 'nature'));
        $requestEntity->set('expenditure_no', array_column($matrix, 'no'));
        $requestEntity->set('expenditure_amount', array_column($matrix, 'amount'));
        $requestEntity->set('expenditure_total', array_column($matrix, 'total'));

        $this->loadModel('Users');
        $this->loadModel('RequestApprovals');

        $admins = $this->Users->find()
            ->where(['role IN' => ['Administrator', 'Approver']])
            ->orderAsc('id')
            ->all();
        $admins = $this->orderApproverHierarchy($admins);

        $userRequests = [];
        $requestSummaries = [];
        if ($authId) {
            $userRequests = $this->Requests->find()
                ->where(['user_id' => $authId])
                ->orderDesc('created_at')
                ->orderDesc('id')
                ->all();
            foreach ($userRequests as $request) {
                $detailsSource = $request->details;
                if ($detailsSource === null || trim((string)$detailsSource) === '') {
                    $detailsSource = $request->message ?? '';
                }
                $fields = $this->extractFieldsFromDetails((string)$detailsSource);
                $requestSummaries[$request->id] = [
                    'pmis_activity_code' => $fields['PMIS Activity Code'] ?? '',
                    'title_of_activity' => $fields['Title of Activity'] ?? ($request->title ?? ''),
                    'activity_schedule' => $fields['Activity Schedule'] ?? '',
                    'budget_requirement' => $fields['Budget Requirement'] ?? '',
                    'source_of_fund' => $fields['Source of Fund'] ?? '',
                    'grand_total' => $fields['Grand Total'] ?? '',
                    'attachment_sub_aro' => $fields['Attachment SUB-ARO'] ?? '',
                    'attachment_sfwp' => $fields['Attachment SFWP'] ?? '',
                    'attachment_ar' => $fields['Attachment AR'] ?? '',
                    'attachment_ac' => $fields['Attachment AC'] ?? '',
                ];
            }
        }

        $lastRequest = $requestEntity;
        $approvalStatuses = [];
        $approvalRemarks = [];
        $approvalMeta = [];
        if ($lastRequest) {
            $approvalRows = $this->RequestApprovals->find()
                ->select(['admin_user_id', 'status', 'remarks', 'created'])
                ->where(['request_id' => $lastRequest->id])
                ->enableHydration(false)
                ->all();
            foreach ($approvalRows as $row) {
                $adminKey = (int)($row['admin_user_id'] ?? 0);
                if (!$adminKey) {
                    continue;
                }
                $approvalStatuses[$adminKey] = $row['status'] ?? null;
                if (!empty($row['remarks'])) {
                    $approvalRemarks[$adminKey] = (string)$row['remarks'];
                }
                if (!empty($row['created'])) {
                    $approvalMeta[$adminKey]['created'] = $row['created'];
                }
            }
        }

        $showForm = true;
        $isEdit = true;
        $this->set(compact(
            'requestEntity',
            'admins',
            'lastRequest',
            'approvalStatuses',
            'approvalRemarks',
            'approvalMeta',
            'selectedFunds',
            'userRequests',
            'requestSummaries',
            'showForm',
            'isEdit'
        ));
        $this->render('add');
    }

    public function pending()
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $adminId = (int)$this->Auth->user('id');
        $hiddenRequestIds = $this->getHiddenRequestIds($adminId);
        $counts = $this->buildCounts($adminId, $hiddenRequestIds);

        $requests = $this->Requests->find()
            ->where(['status !=' => 'deleted'])
            ->orderDesc('created_at')
            ->orderDesc('id')
            ->all();
        if (!empty($hiddenRequestIds)) {
            $requests = $this->Requests->find()
                ->where(['status !=' => 'deleted'])
                ->where(['id NOT IN' => $hiddenRequestIds])
                ->orderDesc('created_at')
                ->orderDesc('id')
                ->all();
        }

        $emails = [];
        foreach ($requests as $request) {
            if (empty($request->name) && !empty($request->email)) {
                $emails[] = $request->email;
            }
        }

        if ($emails) {
            try {
                $this->loadModel('Students');
                $students = $this->Students->find()
                    ->select(['email', 'firstname', 'middlename', 'lastname'])
                    ->where(['email IN' => array_values(array_unique($emails))])
                    ->all();

                $emailToName = [];
                foreach ($students as $student) {
                    $parts = array_filter([
                        $student->firstname ?? null,
                        $student->middlename ?? null,
                        $student->lastname ?? null,
                    ]);
                    $fullName = trim(implode(' ', $parts));
                    if ($fullName !== '') {
                        $emailToName[$student->email] = $fullName;
                    }
                }

                foreach ($requests as $request) {
                    if (empty($request->name) && !empty($request->email)) {
                        $request->display_name = $emailToName[$request->email] ?? null;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore if students table isn't available.
            }
        }

        $adminApprovalStatus = [];
        $adminApprovalMeta = [];
        if ($adminId > 0 && !empty($requests)) {
            try {
                $this->loadModel('RequestApprovals');
                $requestIds = [];
                foreach ($requests as $req) {
                    if (!empty($req->id)) {
                        $requestIds[] = $req->id;
                    }
                }
                if (!empty($requestIds)) {
                    $rows = $this->RequestApprovals->find()
                        ->select(['request_id', 'status', 'created'])
                        ->where([
                            'request_id IN' => $requestIds,
                            'admin_user_id' => $adminId,
                        ])
                        ->enableHydration(false)
                        ->all()
                        ->toArray();
                    foreach ($rows as $row) {
                        $rid = (int)($row['request_id'] ?? 0);
                        if (!$rid) {
                            continue;
                        }
                        $adminApprovalStatus[$rid] = $row['status'] ?? null;
                        $adminApprovalMeta[$rid] = [
                            'status' => $row['status'] ?? null,
                            'created' => $row['created'] ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Ignore if approvals table isn't available.
            }
        }

        $pageTitle = 'Pending Requests';
        $headerBadge = 'Note: All Activities needs approver approval';
        $viewType = 'pending';
        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }
        unset($counts['deleted']);
        $this->set(compact('requests', 'counts', 'pageTitle', 'headerBadge', 'viewType', 'inModal', 'adminApprovalStatus', 'adminApprovalMeta'));
    }

    public function approved()
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $adminId = (int)$this->Auth->user('id');
        $hiddenRequestIds = $this->getHiddenRequestIds($adminId);
        $counts = $this->buildCounts($adminId, $hiddenRequestIds);

        $requests = $this->Requests->find()
            ->where(['status IN' => ['approved', 'Approved']])
            ->orderDesc('created_at')
            ->orderDesc('id')
            ->all();
        if (!empty($hiddenRequestIds)) {
            $requests = $this->Requests->find()
                ->where(['status IN' => ['approved', 'Approved']])
                ->where(['id NOT IN' => $hiddenRequestIds])
                ->orderDesc('created_at')
                ->orderDesc('id')
                ->all();
        }

        $pageTitle = 'Approved Requests';
        $headerBadge = 'Fully approved';
        $viewType = 'approved';
        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }
        unset($counts['deleted']);
        $this->set(compact('requests', 'counts', 'pageTitle', 'headerBadge', 'viewType', 'inModal'));
        $this->render('pending');
    }

    public function rejected()
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $adminId = (int)$this->Auth->user('id');
        $hiddenRequestIds = $this->getHiddenRequestIds($adminId);
        $counts = $this->buildCounts($adminId, $hiddenRequestIds);

        $requests = $this->Requests->find()
            ->where(['Requests.status !=' => 'deleted'])
            ->matching('RequestApprovals', function ($q) {
                return $q->where(['RequestApprovals.status' => 'declined']);
            })
            ->distinct(['Requests.id'])
            ->orderDesc('Requests.created_at')
            ->orderDesc('Requests.id')
            ->all();
        if (!empty($hiddenRequestIds)) {
            $requests = $this->Requests->find()
                ->where(['Requests.status !=' => 'deleted'])
                ->where(['Requests.id NOT IN' => $hiddenRequestIds])
                ->matching('RequestApprovals', function ($q) {
                    return $q->where(['RequestApprovals.status' => 'declined']);
                })
                ->distinct(['Requests.id'])
                ->orderDesc('Requests.created_at')
                ->orderDesc('Requests.id')
                ->all();
        }

        $pageTitle = 'Rejected Requests';
        $headerBadge = 'At least one admin declined';
        $viewType = 'rejected';
        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }
        unset($counts['deleted']);
        $this->set(compact('requests', 'counts', 'pageTitle', 'headerBadge', 'viewType', 'inModal'));
        $this->render('pending');
    }

    public function deleted()
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $adminId = (int)$this->Auth->user('id');
        $hiddenRequestIds = $this->getHiddenRequestIds($adminId);
        $counts = $this->buildCounts($adminId, $hiddenRequestIds);

        $deleteLogs = [];
        try {
            $this->loadModel('RequestDeletes');
            $deleteLogs = $this->RequestDeletes->find()
                ->where(['deleted_by' => $adminId])
                ->orderDesc('deleted_at')
                ->orderDesc('id')
                ->all();
        } catch (\Throwable $e) {
            // Ignore if migration hasn't been applied yet.
        }

        $pageTitle = 'Deleted Requests';
        $headerBadge = 'Deleted by you';
        $viewType = 'deleted';
        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }
        unset($counts['deleted']);
        $this->set(compact('deleteLogs', 'counts', 'pageTitle', 'headerBadge', 'viewType', 'inModal'));
        $this->render('deleted');
    }

    public function view($id = null)
    {
        $requestEntity = $this->Requests->get($id);
        $auth = $this->Auth->user();
        $adminId = (int)($auth['id'] ?? 0);
        $role = $auth['role'] ?? null;
        $isAdmin = in_array($role, ['Administrator', 'Superuser', 'Approver'], true);
        $isOwner = $adminId > 0 && (int)$requestEntity->user_id === $adminId;
        if (!$auth) {
            $sessionRequestId = (int)$this->request->getSession()->read('last_request_id');
            if ($sessionRequestId && $sessionRequestId === (int)$requestEntity->id) {
                $isOwner = true;
            }
        }
        if (!$isAdmin && !$isOwner) {
            $this->Flash->error('You are not allowed to access this request.');
            return $this->redirect(['action' => 'add']);
        }

        $approvals = [];
        try {
            $this->loadModel('RequestApprovals');
            $approvals = $this->RequestApprovals->find()
                ->where(['request_id' => $requestEntity->id])
                ->contain(['Users'])
                ->orderDesc('created')
                ->all();
        } catch (\Throwable $e) {
            // Ignore if approvals table isn't available.
        }

        $admins = [];
        $approvalStatuses = [];
        try {
            $this->loadModel('Users');
        $admins = $this->Users->find()
            ->where(['role IN' => ['Administrator', 'Approver']])
            ->orderAsc('id')
            ->all();
        $admins = $this->orderApproverHierarchy($admins);
        } catch (\Throwable $e) {
            // Ignore if users table isn't available.
        }

        if (!empty($admins)) {
            try {
                $this->loadModel('RequestApprovals');
                $approvalStatuses = $this->RequestApprovals->find()
                    ->select(['admin_user_id', 'status'])
                    ->where(['request_id' => $requestEntity->id])
                    ->enableHydration(false)
                    ->all()
                    ->combine('admin_user_id', 'status')
                    ->toArray();
            } catch (\Throwable $e) {
                // Ignore if approvals table isn't available.
            }
        }

        $remarksList = [];
        try {
            $this->loadModel('RequestApprovals');
            $remarkRows = $this->RequestApprovals->find()
                ->select(['admin_user_id', 'remarks', 'created'])
                ->where(['request_id' => $requestEntity->id])
                ->where(function ($exp) {
                    return $exp->isNotNull('remarks');
                })
                ->enableHydration(false)
                ->orderDesc('created')
                ->all()
                ->toArray();
            $remarkRows = array_filter($remarkRows, function ($row) {
                return trim((string)($row['remarks'] ?? '')) !== '';
            });
            if (!empty($remarkRows)) {
                $userIds = array_values(array_unique(array_map('intval', array_column($remarkRows, 'admin_user_id'))));
                $userMap = [];
                if (!empty($userIds)) {
                    $this->loadModel('Users');
                    $userMap = $this->Users->find()
                        ->select(['id', 'username'])
                        ->where(['id IN' => $userIds])
                        ->enableHydration(false)
                        ->all()
                        ->combine('id', 'username')
                        ->toArray();
                }
                foreach ($remarkRows as $row) {
                    $adminId = (int)($row['admin_user_id'] ?? 0);
                    $remarksList[] = [
                        'name' => $userMap[$adminId] ?? 'Reviewer',
                        'remark' => (string)($row['remarks'] ?? ''),
                        'created' => $row['created'] ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Ignore if approvals table isn't available.
        }

        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }

        $pageTitle = 'Request Details';
        $statusOnly = (bool)$this->request->getQuery('status');
        $this->set(compact(
            'requestEntity',
            'approvals',
            'pageTitle',
            'adminId',
            'inModal',
            'admins',
            'approvalStatuses',
            'statusOnly',
            'remarksList'
        ));
    }

    public function exportPdf($id = null)
    {
        $requestEntity = $this->Requests->get($id);
        $auth = $this->Auth->user();
        $role = $auth['role'] ?? null;
        $isAdmin = in_array($role, ['Administrator', 'Superuser', 'Approver'], true);
        $isOwner = false;
        if (!empty($auth['id']) && (int)$requestEntity->user_id === (int)$auth['id']) {
            $isOwner = true;
        }
        if (!$auth) {
            $sessionRequestId = (int)$this->request->getSession()->read('last_request_id');
            if ($sessionRequestId && $sessionRequestId === (int)$requestEntity->id) {
                $isOwner = true;
            }
        }
        if (!$isAdmin && !$isOwner) {
            $this->Flash->error('You are not allowed to export this request.');
            return $this->redirect(['action' => 'add']);
        }

        $isApproved = in_array($requestEntity->status, ['approved', 'Approved'], true)
            || ((int)$requestEntity->approvals_needed > 0
                && (int)$requestEntity->approvals_count >= (int)$requestEntity->approvals_needed);

        if (!$isApproved) {
            $this->Flash->error('This request is not fully approved yet.');
            return $this->redirect(['action' => $isAdmin ? 'pending' : 'add']);
        }

        $proponentName = '';
        $proponentDegree = '';
        $proponentPosition = '';
        $detailsSource = $requestEntity->details;
        if ($detailsSource === null || trim((string)$detailsSource) === '') {
            $detailsSource = $requestEntity->message ?? '';
        }
        $detailsText = trim((string)$detailsSource);
        if ($detailsText !== '') {
            $lines = preg_split("/\\r\\n|\\n|\\r/", $detailsText);
            foreach ($lines as $line) {
                $line = trim((string)$line);
                if ($line === '') {
                    continue;
                }
                $pos = strpos($line, ':');
                if ($pos === false) {
                    continue;
                }
                $label = trim(substr($line, 0, $pos));
                $value = trim(substr($line, $pos + 1));
                if (strcasecmp($label, 'Proponent/s') === 0 && $value !== '') {
                    $proponentName = $value;
                }
            }
        }

        if ($proponentName === '' && !empty($requestEntity->user_id)) {
            try {
                $this->loadModel('Users');
                $user = $this->Users->find()
                    ->select([
                        'first_name',
                        'middle_initial',
                        'last_name',
                        'suffix',
                        'degree',
                        'position',
                        'email_address',
                    ])
                    ->where(['id' => (int)$requestEntity->user_id])
                    ->first();
                if ($user) {
                    $parts = array_filter([
                        $user->first_name ?? null,
                        $user->middle_initial ?? null,
                        $user->last_name ?? null,
                    ]);
                    $name = trim(implode(' ', $parts));
                    if (!empty($user->suffix)) {
                        $name = trim($name . ' ' . $user->suffix);
                    }
                    if ($name !== '') {
                        $proponentName = $name;
                    }
                    $proponentDegree = (string)($user->degree ?? '');
                    $proponentPosition = (string)($user->position ?? '');
                }
            } catch (\Throwable $e) {
                // Ignore if users table isn't available.
            }
        }

        $this->viewBuilder()->setLayout('ajax');
        $pageTitle = 'Activity Proposal';
        $this->set(compact(
            'requestEntity',
            'pageTitle',
            'proponentName',
            'proponentDegree',
            'proponentPosition'
        ));
    }

    public function approve($id = null)
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $this->request->allowMethod(['post']);

        $requestEntity = $this->Requests->get($id);
        if ((int)$requestEntity->approvals_needed === 0) {
            $this->loadModel('Users');
            $requestEntity->approvals_needed = $this->Users->find()
                ->where(['role IN' => ['Administrator', 'Approver']])
                ->count();
            $this->Requests->save($requestEntity);
        }
        if ((int)$requestEntity->approvals_count >= (int)$requestEntity->approvals_needed) {
            $this->Flash->error('This request is already fully approved.');
            return $this->redirect(['action' => 'pending']);
        }

        $this->loadModel('RequestApprovals');
        $this->loadModel('Users');
        $adminId = (int)$this->Auth->user('id');

        $hierarchy = $this->getApproverHierarchy();
        $currentUser = $this->Users->find()
            ->select(['id', 'username'])
            ->where(['id' => $adminId])
            ->first();
        $currentRank = $this->getApproverRank($currentUser->username ?? null, $hierarchy);
        if ($currentRank !== null) {
            $admins = $this->Users->find()
                ->select(['id', 'username'])
                ->where(['role IN' => ['Administrator', 'Approver']])
                ->all();
            $lowerAdminIds = [];
            foreach ($admins as $admin) {
                $rank = $this->getApproverRank($admin->username ?? null, $hierarchy);
                if ($rank !== null && $rank < $currentRank) {
                    $lowerAdminIds[] = (int)$admin->id;
                }
            }
            if (!empty($lowerAdminIds)) {
                $approvedLower = $this->RequestApprovals->find()
                    ->select(['admin_user_id'])
                    ->where([
                        'request_id' => $requestEntity->id,
                        'status' => 'approved',
                        'admin_user_id IN' => $lowerAdminIds,
                    ])
                    ->enableHydration(false)
                    ->all()
                    ->extract('admin_user_id')
                    ->toList();
                $pendingLower = array_diff($lowerAdminIds, array_map('intval', $approvedLower));
                if (!empty($pendingLower)) {
                    $this->Flash->error('Final approval is pending completion of required approvals.');
                    return $this->redirect(['action' => 'pending']);
                }
            }
        }

        $existing = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'admin_user_id' => $adminId])
            ->first();

        if ($existing) {
            if ($existing->status === 'approved') {
                $this->Flash->error('You already approved this request.');
                return $this->redirect(['action' => 'pending']);
            }
            $existing->status = 'approved';
            $existing->created = FrozenTime::now();
            if (!$this->RequestApprovals->save($existing)) {
                $this->Flash->error('Failed to record approval.');
                return $this->redirect(['action' => 'pending']);
            }
        } else {
            $approval = $this->RequestApprovals->newEmptyEntity();
            $approval->request_id = $requestEntity->id;
            $approval->admin_user_id = $adminId;
            $approval->status = 'approved';
            $approval->created = FrozenTime::now();
            if (!$this->RequestApprovals->save($approval)) {
                $this->Flash->error('Failed to record approval.');
                return $this->redirect(['action' => 'pending']);
            }
        }

        $approvedCount = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'status' => 'approved'])
            ->count();
        $declinedCount = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'status' => 'declined'])
            ->count();

        $requestEntity->approvals_count = $approvedCount;
        if ($declinedCount > 0) {
            $requestEntity->status = 'declined';
        } elseif ($approvedCount >= (int)$requestEntity->approvals_needed) {
            $requestEntity->status = 'approved';
        } else {
            $requestEntity->status = 'pending';
        }

        if ($this->Requests->save($requestEntity)) {
            $this->Flash->success('Approval recorded.');
        } else {
            $this->Flash->error('Failed to update request status.');
        }

        return $this->redirect(['action' => 'pending']);
    }

    public function decline($id = null)
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $this->request->allowMethod(['post']);

        $remarks = trim((string)$this->request->getData('remarks'));
        if ($remarks === '') {
            $this->Flash->error('Please enter remarks before marking for review.');
            return $this->redirect(['action' => 'pending']);
        }

        $requestEntity = $this->Requests->get($id);
        $this->loadModel('Users');
        $this->loadModel('RequestApprovals');
        $adminId = (int)$this->Auth->user('id');

        $existing = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'admin_user_id' => $adminId])
            ->first();

        if ($existing) {
            $existing->status = 'declined';
            $existing->remarks = $remarks;
            $existing->created = FrozenTime::now();
            if (!$this->RequestApprovals->save($existing)) {
                $this->Flash->error('Failed to record decline.');
                return $this->redirect(['action' => 'pending']);
            }
        } else {
            $decline = $this->RequestApprovals->newEmptyEntity();
            $decline->request_id = $requestEntity->id;
            $decline->admin_user_id = $adminId;
            $decline->status = 'declined';
            $decline->remarks = $remarks;
            $decline->created = FrozenTime::now();
            if (!$this->RequestApprovals->save($decline)) {
                $this->Flash->error('Failed to record decline.');
                return $this->redirect(['action' => 'pending']);
            }
        }

        $approvedCount = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'status' => 'approved'])
            ->count();
        $declinedCount = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'status' => 'declined'])
            ->count();

        $requestEntity->approvals_count = $approvedCount;
        if ($declinedCount > 0) {
            $requestEntity->status = 'declined';
        } elseif ($approvedCount >= (int)$requestEntity->approvals_needed) {
            $requestEntity->status = 'approved';
        } else {
            $requestEntity->status = 'pending';
        }

        if ($this->Requests->save($requestEntity)) {
            $this->Flash->success('Request sent for review.');
        } else {
            $this->Flash->error('Failed to send request for review.');
        }

        return $this->redirect(['action' => 'pending']);
    }

    public function delete($id = null)
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $this->request->allowMethod(['post', 'delete']);

        $this->loadModel('RequestApprovals');
        $this->loadModel('RequestDeletes');
        $requestEntity = $this->Requests->get($id);
        $adminId = (int)$this->Auth->user('id');
        $requestTitle = $requestEntity->title ?? $requestEntity->subject ?? null;

        $existingSoftDelete = null;
        if ($adminId) {
            $existingSoftDelete = $this->RequestDeletes->find()
                ->where([
                    'request_id' => $requestEntity->id,
                    'delete_mode' => 'soft',
                    'deleted_by' => $adminId,
                ])
                ->first();
        }

        if ($existingSoftDelete) {
            $deleteLog = $this->RequestDeletes->newEmptyEntity();
            $deleteLog->request_id = $requestEntity->id;
            $deleteLog->delete_mode = 'hard';
            $deleteLog->deleted_by = $adminId ?: null;
            $deleteLog->request_title = $requestTitle;
            $deleteLog->request_status = $requestEntity->status;
            $deleteLog->deleted_at = FrozenTime::now();
            $this->RequestDeletes->save($deleteLog);

            $this->RequestApprovals->deleteAll(['request_id' => $id]);
            if ($this->Requests->delete($requestEntity)) {
                $this->Flash->success('Request deleted.');
            } else {
                $this->Flash->error('Failed to delete request.');
            }

            return $this->redirect(['action' => 'pending']);
        }

        $deleteLog = $this->RequestDeletes->newEmptyEntity();
        $deleteLog->request_id = $requestEntity->id;
        $deleteLog->delete_mode = 'soft';
        $deleteLog->deleted_by = $adminId ?: null;
        $deleteLog->request_title = $requestTitle;
        $deleteLog->request_status = $requestEntity->status;
        $deleteLog->deleted_at = FrozenTime::now();

        if ($this->RequestDeletes->save($deleteLog)) {
            $this->Flash->success('Request deleted.');
        } else {
            $this->Flash->error('Failed to delete request.');
        }

        return $this->redirect(['action' => 'pending']);
    }

    public function pendingCount()
    {
        $this->request->allowMethod(['get']);

        $role = $this->Auth->user('role');
        if (!in_array($role, ['Administrator', 'Superuser'], true)) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['count' => 0]));
        }

        $count = $this->Requests->find()
            ->where(['status !=' => 'deleted'])
            ->where(function ($exp) {
                return $exp->lt('approvals_count', new IdentifierExpression('approvals_needed'));
            })
            ->count();

        $adminId = (int)$this->Auth->user('id');
        if ($adminId) {
            try {
                $this->loadModel('RequestDeletes');
                $hiddenRequestIds = $this->RequestDeletes->find()
                    ->select(['request_id'])
                    ->where([
                        'delete_mode' => 'soft',
                        'deleted_by' => $adminId,
                        'request_id IS NOT' => null,
                    ])
                    ->enableHydration(false)
                    ->all()
                    ->extract('request_id')
                    ->toList();
                if (!empty($hiddenRequestIds)) {
                    $count = $this->Requests->find()
                        ->where(['status !=' => 'deleted'])
                        ->where(['id NOT IN' => $hiddenRequestIds])
                        ->where(function ($exp) {
                            return $exp->lt('approvals_count', new IdentifierExpression('approvals_needed'));
                        })
                        ->count();
                }
            } catch (\Throwable $e) {
                // Ignore if migration hasn't been applied yet.
            }
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['count' => $count]));
    }

    public function clearAll()
    {
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        if (($this->Auth->user('role') ?? '') !== 'Superuser') {
            $this->Flash->error('Only superuser can clear requests.');
            return $this->redirect(['action' => 'pending']);
        }

        $this->request->allowMethod(['post']);

        $this->loadModel('RequestApprovals');
        $this->RequestApprovals->deleteAll([]);

        try {
            $this->loadModel('RequestDeletes');
            $this->RequestDeletes->deleteAll([]);
        } catch (\Throwable $e) {
            // Ignore if deletes table isn't available.
        }

        $this->Requests->deleteAll([]);

        $this->Flash->success('All requests cleared.');
        return $this->redirect(['action' => 'pending']);
    }

    private function notifyAdmins(int $requestId): void
    {
        $this->loadModel('Users');
        $this->loadModel('Notifications');

        $adminUsers = $this->Users->find()
            ->where(['role IN' => ['Administrator', 'Approver']])
            ->orderAsc('id')
            ->all();

        if ($adminUsers->isEmpty()) {
            return;
        }

        $now = FrozenTime::now();
        $notifications = [];

        foreach ($adminUsers as $admin) {
            $notification = $this->Notifications->newEmptyEntity();
            $notification->recipient_user_id = $admin->id;
            $notification->type = 'request_submitted';
            $notification->message = 'New request pending approval.';
            $notification->ref_id = $requestId;
            $notification->is_read = false;
            $notification->created = $now;
            $notifications[] = $notification;
        }

        $this->Notifications->saveMany($notifications);
    }

    private function orderApproverHierarchy(iterable $admins): array
    {
        $adminList = is_array($admins) ? $admins : iterator_to_array($admins, false);
        if (empty($adminList)) {
            return [];
        }

        $hierarchy = $this->getApproverHierarchy();

        $rankFor = function ($admin) use ($hierarchy): array {
            $label = strtoupper(trim((string)($admin->username ?? '')));
            $rank = $this->getApproverRank($label, $hierarchy);
            if ($rank !== null) {
                return [$rank, $label, (int)($admin->id ?? 0)];
            }
            return [PHP_INT_MAX, $label, (int)($admin->id ?? 0)];
        };

        usort($adminList, function ($a, $b) use ($rankFor): int {
            [$rankA, $labelA, $idA] = $rankFor($a);
            [$rankB, $labelB, $idB] = $rankFor($b);
            if ($rankA !== $rankB) {
                return $rankA <=> $rankB;
            }
            $labelCompare = strcmp($labelA, $labelB);
            if ($labelCompare !== 0) {
                return $labelCompare;
            }
            return $idA <=> $idB;
        });

        return $adminList;
    }

    private function getApproverHierarchy(): array
    {
        return [
            'PO' => 1,
            'SMMNE' => 2,
            'AO' => 3,
            'BUDGET' => 4,
            'ACCOUNTANT' => 5,
            'ASDS' => 6,
            'SDS' => 7,
        ];
    }

    private function getApproverRank(?string $username, array $hierarchy): ?int
    {
        $label = strtoupper(trim((string)$username));
        if ($label === '') {
            return null;
        }
        foreach ($hierarchy as $key => $rank) {
            if ($label === $key) {
                return $rank;
            }
        }
        foreach ($hierarchy as $key => $rank) {
            if (strpos($label, $key) !== false) {
                return $rank;
            }
        }
        return null;
    }

    private function extractFieldsFromDetails(?string $detailsText): array
    {
        $fields = [];
        $detailsText = trim((string)$detailsText);
        if ($detailsText === '') {
            return $fields;
        }

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
        $lines = preg_split("/\\r\\n|\\n|\\r/", $detailsText);
        $inMatrix = false;
        foreach ($lines as $line) {
            $trimLine = trim((string)$line);
            if ($trimLine === '') {
                continue;
            }
            if (stripos($trimLine, 'Expenditure Matrix:') === 0) {
                $inMatrix = true;
                continue;
            }
            if ($inMatrix) {
                if (strpos($trimLine, '- ') === 0) {
                    continue;
                }
                $inMatrix = false;
            }

            foreach ($knownLabels as $label) {
                $prefix = $label . ':';
                if (stripos($trimLine, $prefix) === 0) {
                    $value = ltrim(substr($trimLine, strlen($prefix)));
                    $fields[$label] = $value;
                    break;
                }
            }
        }

        return $fields;
    }

    private function prepareAttachmentUploads(array $formData): array
    {
        $fieldMap = [
            'attachment_sub_aro' => 'Attachment SUB-ARO',
            'attachment_sfwp' => 'Attachment SFWP',
            'attachment_ar' => 'Attachment AR',
            'attachment_ac' => 'Attachment AC',
        ];
        $uploads = [];

        foreach ($fieldMap as $field => $label) {
            $file = $formData[$field] ?? null;
            if (!($file instanceof \Psr\Http\Message\UploadedFileInterface)) {
                continue;
            }
            if ($file->getError() !== UPLOAD_ERR_OK) {
                continue;
            }
            $clientName = (string)$file->getClientFilename();
            $base = pathinfo($clientName, PATHINFO_FILENAME);
            $ext = pathinfo($clientName, PATHINFO_EXTENSION);
            $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', $base);
            if ($safeBase === '') {
                $safeBase = 'file';
            }
            $filename = sprintf(
                '%s_%s_%s',
                $safeBase,
                date('YmdHis'),
                bin2hex(random_bytes(4))
            );
            if ($ext !== '') {
                $filename .= '.' . $ext;
            }
            $uploads[$label] = [
                'file' => $file,
                'filename' => $filename,
            ];
        }

        return $uploads;
    }

    private function storeAttachmentUploads(array $uploads, int $requestId): void
    {
        if ($requestId <= 0 || empty($uploads)) {
            return;
        }

        $directory = WWW_ROOT . 'uploads' . DS . 'requests' . DS . $requestId;
        if (!is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }

        foreach ($uploads as $upload) {
            $file = $upload['file'] ?? null;
            $filename = $upload['filename'] ?? null;
            if (!$file instanceof \Psr\Http\Message\UploadedFileInterface || !$filename) {
                continue;
            }
            try {
                $file->moveTo($directory . DS . $filename);
            } catch (\Throwable $e) {
                // Ignore upload errors to avoid blocking form submission.
            }
        }
    }

    private function ensureAdmin()
    {
        $role = $this->Auth->user('role');
        if (!in_array($role, ['Administrator', 'Superuser', 'Approver'], true)) {
            $this->Flash->error('You are not allowed to access this page.');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        return null;
    }

    private function getHiddenRequestIds(int $adminId): array
    {
        if ($adminId <= 0) {
            return [];
        }

        try {
            $this->loadModel('RequestDeletes');
            return $this->RequestDeletes->find()
                ->select(['request_id'])
                ->where([
                    'delete_mode' => 'soft',
                    'deleted_by' => $adminId,
                    'request_id IS NOT' => null,
                ])
                ->enableHydration(false)
                ->all()
                ->extract('request_id')
                ->toList();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function buildCounts(int $adminId, array $hiddenRequestIds = []): array
    {
        if ($adminId > 0) {
            try {
                $this->loadModel('RequestApprovals');

                $pendingQuery = $this->Requests->find()
                    ->where(['Requests.status !=' => 'deleted'])
                    ->where(function ($exp) {
                        return $exp->lt('approvals_count', new IdentifierExpression('approvals_needed'));
                    })
                    ->notMatching('RequestApprovals', function ($q) use ($adminId) {
                        return $q->where(['RequestApprovals.admin_user_id' => $adminId]);
                    });
                if (!empty($hiddenRequestIds)) {
                    $pendingQuery->where(['Requests.id NOT IN' => $hiddenRequestIds]);
                }
                $pendingCount = $pendingQuery->count();

                $approvedQuery = $this->Requests->find()
                    ->where(['Requests.status !=' => 'deleted'])
                    ->matching('RequestApprovals', function ($q) use ($adminId) {
                        return $q->where([
                            'RequestApprovals.admin_user_id' => $adminId,
                            'RequestApprovals.status' => 'approved',
                        ]);
                    })
                    ->distinct(['Requests.id']);
                if (!empty($hiddenRequestIds)) {
                    $approvedQuery->where(['Requests.id NOT IN' => $hiddenRequestIds]);
                }
                $approvedCount = $approvedQuery->count();

                $rejectedQuery = $this->Requests->find()
                    ->where(['Requests.status !=' => 'deleted'])
                    ->matching('RequestApprovals', function ($q) use ($adminId) {
                        return $q->where([
                            'RequestApprovals.admin_user_id' => $adminId,
                            'RequestApprovals.status' => 'declined',
                        ]);
                    })
                    ->distinct(['Requests.id']);
                if (!empty($hiddenRequestIds)) {
                    $rejectedQuery->where(['Requests.id NOT IN' => $hiddenRequestIds]);
                }
                $rejectedCount = $rejectedQuery->count();

                return [
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'rejected' => $rejectedCount,
                ];
            } catch (\Throwable $e) {
                // Fallback to global counts if approvals table isn't available.
            }
        }

        $pendingQuery = $this->Requests->find()
            ->where(['status !=' => 'deleted'])
            ->where(function ($exp) {
                return $exp->lt('approvals_count', new IdentifierExpression('approvals_needed'));
            });
        if (!empty($hiddenRequestIds)) {
            $pendingQuery->where(['id NOT IN' => $hiddenRequestIds]);
        }
        $pendingCount = $pendingQuery->count();

        $approvedQuery = $this->Requests->find()
            ->where(['status IN' => ['approved', 'Approved']]);
        if (!empty($hiddenRequestIds)) {
            $approvedQuery->where(['id NOT IN' => $hiddenRequestIds]);
        }
        $approvedCount = $approvedQuery->count();

        $rejectedQuery = $this->Requests->find()
            ->where(['Requests.status !=' => 'deleted'])
            ->matching('RequestApprovals', function ($q) {
                return $q->where(['RequestApprovals.status' => 'declined']);
            })
            ->distinct(['Requests.id']);
        if (!empty($hiddenRequestIds)) {
            $rejectedQuery->where(['Requests.id NOT IN' => $hiddenRequestIds]);
        }
        $rejectedCount = $rejectedQuery->count();

        return [
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount,
        ];
    }
}
