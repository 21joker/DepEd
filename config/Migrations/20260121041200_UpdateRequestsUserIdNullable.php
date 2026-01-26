<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class UpdateRequestsUserIdNullable extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('requests')) {
            return;
        }

        $table = $this->table('requests');
        if (!$table->hasColumn('user_id')) {
            return;
        }

        if (method_exists($table, 'hasForeignKey') && $table->hasForeignKey('user_id')) {
            $table->dropForeignKey('user_id');
        }

        $table->changeColumn('user_id', 'integer', ['null' => true, 'default' => null])
            ->update();
    }
}
