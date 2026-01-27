<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RequestApproval Entity
 *
 * @property int $id
 * @property int $request_id
 * @property int $admin_user_id
 * @property string|null $status
 * @property string|null $remarks
 * @property \Cake\I18n\FrozenTime $created
 */
class RequestApproval extends Entity
{
    protected $_accessible = [
        'request_id' => true,
        'admin_user_id' => true,
        'status' => true,
        'remarks' => true,
        'created' => true,
    ];
}
