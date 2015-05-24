<?php

namespace SimpleBlog\Models;

class Comments extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id_comment;

    /**
     *
     * @var integer
     */
    protected $author_id;

    /**
     *
     * @var string
     */
    protected $content;

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
     * @var string
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $revision;

    /**
     *
     * @var integer
     */
    protected $comment_id;

    /**
     *
     * @var integer
     */
    protected $ip4;

    /**
     *
     * @var integer
     */
    protected $ip6;

    /**
     * Method to set the value of field id_comment
     *
     * @param integer $id_comment
     * @return $this
     */
    public function setIdComment($id_comment)
    {
        $this->id_comment = $id_comment;

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
     * Method to set the value of field comment_id
     *
     * @param integer $comment_id
     * @return $this
     */
    public function setCommentId($comment_id)
    {
        $this->comment_id = $comment_id;

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
     * Returns the value of field id_comment
     *
     * @return integer
     */
    public function getIdComment()
    {
        return $this->id_comment;
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
     * Returns the value of field status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
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
     * Returns the value of field comment_id
     *
     * @return integer
     */
    public function getCommentId()
    {
        return $this->comment_id;
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
     * Returns the value of field ip6
     *
     * @return integer
     */
    public function getIp6()
    {
        return $this->ip6;
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
	     
    /**
     * A Comment Belong to a Post
     * @var integer
     */
    protected $post_id;
    
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
     * Returns the value of field post_id
     *
     * @return self
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
        return $this;
    }
    
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		// TODO : Check ManyToMany relationship definition on Phalcon
        //~ $this->hasMany('id_comment', 'SimpleBlog\Models\Comments', 'comment_id', array('alias' => 'Comments'));
        $this->belongsTo('author_id', 'SimpleBlog\Models\Members', 'id_member', array('alias' => 'Members'));
        $this->belongsTo('post_id', 'SimpleBlog\Models\Posts', 'id_post', array('alias' => 'Posts'));
        $this->belongsTo('post_id', 'SimpleBlog\Models\Posts', 'id_post', array('foreignKey' => true));
    }

    /**
     * check if a comment is deleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
		// TODO : Add DELETED to constants file
        return strtolower($this->status) === DELETED;
    }

    /**
     * check if a comment is spam
     *
     * @return boolean
     */
    public function isSpam()
    {
		// TODO : Add SPAM to constants file
        return strtolower($this->status) === SPAM;
    }

    /**
     * check if a comment is pending
     *
     * @return boolean
     */
    public function isPending()
    {
		// TODO : Add PENDING to constants file
        return strtolower($this->status) === PENDING;
    }

    /**
     * check if a comment is approved
     *
     * @return boolean
     */
    public function isApproved()
    {
		// TODO : Add APPROVED to constants file
        return strtolower($this->status) === APPROVED;
    }

    /**
     * Set the comment status as deleted
     *
     * @return self
     */
    public function setAsDeleted()
    {
        $this->status = DELETED;
        return $this;
    }

    /**
     * Set the comment status as spam
     *
     * @return self
     */
    public function setAsSpam()
    {
        $this->status = SPAM;
        return $this;
    }

    /**
     * Set the comment status as pending
     *
     * @return self
     */
    public function setAsPending()
    {
        $this->status = PENDING;
        return $this;
    }

    /**
     * Set the comment status as approved
     *
     * @return self
     */
    public function setAsApproved()
    {
        $this->status = APPROVED;
        return $this;
    }
    	/**
	 * Get specific Comments
	 * @param	$params mixed	filter the comments
	 * @return	mixed	$result	array of comments		
	 * */	 
    static public function getApprovedComments($params) {
		
		// TODO : Get only some comments.
		// TODO : Include pagination for ajax call		
		$result = null;	
		// Retrieve only approved comments	
		// Check first if $params has a field conditions
		if(isset($params["conditions"])){
			
			// If it s an array add new filter
			if(is_array($params["conditions"])){
				$params["conditions"][] =" status ='approved' " ;
				
			}elseif(is_string($params["conditions"])){
				
				// Else (it s string), concat new filter
				$params["conditions"] .= " AND status ='approved' " ;
			}
		}else{	
			// If the field doesn t exist add a new one (filter)
			$params["conditions"] =" status ='approved' " ;
		}
		// Get Comments list
		$comments= Posts::getComments($params);
		if(count($comments)){			
			foreach($comments as $comment){
				
				// Same process than Posts
				// @see Posts::getLatestApprovedPosts()
				$temp = new \StdClass;
				$temp->PostId = $comment->gerPostId();
				$temp->idComment = $comment->getIdComment();
				$temp->content = $comment->getContent();
				
				// TODO: Define a helper to format a date
				$temp->prettyDateTime = $comment->getCreationDate();
				$temp->creationDate = $comment->getCreationDate();
				
				// TODO : Add UNKNOWN to constants file
				$temp->authorName = UNKNOWN;
				$temp->author_id = $post->getAuthorId();
				
				// Get the author name's (good job Phalcon team)
				$author=$comment->getMembers();
				if($author){
					$temp->authorName = $author->getNicename();
				}				
				$result[] = $temp;
			}
		}
		return $result;
		
	}
	
	static public function getMoreComments($args){
		$result=null;
		try{
			$params =array(
				"conditions"=>"status='approved'",
				"order"	=>"creation_date desc");
				
			if(!is_null($args["limit"])){
				if(!is_null($args["offset"])){
					$params["limit"] = "{$args["limit"]}, {$args["offset"]}";
				}else{
					$params["limit"] = "{$args["limit"]}";
				}
			}
			
			$posts= Posts::findByIdPost($args["idPost"]);			
			if($posts && count($posts)){
				$post= $posts[0];
				// Get Comments list
				$comments= $post->getComments($params);
				if(count($comments)){			
					foreach($comments as $comment){
						
						// Same process than Posts
						// @see Posts::getLatestApprovedPosts()
						$temp = new \StdClass;
						$temp->postId = $comment->getPostId();
						$temp->idComment = $comment->getIdComment();
						$temp->content = $comment->getContent();
						
						// TODO: Define a helper to format a date
						$temp->prettyDateTime = $comment->getCreationDate();
						$temp->creationDate = $comment->getCreationDate();
						
						// TODO : Add UNKNOWN to constants file
						$temp->authorName = "UNKNOWN";
						$temp->authorId = $comment->getAuthorId();
						
						// Get the author name's (good job Phalcon team)
						$author=$comment->getMembers();
						if($author){
							$temp->authorName = $author->getNicename();
							
							if(isset($args["idMember"]) && ($args["idMember"]==$comment->getAuthorId())
							  && $author->isTheAuthor($comment->getAuthorId()) && $author->canEditComment()){
								$temp->canEdit =  true;
							}
						}
						$result[] = $temp;
					}
				}
			}		
		}catch(Exception $e){
			PostsController::log($e->getMessage());
		}
		return $result;
	}
}
