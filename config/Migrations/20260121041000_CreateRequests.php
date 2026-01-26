<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class CreateRequests extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('requests')) {
            return;
        }

        $table = $this->table('requests');
        $table
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('subject', 'string', ['limit' => 150])
            ->addColumn('message', 'text')
            ->addColumn('status', 'string', ['limit' => 30, 'default' => 'pending'])
            ->addColumn('approvals_needed', 'integer', ['default' => 5])
            ->addColumn('approvals_count', 'integer', ['default' => 0])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addIndex(['status'])
            ->create();
    }
}
