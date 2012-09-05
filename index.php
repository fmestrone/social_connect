<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------

require_once (dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// well, dont need theses
restore_error_handler();
restore_exception_handler();

require_once('vendors/hybridauth/Hybrid/Auth.php');
require_once('vendors/hybridauth/Hybrid/Endpoint.php'); 

Hybrid_Endpoint::process();
