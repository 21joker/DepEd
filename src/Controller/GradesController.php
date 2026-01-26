<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Grades Controller
 *
 * @property \App\Model\Table\GradesTable $Grades
 * @method \App\Model\Entity\Grade[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GradesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $grade = $this->Grades->newEmptyEntity();
        $students = $this->Grades->Students->find('list', ['limit' => 200])->all();

        $this->set(compact('grade','students'));
    }

    public function getGrades()
{
    $this->request->allowMethod(['get']);

    $grades = $this->Grades->find()
        ->contain(['Students'])
        ->all();

    $data = [];

    foreach ($grades as $g) {
        $avg = (
            $g->english +
            $g->science +
            $g->math +
            $g->filipino +
            $g->mapeh
        ) / 5;

        $data[] = [
            'id' => $g->id,
            'name' => $g->student->name,
            'english' => $g->english,
            'science' => $g->science,
            'math' => $g->math,
            'filipino' => $g->filipino,
            'mapeh' => $g->mapeh,
            'average' => number_format($avg, 2),
        ];
    }

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode(['data' => $data]));
}


    /**
     * View method
     *
     * @param string|null $id Grade id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $grade = $this->Grades->get($id, [
            'contain' => ['Students'],
        ]);

        $this->set(compact('grade'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $grade = $this->Grades->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['average'] = $this->Grades->computeAverage($data);
            $grade = $this->Grades->patchEntity($grade, $data);
            if ($this->Grades->save($grade)) {
                $result = ['status' => 'success', 'message' => 'The grades has been saved.'];
            }else{
                $result = ['status' => 'error', 'message' => 'The grades could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        $students = $this->Grades->Students->find('list', ['limit' => 200])->all();
        $this->set(compact('grade', 'students'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Grade id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $grade = $this->Grades->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $grade = $this->Grades->patchEntity($grade, $this->request->getData());
            if ($this->Grades->save($grade)) {
                $result = ['status' => 'success', 'message' => 'The grades has been saved.'];
            }else{
                $result = ['status' => 'error', 'message' => 'The grades could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($grade));
    }

    /**
     * Delete method
     *
     * @param string|null $id Grade id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $grade = $this->Grades->get($id);
        if ($this->Grades->delete($grade)) {
            $result = ['status' => 'success', 'message' => 'The grades has been deleted.'];
        }else{
            $result = ['status' => 'error', 'message' => 'The grades could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
