<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class AddUserRank extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('users')) {
            return;
        }

        $table = $this->table('users');
        if (!$table->hasColumn('rank')) {
            $table->addColumn('rank', 'string', ['limit' => 50, 'null' => true, 'after' => 'degree']);
        }
        $table->update();
    }
}
