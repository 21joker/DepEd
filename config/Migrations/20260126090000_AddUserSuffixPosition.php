<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class AddUserSuffixPosition extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('users')) {
            return;
        }

        $table = $this->table('users');
        if (!$table->hasColumn('suffix')) {
            $table->addColumn('suffix', 'string', ['limit' => 20, 'null' => true]);
        }
        if (!$table->hasColumn('position')) {
            $table->addColumn('position', 'string', ['limit' => 100, 'null' => true]);
        }
        $table->update();
    }
}
