<?php
elgg_register_event_handler('init', 'system', 'social_connect_init');

function social_connect_init() {
    // add connect form after login form
	if ( !elgg_get_plugin_setting('social_bar_hide_login', 'social_connect') ) {
		elgg_extend_view('forms/login'   , 'social_connect/connect', 501);
	}
    // add connect form before register form
	if ( !elgg_get_plugin_setting('social_bar_hide_register', 'social_connect') ) {
		elgg_extend_view('forms/register', 'social_connect/connect', 499);
	}
    // extend admin style sheet
	elgg_extend_view('css/admin'     , 'social_connect/admincss');
    // extend main style sheet
	elgg_extend_view('css/elgg'      , 'social_connect/css');
    // handle 'public_pages','wall_garden' hook to allow plugin to work in walled garden too
    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'social_connect_public_pages');
    // register this plugin for version updates
    if ( elgg_is_active_plugin('version_check') ) {
        version_check_register_plugin('social_connect');
    }
}

function social_connect_public_pages($hook, $type, $return_value, $params) {
    // add to the current list of public pages that should be available from the walled garden
    $return_value[] = 'mod/social_connect/authenticate\\.php';
    $return_value[] = 'mod/social_connect/index\\.php';
    // return the modified value
    return $return_value;
}

function social_connect_handle_authentication($user_profile, $provider) {
	global $CONFIG;
    global $HA_SOCIAL_CONNECT_PROVIDERS_CONFIG;

    $ignore_access = elgg_get_ignore_access();

	$provider_name = $HA_SOCIAL_CONNECT_PROVIDERS_CONFIG[$provider]['provider_name'];
	$user_uid = $user_profile->identifier;

    // establish the value for the proceeding hook
    $default_proceed = elgg_get_plugin_setting("ha_settings_{$provider}_hook1_default", 'social_connect');
    if ( !$default_proceed || $default_proceed == 'global' ) {
        $default_proceed = elgg_get_plugin_setting('ha_settings_hook1_default', 'social_connect');
    }
    if ( !$default_proceed ) {
        $default_proceed = SOCIAL_CONNECT_DEFAULT_PROCEED;
    } else if ( $default_proceed == 'true' ) {
        $default_proceed = true;
    } else if ( $default_proceed == 'false' ) {
        $default_proceed = false;
    }

	// the arguments for social connect events and hooks
	$args = array(
		'mode' => null,
		'userid' => $user_uid,
		'provider' => $HA_SOCIAL_CONNECT_PROVIDERS_CONFIG[$provider],
		'user' => null,
		'profile' => $user_profile,
	);

	// look for users that have already connected via this plugin
	$options = array(
		'type' => 'user',
		'plugin_id' => 'social_connect',
		'plugin_user_setting_name_value_pairs' => array(
			"$provider/uid" => $user_uid
		),
		'plugin_user_setting_name_value_pairs_operator' => 'AND',
		'limit' => 0
	);
	$users = elgg_get_entities_from_plugin_user_settings($options);

	if ( !$users ) { // user has not connected with plugin before
		$args['mode'] = 'connect';
        elgg_set_ignore_access(true);
        $proceed = elgg_trigger_plugin_hook('social_connect', 'user', $args, $default_proceed);
        elgg_set_ignore_access($ignore_access);
		if ( $proceed === false ) {  // hook prevented social connection
			return;
		} else if ( $proceed === 'email' || $proceed === 'emailOnly' ) { // hook wants to try and connect via email address
			// check whether the user already exists with the email provided
			$useremail = $user_profile->email;
			if ( $useremail && ($users = get_user_by_email($useremail)) ) {
				social_connect_user($user_uid, $users[0], $user_profile, $provider);
				system_message(sprintf(elgg_echo('social_connect:connect:ok'), $provider_name));
				$args['mode'] = 'email';
				$args['user'] = $users[0];
                elgg_set_ignore_access(true);
				elgg_trigger_event('social_connect', 'user', $args);
                elgg_set_ignore_access($ignore_access);
				return;
			}
			if ( $proceed === 'emailOnly' ) { // hook wants only email address connection or failure
				register_error(sprintf(elgg_echo('social_connect:connect:emailnotfound'), $proceed));
				return;
			}
		}
		// email connection not required or failed, so register a new user
		$userlogin = str_replace(' ', '', $user_profile->displayName);
		if ( !$userlogin ) {
			$userlogin = $provider . '_user_' . rand(1000, 9999);
		}
		$org_userlogin = $userlogin;
		while ( get_user_by_username($userlogin) ) {
			$userlogin = $org_userlogin . '_' . rand(1000, 9999);
		}
		unset($org_userlogin);

		$password = generate_random_cleartext_password();
		$username = $user_profile->displayName;

		$user = new ElggUser();
		$user->username = $userlogin;
		$user->name = $username;
		$user->email = $user_profile->email;
		$user->access_id = ACCESS_PUBLIC;
		$user->salt = generate_random_cleartext_password();
		$user->password = generate_user_password($user, $password);
		$user->owner_guid = 0;
		$user->container_guid = 0;
		if ( $user->save() ) {
			if ( $user->email && elgg_get_plugin_setting('notify_new_user', 'social_connect') ) {
				$email = elgg_echo('email:social_connect:body', array($userlogin, $password));
				set_user_notification_setting($user->getGUID(), 'email', true);
				notify_user($user->guid, $CONFIG->site->guid, elgg_echo('email:social_connect:subject', array($provider_name)), $email, NULL, 'email');
			}
		} else {
			register_error(sprintf(elgg_echo('social_connect:register:bad'), $provider_name));
			return;
		}
		system_message(sprintf(elgg_echo('social_connect:register:ok'), $provider_name));
		social_connect_user($user_uid, $user, $user_profile, $provider);
        $args['mode'] = 'register';
        $args['user'] = $user;
        elgg_set_ignore_access(true);
        elgg_trigger_event('social_connect', 'user', $args);
        elgg_set_ignore_access($ignore_access);
	} elseif ( count($users) == 1 ) { // one user has already been registered on Elgg with this provider
		$args['mode'] = 'login';
		$args['user'] = $users[0];
        elgg_set_ignore_access(true);
		if ( elgg_trigger_plugin_hook('social_connect', 'user', $args, (bool)$default_proceed) ) {   // if not, hook prevented social connection
			login($users[0]);
			system_message(sprintf(elgg_echo('social_connect:login:ok'), $provider_name));
		}
        elgg_set_ignore_access($ignore_access);
	} else {
		throw new Exception(sprintf(elgg_echo('social_connect:login:bad'), $provider_name));
	} 
}

function social_connect_user($user_uid, $user, $user_profile, $provider) {
	// register user && provider
	elgg_set_plugin_user_setting("$provider/uid", $user_uid, $user->guid, 'social_connect');

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
            if ( !$filehandler->exists() ) {
                $filehandler->open('write');
                $filehandler->write($image);
                $filehandler->close();
            }
		}

		$user->icontime = time(); 
	}
	# }}} user image
}