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
        $this->refreshUserSchema();
        $users = $this->Users->find()
            ->select($this->getUserSelectFields())
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
        $this->refreshUserSchema();
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $schema = $this->Users->getSchema();
            $rawEsignature = $data['esignature'] ?? null;
            if ($rawEsignature instanceof \Psr\Http\Message\UploadedFileInterface && !$schema->hasColumn('esignature')) {
                $result = [
                    'status' => 'error',
                    'message' => 'E-signature column is missing. Please add `esignature` to the users table.',
                ];
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($result));
            }
            $esignatureError = null;
            $esignatureUpload = null;
            if ($schema->hasColumn('esignature')) {
                $esignatureUpload = $this->extractEsignatureUpload($data, $esignatureError);
                if ($esignatureError !== null) {
                    $result = [
                        'status' => 'error',
                        'message' => $esignatureError,
                    ];
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode($result));
                }
            }
            unset($data['esignature']);
            foreach (['id_number','first_name','middle_initial','last_name','suffix','degree','rank','position','email_address','office','section_unit','esignature'] as $field) {
                if (!$schema->hasColumn($field)) {
                    unset($data[$field]);
                }
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                if ($esignatureUpload instanceof \Psr\Http\Message\UploadedFileInterface) {
                    $esignaturePath = $this->storeEsignatureUpload($esignatureUpload, (int)$user->id);
                    if ($esignaturePath === null) {
                        $result = [
                            'status' => 'error',
                            'message' => 'Failed to store e-signature. Please, try again.',
                        ];
                        return $this->response->withType('application/json')
                            ->withStringBody(json_encode($result));
                    }
                    $this->Users->updateAll(['esignature' => $esignaturePath], ['id' => $user->id]);
                }
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
        $this->refreshUserSchema();
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $userAuth = $this->Auth->user('role');

            if (in_array($userAuth, ['Superuser', 'Administrator'], true)) {
                $data = $this->request->getData();
                $isReset = !empty($data['reset_mode']) && (string)$data['reset_mode'] !== '0';
                if ($isReset) {
                    $resetEmail = strtolower(trim((string)($data['reset_email'] ?? '')));
                    $userEmail = strtolower(trim((string)($user->email_address ?? '')));
                    if ($resetEmail === '' || $userEmail === '' || $resetEmail !== $userEmail) {
                        $result = [
                            'status' => 'error',
                            'message' => 'Email address does not match the user record.',
                        ];
                        return $this->response->withType('application/json')
                            ->withStringBody(json_encode($result));
                    }

                    $upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
                    $lower = 'abcdefghijkmnpqrstuvwxyz';
                    $digits = '23456789';
                    $all = $upper . $lower . $digits;
                    $chars = [
                        $upper[random_int(0, strlen($upper) - 1)],
                        $lower[random_int(0, strlen($lower) - 1)],
                        $digits[random_int(0, strlen($digits) - 1)],
                    ];
                    for ($i = count($chars); $i < 8; $i++) {
                        $chars[] = $all[random_int(0, strlen($all) - 1)];
                    }
                    shuffle($chars);
                    $generatedPassword = implode('', $chars);
                    $user->password = $generatedPassword;
                    if ($this->Users->save($user)) {
                        $result = [
                            'status' => 'success',
                            'message' => 'New password: ' . $generatedPassword,
                        ];
                    } else {
                        $errors = $user->getErrors();
                        $result = [
                            'status' => 'error',
                            'message' => 'Failed to reset password. Please, try again.',
                            'errors' => $errors,
                        ];
                    }

                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode($result));
                }
                unset($data['reset_mode'], $data['reset_email']);
                $schema = $this->Users->getSchema();
                $rawEsignature = $data['esignature'] ?? null;
                if ($rawEsignature instanceof \Psr\Http\Message\UploadedFileInterface && !$schema->hasColumn('esignature')) {
                    $result = [
                        'status' => 'error',
                        'message' => 'E-signature column is missing. Please add `esignature` to the users table.',
                    ];
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode($result));
                }
                $esignatureError = null;
                $esignatureUpload = null;
                if ($schema->hasColumn('esignature')) {
                    $esignatureUpload = $this->extractEsignatureUpload($data, $esignatureError);
                    if ($esignatureError !== null) {
                        $result = [
                            'status' => 'error',
                            'message' => $esignatureError,
                        ];
                        return $this->response->withType('application/json')
                            ->withStringBody(json_encode($result));
                    }
                }
                unset($data['esignature']);
                foreach (['id_number','first_name','middle_initial','last_name','suffix','degree','rank','position','email_address','office','section_unit','esignature'] as $field) {
                    if (!$schema->hasColumn($field)) {
                        unset($data[$field]);
                    }
                }
                if (empty($data['password'])) {
                    unset($data['password']);
                }
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    if ($esignatureUpload instanceof \Psr\Http\Message\UploadedFileInterface) {
                        $esignaturePath = $this->storeEsignatureUpload($esignatureUpload, (int)$user->id);
                        if ($esignaturePath === null) {
                            $result = [
                                'status' => 'error',
                                'message' => 'Failed to store e-signature. Please, try again.',
                            ];
                            return $this->response->withType('application/json')
                                ->withStringBody(json_encode($result));
                        }
                        $this->Users->updateAll(['esignature' => $esignaturePath], ['id' => $user->id]);
                    }
                    $updateFields = [
                        'username',
                        'role',
                        'id_number',
                        'first_name',
                        'middle_initial',
                        'last_name',
                        'suffix',
                        'degree',
                        'rank',
                        'position',
                        'email_address',
                        'office',
                        'section_unit',
                    ];
                    $updateData = [];
                    foreach ($updateFields as $field) {
                        if (array_key_exists($field, $data)) {
                            $value = $data[$field];
                            $updateData[$field] = $value === '' ? null : $value;
                        }
                    }
                    if (!empty($updateData)) {
                        $this->Users->updateAll($updateData, ['id' => $user->id]);
                    }
                    $plainPassword = (string)$data['password'];
                    $freshUser = $this->Users->get($user->id);
                    if ($plainPassword !== '') {
                        $result = [
                            'status' => 'success',
                            'message' => 'Your new password is: ' . $plainPassword,
                            'user' => $freshUser,
                        ];
                    } else {
                        $result = [
                            'status' => 'success',
                            'message' => 'The user has been saved.',
                            'user' => $freshUser,
                        ];
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
        return $this->response
            ->withType('application/json')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
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

    public function debugUser($id = null)
    {
        $response = $this->ensureUserManager();
        if ($response) {
            return $response;
        }

        $this->request->allowMethod(['get']);
        $this->refreshUserSchema();

        $row = null;
        $columns = [];
        try {
            $connection = $this->Users->getConnection();
            $row = $connection
                ->execute('SELECT * FROM users WHERE id = :id', ['id' => $id])
                ->fetch('assoc') ?: null;
            $columns = $connection
                ->execute('SHOW COLUMNS FROM users')
                ->fetchAll('assoc');
        } catch (\Throwable $e) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to query users table.',
                    'error' => $e->getMessage(),
                ]));
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'success',
                'id' => $id,
                'row' => $row,
                'columns' => $columns,
            ]));
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
                $this->request->getSession()->renew();
                $this->request->getSession()->delete('last_request_id');
                $this->Auth->setUser($user);
                try {
                    $this->loadModel('LoginLogs');
                    $log = $this->LoginLogs->newEmptyEntity();
                    $log->user_id = (int)($user['id'] ?? 0) ?: null;
                    $log->username = (string)($user['username'] ?? '');
                    $log->role = (string)($user['role'] ?? '');
                    $log->ip = (string)$this->request->clientIp();
                    $log->user_agent = (string)$this->request->getHeaderLine('User-Agent');
                    $log->created = \Cake\I18n\FrozenTime::now();
                    $this->LoginLogs->save($log);
                } catch (\Throwable $e) {
                    // Ignore logging failures.
                }
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
            if ($role !== 'Approver') {
                $this->Flash->error('You are not allowed to access this page.');
            }
            return $this->redirect(['controller' => 'Requests', 'action' => 'pending']);
        }

        return null;
    }

    private function refreshUserSchema(): void
    {
        try {
            $schema = $this->Users->getConnection()
                ->getSchemaCollection()
                ->describe($this->Users->getTable());
            $this->Users->setSchema($schema);
        } catch (\Throwable $e) {
            // Ignore schema refresh failures.
        }
    }

    private function getUserSelectFields(): array
    {
        $schema = $this->Users->getSchema();
        $fields = [
            'id',
            'username',
            'role',
            'created',
            'modified',
            'id_number',
            'first_name',
            'middle_initial',
            'last_name',
            'suffix',
            'degree',
            'rank',
            'position',
            'email_address',
            'office',
            'section_unit',
            'esignature',
        ];
        $select = [];
        foreach ($fields as $field) {
            if ($schema->hasColumn($field) || in_array($field, ['id', 'username', 'role', 'created', 'modified'], true)) {
                $select[] = $field;
            }
        }
        return $select;
    }

    private function extractEsignatureUpload(array $data, ?string &$error = null): ?\Psr\Http\Message\UploadedFileInterface
    {
        $error = null;
        $file = $data['esignature'] ?? null;
        if (!$file instanceof \Psr\Http\Message\UploadedFileInterface) {
            return null;
        }
        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file->getError() !== UPLOAD_ERR_OK) {
            $error = 'Failed to upload e-signature.';
            return null;
        }
        $clientName = (string)$file->getClientFilename();
        $ext = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
        $allowed = ['png', 'jpg', 'jpeg'];
        if ($ext === '' || !in_array($ext, $allowed, true)) {
            $error = 'E-signature must be a PNG or JPG image.';
            return null;
        }
        return $file;
    }

    private function storeEsignatureUpload(\Psr\Http\Message\UploadedFileInterface $file, int $userId): ?string
    {
        if ($userId <= 0) {
            return null;
        }
        $clientName = (string)$file->getClientFilename();
        $ext = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'png';
        }
        $filename = sprintf(
            'esignature_%s_%s.%s',
            date('Ymd_His'),
            bin2hex(random_bytes(4)),
            $ext
        );
        $directory = WWW_ROOT . 'uploads' . DS . 'esignatures' . DS . $userId;
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            return null;
        }
        try {
            $file->moveTo($directory . DS . $filename);
        } catch (\Throwable $e) {
            return null;
        }
        return 'uploads/esignatures/' . $userId . '/' . $filename;
    }
}
