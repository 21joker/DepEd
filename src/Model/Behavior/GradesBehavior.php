<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;

/**
 * Grades behavior
 */
class GradesBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    public function computeAverage($data)
    {
        $average = 0;

        $average = ($data['english'] + $data['science'] + $data['math'] +
            $data['filipino'] + $data['mapeh'])/5;

       return number_format($average,2);
    }
}
