<?php
/*
 * This array is based on the array used in HybridAuth's install.php
 * It adds keys to the array to make it easier to look up display names in the user interface
 * 
 */

$HA_SOCIAL_LOGIN_PROVIDERS_CONFIG = array(
	'Facebook' => ARRAY( 
		"label"             => "Facebook",
		"provider_name"     => "Facebook", 
		"require_client_id" => TRUE, 
		"new_app_link"      => "https://www.facebook.com/developers/",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Facebook.html",
	)
	,
	'Google' => ARRAY( 
		"label"             => "Google",
		"provider_name"     => "Google", 
		"callback"          => TRUE,
		"require_client_id" => TRUE, 
		"new_app_link"      => "https://code.google.com/apis/console/",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Google.html",
	) 
	,
	'Twitter' => ARRAY( 
		"label"             => "Twitter",
		"provider_name"     => "Twitter",  
		"new_app_link"      => "https://dev.twitter.com/apps",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Twitter.html",
	)
	,
	'Yahoo' => ARRAY( 
		"label"             => "Yahoo",
		"provider_name"     => "Yahoo!", 
		"new_app_link"      => "https://developer.apps.yahoo.com/dashboard/createKey.html",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Yahoo.html",
	)
	,
	'Live' => ARRAY( 
		"label"             => "Live",
		"provider_name"     => "Windows Live", 
		"require_client_id" => TRUE, 
		"new_app_link"      => "https://manage.dev.live.com/ApplicationOverview.aspx",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Live.html",
	)
	,
	'MySpace' => ARRAY( 
		"label"             => "MySpace",
		"provider_name"     => "MySpace", 
		"new_app_link"      => "http://developer.myspace.com/",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_MySpace.html",
	)
	,
	'Foursquare' => ARRAY( 
		"label"             => "Foursquare",
		"provider_name"     => "Foursquare", 
		"require_client_id" => TRUE, 
		"callback"          => TRUE,
		"new_app_link"      => "https://www.foursquare.com/oauth/",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Foursquare.html",
	)
	,
	'LinkedIn' => ARRAY( 
		"label"             => "LinkedIn",
		"provider_name"     => "LinkedIn",  
		"new_app_link"      => "https://www.linkedin.com/secure/developer",
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_LinkedIn.html",
	)
	,
	'OpenID' => ARRAY( 
		"label"             => "OpenID",
		"provider_name"     => "OpenID", 
		"new_app_link"      => NULL,
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_OpenID.html",
	)
	,
	'AOL' => ARRAY( 
		"label"             => "AOL",
		"provider_name"     => "AOL", 
		"new_app_link"      => NULL,
		"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_AOL.html",
	)
);
