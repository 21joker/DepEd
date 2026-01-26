<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class AddStatusToRequestApprovals extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('request_approvals')) {
            return;
        }

        $table = $this->table('request_approvals');
        if ($table->hasColumn('status')) {
            return;
        }

        $table
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'approved', 'null' => true])
            ->addIndex(['status'])
            ->update();
    }
}
