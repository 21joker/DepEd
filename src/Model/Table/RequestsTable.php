<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Requests Model
 *
 * @property \App\Model\Table\RequestApprovalsTable&\Cake\ORM\Association\HasMany $RequestApprovals
 *
 * @method \App\Model\Entity\Request newEmptyEntity()
 * @method \App\Model\Entity\Request newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Request[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Request get($primaryKey, $options = [])
 * @method \App\Model\Entity\Request|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Request saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class RequestsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('requests');
        $this->setDisplayField('subject');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ],
            ],
        ]);

        $this->hasMany('RequestApprovals', [
            'foreignKey' => 'request_id',
            'dependent' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 150)
            ->allowEmptyString('name');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('title')
            ->maxLength('title', 150)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('details')
            ->requirePresence('details', 'create')
            ->notEmptyString('details');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('approvals_needed')
            ->requirePresence('approvals_needed', 'create')
            ->notEmptyString('approvals_needed');

        $validator
            ->integer('approvals_count')
            ->requirePresence('approvals_count', 'create')
            ->notEmptyString('approvals_count');

        return $validator;
    }
}
