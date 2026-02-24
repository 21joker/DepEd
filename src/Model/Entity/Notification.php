<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity
 *
 * @property int $id
 * @property int $recipient_user_id
 * @property string $type
 * @property string $message
 * @property int|null $ref_id
 * @property int|null $ref_request_id
 * @property bool $is_read
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $created_at
 */
class Notification extends Entity
{
    protected $_accessible = [
        'recipient_user_id' => true,
        'type' => true,
        'message' => true,
        'ref_id' => true,
        'ref_request_id' => true,
        'is_read' => true,
        'created' => true,
        'created_at' => true,
    ];
}
