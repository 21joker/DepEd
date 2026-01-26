<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\GradesBehavior;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\GradesBehavior Test Case
 */
class GradesBehaviorTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Behavior\GradesBehavior
     */
    protected $Grades;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $table = new Table();
        $this->Grades = new GradesBehavior($table);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Grades);

        parent::tearDown();
    }
}
