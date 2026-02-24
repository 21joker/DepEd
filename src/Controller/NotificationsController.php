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
        $refField = $schema->hasColumn('ref_id') ? 'ref_id' : ($schema->hasColumn('ref_request_id') ? 'ref_request_id' : null);

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

        $payload = [
            'unread_count' => $unreadCount,
            'items' => array_map(function (array $item): array {
                $created = $createdField ? ($item[$createdField] ?? null) : null;
                $createdLabel = '';
                if ($created instanceof \Cake\I18n\FrozenTime) {
                    $createdLabel = $created->i18nFormat('MM/dd/yyyy h:mm a');
                } elseif ($created instanceof \DateTimeInterface) {
                    $createdLabel = $created->format('m/d/Y h:i a');
                } elseif (!empty($created)) {
                    $createdLabel = (string)$created;
                }
                return [
                    'id' => (int)($item['id'] ?? 0),
                    'type' => (string)($item['type'] ?? ''),
                    'message' => (string)($item['message'] ?? ''),
                    'ref_id' => (int)($refField ? ($item[$refField] ?? 0) : 0),
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
