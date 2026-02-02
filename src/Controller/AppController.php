<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent("Auth", [
            "authenticate" => [
                "Form" => [
                    "fields" => [
                        "username" => "username",
                        "password" => "password"
                    ],
                    "userModel" => "Users"
                ]
            ],
            "loginAction" => [
                "controller" => "Users",
                "action" => "login"
            ],
            "loginRedirect" => [
                "controller" => "Users",
                "action" => "index"
            ],
            "logoutRedirect" => [
                "controller" => "Users",
                "action" => "login"
            ]
        ]);

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Auth->allow(['login','register','forgotPassword']);
        $auth = $this->Auth->user();
        $authDisplayName = null;
        $authOfficeLine = null;
        if (!empty($auth)) {
            $authDisplayName = $auth['username'] ?? null;
            if (!empty($auth['id'])) {
                $userRecord = null;
                try {
                    $this->loadModel('Students');
                    $student = $this->Students->find()
                        ->select(['firstname', 'middlename', 'lastname'])
                        ->where(['user_id' => $auth['id']])
                        ->first();
                    if ($student) {
                        $parts = array_filter([
                            $student->firstname ?? null,
                            $student->middlename ?? null,
                            $student->lastname ?? null,
                        ]);
                        $fullName = trim(implode(' ', $parts));
                        if ($fullName !== '') {
                            $authDisplayName = $fullName;
                        }
                    }
                } catch (\Throwable $e) {
                    // Ignore if students table isn't available.
                }

                try {
                    $this->loadModel('Users');
                    $schema = $this->Users->getSchema();
                    $columns = ['first_name', 'middle_initial', 'last_name', 'suffix', 'degree'];
                    if ($schema->hasColumn('office')) {
                        $columns[] = 'office';
                    }
                    if ($schema->hasColumn('section_unit')) {
                        $columns[] = 'section_unit';
                    }
                    $userRecord = $this->Users->find()
                        ->select($columns)
                        ->where(['id' => $auth['id']])
                        ->first();
                } catch (\Throwable $e) {
                    // Ignore if users table columns aren't available.
                }

                if ($userRecord) {
                    if ($authDisplayName === null || $authDisplayName === ($auth['username'] ?? null)) {
                        $suffix = trim((string)($userRecord->suffix ?? ''));
                        $suffix = rtrim($suffix, " ,");
                        $parts = array_filter([
                            $userRecord->first_name ?? null,
                            $userRecord->middle_initial ?? null,
                            $userRecord->last_name ?? null,
                            $suffix !== '' ? $suffix : null,
                        ]);
                        $fullName = trim(implode(' ', $parts));
                        if ($fullName !== '') {
                            $authDisplayName = $fullName;
                        }
                    }

                    $degree = trim((string)($userRecord->degree ?? ''));
                    if ($degree !== '') {
                        $base = trim((string)($authDisplayName ?? ''));
                        $base = rtrim($base, " ,");
                        if ($base === '' || stripos($base, $degree) === false) {
                            $authDisplayName = $base !== '' ? $base . ', ' . $degree : $degree;
                        }
                    }

                    $office = trim((string)($userRecord->office ?? ($auth['office'] ?? '')));
                    $section = trim((string)($userRecord->section_unit ?? ($auth['section_unit'] ?? '')));
                    $officeParts = array_filter([$office, $section], function ($value) {
                        return $value !== '';
                    });
                    if (!empty($officeParts)) {
                        $officeLabel = implode(' - ', $officeParts);
                        $authOfficeLine = $officeLabel;
                    }
                }
            }
        }

        $this->set(compact('auth', 'authDisplayName', 'authOfficeLine'));
    }
}
