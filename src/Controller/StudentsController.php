<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\Mailer;
/**
 * Students Controller
 *
 * @property \App\Model\Table\StudentsTable $Students
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StudentsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $student = $this->Students->newEmptyEntity();

        $this->set(compact('student'));
    }

    public function getStudents()
    {
        $students = $this->Students->find()->contain('Users');
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$students]));

    }

    /**
     * View method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $student = $this->Students->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('student'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $student = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $student = $this->Users->patchEntity($student, $data);
            if ($this->Users->save($student,['associated'=>['Students']])) {
                $result = ['status' => 'success', 'message' => 'The student has been saved.'];
            }else{
                $result = ['status' => 'error', 'message' => 'The student could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        $users = $this->Students->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('student', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $student = $this->Users->get($id, [
            'contain' => ['Students'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $student = $this->Users->patchEntity($student, $data);
            if ($this->Users->save($student,['associated'=>['Students']])) {
                $result = ['status' => 'success', 'message' => 'The student has been saved.'];
            }else{
                $result = ['status' => 'error', 'message' => 'The student could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($student));
    }

    public function sendMessage($id = null)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->get($id);
            $data = $this->request->getData();
            $mailer = new Mailer('default');
            $mailer->setTransport('default');
            $mailer->setFrom(['info@skps.site' => 'Northeastern College'])
                ->setTo($student->email)
                ->setEmailFormat('html')
                ->setSubject($data['subject'])
                ->deliver($data['message']);

            $result = ['status' => 'success', 'message' => 'The message has been sent.'];

            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $student = $this->Students->get($id);
        if ($this->Students->delete($student)) {
            $result = ['status' => 'success', 'message' => 'The student has been deleted.'];
        }else{
            $result = ['status' => 'error', 'message' => 'The student could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
