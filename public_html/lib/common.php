<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

date_default_timezone_set('America/Edmonton');
define('DEVELOPEMENT', 'developement');
define('TESTING', 'testing');
define('STAGING', 'staging');
define('PRODUCTION', 'production');
define('UNDER_CONSTRUCTION', TRUE);
define('SITE_ACTIVE', TRUE);


if (!function_exists('detect_environment')) {

    function detect_environment() {

        /*
         * ---------------------------------------------------------------
         * APPLICATION ENVIRONMENT
         * ---------------------------------------------------------------
         *
         * You can load different configurations depending on your
         * current environment. Setting the environment also influences
         * things like logging and error reporting.
         *
         * This can be set to anything, but default usage is:
         *
         *     development
         *     testing
         *     production
         *
         * NOTE: If you change these, also change the error_reporting() code below
         *
         */
         
         
         // The code below autodetect the environment. It help me to fix the database credentials
         // It allows me to know if I have to insert google analytics snipet or not,...
         
         // My best practice : 
         // Prefix the vhost name with dev. when I work locally
         // Prefix vhost name with staging. when I deploy the code on my testing VPS
         // No prefix means that I m in production 
         
        $http_host = strtolower($_SERVER["HTTP_HOST"]);
        if (strpos($http_host, 'dev.') === FALSE) {
            if (strpos($http_host, 'staging.') === FALSE) {
                define('DOMAIN_PREFIX', '');
                define('ENVIRONMENT', PRODUCTION);
            } else {
                define('DOMAIN_PREFIX', 'staging');
                define('ENVIRONMENT', STAGING);
            }
        } else {
            define('DOMAIN_PREFIX', 'dev');
            define('ENVIRONMENT', DEVELOPEMENT);
        }

        /*
         * ---------------------------------------------------------------
         * ERROR REPORTING
         * ---------------------------------------------------------------
         *
         * Different environments will require different levels of error reporting.
         * By default development will show errors but testing and live will hide them.
         */

        if (defined('ENVIRONMENT')) {
            switch (ENVIRONMENT) {
                case DEVELOPEMENT:
                    error_reporting(E_ALL | E_STRICT);
                    break;
                case STAGING:
                case TESTING:
                    error_reporting(E_ALL | E_STRICT);
                    break;
                case PRODUCTION:
                    error_reporting(0);
                    break;

                default:
                    exit('The application environment is not set correctly.');
            }
        }
    }
}

/**
 * Function to check if the current environment is production
 */
if (!function_exists('is_production')) {
    function is_production() {
        $is_production = ENVIRONMENT === PRODUCTION;
        return $is_production;
    }
}

/**
 * Function to check if the current environment is staging
 */
if (!function_exists('is_testing')) {
    function is_testing() {
        $is_testing = ENVIRONMENT === STAGING;
        return $is_testing;
    }
}

/**
 * Alias for function is_testing
 */
if (!function_exists('is_staging')) {
    function is_staging() {
        return is_testing();
    }
}

/**
 * Function to check if the current environment is development
 */
if (!function_exists('is_developement')) {
    function is_development() {
        $is_development = ENVIRONMENT === DEVELOPEMENT;
        return $is_development;
    }
}
/*
 * Print the data on the screen
 */
if (!function_exists('trace')) {
    function trace($to_trace = NULL) {
		if(!is_production()){
			if (!(is_string($to_trace) || is_numeric($to_trace))) {
				$to_trace = json_encode($to_trace);
			}
			print $to_trace;
		}
    }

}
/*
 * Print the data on the screen and exit immediately
 */
if (!function_exists('trace_die')) {
    function trace_die($var = NULL) {
		if(!is_production()){
			echo '<pre>';
			if(is_array($var) || is_object($var)){
				print_r($var);
			}else{
				echo $var;
			}
			echo '</pre>';
			die();
		}
    }
}
/*
 * Check if an array can be fetched or not
 * Useful for foreach statement
 */
if (!function_exists('can_fetch')) {
    function can_fetch($array) {
        $result=FALSE;
        if(isset($array) && is_array($array) && count($array)>0){
            $result=TRUE;
        }
        return $result;
    }
}
