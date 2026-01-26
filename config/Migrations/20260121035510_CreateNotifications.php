<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

final class CreateNotifications extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('notifications')) {
            return;
        }

        $table = $this->table('notifications');
        $table
            ->addColumn('recipient_user_id', 'integer')
            ->addColumn('type', 'string', ['limit' => 50])
            ->addColumn('message', 'string', ['limit' => 255])
            ->addColumn('ref_id', 'integer', ['null' => true]) // request id
            ->addColumn('is_read', 'boolean', ['default' => false])
            ->addColumn('created', 'datetime')
            ->addIndex(['recipient_user_id'])
            ->addIndex(['is_read'])
            ->create();
    }
}
