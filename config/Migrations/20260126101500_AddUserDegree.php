<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class AddUserDegree extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('users')) {
            return;
        }

        $table = $this->table('users');
        if (!$table->hasColumn('degree')) {
            $table->addColumn('degree', 'string', ['limit' => 50, 'null' => true]);
        }
        $table->update();
    }
}
