<?php
/**
 * Set up initial variables
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// well, dont need theses
restore_error_handler();
restore_exception_handler();

global $CONFIG;
$assets_base_url  = "{$CONFIG->url}mod/elgg_social_login/";
$assets_base_path = "{$CONFIG->pluginspath}elgg_social_login/";
$provider = @trim(strip_tags($_GET['provider']));

// let's display a loading message... better than a white screen
if ( isset($_GET['provider']) && !isset($_GET['redirect_to_provider']) ) {

/**
 * Display stand-in message and reload on timer
 */
	// selected provider 

	$_SESSION['HA::STORE'] = array();
	?>
	<table width="100%" border="0">
	  <tr>
		<td align="center" height="200px" valign="middle"><img src="<?php echo $assets_base_url ; ?>graphics/loading.gif" /></td>
	  </tr>
	  <tr>
		<td align="center"><br /><h3>Loading...</h3><br /></td> 
	  </tr>
	  <tr>
		<td align="center">Contacting <b><?php echo ucfirst($provider); ?></b>, please wait...</td>
	  </tr>
	</table>
	<script> 
		setTimeout(function() { window.location.href = window.location.href + "&redirect_to_provider=true"; }, 750);
	</script>
	<?php
	die();
} // end display loading 



/**
 * Reloaded page - check requirements
 */

// if user select a provider to login with 
// and redirect_to_provider eq ture
if ( isset($_GET['provider']) && isset($_GET['redirect_to_provider']) ) {


/**
 * Authenticate using the provider requested
 */

	// load hybridauth
	require_once("{$assets_base_path}vendors/hybridauth/Hybrid/Auth.php");

	// build required configuratoin for this provider
	if ( !get_plugin_setting("ha_settings_{$provider}_enabled", 'elgg_social_login') ) {
		throw new Exception( 'Unknown or disabled provider' );
	}

	$config = array();

	$config['base_url'] = $assets_base_url;

	if ( get_plugin_setting('ha_settings_test_mode', 'elgg_social_login') ) {
		$config['debug_mode'] = true;
		$config['debug_file'] = $assets_base_path . 'log/debug.log';
	}
	
	
	$providerConfig = array();
	
	$providerConfig['enabled'] = true;

	// provider application id ?
	if ( ($appid = get_plugin_setting("ha_settings_{$provider}_app_id", 'elgg_social_login')) ) {
		$providerConfig['keys']['id'] = $appid;
	}

	// provider application key ?
	if ( ($appkey = get_plugin_setting("ha_settings_{$provider}_app_key", 'elgg_social_login')) ) {
		$providerConfig['keys']['key'] = $appkey;
	}

	// provider application secret ?
	if ( ($appsecret = get_plugin_setting("ha_settings_{$provider}_app_secret", 'elgg_social_login')) ) {
		$providerConfig['keys']['secret'] = $appsecret;
	}
	
	// if facebook
	if ( $provider == 'Facebook' ) {
		$providerConfig['display'] = 'popup';
	}

	$config['providers'] = array($provider => $providerConfig);

	// start login with facebook?
	try {
		$hybridauth = new Hybrid_Auth($config);

		$adapter = $hybridauth->authenticate($provider);

		$user_profile = $adapter->getUserProfile();

		elgg_social_handle_authentication($user_profile, $provider);
?>
		<html>
		<head>
		<script>
			function init() {
				window.opener.location.reload();
				window.close();
			}
		</script>
		</head>
		<body onload="init();">
		</body>
		</html>
	<?php
	} catch ( Exception $e ) {
		$message = 'Unspecified error!';
		switch ( $e->getCode() ) {
			case 0 : $message = 'Unspecified error.'; break;
			case 1 : $message = 'Hybriauth configuration error.'; break;
			case 2 : $message = 'Provider not properly configured.'; break;
			case 3 : $message = 'Unknown or disabled provider.'; break;
			case 4 : $message = 'Missing provider application credentials.'; break;
			case 5 : $message = 'Authentification failed. The user has canceled the authentication or the provider refused the connection.'; break;
		}  
		?>
		<style> 
		HR {
			width:100%;
			border: 0;
			border-bottom: 1px solid #ccc; 
			padding: 50px;
		}
		</style>
		<table width="100%" border="0">
		  <tr>
			<td align="center"><br /><img src="<?php echo $assets_base_url ; ?>graphics/alert.png" /></td>
		  </tr>
		  <tr>
			<td align="center"><br /><h3>Something bad happen!</h3><br /></td> 
		  </tr>
		  <tr>
			<td align="center">&nbsp;<?php echo $message ; ?></td> 
		  </tr>
		<?php 
			if( get_plugin_setting( 'ha_settings_test_mode', 'elgg_social_login' ) ){
		?>
				<tr>
				  <td align="center"> 
					  <div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
						  <br /> 
						  &nbsp;<b>This plugin is still in alpha</b><br /><br /><b style="color:#cc0000;">But you can make it better by sending the generated error report to the developer!</b>
						  <br />
						  <br />

						  <div id="bug_report">
							  <form method="post" action="http://hybridauth.sourceforge.net/reports/index.php?product=elgg-plugin-1.8&v=1.0.2">
								  <table width="90%" border="0">
									  <tr>
										  <td align="left" valign="top">
											  Your email (recommended)
											  <input type="text" name="email" style="width: 98%;border: 1px solid #CCCCCC;border-radius: 5px;padding: 5px;" />
										  </td> 
									  </tr>
									  <tr>
										  <td align="left" valign="top"> 
											  A comment? how it did happen? (optional)
											  <textarea name="comment" style="width: 98%;border: 1px solid #CCCCCC;border-radius: 5px;padding: 5px;"></textarea>
										  </td> 
									  </tr>
									  <tr>
										  <td align="center" valign="top"> 
											  <input type="submit" style="width: 300px;height: 33px;" value="Send the error report" /> 
										  </td> 
									  </tr>
								  </table> 

								  <textarea name="report" style="display:none;"><?php echo base64_encode( print_r( array( $e, $_SERVER ), TRUE ) ) ?></textarea>
							  </form> 
							  <small>
								  Note: This message can be disabled from the plugin settings by setting <b>test mode</b> to <b>NO</b>.
							  </small>
						  </div>
					  </div>
				  </td> 
				</tr>
				<?php
				} // end test mode
				?>
			</table>  
			<?php 
			// diplay error and RIP
			die();
		}
	}
