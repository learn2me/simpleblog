<?php

namespace SimpleBlog\Frontend\Controllers;

use SimpleBlog\Models\Posts,
	SimpleBlog\Models\Members;

class PostsController extends ControllerBase
{
	public $user = null;	

    public function initialize() {
		parent::initialize();
	}
	
    public function indexAction($idPost)
    {
		
		// Sanitize input for SQL injection protection
		$idPost = $this->filter->sanitize($idPost,'int');
		
		$config = $this->getConfig();
		$post = new Posts;
		$limit = $config->application->commentsPerPost;
		$this->view->setVar("loadTarget", "comments");
		$this->view->setVar("currentPage", $limit);
		try{
			$post = $post->getPost($idPost);
			if($post && count($post)){
				// commentsPerPost contena the number of comment to show first time
				$this->view->commentsPerPost = $config->application->commentsPerPost;
				$this->view->post=$post;	
				$this->view->idPost=$post->idPost;	
				if(count($post-comments)==$limit){
					$this->view->setVar("showViewLoadMore", true);
					$this->view->setVar("loadTarget", "comments");
					$this->view->setVar("currentPage", $limit);
				}
			}
		}catch(Exception $e){
			PostsController::log($e->getMessage());
		}
		
		$this->member->set('referer', $this->router->getRewriteUri());
		
		$this->view->setVar("member",$this->member );	
		$this->view->setVar('user',$this->user );	
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());	
	}
}
