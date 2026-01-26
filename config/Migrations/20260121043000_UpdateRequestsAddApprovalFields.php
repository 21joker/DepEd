<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class UpdateRequestsAddApprovalFields extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('requests')) {
            return;
        }

        $table = $this->table('requests');
        $updated = false;

        if (!$table->hasColumn('name')) {
            $table->addColumn('name', 'string', ['limit' => 150, 'null' => true]);
            $updated = true;
        }

        if (!$table->hasColumn('email')) {
            $table->addColumn('email', 'string', ['limit' => 150, 'null' => true]);
            $updated = true;
        }

        if (!$table->hasColumn('approvals_needed')) {
            $table->addColumn('approvals_needed', 'integer', ['default' => 5]);
            $updated = true;
        }

        if (!$table->hasColumn('approvals_count')) {
            $table->addColumn('approvals_count', 'integer', ['default' => 0]);
            $updated = true;
        }

        if ($updated) {
            $table->update();
        }
    }
}
