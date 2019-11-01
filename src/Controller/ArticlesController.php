<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
	 public function initialize()
    {
    	parent::initialize();
    	$this->Auth->allow(['tags']);
    }
	 public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    public function view($slug = null)//表出
	{
	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    $this->set(compact('article'));
	}

	public function add() //追加
    {
       $article = $this->Articles->newEntity();
       if ($this->request->is('post')) {
           $article = $this->Articles->patchEntity($article,$this->request->getData());
           //Changed :set the user_id from the session.
           $article->use_id = $this->Auth->user('id');

           if ($this->Articles->save($article)) {
           	   $this->Flash->success(__('You article has been saved.'));
           	   return $this->redirect(['action' => 'index']);
               }
               $this->Flash->error(__('Unable to add article'));       	
           }
            $this->set('article', $article);              
    }

    public function edit($slug)
    {
    	$article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();
    	if ($this->request->is(['post','put'])) {
    		$this->Articles->patchEntity($article,$this->request->getData(),[
    			// Added: Disable modification of user_id.
    			'accessibleFields' => ['user_id' => false]
    		]);
    		if ($this->Articles->save($article)) {
    			$this->Flash->success(__('Your article has been updated.'));
    			return $this->redirect(['action' => 'index']);
    			# code...
    		}
    		$this->Flash->error(__('Unable to update your article'));
    	}
    	$this->set('article',$article);
    }

    public function delete($slug)
    {
    	$this->request->allowMethod(['post','delete']);
    	$article = $this->Articles->findBySlug($slug)->firstOrFail();
    	if ($this->Articles->delete($article)) {
    		$this->Flash->success(__('The {0} article has been deleted.', $article->title));
    		return $this->redirect(['action' => 'index']);
    	}
    }
    public function isAuthorized($user)
    {
    	$action = $this->request->getParam('action');
    	if (in_array($action, ['add','tags'])) {
    		return true;
    		# code...
    	}
    	//all other actions require a slug.
    	$slug = $this->Articles->findBySlug($slug)->first();
    	return $article->user_id == $user['id'];
    	# code...
    }
}