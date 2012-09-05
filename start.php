<?php
register_elgg_event_handler( 'init', 'system', 'elgg_social_login_init' );

function elgg_social_login_init() {
	elgg_extend_view('forms/login'   , 'elgg_social_login/connect');
	elgg_extend_view('forms/register', 'elgg_social_login/connect', '499');
	elgg_extend_view('css/admin'     , 'elgg_social_login/admincss');
	elgg_extend_view('css/elgg'      , 'elgg_social_login/css');
}

function elgg_social_handle_authentication($user_profile, $provider) {
	$user_uid = "{$provider}_{$user_profile->identifier}";
	// attempt to find user
	// !!! taken from Elgg Facebook Services plugin by anirupdutta 
	$options = array(
		'type' => 'user',
		'plugin_id' => 'elgg_social_login',
		'plugin_user_setting_name_value_pairs' => array(
			'uid' => $user_uid,
			'provider' => $provider,
		),
		'plugin_user_setting_name_value_pairs_operator' => 'AND',
		'limit' => 0
	);

	$users = elgg_get_entities_from_plugin_user_settings($options);

	if ( !$users ) {
		$userlogin = str_replace(' ', '', $user_profile->displayName);

		if ( !$userlogin ) {
			$userlogin = $provider . '_user_' . rand(1000, 9999);
		}

		while ( get_user_by_username($userlogin) ) {
			$userlogin = str_replace(' ', '', $user_profile->displayName) . '_' . rand(1000, 9999);
		}

		$password = generate_random_cleartext_password();

		$username = $user_profile->displayName;

		$useremail = $user_profile->email;
 
		$user = new ElggUser();
		$user->username = $userlogin;
		$user->name = $username;
		$user->access_id = ACCESS_PUBLIC;
		$user->salt = generate_random_cleartext_password();
		$user->password = generate_user_password($user, $password);
		$user->owner_guid = 0;
		$user->container_guid = 0;

		if ( !$user->save() ) {
			register_error(elgg_echo('registerbad'));
		}
 
		// register user && provider
		elgg_set_plugin_user_setting('uid', $user_uid, $user->guid, 'elgg_social_login');
		elgg_set_plugin_user_setting('provider', $provider, $user->guid, 'elgg_social_login');

		// notice && login
		system_message(elgg_echo("A new user account has been created from your $provider account."));
		login($user);
				
			# {{{ update user profile
				// access_id 1 => Logged in users

				// 1. About me
//				create_metadata( $user->guid, "description", html_entity_decode( $user_profile->description, ENT_COMPAT, 'UTF-8'), "text", $user->guid, 1 );

				// 2. Brief description
//				create_metadata( $user->guid, "briefdescription", html_entity_decode( $user_profile->description, ENT_COMPAT, 'UTF-8'), "text", $user->guid, 1 );

				// 3. Location
//				create_metadata( $user->guid, "location", html_entity_decode( $user_profile->region, ENT_COMPAT, 'UTF-8'), "text", $user->guid, 1 );

				// 4. contactemail
//				create_metadata( $user->guid, "contactemail", html_entity_decode( $user_profile->email, ENT_COMPAT, 'UTF-8'), "text", $user->guid, 1 );

				// 5. website
//				create_metadata( $user->guid, "website", html_entity_decode( $user_profile->profileURL, ENT_COMPAT, 'UTF-8'), "text", $user->guid, 1 );
			# }}} update user profile

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
	} elseif ( count($users) == 1 ) {
		// login user
		login($users[0]);
		// notice
		system_message(elgg_echo("You have signed in with $provider"));
	} else {
		throw new Exception("Unable to login with $provider");
	} 
}