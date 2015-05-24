<?php

$router = new \Phalcon\Mvc\Router(false);

$router->removeExtraSlashes(true);

/**
 * Frontend routes
 */
 
// Landing page
$router->add('/', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'index'
));

// Single post page
$router->add('/posts/{idPost}', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'posts',
	'action' => 'index'
));

// Add comment action (GET OR POST)
$router->add('/comments/{idPost}', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'members',
	'action' => 'addComment'
));

// Update Delete comment action (GET OR POST)
$router->add('/update-comments/{idComment}', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'members',
	'action' => 'updateComment'
));

// Login action
$router->add('/login', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'members',
	'action' => 'login'
));

// Add Post action
$router->add('/add-post', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'members',
	'action' => 'addPost'
));

// Update Delete Post action
$router->add('/update-post', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'members',
	'action' => 'updatePost'
));

// List Posts Tagged action
$router->add('/tag-date/{date}', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'listByDate'
));

// Authors list action
$router->add('/authors-list', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'listByAuthors'
));


// List of readers action
$router->add('/readers-list', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'listByReaders'
));

// Contact page action
$router->add('/contact', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'contact'
));


// About page action
$router->add('/about', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'about'
));

// List All Member Posts action
$router->add('/member-posts/{idMember}', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'listAuthors'
));

// List All Member Comemnts action
$router->add('/member-comments/{idMember}', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'listReaders'
));

// List More Posts or More Comemnts (Ajax) action
$router->add('/load-more', array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'loadMore'
));

$router->notFound(array(
	'module' => 'frontend',
	'namespace' => 'SimpleBlog\Frontend\Controllers\\',
	'controller' => 'index',
	'action' => 'route404'
));

return $router;
