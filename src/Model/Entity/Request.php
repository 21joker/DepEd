<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Request Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string $title
 * @property string|null $subject
 * @property string $details
 * @property string|null $message
 * @property string $status
 * @property int $approvals_needed
 * @property int $approvals_count
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 * @property \Cake\I18n\FrozenTime|null $user_updated_at
 */
class Request extends Entity
{
    protected $_accessible = [
        'name' => true,
        'email' => true,
        'title' => true,
        'subject' => true,
        'details' => true,
        'message' => true,
        'status' => true,
        'approvals_needed' => true,
        'approvals_count' => true,
        'created_at' => true,
        'updated_at' => true,
        'user_updated_at' => true,
    ];
}
