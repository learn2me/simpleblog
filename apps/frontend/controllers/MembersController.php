<?php

namespace SimpleBlog\Frontend\Controllers;

use SimpleBlog\Models\Members,
	\Phalcon\Mvc\View,
	SimpleBlog\Models\Roles,
	SimpleBlog\Models\Posts,
	SimpleBlog\Models\Comments,	
	\Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
	
class MembersController extends ControllerBase {

    public function initialize() {
		parent::initialize();
	}
	
	/**
	 * logout the Member and cleanup session
	 * @return boolean
	 */
    private function logout() {
		$this->user->setLogout();
		$this->user = null;
		$this->member->remove("loggedin");		
		$this->member->remove("idMember");		
		$this->member->remove("referer");		
		$this->session->destroy();
		$this->view->setVar("user",$this->user );
		$this->view->setVar("member",$this->member );
		$this->view->setVar("message",$this->message );
		$config= ControllerBase::getConfig();
		$this->response->redirect($config->application->site_link);
		
		$result =true;
	}
	
	/**
	 * Check Member loggin data
	 * @return boolean
	 */
    private function login() {
		$result =false;		
		$config = IndexController::getConfig();
		$password = $this->request->getPost("password",array("string","striptags"));
		$email = $this->request->getPost("email","email");
		if(isset($password,$email)) {	
			
			// Get the Member using credentials				
			$today = date('Y-m-d H:i:s');
			$password = md5($password . $config->application->salt );
			$users = Members::find(array(
				"conditions"=>"email =?1 AND password=?2 AND status='active'" ,
				"limit"=>1,
				"bind"=>array(1=>$email,2=>$password)
			));
			
			// count>0 if Member in data base. Else credentials are wrong
			if(count($users)){
				$this->user = $users[0];
				
				// Set the member is loggedin
				$this->user->setLoggedin();
				$this->member->set("idMember",$this->user->getIdMember());
				
				
				$this->view->setVar("user",$this->user );
				$this->view->setVar("member",$this->member );
				$this->member->set("loggedin",true);
				$result =true;
				
			}else{ // End checking if registred Member
				$this->message->set('text',"error_please_check_email_password");
				$this->message->set('type','error');					
			}
					
		}else{ // End checking Fields
			$this->message->set('text',"error_some_data_are_missing_or_invalid");
			$this->message->set('type','error');
		}
		return $result;
	}
	
	/**
	 * Check if the user is logged in or not
	 * The user will be redirected automatically
	 * to login page if not connected
	 * @return boolean
	 */
    private function checkAuth() {
		$result = false;
		// Verify if variable loggedin is set
		if(!$this->member->has('loggedin')){
			$this->message->set('text',"you_must_create_an_account_or_sign_in_to_access_this_area");
			$this->message->set('type','error');
			$this->view->setVar('message',$this->message);
		}else{
			$users = (new Members())->find($this->member->has('idMember'));
			if(count($users)){
				$this->user = $users[0];
			}
			$result =true;
		}	
		return $result;		
	}
	
	/**
	 * Display login form
	 **/
    public function loginAction() {		
		
		$result =false;		
		// Variable used to avoid displaying login form twice
		$this->view->setVar("loginPage","loginPage" );
		if($this->request->isPost()){
			// Avoid to resend the same data form twice
			// And protect against CSRF
			if ($this->security->checkToken()) {
			//~ if (true) {
				$login = $this->request->getPost("login",array("string","striptags"));
				$logout = $this->request->getPost("logout",array("string","striptags"));
				if(isset($login)){
					if($this->login()){	
						$referer= "/";
						if($this->member->has("referer")){
							$referer= $this->member->get("referer");
							$this->member->remove('referer');
						}				
						$this->message->set('text',"you_are_logged_in");
						$this->message->set('type','success');
						$this->view->setVar("message",$this->message );						
						$config= ControllerBase::getConfig();
						$link= $config->application->site_link.$referer;
						$this->response->redirect($link);
					}
				}elseif(isset($logout)){
					$this->logout();				
				}
				
			}else{ // End checking for CSRF
				$this->message->set('text',"error_data_form_are_expired");
				$this->message->set('type','error');
			}
		}
		if(!$this->member->has("referer")){
			$this->member->set('referer', $this->router->getRewriteUri());
		}
		$this->view->setVar("member",$this->member );
		// Token for CSRF
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());
	}
	
	/**
	 * Add new Comment
	 * @param integer	Post id. The comment will belong to this post
	 */
    public function addCommentAction($idPost) {	
		if($this->request->isAjax()){
			$this->view->disable();
		}
	
		// If not authantificated redirect to /login
		if(!$this->checkAuth()){						
			$this->response->redirect('/login');
			$this->view->disable();
		}
		
		// Sanitize input for SQL injection protection
		$idPost = $this->filter->sanitize($idPost,'int');
		if($this->request->isPost()){
			
			// Avoid to resend the same data form twice
			// And protect against CSRF
			if ($this->security->checkToken()) {
				$content = $this->request->getPost("comment",array("string","striptags"));
				$idPost = $this->request->getPost("idPost","int");
				if(isset($idPost,$content)) {					
						
						// Emulate ACL Check with success
						if(!is_null($this->user) && $this->user->canAddComment()){
							
							$transactionManager = new TransactionManager();
							$transaction = $transactionManager->get();
							$today = date('Y-m-d H:i:s');
							$newComment = new Comments();
							$newComment->setContent($content);
							$newComment->setPostId($idPost);
							$newComment->setAuthorId($this->user->getIdMember());
							$newComment->setCreationDate($today);
							$newComment->setRevision(1);
							
							// TODO : Add constant PENDING
							$newComment->setStatus("pending");
							// Because no backend is implemented
							// Comment status is set to approved to display 
							// immediately
							$newComment->setStatus("approved");
							
							// Convert IP address to long
							// TODO : check if the IP is valid or not. If not print a message and exit
							$newComment->setIp4(ip2long($_SERVER["REMOTE_ADDR"]));
							$newComment->setTransaction($transaction);
							try {
								// If inserting failed Rollback
								if($newComment->save()==false){
									$this->message->set('text',"error_when_inserting_data_please_try_again");
									$this->message->set('type','error');
									// Return a message error for Try Catch
									$transaction->rollback($newComment->getMessages());
								}else{
									$transaction->commit();
									$this->view->setVar('commentAdded',true);
									$this->message->set('idComment',$newComment->getIdComment());
									$this->message->set('text',"comment_added_successfully");
									$this->message->set('type','success');
								}
							}catch(Exception $e){
								MembersController::log($e->getMessage());
							}
							$this->message->set('text',"comment_successfully_submited");
							$this->message->set('type','success');
						}else{ // End verifiyng if author can add comment
							$this->message->set('text',"error_you_dont_have_permission_to_execute_the_action");
							$this->message->set('type','error');
						}
				}else{ // End checking Fields
					$this->message->set('text',"error_some_data_are_missing_or_invalid");
					$this->message->set('type','error');
				}
			}else{ // End checking for CSRF
				$this->message->set('text',"error_data_form_are_expired");
				$this->message->set('type','error');
			}
		}	
		if($this->request->isAjax()){
			if(!isset($this->message) || !($this->message->has("idComment") && $this->message->has("type") && $this->message->has("text"))){
				$result =  array(
					"idComment" 	=> $this->message->get("idComment"),
					"idPost" 	=> $idPost,
					"error" 	=> true,
					"success" 	=> false,
					"date"		=>$today,
					"message" 	=> "no_action_was_taken",
				);
			}else{
				$content = $this->request->getPost("comment",array("string","striptags"));
				// TODO: Get date after success using javascript object Date
				$today = date('Y-m-d H:is');
				$error=$this->message->get("type")==="error";
				$message=$this->message->get("text");
				$result =  array(
					"idComment" 	=> $this->message->get("idComment"),
					"idPost" 	=> $idPost,
					"error" 	=> $error,
					"success" 	=> !$error,
					"date"		=>$today,
					"content" 	=> $content,
					"message" 	=> $message,
					'formToken' => $this->security->getToken(),
					'formTokenKey'=> $this->security->getTokenKey()
				);
				
			}
			print json_encode($result);
			exit;
		}
	
		// Pass variable to the view
		$this->view->idPost = $idPost;
		$post = new Posts;
		try{
			// Get Post details
			$post = $post->getPost($idPost);
			if($post && count($post)){
				$this->view->post = $post;
			}
				
		}catch(Exception $e){
			MembersController::log($e->getMessage());
		}
		
		// Pass variables to the view
		$this->view->setVar('message',$this->message);	
		$this->view->setVar('user',$this->user );	
		// Generate CSRF Token for the form
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());
	}

	/**
	 * Update or Delete comment
	 * @param	integer	id comment to be updated or deleted
	 */ 
    public function updateCommentAction($idComment) {
			
		// If not authantificated redirect to /login
		if(!$this->checkAuth()){			
			$this->response->redirect('/login');
			$this->view->disable();
		}
		
		// Sanitize input for SQL injection protection
		$idComment = $this->filter->sanitize($idComment,'int');
		$idPost = $this->request->getPost("idPost","int");
		if($this->request->isPost() && $idComment){
			
			// Avoid to resend the same data form twice
			// And protect against CSRF
			if ($this->security->checkToken()) {
				
				// Sanitize input for SQL injection protection
				$deleteComment = $this->request->getPost("deleteComment",array("string","striptags"));
				$updateComment = $this->request->getPost("updateComment",array("string","striptags"));
				$content = $this->request->getPost("comment",array("string","striptags"));
				
				if(isset($content) && (isset($updateComment) || isset($deleteComment))){
					$today = date('Y-m-d H:i:s');
					
					// for security: get the comment data first to check the author
					$comment = Comments::find($idComment);
					if($comment && count($comment)){
						$comment = $comment[0];
						
							// Checking the comment author
							if($this->user->isTheAuthor($comment->getAuthorId())){
								if(isset($updateComment)){	
									
									// Updating comment content			
									$transactionManager = new TransactionManager();
									$transaction = $transactionManager->get();
									
									// Each time a comment is added or its content modifed
									// we need to approve the change
									$comment->setStatus("pending");
									
									// Because no backend is implemented
									// Comment status is set to approved to display 
									// immediately
									$comment->setStatus("approved");
									
									$comment->setContent($content);
									$comment->incRevision();
									$comment->setModificationDate($today);
									$comment->setTransaction($transaction);							
									// If inserting failed Rollback
									if($comment->save()==false){
										$this->message->set('text',"error_when_deleting_data_please_try_again");
										$this->message->set('type','error');
										// Return a message error for Try Catch
										$transaction->rollback($comment->getMessages());
									}else{
										$transaction->commit();
										$this->view->setVar('commentAdded',true);
										$this->message->set('text',"comment_updated_successfully");
										$this->message->set('type','success');
									}
									
								}else // End Updating comemnt content
									if(isset($deleteComment)){
									
									// the comment remain on the database. No deletetion.
									// The comment status change to deleted
									$transactionManager = new TransactionManager();
									$transaction = $transactionManager->get();
									// TODO : Add constant DELETED
									$comment->setStatus("deleted");
									$comment->setModificationDate($today);
									$comment->setTransaction($transaction);							
									// If inserting failed Rollback
									if($comment->save()==false){
										$this->message->set('text',"error_when_deleting_data_please_try_again");
										$this->message->set('type','error');
										// Return a message error for Try Catch
										$transaction->rollback($comment->getMessages());
									}else{
										$transaction->commit();
										$this->view->setVar('commentAdded',true);
										$this->message->set('text',"comment_deleted_successfully");
										$this->message->set('type','success');
									}
										
								}// End deletetion comment
							}
						
					}// End testing valid comment
					
				}else{ // End checking Fields
					$this->message->set('text',"error_some_data_are_missing_or_invalid");
					$this->message->set('type','error');
				}
			}
		}
		$this->response->redirect("posts/".$idPost);
	}
	
	/**
	 * Add new Post
	 */
    public function addPostAction() {	
		// If not authantificated redirect to /login
		if(!$this->checkAuth()){			
			$this->response->redirect('/login');
			$this->view->disable();
		}
		
		$config= ControllerBase::getConfig();
		if($this->request->isPost()){
			
			// Avoid to resend the same data form twice
			// And protect against CSRF
			if ($this->security->checkToken()) {
				$content = $this->request->getPost("content",array("string","striptags"));
				$title = $this->request->getPost("title",array("string","striptags"));
				$excerpt = $this->request->getPost("excerpt",array("string","striptags"));
				if(isset($title,$content,$excerpt)) {					
						
						// Emulate ACL Check with success
						if(!is_null($this->user) && $this->user->canAddPost()){
							
							$transactionManager = new TransactionManager();
							$transaction = $transactionManager->get();
							$today = date('Y-m-d H:i:s');
							
							// Get blog Setting from common config.ini
							$acceptComments = $config->blog->acceptComments;
							if($acceptComments){
								$acceptComments=1;
							}else{
								$acceptComments=0;
							}
							// Populate Post
							$newPost = new Posts();
							$newPost->setTitle($title);
							$newPost->setContent($content);
							$newPost->setExcerpt($excerpt);
							$newPost->setAcceptComments($acceptComments);
							$newPost->setAuthorId($this->user->getIdMember());
							$newPost->setCreationDate($today);
							$newPost->setRevision(1);
							
							// TODO : Add constant PENDING
							$newPost->setStatus("pending");
							// Because no backend is implemented
							// Comment status is set to approved to display 
							// immediately
							$newPost->setStatus("approved");
							
							// Convert IP address to long
							// TODO : check if the IP is valid or not. If not print a message and exit
							$newPost->setIp4(ip2long($_SERVER["REMOTE_ADDR"]));
							$newPost->setTransaction($transaction);
							try {
								// If inserting failed Rollback
								if($newPost->save()==false){
									$this->message->set('text',"error_when_inserting_data_please_try_again");
									$this->message->set('type','error');
									// Return a message error for Try Catch
									$transaction->rollback($newPost->getMessages());
								}else{
									$transaction->commit();
									$this->view->setVar('postAdded',true);
									$this->message->set('text',"post_added_successfully");
									$this->message->set('type','success');									
								}
							}catch(Exception $e){
								MembersController::log($e->getMessage());
							}
						}else{ // End verifiyng if author can add comment
							$this->message->set('text',"error_you_dont_have_permission_to_execute_the_action");
							$this->message->set('type','error');
						}
				}else{ // End checking Fields
					$this->message->set('text',"error_some_data_are_missing_or_invalid");
					$this->message->set('type','error');
				}
			}else{ // End checking for CSRF
				$this->message->set('text',"error_data_form_are_expired");
				$this->message->set('type','error');
			}
		}
			
		// Generate CSRF Token for the form
		$this->view->setVar('formToken', $this->security->getToken());
		$this->view->setVar('formTokenKey', $this->security->getTokenKey());
		

		// Pass variables to the view
		$this->view->setVar('message',$this->message);	
		$this->view->setVar('user',$this->user );
		
		// Redirect to home page to display post					
		$link= $config->application->site_link;
		$this->response->redirect($link);
		
	}

	/**
	 * Update or Delete comment
	 * @param	integer	id comment to be updated or deleted
	 */ 
    public function updatePostAction() {
			
		// If not authantificated redirect to /login
		if(!$this->checkAuth()){			
			$this->response->redirect('/login');
			$this->view->disable();
		}
		// Sanitize input for SQL injection protection
		$idPost = $this->request->getPost("idPost","int");
		if($this->request->isPost() && $idPost){
			
			// Avoid to resend the same data form twice
			// And protect against CSRF
			//~ if ($this->security->checkToken()) {
			if (true) {
				
				// Sanitize input for SQL injection protection
				$deletePost = $this->request->getPost("deletePost",array("string","striptags"));
				$updatePost = $this->request->getPost("updatePost",array("string","striptags"));
				$title = $this->request->getPost("title",array("string","striptags"));
				$excerpt = $this->request->getPost("excerpt",array("string","striptags"));
				$content = $this->request->getPost("content",array("string","striptags"));
				
				if((isset($content,$title,$excerpt) && isset($updatePost)) || isset($deletePost)){
					$today = date('Y-m-d H:i:s');
					
					// for security: get the comment data first to check the author
					$post = Posts::find($idPost);
					if($post && count($post)){
						$post = $post[0];
						
							// Checking the comment author
							if($this->user->isTheAuthor($post->getAuthorId())){
								if(isset($updatePost)){	
									
									// Updating post content			
									$transactionManager = new TransactionManager();
									$transaction = $transactionManager->get();
									
									// Each time a post is added or its content modifed
									// we need to approve the change
									$post->setStatus("pending");
									
									// Because no backend is implemented
									// Comment status is set to approved to display 
									// immediately
									$post->setStatus("approved");
									
									$post->setContent($content);
									$post->setTitle($title);
									$post->setExcerpt($excerpt);
									$post->incRevision();
									$post->setModificationDate($today);
									$post->setTransaction($transaction);							
									// If inserting failed Rollback
									if($post->save()==false){
										$this->message->set('text',"error_when_deleting_data_please_try_again");
										$this->message->set('type','error');
										// Return a message error for Try Catch
										$transaction->rollback($post->getMessages());
									}else{
										$transaction->commit();
										$this->view->setVar('commentAdded',true);
										$this->message->set('text',"post_updated_successfully");
										$this->message->set('type','success');
									}
									$this->response->redirect("posts/".$idPost);									
								}else // End Updating post content
									if(isset($deletePost)){

									// the comment remain on the database. No deletetion.
									// The comment status change to deleted
									$transactionManager = new TransactionManager();
									$transaction = $transactionManager->get();
									
									// TODO : Add constant DELETED
									$post->setStatus("deleted");
									$post->setModificationDate($today);
									$post->setTransaction($transaction);							
									// If inserting failed Rollback
									if($post->save()==false){
										$this->message->set('text',"error_when_deleting_data_please_try_again");
										$this->message->set('type','error');
										// Return a message error for Try Catch
										$transaction->rollback($post->getMessages());
									}else{
										$transaction->commit();
										$this->view->setVar('postAdded',true);
										$this->message->set('text',"post_deleted_successfully");
										$this->message->set('type','success');
									}
										
								}// End deletetion post
							}
						
					}// End testing valid post
					
				}else{ // End checking Fields
					$this->message->set('text',"error_some_data_are_missing_or_invalid");
					$this->message->set('type','error');
				}
			}
		}
		// Redirect to home page to display post					
		$link= $config->application->site_link;
		$this->response->redirect($link);
	}	
}
