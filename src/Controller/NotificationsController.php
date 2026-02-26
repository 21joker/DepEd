<?php
declare(strict_types=1);

namespace App\Controller;

class NotificationsController extends AppController
{
    public function fetch()
    {
        $this->request->allowMethod(['get']);

        $userId = (int)$this->Auth->user('id');
        if ($userId <= 0) {
            return $this->response->withStatus(401);
        }

        $this->loadModel('Notifications');
        $schema = $this->Notifications->getSchema();
        $createdField = null;
        if ($schema->hasColumn('created')) {
            $createdField = 'created';
        } elseif ($schema->hasColumn('created_at')) {
            $createdField = 'created_at';
        }
        $refField = null;
        if ($schema->hasColumn('ref_id')) {
            $refField = 'ref_id';
        } elseif ($schema->hasColumn('ref_request_id')) {
            $refField = 'ref_request_id';
        } elseif ($schema->hasColumn('request_id')) {
            $refField = 'request_id';
        }

        $baseQuery = $this->Notifications->find()
            ->where(['recipient_user_id' => $userId]);

        $unreadCount = (clone $baseQuery)
            ->where(['is_read' => 0])
            ->count();

        $selectFields = ['id', 'type', 'message', 'is_read'];
        if ($refField !== null) {
            $selectFields[] = $refField;
        }
        if ($createdField !== null) {
            $selectFields[] = $createdField;
        }
        $orderField = $createdField ?? 'id';
        $items = (clone $baseQuery)
            ->select($selectFields)
            ->orderDesc($orderField)
            ->limit(10)
            ->enableHydration(false)
            ->toArray();

        $requestsTable = null;
        try {
            $this->loadModel('Requests');
            $requestsTable = $this->Requests;
        } catch (\Throwable $e) {
            $requestsTable = null;
        }

        $resolveRequestId = function (array $item) use ($refField, $requestsTable): int {
            $refId = (int)($refField ? ($item[$refField] ?? 0) : 0);
            if ($requestsTable) {
                if ($refId > 0 && $requestsTable->exists(['id' => $refId])) {
                    return $refId;
                }
                $refId = 0;
                $message = (string)($item['message'] ?? '');
                $title = '';
                if (preg_match('/^New request:\s*(.+?)\s*\\(pending approval\\)/i', $message, $matches)) {
                    $title = trim($matches[1]);
                } elseif (preg_match('/^Request\\s+\\"(.+?)\\"\\s+status\\s+changed/i', $message, $matches)) {
                    $title = trim($matches[1]);
                }
                if ($title !== '') {
                    $requestSchema = $requestsTable->getSchema();
                    $orConditions = [];
                    if ($requestSchema->hasColumn('title')) {
                        $orConditions[] = ['title' => $title];
                    }
                    if ($requestSchema->hasColumn('subject')) {
                        $orConditions[] = ['subject' => $title];
                    }
                    if (empty($orConditions)) {
                        return 0;
                    }
                    $request = $requestsTable->find()
                        ->select(['id'])
                        ->where(['OR' => $orConditions])
                        ->orderDesc('id')
                        ->first();
                    if ($request) {
                        return (int)($request->id ?? 0);
                    }
                }
            }
            return $refId;
        };

        $payload = [
            'unread_count' => $unreadCount,
            'items' => array_map(function (array $item) use ($createdField, $resolveRequestId): array {
                $created = $createdField ? ($item[$createdField] ?? null) : null;
                $createdLabel = '';
                if ($created instanceof \Cake\I18n\FrozenTime) {
                    $createdLabel = $created->i18nFormat('MM/dd/yyyy h:mm a');
                } elseif ($created instanceof \DateTimeInterface) {
                    $createdLabel = $created->format('m/d/Y h:i a');
                } elseif (!empty($created)) {
                    $createdLabel = (string)$created;
                }
                $resolvedRefId = $resolveRequestId($item);
                return [
                    'id' => (int)($item['id'] ?? 0),
                    'type' => (string)($item['type'] ?? ''),
                    'message' => (string)($item['message'] ?? ''),
                    'ref_id' => $resolvedRefId,
                    'is_read' => (bool)($item['is_read'] ?? false),
                    'created' => $createdLabel,
                ];
            }, $items),
        ];

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($payload));
    }

    public function markRead()
    {
        $this->request->allowMethod(['post']);

        $userId = (int)$this->Auth->user('id');
        if ($userId <= 0) {
            return $this->response->withStatus(401);
        }

        $this->loadModel('Notifications');
        $this->Notifications->updateAll(
            ['is_read' => 1],
            ['recipient_user_id' => $userId]
        );

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'ok']));
    }

    public function markOne($id = null)
    {
        $this->request->allowMethod(['post']);

        $userId = (int)$this->Auth->user('id');
        if ($userId <= 0) {
            return $this->response->withStatus(401);
        }

        $notificationId = (int)$id;
        if ($notificationId <= 0) {
            return $this->response->withStatus(400);
        }

        $this->loadModel('Notifications');
        $this->Notifications->updateAll(
            ['is_read' => 1],
            ['id' => $notificationId, 'recipient_user_id' => $userId]
        );

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'ok']));
    }

    public function clearAll()
    {
        $this->request->allowMethod(['post']);

        $userId = (int)$this->Auth->user('id');
        if ($userId <= 0) {
            return $this->response->withStatus(401);
        }

        $this->loadModel('Notifications');
        $this->Notifications->deleteAll(['recipient_user_id' => $userId]);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'ok']));
    }
}
