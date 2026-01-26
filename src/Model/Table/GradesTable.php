<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Grades Model
 *
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\BelongsTo $Students
 *
 * @method \App\Model\Entity\Grade newEmptyEntity()
 * @method \App\Model\Entity\Grade newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Grade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Grade get($primaryKey, $options = [])
 * @method \App\Model\Entity\Grade findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Grade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Grade[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Grade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Grade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Grade[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Grade[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Grade[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Grade[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GradesTable extends Table
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

        $this->setTable('grades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Grades');

        $this->belongsTo('Students', [
            'foreignKey' => 'student_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('student_id')
            ->notEmptyString('student_id');

        $validator
            ->decimal('english')
            ->requirePresence('english', 'create')
            ->notEmptyString('english');

        $validator
            ->decimal('science')
            ->requirePresence('science', 'create')
            ->notEmptyString('science');

        $validator
            ->decimal('math')
            ->requirePresence('math', 'create')
            ->notEmptyString('math');

        $validator
            ->decimal('filipino')
            ->requirePresence('filipino', 'create')
            ->notEmptyString('filipino');

        $validator
            ->decimal('mapeh')
            ->requirePresence('mapeh', 'create')
            ->notEmptyString('mapeh');

        $validator
            ->decimal('average')
            ->requirePresence('average', 'create')
            ->notEmptyString('average');

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
        $rules->add($rules->existsIn('student_id', 'Students'), ['errorField' => 'student_id']);

        return $rules;
    }
}
