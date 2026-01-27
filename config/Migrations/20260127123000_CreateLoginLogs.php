<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class CreateLoginLogs extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('login_logs')) {
            return;
        }

        $table = $this->table('login_logs');
        $table
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('username', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('role', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('ip', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('user_agent', 'text', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addIndex(['user_id'])
            ->addIndex(['created'])
            ->create();
    }
}
