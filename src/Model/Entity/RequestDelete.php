<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class RequestDelete extends Entity
{
    protected $_accessible = [
        'request_id' => true,
        'delete_mode' => true,
        'deleted_by' => true,
        'request_title' => true,
        'request_status' => true,
        'deleted_at' => true,
    ];
}
