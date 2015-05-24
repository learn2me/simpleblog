<?php

namespace SimpleBlog\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config\Adapter\Ini as ConfigIni;

class Module implements ModuleDefinitionInterface
{

    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders()
    {

        $loader = new Loader();

        $loader->registerNamespaces(array(
            'SimpleBlog\Frontend\Controllers' => __DIR__ . '/controllers/',
            'SimpleBlog\Models' => __DIR__ . '/../../common/models/',
			'Phalcon\Translate\Adapter' => __DIR__.'/../../common/library/Translate/Adapter',
			'SimpleBlog\Collections' => __DIR__.'/../../common/library/SB/Collections',
			'SimpleBlog\ThirdParty' => __DIR__.'/../../common/library/third-party',
			'SimpleBlog\Helpers' => __DIR__.'/../../common/library/SB/Helpers'
        ));

        $loader->register();
    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices($di)
    {

        /**
         * Read configuration
         */
         $commonConfig = new ConfigIni( __DIR__ . '/config/config.ini');
        $specificConfig = new ConfigIni(__DIR__ . "/config/".ENVIRONMENT."/config.ini");
        $commonConfig->merge($specificConfig);
        
		$di->set("config",$commonConfig);
		
		/**
		 * Setting up the view component
		 */
		$di['view'] = function() {

			$view = new \Phalcon\Mvc\View();

			$view->setViewsDir(__DIR__.'/views/');

			$view->setTemplateBefore('main');

			$view->registerEngines(array(
				".html" => function($view, $di) {

					$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

					$volt->setOptions(array(
						"compiledPath" => __DIR__."/../../var/volt/",
						"compiledExtension" => ".php"
					));

					return $volt;
				}
			));

			return $view;

		};
		
		$di->set("view",$di['view']);
		
		/**
		 * Database connection is created based in the parameters defined in the configuration file
		 */
		$di['db'] = function () use ($commonConfig) {
			return new DbAdapter(array(
				"host" => $commonConfig->database->host,
				"username" => $commonConfig->database->username,
				"password" => $commonConfig->database->password,
				"dbname" => $commonConfig->database->dbname,
				"charset" => $commonConfig->database->charset
			));
        };

    }

}
