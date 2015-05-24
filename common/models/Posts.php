<?php

namespace SimpleBlog\Models;

use SimpleBlog\Frontend\Controllers\PostsController;
use SimpleBlog\Models\Comments;

class Posts extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id_post;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $content;

    /**
     *
     * @var string
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $ip4;

    /**
     *
     * @var string
     */
    protected $excerpt;

    /**
     *
     * @var integer
     */
    protected $accept_comments;

    /**
     *
     * @var integer
     */
    protected $ip6;

    /**
     *
     * @var string
     */
    protected $creation_date;

    /**
     *
     * @var string
     */
    protected $modification_date;

    /**
     *
     * @var integer
     */
    protected $revision;

    /**
     *
     * @var integer
     */
    protected $post_id;

    /**
     *
     * @var integer
     */
    protected $author_id;

    /**
     * Method to set the value of field id_post
     *
     * @param integer $id_post
     * @return $this
     */
    public function setIdPost($id_post)
    {
        $this->id_post = $id_post;

        return $this;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field ip4
     *
     * @param integer $ip4
     * @return $this
     */
    public function setIp4($ip4)
    {
        $this->ip4 = $ip4;

        return $this;
    }

    /**
     * Method to set the value of field excerpt
     *
     * @param string $excerpt
     * @return $this
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Method to set the value of field ip6
     *
     * @param integer $ip6
     * @return $this
     */
    public function setIp6($ip6)
    {
        $this->ip6 = $ip6;

        return $this;
    }

    /**
     * Method to set the value of field creation_date
     *
     * @param string $creation_date
     * @return $this
     */
    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * Method to set the value of field modification_date
     *
     * @param string $modification_date
     * @return $this
     */
    public function setModificationDate($modification_date)
    {
        $this->modification_date = $modification_date;

        return $this;
    }

    /**
     * Method to set the value of field revision
     *
     * @param integer $revision
     * @return $this
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;

        return $this;
    }

    /**
     * Method to set the value of field post_id
     *
     * @param integer $post_id
     * @return $this
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * Method to set the value of field author_id
     *
     * @param integer $author_id
     * @return $this
     */
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;

        return $this;
    }

    /**
     * Returns the value of field id_post
     *
     * @return integer
     */
    public function getIdPost()
    {
        return $this->id_post;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the value of field status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field ip4
     *
     * @return integer
     */
    public function getIp4()
    {
        return $this->ip4;
    }

    /**
     * Returns the value of field excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Returns the value of field ip6
     *
     * @return integer
     */
    public function getIp6()
    {
        return $this->ip6;
    }

    /**
     * Returns the value of field creation_date
     *
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Returns the value of field modification_date
     *
     * @return string
     */
    public function getModificationDate()
    {
        return $this->modification_date;
    }

    /**
     * Returns the value of field revision
     *
     * @return integer
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Returns the value of field post_id
     *
     * @return integer
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Returns the value of field author_id
     *
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    
    /* 
     * Phalcon function
     * This function is loaded immediately after __construct
     **/
    public function initialize()
    {
		// Defining the relationship between tables
        $this->belongsTo('author_id', 'SimpleBlog\Models\Members', 'id_member', array('alias' => 'Members'));
		$this->hasMany('id_post', 'SimpleBlog\Models\Comments', 'post_id', array('alias' => 'Comments'));
    }



	/********************************************************
	 *														*
	 * 				MY OWN CODE START HERE 					*
	 * 														*
	 * *****************************************************/
	 	 
    /**
     * Method to increment the value of field revision
     *
     * @return $this
     */
    public function incRevision()
    {		
        $this->revision += 1;

        return $this;
    }    
    
    // TODO : Override save function (built-in Phalcon function)
    public function save($data = NULL, $whiteList = NULL){
		return parent::save($data , $whiteList );
	}	
    // TODO : Override update function (builtin Phalcon function)
    public function update($data = NULL, $whiteList = NULL){
		return parent::update($data , $whiteList );
	}	
    // TODO : Override delete function (builtin Phalcon function)
    public function delete($data = NULL, $whiteList = NULL){
		return parent::delete($data , $whiteList );
	}	
	
    /**
     * Method to set the value of field accept_comments
     *
     * @param boolean $accept_comments
     * @return $this
     */
    public function setAcceptComments($accept_comments)
    {
        $this->accept_comments = ($accept_comments===true?1:0);

        return $this;
    }

    /**
     * Returns the value of field accept_comments
     *
     * @return boolean
     */
    public function isAcceptComments()
    {
        return intval($this->accept_comments)===1;
    }

    /**
     * check if a post is deleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
		// TODO : Add DELETED to constants file
        return strtolower($this->status) === DELETED;
    }

    /**
     * check if a post is spam
     *
     * @return boolean
     */
    public function isSpam()
    {
		// TODO : Add SPAM to constants file
        return strtolower($this->status) === SPAM;
    }

    /**
     * check if a post is pending
     *
     * @return boolean
     */
    public function isPending()
    {
		// TODO : Add PENDING to constants file
        return strtolower($this->status) === PENDING;
    }

    /**
     * check if a post is approved
     *
     * @return boolean
     */
    public function isApproved()
    {
		// TODO : Add APPROVED to constants file
        return strtolower($this->status) === APPROVED;
    }

    /**
     * Set the post status as deleted
     *
     * @return self
     */
    public function setAsDeleted()
    {
        $this->status = DELETED;
        return $this;
    }

    /**
     * Set the post status as spam
     *
     * @return self
     */
    public function setAsSpam()
    {
        $this->status = SPAM;
        return $this;
    }

    /**
     * Set the post status as pending
     *
     * @return self
     */
    public function setAsPending()
    {
        $this->status = PENDING;
        return $this;
    }

    /**
     * Set the post status as approved
     *
     * @return self
     */
    public function setAsApproved()
    {
        $this->status = APPROVED;
        return $this;
    }

    /**
     * Get approved post comemnts
     * @return mixed
     */
    public function getApprovedComments()
    {
        $result = null;
        // Get all comments
		$comments = Comments::find(array(
							"conditions"=>"post_id =?1 AND status='approved'" ,
							"bind"=>array(1=>$this->getIdPost())
						));
		if(count($comments)){
			// Total comments
			$result["totalComments"] = count($comments);
			$result["comments"] = null;
			$config = PostsController::getConfig();
			
			// Display only the required number set in common config.ini
			$commentsPerPost = $config->application->commentsPerPost;
			$max = count($comments);
			
			// To avoid error IndexOutOfRange, fix the max iteration to the right number
			if($commentsPerPost<$max){
				$max=$commentsPerPost;
			}
			// Return only the data displayed in the template
			for($i=0;$i<$max;$i++){
				$comment=$comments[$i];
				$data =	new \StdClass;
				$data->idComment = $comment->getIdComment();
				$data->content = $comment->getContent();
				$data->postId = $comment->getPostId();
				$data->prettyDateTime = $comment->getCreationDate();
				
				// Id to check if the current user can edit the comment
				$data->authorId = $comment->getAuthorId();
				$data->authorName = "UNKNOWN";
				$author=$comment->getMembers();
				if($author){
					$data->authorName = $author->getNicename();
				}
				$result["comments"][] = $data;	
			}
		}
		return $result;
    }
       
	/**
	 * Get the latest n Posts
	 * @param	$params mixed	filter the posts
	 * @return	mixed	$result	array of posts		
	 * */
    static public function getLatestApprovedPosts($params=null)
    {
		$result = null;	
			
		// Retrieve only approved posts	
		// Check first if $params has a field conditions
		if(isset($params["conditions"])){
			
			// If it s an array add new filter
			if(is_array($params["conditions"])){
				$params["conditions"][] ="status ='approved'" ;
				
			}elseif(is_string($params["conditions"])){
				
				// Else (it s string), concat new filter
				$params["conditions"] .= " AND status ='approved'" ;
			}
		}else{	
			// If the field doesn t exist add a new one (filter)
			$params["conditions"] ="status ='approved'" ;
		}
			
		// Retrieve only approved posts	
		// Check first if $params has a field conditions
		if(isset($params["order"])){
			
			// If it s an array add new filter
			if(is_array($params["order"])){
				$params["order"][] ="creation_date asc" ;
				
			}elseif(is_string($params["order"])){
				
				// Else (it s string), concat new filter
				$params["order"] .= ", creation_date asc" ;
			}
		}else{	
			// If the field doesn t exist add a new one (filter)
			$params["order"] ="creation_date desc" ;
		}
		
		// Get Posts list
		$posts= Posts::find($params);
		if(count($posts)){			
			foreach($posts as $post){
				
				// Return a new object.
				// If we are willing to make template extendable by designers,
				// for security, we want to return only useful data	instead
				// of using : return $posts
				$temp = new \StdClass;
				$temp->idPost = $post->getIdPost();
				$temp->title = $post->getTitle();
				$temp->excerpt = $post->getExcerpt();
				$temp->content = $post->getContent();
				
				// TODO: Define a helper to format a date
				$temp->prettyDateTime = $post->getCreationDate();
				$temp->creationDate = $post->getCreationDate();
				$adate = strtotime($post->getCreationDate());
				$temp->dateCategory = date("M Y",$adate);
				$temp->dateCategoryLink = date("Y-m",$adate);
				
				// TODO : Add UNKNOWN to constants file
				$temp->authorName = "UNKNOWN";
				$temp->authorId = $post->getAuthorId();
				
				// Get the author name's (good job Phalcon team)
				$author=$post->getMembers();
				if($author){
					$temp->authorName = $author->getNicename();
				}				
				$temp->comments = null;
				$comments =$post->getApprovedComments();
				if(is_array($comments)){
					$temp->comments = $comments["comments"];
				}
				$result[] = $temp;
			}
		}
		return $result;
	}	
	
	/**
	 * Get One post by id and some comments details
	 * @param	integer		$idPost post identifiant
	 * @return	mixed
	 */
	static public function getPost($idPost){
		$post=null;
		try{
			$post= Posts::find(array(
				"conditions"=>" id_post=?1 AND status='approved'",
				"bind" =>array(1 => $idPost)
			));
			if($post && count($post)){
				$temp=$post[0];
				$post = new \StdClass;
				$post->idPost = $temp->getIdPost();
				$post->title = $temp->getTitle();
				$post->content = $temp->getContent();
				$post->excerpt = $temp->getExcerpt();
				$post->prettyDateTime = $temp->getCreationDate();
				$post->creationDate = $temp->getCreationDate();
				$adate = strtotime($temp->getCreationDate());
				$post->dateCategory = date("M Y",$adate);
				$post->dateCategoryLink = date("Y-m",$adate);
				
				// TODO : Add UNKNOWN to constants file
				$post->authorName = "UNKNOWN";
				$post->authorId = $temp->getAuthorId();
				
				$author=$temp->getMembers();
				if($author){
					$post->authorName = $author->getNicename();
				}
				$post->comments = null;	
						
				// Get Post Comemnts	
				$comments =$temp->getApprovedComments();
				if(is_array($comments)){
					$post->comments = $comments["comments"];
				}
				
				
			}else{
				$post=null;
			}
		}catch(Exception $e){
			PostsController::log($e->getMessage());
		}
		return $post;
	}

	/**
	 * Get One post by id and some comments details
	 * @param	integer		$idPost post identifiant
	 * @return	mixed
	 */
	static public function getMorePosts($args){
		$result=null;
		try{
			$params =array(
				"conditions"=>"status='approved'",
				"order"	=>"creation_date desc");

			if(!is_null($args["offset"])){
					$params["limit"] = "{$args["limit"]}, {$args["offset"]}";
			}else{
					$params["limit"] = "{$args["limit"]}";
			}
			$posts= Posts::find($params);
			if($posts && count($posts)){
				foreach($posts as $temp){
					$post = new \StdClass;
					$post->idPost = $temp->getIdPost();
					$post->title = $temp->getTitle();
					$post->content = $temp->getContent();
					$post->excerpt = $temp->getExcerpt();
					$post->prettyDateTime = $temp->getCreationDate();
					$post->creationDate = $temp->getCreationDate();
					$adate = strtotime($temp->getCreationDate());
					$post->dateCategory = date("M Y",$adate);
					$post->dateCategoryLink = date("Y-m",$adate);
					
					// TODO : Add UNKNOWN to constants file
					$post->authorName = "UNKNOWN";					
					
					$author=$temp->getMembers();
					if($author){
						$post->authorName = $author->getNicename();
						if(isset($args["idMember"]) && ($args["idMember"]==$temp->getAuthorId()) 
						 && $author->isTheAuthor($temp->getAuthorId()) && $author->canEditPost()){
							$post->canEdit =  true;
						}
					}
					$post->totalComments = 0;
					// Get Post Comemnts	
					$comments =$temp->getApprovedComments();
					if(is_array($comments)){
						$post->totalComments = $comments["totalComments"];
					}
					$result[]=$post;
				}
			}else{
				$result=null;
			}
		}catch(Exception $e){
			PostsController::log($e->getMessage());
		}
		return $result;
	}
}
