<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class AddUserProfileFields extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('users')) {
            return;
        }

        $table = $this->table('users');
        if (!$table->hasColumn('first_name')) {
            $table->addColumn('first_name', 'string', ['limit' => 100, 'null' => true]);
        }
        if (!$table->hasColumn('middle_initial')) {
            $table->addColumn('middle_initial', 'string', ['limit' => 10, 'null' => true]);
        }
        if (!$table->hasColumn('last_name')) {
            $table->addColumn('last_name', 'string', ['limit' => 100, 'null' => true]);
        }
        if (!$table->hasColumn('email_address')) {
            $table->addColumn('email_address', 'string', ['limit' => 150, 'null' => true]);
        }
        if (!$table->hasColumn('level_of_governance')) {
            $table->addColumn('level_of_governance', 'string', ['limit' => 50, 'null' => true]);
        }

        $table->update();
    }
}
