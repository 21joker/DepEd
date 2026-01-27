<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

class LogsController extends AppController
{
    public function index()
    {
        $role = $this->Auth->user('role');
        if ($role !== 'Superuser') {
            $this->Flash->error('You are not allowed to access this page.');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->loadModel('LoginLogs');
        $this->loadModel('Users');

        $period = (string)$this->request->getQuery('period', 'all');
        $date = (string)$this->request->getQuery('date', '');
        $month = (string)$this->request->getQuery('month', '');
        $year = (string)$this->request->getQuery('year', '');
        $search = trim((string)$this->request->getQuery('q', ''));

        $now = FrozenTime::now();
        if ($period === 'daily' && $date === '') {
            $date = $now->i18nFormat('yyyy-MM-dd');
        } elseif ($period === 'monthly' && $month === '') {
            $month = $now->i18nFormat('yyyy-MM');
        } elseif ($period === 'yearly' && $year === '') {
            $year = $now->i18nFormat('yyyy');
        }

        $loginQuery = $this->LoginLogs->find()
            ->contain(['Users'])
            ->orderDesc('LoginLogs.created');

        $usersQuery = $this->Users->find()
            ->orderDesc('Users.created');

        if ($period === 'daily' && $date !== '') {
            $loginQuery->where(function ($exp) use ($date) {
                return $exp->like('LoginLogs.created', $date . '%');
            });
            $usersQuery->where(function ($exp) use ($date) {
                return $exp->like('Users.created', $date . '%');
            });
        } elseif ($period === 'monthly' && $month !== '') {
            $loginQuery->where(function ($exp) use ($month) {
                return $exp->like('LoginLogs.created', $month . '%');
            });
            $usersQuery->where(function ($exp) use ($month) {
                return $exp->like('Users.created', $month . '%');
            });
        } elseif ($period === 'yearly' && $year !== '') {
            $loginQuery->where(function ($exp) use ($year) {
                return $exp->like('LoginLogs.created', $year . '%');
            });
            $usersQuery->where(function ($exp) use ($year) {
                return $exp->like('Users.created', $year . '%');
            });
        }

        if ($search !== '') {
            $loginQuery->where(function ($exp) use ($search) {
                return $exp->or_([
                    'LoginLogs.username LIKE' => '%' . $search . '%',
                    'LoginLogs.role LIKE' => '%' . $search . '%',
                ]);
            });
            $usersQuery->where(function ($exp) use ($search) {
                return $exp->or_([
                    'Users.username LIKE' => '%' . $search . '%',
                    'Users.role LIKE' => '%' . $search . '%',
                ]);
            });
        }

        $loginLogs = $loginQuery->all();
        $userLogs = $usersQuery->all();

        $this->set(compact('loginLogs', 'userLogs', 'period', 'date', 'month', 'year', 'search'));
    }

    public function export()
    {
        $role = $this->Auth->user('role');
        if ($role !== 'Superuser') {
            $this->Flash->error('You are not allowed to access this page.');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->loadModel('LoginLogs');
        $this->loadModel('Users');

        $period = (string)$this->request->getQuery('period', 'all');
        $date = (string)$this->request->getQuery('date', '');
        $month = (string)$this->request->getQuery('month', '');
        $year = (string)$this->request->getQuery('year', '');
        $search = trim((string)$this->request->getQuery('q', ''));

        $loginQuery = $this->LoginLogs->find()
            ->orderDesc('LoginLogs.created');
        $usersQuery = $this->Users->find()
            ->orderDesc('Users.created');

        if ($period === 'daily' && $date !== '') {
            $loginQuery->where(function ($exp) use ($date) {
                return $exp->like('LoginLogs.created', $date . '%');
            });
            $usersQuery->where(function ($exp) use ($date) {
                return $exp->like('Users.created', $date . '%');
            });
        } elseif ($period === 'monthly' && $month !== '') {
            $loginQuery->where(function ($exp) use ($month) {
                return $exp->like('LoginLogs.created', $month . '%');
            });
            $usersQuery->where(function ($exp) use ($month) {
                return $exp->like('Users.created', $month . '%');
            });
        } elseif ($period === 'yearly' && $year !== '') {
            $loginQuery->where(function ($exp) use ($year) {
                return $exp->like('LoginLogs.created', $year . '%');
            });
            $usersQuery->where(function ($exp) use ($year) {
                return $exp->like('Users.created', $year . '%');
            });
        }

        if ($search !== '') {
            $loginQuery->where(function ($exp) use ($search) {
                return $exp->or_([
                    'LoginLogs.username LIKE' => '%' . $search . '%',
                    'LoginLogs.role LIKE' => '%' . $search . '%',
                ]);
            });
            $usersQuery->where(function ($exp) use ($search) {
                return $exp->or_([
                    'Users.username LIKE' => '%' . $search . '%',
                    'Users.role LIKE' => '%' . $search . '%',
                ]);
            });
        }

        $loginLogs = $loginQuery->all();
        $userLogs = $usersQuery->all();

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['type', 'time', 'username', 'role', 'ip', 'user_agent', 'name']);

        foreach ($loginLogs as $log) {
            fputcsv($handle, [
                'login',
                (string)($log->created ?? ''),
                (string)($log->username ?? ''),
                (string)($log->role ?? ''),
                (string)($log->ip ?? ''),
                (string)($log->user_agent ?? ''),
                '',
            ]);
        }

        foreach ($userLogs as $user) {
            $name = trim((string)($user->first_name ?? '') . ' ' . (string)($user->last_name ?? ''));
            fputcsv($handle, [
                'account_created',
                (string)($user->created ?? ''),
                (string)($user->username ?? ''),
                (string)($user->role ?? ''),
                '',
                '',
                $name !== '' ? $name : '—',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $filename = 'logs_' . date('Ymd_His') . '.csv';

        return $this->response
            ->withType('csv')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withStringBody($csv);
    }

    public function clearLoginLogs()
    {
        $role = $this->Auth->user('role');
        if ($role !== 'Superuser') {
            $this->Flash->error('You are not allowed to access this page.');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->request->allowMethod(['post']);

        $this->loadModel('LoginLogs');
        $this->LoginLogs->deleteAll([]);

        $this->Flash->success('Login logs cleared.', ['key' => 'logs']);
        return $this->redirect(['action' => 'index']);
    }
}
