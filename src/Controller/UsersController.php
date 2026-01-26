<?php
declare(strict_types=1);

namespace App\Controller;
use function Symfony\Component\Config\Definition\Builder\validate;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }
        $user = $this->Users->newEmptyEntity();

        $this->set(compact('user'));
    }

    public function getUsers()
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }
        $users = $this->Users->find()
            ->select([
                'id',
                'username',
                'role',
                'created',
                'modified',
                'first_name',
                'middle_initial',
                'last_name',
                'email_address',
                'level_of_governance',
            ])
            ->enableHydration(false)
            ->toArray();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$users]));

    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }
        $user = $this->Users->get($id, [
            'contain' => ['Students'],
        ]);


        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $schema = $this->Users->getSchema();
            foreach (['first_name','middle_initial','last_name','email_address','level_of_governance'] as $field) {
                if (!$schema->hasColumn($field)) {
                    unset($data[$field]);
                }
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $result = ['status' => 'success', 'message' => 'The user has been saved.'];
            }else{
                $errors = $user->getErrors();
                $result = [
                    'status' => 'error',
                    'message' => 'The user could not be saved. Please, try again.',
                    'errors' => $errors,
                ];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $userAuth = $this->Auth->user('role');

            if (in_array($userAuth, ['Superuser', 'Administrator'], true)) {
                $data = $this->request->getData();
                $schema = $this->Users->getSchema();
                foreach (['first_name','middle_initial','last_name','email_address','level_of_governance'] as $field) {
                    if (!$schema->hasColumn($field)) {
                        unset($data[$field]);
                    }
                }
                if (empty($data['password'])) {
                    unset($data['password']);
                }
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $plainPassword = (string)$data['password'];
                    if ($plainPassword !== '') {
                        $result = ['status' => 'success', 'message' => 'Your new password is: ' . $plainPassword];
                    } else {
                        $result = ['status' => 'success', 'message' => 'The user has been saved.'];
                    }
                }else{
                    $errors = $user->getErrors();
                    $result = [
                        'status' => 'error',
                        'message' => 'The user could not be saved. Please, try again.',
                        'errors' => $errors,
                    ];
                }
            }else{
                $result = ['status' => 'error', 'message' => 'Your are not allowed to perform edit'];
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($user));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $result = ['status' => 'success', 'message' => 'The user has been delete.'];
        }else{
            $result = ['status' => 'error', 'message' => 'The user could not be delete. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('login');

        $users = $this->Users->find()->count();
        if($users<1){
            return $this->redirect('/Users/register');
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                if (in_array($user['role'], ['Superuser', 'Administrator'], true)) {
                    return $this->redirect(['controller' => 'Requests', 'action' => 'pending']);
                }
                if (in_array($user['role'], ['Manager', 'User'], true)) {
                    return $this->redirect('/request/submit');
                }
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function register()
    {
        $this->viewBuilder()->setLayout('login');

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['role']='Superuser';

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
    }

    public function forgotPassword()
    {
        $this->request->allowMethod(['post']);

        $username = trim((string)$this->request->getData('username'));
        $contact = trim((string)$this->request->getData('contact'));

        if ($username === '' && $contact === '') {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Please enter your username or contact info.',
                ]));
        }

        $this->loadModel('Users');
        $superusers = $this->Users->find()
            ->where(['role' => 'Superuser'])
            ->all();

        if ($superusers->isEmpty()) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'No superuser found to receive the request.',
                ]));
        }

        $user = null;
        if ($username !== '') {
            $user = $this->Users->find()
                ->where(['username' => $username])
                ->first();
        }

        $messageParts = [];
        if ($username !== '') {
            $messageParts[] = "username: {$username}";
        }
        if ($contact !== '') {
            $messageParts[] = "contact: {$contact}";
        }
        $message = 'Forgot password request (' . implode(', ', $messageParts) . ').';

        $this->loadModel('Notifications');
        $now = \Cake\I18n\FrozenTime::now();
        $notifications = [];

        foreach ($superusers as $superuser) {
            $notification = $this->Notifications->newEmptyEntity();
            $notification->recipient_user_id = $superuser->id;
            $notification->type = 'forgot_password';
            $notification->message = $message;
            $notification->ref_id = $user?->id;
            $notification->is_read = false;
            $notification->created = $now;
            $notifications[] = $notification;
        }

        if ($this->Notifications->saveMany($notifications)) {
            $superEmail = \Cake\Core\Configure::read('App.superuserEmail');
            if ($superEmail) {
                try {
                    $email = new \Cake\Mailer\Email('default');
                    $email
                        ->setTo($superEmail)
                        ->setSubject('Forgot Password Request')
                        ->setEmailFormat('text')
                        ->setViewVars([])
                        ->send(
                            "A user requested a password reset.\n" .
                            implode("\n", $messageParts) . "\n" .
                            "Requested at: " . $now->i18nFormat('yyyy-MM-dd HH:mm:ss') . "\n"
                        );
                } catch (\Throwable $e) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode([
                            'status' => 'error',
                            'message' => 'Request saved, but email failed to send.',
                        ]));
                }
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'success',
                    'message' => 'Request sent to superuser.',
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to send request.',
            ]));
    }

    public function getPendingLetters()
{
    $this->request->allowMethod(['ajax']);

    $count = $this->Letters
        ->find()
        ->where(['status' => 'pending'])
        ->count();

    echo json_encode(['count' => $count]);
    exit;
}

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    private function ensureUserManager()
    {
        $role = $this->Auth->user('role');
        if (!in_array($role, ['Superuser', 'Administrator'], true)) {
            $this->Flash->error('You are not allowed to access this page.');
            return $this->redirect(['controller' => 'Requests', 'action' => 'pending']);
        }

        return null;
    }
}
