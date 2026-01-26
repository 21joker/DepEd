<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Grade Entity
 *
 * @property int $id
 * @property int $student_id
 * @property string $english
 * @property string $science
 * @property string $math
 * @property string $filipino
 * @property string $mapeh
 * @property string $average
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Student $student
 */
class Grade extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'student_id' => true,
        'english' => true,
        'science' => true,
        'math' => true,
        'filipino' => true,
        'mapeh' => true,
        'average' => true,
        'created' => true,
        'modified' => true,
        'student' => true,
    ];
}
