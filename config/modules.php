<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'frontend' => array(
        'className' => 'SimpleBlog\Frontend\Module',
        'path' => __DIR__ . '/../apps/frontend/Module.php'
    ),
    'backend' => array(
        'className' => 'SimpleBlog\Backend\Module',
        'path' => '/../apps/backend/Module.php',
    )
));
