<?php
namespace App\Controller;

class PapersController extends AppController
{
    public function add()
    {
        $paper = $this->Papers->newEmptyEntity();

        if ($this->request->is('post')) {
            $paper = $this->Papers->patchEntity($paper, $this->request->getData());
            $paper->status = 'pending';

            if ($this->Papers->save($paper)) {
                $this->Flash->success('Paper submitted successfully.');
                return $this->redirect(['action' => 'add']);
            }

            $this->Flash->error('Failed to submit paper.');
        }

        $this->set(compact('paper'));
    }

    public function approve($id)
    {
        $paper = $this->Papers->get($id);
        $paper->status = 'approved';

        if ($this->Papers->save($paper)) {
            $this->Flash->success('Paper approved.');
        }

        return $this->redirect(['action' => 'pending']);
    }
    
}
