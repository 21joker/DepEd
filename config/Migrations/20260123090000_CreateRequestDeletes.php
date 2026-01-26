<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class CreateRequestDeletes extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('request_deletes')) {
            return;
        }

        $table = $this->table('request_deletes');
        $table
            ->addColumn('request_id', 'integer', ['null' => true])
            ->addColumn('delete_mode', 'string', ['limit' => 10])
            ->addColumn('deleted_by', 'integer', ['null' => true])
            ->addColumn('request_title', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('request_status', 'string', ['limit' => 30, 'null' => true])
            ->addColumn('deleted_at', 'datetime')
            ->addIndex(['delete_mode'])
            ->addIndex(['request_id'])
            ->create();
    }
}
