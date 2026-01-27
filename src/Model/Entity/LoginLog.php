<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class LoginLog extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'username' => true,
        'role' => true,
        'ip' => true,
        'user_agent' => true,
        'created' => true,
    ];
}
