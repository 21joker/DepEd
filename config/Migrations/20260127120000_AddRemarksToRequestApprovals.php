<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class AddRemarksToRequestApprovals extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('request_approvals')) {
            return;
        }

        $table = $this->table('request_approvals');
        if ($table->hasColumn('remarks')) {
            return;
        }

        $table
            ->addColumn('remarks', 'text', ['null' => true, 'after' => 'status'])
            ->update();
    }
}
