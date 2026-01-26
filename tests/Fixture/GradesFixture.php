<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GradesFixture
 */
class GradesFixture extends TestFixture
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
                'student_id' => 1,
                'english' => 1.5,
                'science' => 1.5,
                'math' => 1.5,
                'filipino' => 1.5,
                'mapeh' => 1.5,
                'average' => 1.5,
                'created' => '2024-12-07 00:56:31',
                'modified' => '2024-12-07 00:56:31',
            ],
        ];
        parent::init();
    }
}
