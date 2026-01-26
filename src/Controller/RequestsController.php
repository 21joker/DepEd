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
        $this->Auth->allow(['add']);
    }

    public function add()
    {
        $this->viewBuilder()->setLayout($this->Auth->user() ? 'default' : 'login');
        $requestEntity = $this->Requests->newEmptyEntity();
        $this->loadModel('Users');

        if ($this->request->is('post')) {
            $formData = $this->request->getData();
            $requestEntity = $this->Requests->patchEntity($requestEntity, $formData);
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

        $lastRequestId = $this->request->getSession()->read('last_request_id');
        $lastRequest = null;
        $approvalStatuses = [];
        $authId = (int)$this->Auth->user('id');

        if ($authId) {
            $lastRequest = $this->Requests->find()
                ->where(['user_id' => $authId])
                ->orderDesc('created_at')
                ->orderDesc('id')
                ->first();
        } elseif ($lastRequestId) {
            $lastRequest = $this->Requests->find()
                ->where(['id' => $lastRequestId])
                ->first();
        }

        if ($lastRequest) {
            $approvalStatuses = $this->RequestApprovals->find()
                ->select(['admin_user_id', 'status'])
                ->where(['request_id' => $lastRequest->id])
                ->enableHydration(false)
                ->all()
                ->combine('admin_user_id', 'status')
                ->toArray();
        }

        $this->set(compact('requestEntity', 'admins', 'lastRequest', 'approvalStatuses'));
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
            ->where(function ($exp) {
                return $exp->lt('approvals_count', new IdentifierExpression('approvals_needed'));
            })
            ->orderDesc('created_at')
            ->orderDesc('id')
            ->all();
        if (!empty($hiddenRequestIds)) {
            $requests = $this->Requests->find()
                ->where(['status !=' => 'deleted'])
                ->where(['id NOT IN' => $hiddenRequestIds])
                ->where(function ($exp) {
                    return $exp->lt('approvals_count', new IdentifierExpression('approvals_needed'));
                })
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
                    $adminApprovalStatus = $this->RequestApprovals->find()
                        ->select(['request_id', 'status'])
                        ->where([
                            'request_id IN' => $requestIds,
                            'admin_user_id' => $adminId,
                        ])
                        ->enableHydration(false)
                        ->all()
                        ->combine('request_id', 'status')
                        ->toArray();
                }
            } catch (\Throwable $e) {
                // Ignore if approvals table isn't available.
            }
        }

        $pageTitle = 'Pending Requests';
        $headerBadge = 'Needs all admin approvals';
        $viewType = 'pending';
        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }
        unset($counts['deleted']);
        $this->set(compact('requests', 'counts', 'pageTitle', 'headerBadge', 'viewType', 'inModal', 'adminApprovalStatus'));
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
        $response = $this->ensureAdmin();
        if ($response) {
            return $response;
        }

        $adminId = (int)$this->Auth->user('id');
        $requestEntity = $this->Requests->get($id);

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

        $inModal = (bool)$this->request->getQuery('modal');
        if ($inModal) {
            $this->viewBuilder()->setLayout('ajax');
        }

        $pageTitle = 'Request Details';
        $this->set(compact('requestEntity', 'approvals', 'pageTitle', 'adminId', 'inModal', 'admins', 'approvalStatuses'));
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
        $adminId = (int)$this->Auth->user('id');

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
        if ($approvedCount >= (int)$requestEntity->approvals_needed) {
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

        $requestEntity = $this->Requests->get($id);
        $this->loadModel('RequestApprovals');
        $adminId = (int)$this->Auth->user('id');

        $existing = $this->RequestApprovals->find()
            ->where(['request_id' => $requestEntity->id, 'admin_user_id' => $adminId])
            ->first();

        if ($existing) {
            $existing->status = 'declined';
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
        if ($approvedCount >= (int)$requestEntity->approvals_needed) {
            $requestEntity->status = 'approved';
        } else {
            $requestEntity->status = 'pending';
        }

        if ($this->Requests->save($requestEntity)) {
            $this->Flash->success('Request declined.');
        } else {
            $this->Flash->error('Failed to decline request.');
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
