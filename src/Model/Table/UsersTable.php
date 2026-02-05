<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\HasMany $Students
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        if ($this->tableExists('students')) {
            $this->hasOne('Students', [
                'foreignKey' => 'user_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
        }
    }

    private function tableExists(string $table): bool
    {
        try {
            $tables = $this->getConnection()
                ->getSchemaCollection()
                ->listTables();
            return in_array($table, $tables, true);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('username')
            ->maxLength('username', 55)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('role')
            ->maxLength('role', 55)
            ->requirePresence('role', 'create')
            ->notEmptyString('role');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 100)
            ->allowEmptyString('first_name');

        $validator
            ->scalar('middle_initial')
            ->maxLength('middle_initial', 10)
            ->allowEmptyString('middle_initial');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 100)
            ->allowEmptyString('last_name');

        $validator
            ->scalar('suffix')
            ->maxLength('suffix', 20)
            ->allowEmptyString('suffix');

        $validator
            ->scalar('degree')
            ->maxLength('degree', 50)
            ->allowEmptyString('degree');

        $validator
            ->scalar('rank')
            ->maxLength('rank', 50)
            ->allowEmptyString('rank');

        $validator
            ->scalar('position')
            ->maxLength('position', 100)
            ->allowEmptyString('position');

        $validator
            ->email('email_address')
            ->allowEmptyString('email_address');

        $validator
            ->scalar('office')
            ->maxLength('office', 50)
            ->allowEmptyString('office');

        $validator
            ->scalar('section_unit')
            ->maxLength('section_unit', 100)
            ->allowEmptyString('section_unit');

        $validator
            ->scalar('esignature')
            ->maxLength('esignature', 255)
            ->allowEmptyString('esignature');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);

        return $rules;
    }
}
