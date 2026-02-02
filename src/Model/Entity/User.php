<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $role
 * @property string|null $id_number
 * @property string|null $first_name
 * @property string|null $middle_initial
 * @property string|null $last_name
 * @property string|null $suffix
 * @property string|null $degree
 * @property string|null $rank
 * @property string|null $position
 * @property string|null $email_address
 * @property string|null $office
 * @property string|null $section_unit
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Student[] $students
 */
class User extends Entity
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
        'username' => true,
        'password' => true,
        'role' => true,
        'id_number' => true,
        'first_name' => true,
        'middle_initial' => true,
        'last_name' => true,
        'suffix' => true,
        'degree' => true,
        'rank' => true,
        'position' => true,
        'email_address' => true,
        'office' => true,
        'section_unit' => true,
        'created' => true,
        'modified' => true,
        'student' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
