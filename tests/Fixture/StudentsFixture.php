<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StudentsFixture
 */
class StudentsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'lastname' => 'Lorem ipsum dolor sit amet',
                'firstname' => 'Lorem ipsum dolor sit amet',
                'middlename' => 'Lorem ipsum dolor sit amet',
                'created' => '2024-12-07 00:57:25',
                'modified' => '2024-12-07 00:57:25',
            ],
        ];
        parent::init();
    }
}
