<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/
 
/**
 * Debugging and Logging manager
 */
class Hybrid_Logger
{
    private static $enabled = false;
    private static $log_file = '';
    private static $log_level = 3;

	function __construct()
	{
        if ( array_key_exists('debug_mode', Hybrid_Auth::$config) ) {
            Hybrid_Logger::$enabled = Hybrid_Auth::$config['debug_mode'];
        }
        if ( array_key_exists('debug_file', Hybrid_Auth::$config) ) {
            Hybrid_Logger::$log_file = Hybrid_Auth::$config['debug_file'];
        }
        if ( array_key_exists('debug_level', Hybrid_Auth::$config) ) {
            Hybrid_Logger::$log_level = Hybrid_Auth::$config['debug_level'];
        }

		// if debug mode is set to true, then check for the writable log file
		if ( Hybrid_Logger::$enabled ){
			if ( ! file_exists(Hybrid_Logger::$log_file) ) {
				throw new Exception( "'debug_mode' is set to 'true', but no log file path 'debug_file' given.", 1 );
			}

			if ( ! is_writable(Hybrid_Logger::$log_file) ) {
				throw new Exception( "'debug_mode' is set to 'true', but the given log file path 'debug_file' is not a writable file.", 1 );
			}
		}
	}

	public static function debug( $message, $object = NULL )
	{
		if( Hybrid_Logger::$enabled && Hybrid_Logger::$log_level > 2 ){
			$datetime = new DateTime();
			$datetime =  $datetime->format(DATE_ATOM);

			file_put_contents( 
				Hybrid_Auth::$config["debug_file"], 
				"DEBUG -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $datetime . " -- " . $message . " -- " . print_r($object, true) . "\n", 
				FILE_APPEND
			);
		}
	}

	public static function info( $message )
	{ 
		if( Hybrid_Logger::$enabled && Hybrid_Logger::$log_level > 1 ){
			$datetime = new DateTime();
			$datetime =  $datetime->format(DATE_ATOM);

			file_put_contents( 
				Hybrid_Auth::$config["debug_file"], 
				"INFO -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $datetime . " -- " . $message . "\n", 
				FILE_APPEND
			);
		}
	}

	public static function error($message, $object = NULL)
	{ 
		if( Hybrid_Logger::$enabled && Hybrid_Logger::$log_level > 0 ){
			$datetime = new DateTime();
			$datetime =  $datetime->format(DATE_ATOM);

			file_put_contents( 
				Hybrid_Auth::$config["debug_file"], 
				"ERROR -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $datetime . " -- " . $message . " -- " . print_r($object, true) . "\n", 
				FILE_APPEND
			);
		}
	}
}
