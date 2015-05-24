<?php

namespace SimpleBlog\Frontend\Controllers;

use SimpleBlog\Models\Members,
	Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    static private $config = NULL;
    static private $logger = NULL;
    public $member = null;
    public $user = null;
    public $message = null;
    
	public function initialize(){	
		$this->member = new \Phalcon\Session\Bag('member');	
		$this->message = new \Phalcon\Session\Bag('message');
		
		if(isset($this->member) && $this->member->has('idMember')){
			$users = Members::find(array(
				"conditions"=>"id_member =?1 AND status='active'" ,
				"limit"=>1,
				"bind"=>array(1=>$this->member->get('idMember'))
			));
			
			// count>0 if Member in data base. Else credentials are wrong
			if(count($users)){
				$this->user = $users[0];
			}
		}
		
		$this->view->setVar("member",$this->member );
		$this->view->setVar("message",$this->message );
		$this->view->setVar("user",$this->user );
			
		ControllerBase::$config = ControllerBase::getDI()->get('config');			
		ControllerBase::$logger = ControllerBase::getDI()->get('logger');
		$siteTitle = ControllerBase::$config->application->site_title;
		$this->view->setVar("siteTitle",$siteTitle);
		$topMenu = ControllerBase::$config->blog->topMenu;
		$this->view->setVar("topMenu",$topMenu);
	}

    static public function getConfig() {
		if(is_null(ControllerBase::$config)){
			ControllerBase::$config = ControllerBase::getDI()->get('config');
		}
		return ControllerBase::$config;
	}

    public function testAction() {
		echo "This is a test";
	}
	
	static public function log($msg,$type=null){
		if(is_null($type)){
			$type= \Phalcon\Logger::ERROR;
		}
		if(is_string($msg)){
			ControllerBase::$logger->log($msg, $type);
		}
	}
}
