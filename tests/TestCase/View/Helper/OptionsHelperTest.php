<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\OptionsHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\OptionsHelper Test Case
 */
class OptionsHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\OptionsHelper
     */
    protected $Options;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Options = new OptionsHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Options);

        parent::tearDown();
    }
}
