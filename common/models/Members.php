<?php

namespace SimpleBlog\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

use SimpleBlog\Models\Posts,
	SimpleBlog\Frontend\Controllers\ControllerBase;

class Members extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id_member;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $password;

    /**
     *
     * @var string
     */
    protected $nicename;

    /**
     *
     * @var string
     */
    protected $registration;

    /**
     *
     * @var string
     */
    protected $modification;

    /**
     *
     * @var string
     */
    protected $last_activity;

    /**
     *
     * @var string
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $role_id;

    /**
     * Method to set the value of field id_member
     *
     * @param integer $id_member
     * @return $this
     */
    public function setIdMember($id_member)
    {
        $this->id_member = $id_member;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field nicename
     *
     * @param string $nicename
     * @return $this
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;

        return $this;
    }

    /**
     * Method to set the value of field registration
     *
     * @param string $registration
     * @return $this
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;

        return $this;
    }

    /**
     * Method to set the value of field modification
     *
     * @param string $modification
     * @return $this
     */
    public function setModification($modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Method to set the value of field last_activity
     *
     * @param string $last_activity
     * @return $this
     */
    public function setLastActivity($last_activity)
    {
        $this->last_activity = $last_activity;

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
     * Method to set the value of field role_id
     *
     * @param integer $role_id
     * @return $this
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * Returns the value of field id_member
     *
     * @return integer
     */
    public function getIdMember()
    {
        return $this->id_member;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field nicename
     *
     * @return string
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * Returns the value of field registration
     *
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Returns the value of field modification
     *
     * @return string
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Returns the value of field last_activity
     *
     * @return string
     */
    public function getLastActivity()
    {
        return $this->last_activity;
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
     * Returns the value of field role_id
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }



	/********************************************************
	 *														*
	 * 				MY OWN CODE START HERE 					*
	 * 														*
	 * *****************************************************/
	 
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('role_id', 'SimpleBlog\Models\Roles', 'id_role', array('alias' => 'Roles'));
        $this->hasMany('id_member', 'SimpleBlog\Models\Posts', 'author_id', array('alias' => 'Posts'));
        $this->hasMany('id_member', 'SimpleBlog\Models\Comments', 'author_id', array('alias' => 'Comments'));
    }
    
    /**
     * Returns the role name
     *
     * @return string
     */
    public function getRole()
    {
		$result = "anonymous";
		$roles = Roles::findByIdRole($this->role_id);
		if($roles){
			$role=$roles[0];
			$result = $role->getName();
		}
        return $result;
    }
    /**
     * Check if the user is the author of a resource
     * It check the idMember with its id_memeber
     * @param	$IdMember
     * @return	boolean
     * */
	 public function isTheAuthor($idMember){
		$result = false;
		if($idMember===$this->getIdMember()){
			$result = true;
		}
		return $result;
	} 
	
    /**
     * Variable to define if a Member is logged in or not
     * @var boolean
     */
    protected $isLoggedin = false;
    
    /**
     * Set a Member as logged in
     * 
     * @return self
     **/
	 public function setLoggedin()
	 {
		 $this->isLoggedin = true;
		 
		 return $this;
	} 
	
    /**
     * Set a Member as logged out
     * 
     * @return self
     **/
	 public function setLogout(){
		 $this->isLoggedin = false;
		 
		 return $this;
	} 
	
    /**
     * Check if a Member is logged in
     * @return boolean
     **/
	 public function isLoggedin(){
		$result = false;
		$member = new \Phalcon\Session\Bag('member');
		if($member->has("loggedin")){
			$result = true;
		}
		return $result;
	} 
	
	/**
	  * Check if a member have the permission to add a comment
	  * @return	boolean
	  **/
	public function canAddComment(){
		$result = false;
		$params = array();		
		$params = array(
			"role_id"=>$this->getRoleId(),
			"user_id"=>$this->getIdMember()
		);
		$role = $this->getRole();
		$result = Roles::checkPermission("add","comment",$role,$params);
		
		return $result;
	}
	
	/**
	  * Check if a member have the permission to  update or delete a comment
	  * @return	boolean
	  **/
	public function canEditComment(){
		$result = false;
		$params = array(
			"role_id"=>$this->getRoleId(),
			"user_id"=>$this->getIdMember()
		);
		$role = $this->getRole();
		$result = Roles::checkPermission("edit","comment",$role,$params);
		
		return $result;
	}
	 
	 /**
	  * Check if a member have the permission to add a post
	  * @return	boolean
	  **/
	public function canAddPost(){
		$result = false;
		$params = array(
			"role_id"=>$this->getRoleId(),
			"user_id"=>$this->getIdMember()
		);
		$role = $this->getRole();
		$result = Roles::checkPermission("add","post",$role,$params);
		
		return $result;
	}
	 
	 /**
	  * Check if a member have the permission to update or delete a post
	  * @return	boolean
	  **/
	public function canEditPost(){
		$result = false;
		$params = array(
			"role_id"=>$this->getRoleId(),
			"user_id"=>$this->getIdMember()
		);
		$role = $this->getRole();
		$result = Roles::checkPermission("edit","post",$role,$params);
		
		return $result;
	}
	 
	 /**
	  * 
	  **/
	public function getAuthors(){
		$result = false;
		// TODO: Declare $config public in ControllerBAse
		$config = ControllerBase::getConfig();	
		return $result;
	}
	 
	 /**
	  * 
	  **/
	static public function getAuthorsWithPosts(){
		$result = false;
			
		// TODO : Define Roles IDs in config file
		$config = ControllerBase::getConfig();
		$roleId = 2;
		$query = Members::query();
		$joinConditions ="Posts.author_id = SimpleBlog\Models\Members.id_member 
						  AND Posts.status='approved'";
		$query->where("SimpleBlog\Models\Members.status='active'");		
		$query->andWhere("SimpleBlog\Models\Members.role_id='{$roleId}'");		
		$query->join("SimpleBlog\Models\Posts",$joinConditions,"Posts");
		$query->orderBy("Posts.creation_date DESC");
		$resultSet=$query->execute();

		if($resultSet){
			// Walk trougth the resulset to remove duplicated member
			// Because no way to have GROUP BY with Phalcon
			$memberIdArray=array();
			foreach ($resultSet as $member) {
				if(!in_array($member->getIdMember(),$memberIdArray)){
						$memberIdArray[]=$member->getIdMember();
						
						// Return only usefull fields for the view
						$data = new \StdClass;
						$data->name = $member->getNiceName();
						$data->idMember = $member->getIdMember();
						$data->totalPosts = 1;		
						
						//	To display some stats				
						$result["M_".$member->getIdMember()] = $data;
				}else{
					
					//	Update stats
					$result["M_".$member->getIdMember()]->totalPosts+=1;
				}
			}
		}
		return $result;
	}
	 
	 /**
	  * 
	  **/
	public function getReadersWithPosts(){
		$result = false;	
		
		// TODO : Define Roles IDs in config file
		$config = ControllerBase::getConfig();
		$roles = implode(',',array(2,3));
		$query = Members::query();
		$joinConditions ="Comments.author_id = SimpleBlog\Models\Members.id_member 
						  AND Comments.status='approved'";
		$query->where("SimpleBlog\Models\Members.status='active'");		
		$query->andWhere("SimpleBlog\Models\Members.role_id in ('{$roles}')");		
		$query->join("SimpleBlog\Models\Comments",$joinConditions,"Comments");
		$query->orderBy("Comments.creation_date DESC");
		$resultSet=$query->execute();

		if($resultSet){
			// Walk trougth the resulset to remove duplicated member
			// Because no way to have GROUP BY with Phalcon
			$memberIdArray=array();
			foreach ($resultSet as $member) {
				if(!in_array($member->getIdMember(),$memberIdArray)){
						$memberIdArray[]=$member->getIdMember();
						
						// Return only usefull fields for the view						
						$data = new \StdClass;
						$data->name = $member->getNiceName();
						$data->idMember = $member->getIdMember();
						$data->totalPosts = 1;
						
						//	To display some stats
						$result["M_".$member->getIdMember()] = $data;
				}else{
					
					//	Update stats
					$result["M_".$member->getIdMember()]->totalPosts+=1;
				}
			}
		}
		return $result;
	}
	 
	 /**
	  * 
	  **/
	public function getReaders(){
		$result = false;
		$config = ControllerBase::getConfig();	
		return $result;
	}
	
	public function getMemberPosts(){
		$config = ControllerBase::getConfig();	
		
		// Load only some posts (n latest posts)
		$limit = $config->application->latestPostsLimit;
		$params=array(
			"conditions"=>"status='approved'",
			"order"=>"creation_date desc",
			"limit"=> $limit
		);
		$posts=$this->getPosts($params);
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
				$temp->authorId = $post->getAuthorId();				
				$temp->authorName = $this->getNicename();							
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
	
	public function getMemberComments(){
		$config = ControllerBase::getConfig();	
		
		// Load only some posts (n latest posts)
		$limit = $config->application->latestPostsLimit;
		$params=array(
			"conditions"=>"status='approved'",
			"order"=>"creation_date desc",
			"limit"=> $limit
		);
		$comments=$this->getComments($params);
		if(count($comments)){			
			foreach($comments as $comment){
				
				// Return a new object.
				// If we are willing to make template extendable by designers,
				// for security, we want to return only useful data	instead
				// of using : return $posts
				$temp = new \StdClass;
				$temp->idComment = $comment->getIdComment();
				$temp->content = $comment->getContent();
				
				// TODO: Define a helper to format a date
				$temp->postId = $comment->getPostId();
				$temp->prettyDateTime = $comment->getCreationDate();
				$temp->creationDate = $comment->getCreationDate();
				$adate = strtotime($comment->getCreationDate());
				$temp->dateCategory = date("M Y",$adate);
				$temp->dateCategoryLink = date("Y-m",$adate);				
				$temp->authorId = $comment->getAuthorId();				
				$temp->authorName = $this->getNicename();							
				$temp->post = null;
				$post=$comment->getPosts();
				if($post){
					$temp->post = new \StdClass;
					$temp->post->title=$post->getTitle();
					$temp->post->idPost=$post->getIdPost();
				}
				
				$result[] = $temp;
			}
		}
		return $result;
	}
}
