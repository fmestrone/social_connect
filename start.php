<?php
register_elgg_event_handler('init', 'system', 'elgg_social_login_init');

function elgg_social_login_init() {
	elgg_extend_view('forms/login'   , 'elgg_social_login/connect');
	elgg_extend_view('forms/register', 'elgg_social_login/connect', '499');
	elgg_extend_view('css/admin'     , 'elgg_social_login/admincss');
	elgg_extend_view('css/elgg'      , 'elgg_social_login/css');
}

function elgg_social_handle_authentication($user_profile, $provider) {
	global $CONFIG;

	require "{$CONFIG->pluginspath}elgg_social_login/settings.php";

	$provider_name = $HA_SOCIAL_LOGIN_PROVIDERS_CONFIG[$provider]['provider_name'];
	$user_uid = $user_profile->identifier;

	// look for users that have already connected via this plugin
	$options = array(
		'type' => 'user',
		'plugin_id' => 'elgg_social_login',
		'plugin_user_setting_name_value_pairs' => array(
			"$provider/uid" => $user_uid
		),
		'plugin_user_setting_name_value_pairs_operator' => 'AND',
		'limit' => 0
	);
	$users = elgg_get_entities_from_plugin_user_settings($options);

	if ( !$users ) {
		// user has not connected with plugin before

		// check whether the user already exists with the email provided
		$useremail = $user_profile->email;
		if ( !$useremail ) {
			register_error(sprintf(elgg_echo('jasl:connect:bad'), $provider_name));
			return;
		}

		$users = get_user_by_email($useremail);
		if ( $users ) {
			// if so, then connect the existing user to the social profile
			$mode = 'email';

			elgg_social_connect_user($user_uid, $users[0], $user_profile, $provider);
			// notice
			system_message(sprintf(elgg_echo('jasl:connect:ok'), $provider_name));

			// allow other plugins to interact with this (for example to take more data out of profile and register it as metadata in Elgg)
			elgg_trigger_event('social_connect', 'user', array('userid'=>$user_uid, 'provider'=>$provider, 'user'=>$user, 'profile'=>$user_profile));
		} else {
			// if not, register a new user with the data from the social profile
			$mode = 'profile';

			$userlogin = str_replace(' ', '', $user_profile->displayName);

			if ( !$userlogin ) {
				$userlogin = $provider . '_user_' . rand(1000, 9999);
			}

			while ( get_user_by_username($userlogin) ) {
				$userlogin = str_replace(' ', '', $user_profile->displayName) . '_' . rand(1000, 9999);
			}

			$password = generate_random_cleartext_password();

			$username = $user_profile->displayName;

			$user = new ElggUser();
			$user->username = $userlogin;
			$user->name = $username;
			$user->email = $useremail;
			$user->access_id = ACCESS_PUBLIC;
			$user->salt = generate_random_cleartext_password();
			$user->password = generate_user_password($user, $password);
			$user->owner_guid = 0;
			$user->container_guid = 0;

			if ( !$user->save() ) {
				register_error(sprintf(elgg_echo('jasl:register:bad'), $provider_name));
				return;
			}

			elgg_social_connect_user($user_uid, $user, $user_profile, $provider);
			// notice
			system_message(sprintf(elgg_echo('jasl:register:ok'), $provider_name));
		}

		// allow other plugins to interact with this (for example to take more data out of profile and register it as metadata in Elgg)
		elgg_trigger_event('social_connect', 'user', array('mode' => $mode, 'userid'=>$user_uid, 'provider'=>$HA_SOCIAL_LOGIN_PROVIDERS_CONFIG[$provider], 'user'=>$user, 'profile'=>$user_profile));

	} elseif ( count($users) == 1 ) {
		// login user
		login($users[0]);
		// notice
		system_message(sprintf(elgg_echo('jasl:login:ok'), $provider_name));
	} else {
		throw new Exception(sprintf(elgg_echo('jasl:login:bad'), $provider_name));
	} 
}

function elgg_social_connect_user($user_uid, $user, $user_profile, $provider) {
	// register user && provider
	elgg_set_plugin_user_setting("$provider/uid", $user_uid, $user->guid, 'elgg_social_login');

	login($user);

	# {{{ user image
	if ( $user_profile->photoURL ) {
		$sizes = array(
			'topbar' => array(16, 16, TRUE),
			'tiny' => array(25, 25, TRUE),
			'small' => array(40, 40, TRUE),
			'medium' => array(100, 100, TRUE),
			'large' => array(200, 200, FALSE),
			'master' => array(550, 550, FALSE),
		);

		$filehandler = new ElggFile();
		$filehandler->owner_guid = $user->guid;
		foreach ( $sizes as $size => $dimensions ) {
			$image = get_resized_image_from_existing_file(
				$user_profile->photoURL,
				$dimensions[0],
				$dimensions[1],
				$dimensions[2]
			);

			$filehandler->setFilename("profile/{$user->guid}{$size}.jpg");
			$filehandler->open('write');
			$filehandler->write($image);
			$filehandler->close();
		}

		$user->icontime = time(); 
	}
	# }}} user image
}