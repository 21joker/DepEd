<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Options helper
 */
class OptionsHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    public function role()
    {
        $array = [
            'Administrator'=>'Administrator',
            'User'=>'User',
            'Manager'=>'Manager'
        ];

        return $array;
    }

}
