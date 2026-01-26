<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class RequestDeletesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('request_deletes');
        $this->setPrimaryKey('id');
    }
}
