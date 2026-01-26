<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Notifications Model
 *
 * @method \App\Model\Entity\Notification newEmptyEntity()
 * @method \App\Model\Entity\Notification newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Notification[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Notification get($primaryKey, $options = [])
 * @method \App\Model\Entity\Notification|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class NotificationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('notifications');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('recipient_user_id')
            ->requirePresence('recipient_user_id', 'create')
            ->notEmptyString('recipient_user_id');

        $validator
            ->scalar('type')
            ->maxLength('type', 50)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('message')
            ->maxLength('message', 255)
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        $validator
            ->integer('ref_id')
            ->allowEmptyString('ref_id');

        $validator
            ->boolean('is_read')
            ->requirePresence('is_read', 'create')
            ->notEmptyString('is_read');

        return $validator;
    }
}
