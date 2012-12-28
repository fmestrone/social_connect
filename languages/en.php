<?php
$localized = array(
	'social_connect:no_version_check' => 'The Version Check plugin is either not installed or not active. You will not be able to receive notifications of new Social Connect releases.',
	'social_connect:settings:warning' => 'Warning:',
	'social_connect:settings:failed_requirements' => 'Unfortunately your server failed the requirements test for this plugin. Most likely Social Connect will not work correctly!',
	'social_connect:settings:run_tests' => 'Run the Requirements Test',
	'social_connect:settings:user_guide' => 'Read the User\'s Guide',
	'social_connect:settings:general' => 'General Settings',
	'social_connect:settings:debug_mode' => 'Enable Debug Mode?',
    'social_connect:settings:debug_mode_explain' => 'We recommend that you set <b>Enable Debug Mode</b> to <b style="color:green">YES</b> until you are ready to go live.',
    'social_connect:settings:debug_file' => 'Log File',
    'social_connect:settings:debug_file_explain' => 'If blank, it defaults to <i>log/debug.log</i> under the plugin folder.',
    'social_connect:settings:debug_level' => 'Log Level',
    'social_connect:settings:global_hook_default' => 'Social connection handling',
    'social_connect:settings:global_hook_explain' => 'You can override this for each provider in the settings below.',
    'social_connect:settings:global_hook_global' => 'Use global setting',
    'social_connect:settings:global_hook_true' => 'Always',
    'social_connect:settings:global_hook_false' => 'Never',
    'social_connect:settings:global_hook_email' => 'Once per email',
    'social_connect:settings:global_hook_emailonly' => 'Only with email',
	'social_connect:settings:notify_new_user' => 'Notify new users by email?',
	'social_connect:settings:yes' => 'YES',
	'social_connect:settings:no' => 'NO',
	'social_connect:settings:privacy' => 'URL of Privacy Statement',
	'social_connect:settings:privacy_explain' => 'You can leave this blank if you don\'t have a privacy statement.',
	'social_connect:settings:provider_setup' => 'Provider Setup',
	'social_connect:settings:provider_settings_help' => 'Except for OpenID providers, each social network and identity provider will require that you create an external application linking your website to their API. These external applications ensure that users are logging into websites that they recognize and also allow identity providers to send users back to the correct website after successfully authenticating their accounts.',
	'social_connect:settings:provider_enabled_help' => 'To correctly setup these identity providers, set <b>Allow users to sign on</b> to <b style="color:green">YES</b> and carefully follow the help section for each one or consult <a href="https://moodsdesign.atlassian.net/wiki/display/ELGGSOCIAL" target="_blank">the plugin\'s user guide</a> for more information.',
	'social_connect:settings:provider_disabled_help' => 'To prevent end users from connecting with a provider from your website and to hide the provider from the options on the login and registration pages, simply set <b>Allow users to sign on</b> to <b style="color:red">NO</b>.',
	'social_connect:settings:enable_provider' => 'Allow users to sign on with %s?',
    'social_connect:settings:hook_default' => 'Social connection handling',
	'social_connect:settings:howto_provider' => 'How to setup %s:',
	'social_connect:settings:howto0_provider' => 'No registration required for OpenID based providers',
	'social_connect:settings:howto1_provider' => 'Go to <a href="%s" target ="_blank">%1$s</a> and <b>create a new application</b>.',
	'social_connect:settings:howto2_provider' => 'Fill out any required fields such as the application name and description.',
	'social_connect:settings:howto3_provider' => 'On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.',
	'social_connect:settings:howto4_provider' => 'Provide this URL as the Callback URL for your application:',
	'social_connect:settings:howto5_2_provider' => 'Put your website domain in the <b>%s</b> and <b>%s</b> fields. This should match your current hostname <em style="color:#CB4B16;">%s</em>.',
	'social_connect:settings:howto5_1_provider' => 'Put your website domain in the <b>%s</b> field. This should match the current hostname <em style="color:#CB4B16;">%s</em>.',
	'social_connect:settings:howto6_provider' => 'Set the <b>Application Type</b> to <em style="color:#CB4B16;">Web Application</em>.',
	'social_connect:settings:howto7_provider' => 'Set the <b>Application Type</b> to <em style="color:#CB4B16;">Browser</em>.',
	'social_connect:settings:howto9_provider' => 'Once you have registered, copy and paste the credentials of the newly created application into this setup page.',
	'social_connect:settings:howto4_provider' => 'Provide this URL as the Callback URL for your application:',
	'social_connect:settings:howto4_provider' => 'Provide this URL as the Callback URL for your application:',
	'social_connect:settings:howto4_provider' => 'Provide this URL as the Callback URL for your application:',
	'social_connect:settings:howto4_provider' => 'Provide this URL as the Callback URL for your application:',
	'social_connect:settings:appid' => 'Application ID',
	'social_connect:settings:appkey' => 'Application Key',
	'social_connect:settings:appsecret' => 'Application Secret',
	'social_connect:connect:link_title' => 'Connect with %s',
	'social_connect:connect:connect_with' => 'Or connect with ',
	'social_connect:connect:privacy' => 'Privacy',
	'social_connect:register:bad' => 'Could not register with %s',
	'social_connect:register:ok' => 'A new Elgg user account has been created from your %s account.',
	'social_connect:connect:ok' => 'Your Elgg user account has been connected to your %s account.',
	'social_connect:connect:emailnotfound' => 'The email address provided by %s is not registered with Elgg yet',
	'social_connect:login:ok' => 'You have signed in with %s',
	'social_connect:login:bad' => 'Unable to sign you in with %s',
	'social_connect:authenticate:loading' => 'Loading...',
	'social_connect:authenticate:contacting' => 'Contacting <b>%s</b>, please wait...',
	// Email notifications
	'email:social_connect:subject' => 'A new user has been created from your %s account',
	'email:social_connect:body' => "Thanks for signing up!\n\nNew username: %s\n\nGenerated password: %s\n\nYou can login with these details and if you wish change the assigned password in your profile.",
	// Provider-specific options
    'social_connect:settings:OpenID_url' => 'OpenID Provider URL',
);
add_translation('en', $localized);
