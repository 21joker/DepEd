<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class CreateRequestApprovals extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('request_approvals')) {
            return;
        }

        $table = $this->table('request_approvals');
        $table
            ->addColumn('request_id', 'integer')
            ->addColumn('admin_user_id', 'integer')
            ->addColumn('created', 'datetime')
            ->addIndex(['request_id'])
            ->addIndex(['admin_user_id'])
            ->addIndex(['request_id', 'admin_user_id'], ['unique' => true])
            ->create();
    }
}
