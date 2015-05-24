<?php

namespace SimpleBlog\Models;

use SimpleBlog\Frontend\Controllers\ControllerBase;

class Roles extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id_role;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     * Method to set the value of field id_role
     *
     * @param integer $id_role
     * @return $this
     */
    public function setIdRole($id_role)
    {
        $this->id_role = $id_role;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns the value of field id_role
     *
     * @return integer
     */
    public function getIdRole()
    {
        return $this->id_role;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id_role', 'SimpleBlog\Models\Members', 'role_id', array('alias' => 'Members'));
    }


	/********************************************************
	 *														*
	 * 				MY OWN CODE START HERE 					*
	 * 														*
	 * *****************************************************/
	
	/**
	 * Check if a user has specific permission to act on a resource
	 * @param	$action		action to execute (ex:add a comment)
	 * @param	$resource	resource to process (ex: a comment)
	 * @param	$params		extra arguments
	 * @return boolean
	 **/ 
	static public function checkPermission($action,$resource,$role,$params) {		
		$result = false;
		$config= ControllerBase::getConfig();
		$permissions=$config->acl;
		if(isset($permissions->{$resource})){
			$res=$permissions->{$resource};
			if(isset($res->{$role})){
				$rol = $res->{$role};
				if(isset($rol->{$action}) && $rol->{$action}==true){
					$result = true;
				}
			}			
		}
		return $result;
	}
}
