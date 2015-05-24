<?php

namespace SimpleBlog\Frontend\Controllers;

use SimpleBlog\Models\Posts,
	SimpleBlog\Models\Roles,
	SimpleBlog\Models\Comments,
	SimpleBlog\Models\Members;

class IndexController extends ControllerBase
{

    public function initialize() {
		parent::initialize();
	}
	
	/**
	 * Home Page
	 */
    public function indexAction()
    {
		$config = IndexController::getConfig();	
		// Load only some posts (n latest posts)
		$limit = $config->application->latestPostsLimit;
		try{
			$posts = Posts::getLatestApprovedPosts(array("limit"=> $limit));
			if(count($posts)==$limit){
				$this->view->setVar("showViewLoadMore", true);
				$this->view->setVar("loadTarget", "posts");
				$this->view->setVar("currentPage", $limit);
			}
			$this->view->setVar("posts", $posts);
		}catch(Exception $e){
			IndexController::log($e->getMessage());
		}
		
		// Store the referer to return to it
		// if we are redirected to login page
		if(!$this->member->has("referer")){
			$this->member->set('referer', $this->router->getRewriteUri());
		}
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());	
    }
	
	/**
	 * List Tagged posts with a given Date
	 * @param	string	$date	year and month in the format yyyy-mm
	 */
    public function listByDateAction($date)
    {
		$config = IndexController::getConfig();	
		$date = $this->filter->sanitize($date,array('string','striptags'));
		
		// Use the same view as indexAction
		$this->view->pick("index/index");
		$limit = $config->application->latestPostsLimit;
		$postsPerPage = $config->application->postsPerPage;
		try{
			$posts = Posts::getLatestApprovedPosts(array(
							"conditions"=>"date_format(creation_date,'%Y-%m') = ?1",
							"limit"=> $limit,
							"bind"=>array(1=>$date)));
							
			if(count($posts)==$limit){
				$this->view->setVar("showViewLoadMore", true);
				$this->view->setVar("loadTarget", "posts");
				$this->view->setVar("currentPage", $limit);
				$this->view->setVar("dateTag", $date);
			}
			$this->view->setVar("posts", $posts);
			if(count($posts)===$limit){
				$this->view->setVar("loadTarget", "posts");
				$this->view->setVar("showViewLoadMore", true);
				$this->view->setVar("itemsPerPage", $postsPerPage);
			}
		}catch(Exception $e){
			IndexController::log($e->getMessage());
		}
		
		// Store the referer to return to it
		// if we are redirected to login page
		if(!$this->member->has("referer")){
			$this->member->set('referer', $this->router->getRewriteUri());
		}
		
		// Build the date Tag to display
		if($date){
			$adate = strtotime($date."-01");
			$tagDateLink = date("Y-m",$adate);
			$adate = date("M Y",$adate);
			$this->view->setVar("tagDateLink",$tagDateLink);
			$this->view->setVar("tagDate",$adate);
		}
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());	
    }

	/**
	 * 
	 */
	public function listByAuthorsAction()
	{
		// TODO: Declare $config public in ControllerBAse
		$config = IndexController::getConfig();	
		
		// pick a view
		$this->view->pick("index/list-by-authors");
		try{
			$members = Members::getAuthorsWithPosts();
			if(count($members)){
				$this->view->setVar("authorsList",$members );
			}
		}catch(Exception $e){
			IndexController::log($e->getMessage());
		}
		
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());
	}

	/**
	 * 
	 */
	public function listByReadersAction()
	{
		// pick a view
		$this->view->pick("index/list-by-readers");
		try{
			$members = Members::getReadersWithPosts();
			if(count($members)){
				$this->view->setVar("readersList",$members );
			}
		}catch(Exception $e){
			IndexController::log($e->getMessage());
		}
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());

	}

	/**
	 * 
	 */
	public function contactAction()
	{
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());

	}

	/**
	 * 
	 */
	public function aboutAction()
	{
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());

	}

	/**
	 * 
	 */
	public function listAuthorsAction($idMember)
	{
		$config = IndexController::getConfig();
		// Use the same view as indexAction
		$this->view->pick("index/index");	
		try{
			$idMember = $this->filter->sanitize($idMember,'int');
			$member = Members::findByIdMember($idMember);
			if(count($member)){
				$member =$member[0];
				$posts=$member->getMemberPosts();
				$this->view->setVar("posts", $posts);
			}
		}catch(Exception $e){
			IndexController::log($e->getMessage());
		}
		
		// Store the referer to return to it
		// if we are redirected to login page
		if(!$this->member->has("referer")){
			$this->member->set('referer', $this->router->getRewriteUri());
		}
		
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());

	}

	/**
	 * 
	 */
	public function listReadersAction($idMember)
	{	
		$config = IndexController::getConfig();	
		// Use the same view as indexAction
		$this->view->pick("partials/comments-list");
		try{
			$idMember = $this->filter->sanitize($idMember,'int');
			$member = Members::findByIdMember($idMember);
			if(count($member)){
				$member =$member[0];
				$comments=$member->getMemberComments();
				$post = new \StdClass;
				$post->comments =$comments;
				$this->view->setVar("post", $post);
			}
		}catch(Exception $e){
			IndexController::log($e->getMessage());
		}
		
		// Store the referer to return to it
		// if we are redirected to login page
		if(!$this->member->has("referer")){
			$this->member->set('referer', $this->router->getRewriteUri());
		}
		
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());

	}

	/**
	 * List More Posts or More Comemnts (Ajax) action
	 */
	public function loadMoreAction()
	{
		$config = IndexController::getConfig();	;
			//~ trace_die($this->user);
		$this->view->disable();
		
		$today = date('Y-m-d H:i:s');
		
		if($this->request->isAjax()){
			$currentPage = $this->request->getPost("currentPage","int");
			$idMember = $this->request->getPost("idMember","int");
			$target = $this->request->getPost("target",array("string","striptags"));
			
			$formToken= $this->security->getToken();
			$formTokenKey=$this->security->getTokenKey();
		
			if(strcasecmp($target,'posts')===0){	
				$date = $this->request->getPost("date",array("string","striptags"));
				$postsPerPage = $config->application->postsPerPage;			
				$limit = $currentPage;
				$offset = $postsPerPage;
				
				$args=array(
					"limit"=>$limit,
					"offset"=>$offset,
					"idMember" => $idMember 
				);
				
				if(isset($date)){
					$args["conditions"]="date_format(creation_date,'%Y-%m') = ?1";
					$args["bind"]=array(1=>$date);
				}
				$posts = Posts::getMorePosts($args);
				
				$result =  array(
					"error" 	=> false,
					"success" 	=> true,
					"date"		=>$today,
					"items" 	=> $posts,
					"target" => 'posts',
					"currentPage"=> $limit+count($posts),
					"formToken" => $formToken,
					"formTokenKey" => $formTokenKey
				);
				
			}elseif(strcasecmp($target,'comments')===0){	
				$commentsPerPost = $config->application->commentsPerPost;			
				$limit = $currentPage;
				$offset = $commentsPerPost;
				$idPost = $this->request->getPost("idPost","int");
				$args=array(
					"idPost"=>$idPost,
					"limit"=>$limit,
					"offset"=>$offset,
					"idMember" => $idMember 
				);
				$comments = Comments::getMoreComments($args);
				
				$result =  array(
					"error" 	=> false,
					"success" 	=> true,
					"date"		=>$today,
					"items" 	=> $comments,
					"target" => 'comments',
					"currentPage"=> $limit+count($comments),
					"formToken" => $formToken,
					"formTokenKey" => $formTokenKey
				);
				
			}else{
				
				$result =  array(
					"error" 	=> true,
					"success" 	=> false,
					"date"		=>$today,
					"message" 	=> "no_action_was_taken",
				);
			}
			print json_encode($result);
			exit;
		}else{
			$message = "no_direct_access";
			echo $today,'<br/>',$message;
			exit;
		}
	}

	/**
	 * Show 404 Error
	 */
	public function route404Action()
	{

	}
}
