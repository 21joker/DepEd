<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class LoginLogsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('login_logs');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('username')
            ->maxLength('username', 150)
            ->allowEmptyString('username');

        $validator
            ->scalar('role')
            ->maxLength('role', 50)
            ->allowEmptyString('role');

        $validator
            ->scalar('ip')
            ->maxLength('ip', 64)
            ->allowEmptyString('ip');

        $validator
            ->scalar('user_agent')
            ->allowEmptyString('user_agent');

        return $validator;
    }
}
