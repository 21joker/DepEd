<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestApprovals Model
 *
 * @property \App\Model\Table\RequestsTable&\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\RequestApproval newEmptyEntity()
 * @method \App\Model\Entity\RequestApproval newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\RequestApproval[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RequestApproval get($primaryKey, $options = [])
 * @method \App\Model\Entity\RequestApproval|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class RequestApprovalsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('request_approvals');
        $this->setPrimaryKey('id');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'admin_user_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('request_id')
            ->requirePresence('request_id', 'create')
            ->notEmptyString('request_id');

        $validator
            ->integer('admin_user_id')
            ->requirePresence('admin_user_id', 'create')
            ->notEmptyString('admin_user_id');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->allowEmptyString('status');

        return $validator;
    }
}
