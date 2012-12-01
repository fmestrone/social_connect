<?php
global $CONFIG;

require "{$CONFIG->pluginspath}social_connect/settings.php";

$plugin_base_url     = "{$CONFIG->url}mod/social_connect/";
$hybridauth_base_url = "{$CONFIG->url}mod/social_connect/vendors/hybridauth/";
$assets_base_url     = "{$vars['url']}mod/social_connect/graphics/";

$debug_mode_on = $vars['entity']->ha_settings_test_mode;

echo '<div id="social_connect_site_settings">';

if ( !session_id() || !version_compare(PHP_VERSION, '5.2.0', '>=') || !function_exists('curl_version') || class_exists('OAuthException') || extension_loaded('oauth') ) {
?>
<p style="font-size: 14px;margin-left:10px;">
	<br />
	<b style='color:red;'>
	<?php elgg_echo('social_connect:settings:warning') ?>
	<br />
	<?php elgg_echo('social_connect:settings:failed_requirements') ?>
	</b>
	<br />
</p>
<?php
}
// NB slideToggle does not work on table rows
?>
    <script type="text/javascript">
        function toggleDebugOptions() {
            $('#trDebugOptions').toggle();
        }
        function toggleProviderOptions(provider) {
            $('#social_connect_div_' + provider).slideToggle();
        }
    </script>
	<p style="font-size: 14px;margin-left:10px;">
		<br /> 
		<div align="center">
		<b><a href="<?php echo $plugin_base_url ?>diagnostics.php?url=http://www.example.com" target="_blank" style="border: 1px solid #CCCCCC;border-radius: 5px;padding: 7px;text-decoration: none;"> <?php echo elgg_echo('social_connect:settings:run_tests') ?> </a></b>
		&nbsp;
		<b><a href="https://moodsdesign.atlassian.net/wiki/display/ELGGSOCIAL" target="_blank"  style="border: 1px solid #CCCCCC;border-radius: 5px;padding: 7px;text-decoration: none;"> <?php echo elgg_echo('social_connect:settings:user_guide') ?> </a></b>
		</div>
		<br /> 
	</p>

	<br />
	<h2 style="border-bottom: 1px solid #CCCCCC;margin:10px;"><?php echo elgg_echo('social_connect:settings:general') ?></h2>

		<div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
			<table width="100%">
                <thead>
                <tr>
                    <td width="25%">
                        <b><?php echo elgg_echo('social_connect:settings:debug_mode') ?></b>
                    </td>
                    <td width="38%">
                        <select style="height:22px;margin: 3px;" name="params[ha_settings_test_mode]" onchange="toggleDebugOptions();">
                            <option value="1" <?php if( $debug_mode_on ) echo "selected"; ?>><?php echo elgg_echo('social_connect:settings:yes') ?></option>
                            <option value="0" <?php if( !$debug_mode_on ) echo "selected"; ?>><?php echo elgg_echo('social_connect:settings:no') ?></option>
                        </select>
                    </td>
                    <td width="37%">
                        &nbsp;&nbsp; <?php echo elgg_echo('social_connect:settings:debug_mode_explain') ?>
                    </td>
                </tr>
                </thead>
                <tbody id="trDebugOptions" style="display: <?php echo $debug_mode_on ? 'table-row-group' : 'none'; ?>">
                <tr>
                    <td width="25%">
                        <b><?php echo elgg_echo('social_connect:settings:debug_level') ?></b>
                    </td>
                    <td width="38%">
                        <select style="height:22px;margin: 3px;" name="params[ha_settings_test_loglevel]">
                            <option value="3" <?php if ( $vars['entity']->ha_settings_test_loglevel == '3' ) echo "selected"; ?>>DEBUG</option>
                            <option value="2" <?php if ( $vars['entity']->ha_settings_test_loglevel == '2' ) echo "selected"; ?>>INFO</option>
                            <option value="1" <?php if ( $vars['entity']->ha_settings_test_loglevel == '1' ) echo "selected"; ?>>ERROR</option>
                            <option value="0" <?php if ( $vars['entity']->ha_settings_test_loglevel == '0' ) echo "selected"; ?>>OFF</option>
                        </select>
                    </td>
                    <td width="37%">
                        &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><?php echo elgg_echo('social_connect:settings:debug_file') ?></b>
                    </td>
                    <td>
                        <input type="text" style="width: 250px;margin: 3px;"
                               value="<?php echo $vars['entity']->ha_settings_test_logfile; ?>"
                               name="params[ha_settings_test_logfile]">
                    </td>
                    <td>
                        &nbsp;&nbsp; <?php echo elgg_echo('social_connect:settings:debug_file_explain') ?>
                    </td>
                </tr>
                </tbody>
			</table>
		</div> 
 
		<div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
			<table width="100%">
			<tr>
			<td width="25%">
				<b><?php echo elgg_echo('social_connect:settings:privacy') ?></b>
			</td>
			<td width="38%">
				<input type="text" style="width: 250px;margin: 3px;"
					value="<?php echo $vars['entity']->ha_settings_privacy_page; ?>"
					name="params[ha_settings_privacy_page]">
            </td>
            <td width="37%">
                &nbsp;&nbsp; <?php echo elgg_echo('social_connect:settings:privacy_explain') ?>
			</td>
			</tr>
			</table>
		</div>

<div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
    <table width="100%">
        <tr>
            <td width="25%">
                <b><?php echo elgg_echo('social_connect:settings:global_hook_default') ?></b>
            </td>
            <td width="38%">
                <select style="height:22px;margin: 3px;" name="params[ha_settings_hook1_default]">
                    <option value="true" <?php if( $vars['entity']->ha_settings_hook1_default == 'true' ) echo "selected"; ?>>true</option>
                    <option value="false" <?php if( $vars['entity']->ha_settings_hook1_default == 'false' ) echo "selected"; ?>>false</option>
                    <option value="email" <?php if( $vars['entity']->ha_settings_hook1_default == 'email' ) echo "selected"; ?>>email</option>
                    <option value="emailOnly" <?php if( $vars['entity']->ha_settings_hook1_default == 'emailOnly' ) echo "selected"; ?>>emailOnly</option>
                </select>
            </td>
            <td width="37%">
                &nbsp;&nbsp; <?php echo elgg_echo('social_connect:settings:global_hook_explain') ?>
            </td>
        </tr>
    </table>
</div>

<br />
	<h2 style="border-bottom: 1px solid #CCCCCC;margin:10px;"><?php echo elgg_echo('social_connect:settings:provider_setup') ?></h2>
	<p style="margin:10px;">
		<?php echo elgg_echo('social_connect:settings:except_openid') ?>
	</p>
	<ul style="list-style:circle inside;margin-left:30px;">
		<li><?php echo elgg_echo('social_connect:settings:follow_help') ?></li>
		<li><?php echo elgg_echo('social_connect:settings:status_no') ?></li>
	</ul>
	<br />
<?php   
	foreach ( $HA_SOCIAL_CONNECT_PROVIDERS_CONFIG as $item ) {
		$provider_id                = @ $item['label'];
		$provider_name              = @ $item['provider_name'];

		$require_client_id          = @ $item['require_client_id'];
		$provide_email              = @ $item['provide_email'];
		
		$provider_new_app_link      = @ $item['new_app_link'];
		$provider_userguide_section = @ $item['userguide_section'];

		$provider_callback_url      = '';

		if( isset( $item['callback'] ) && $item['callback'] ){
			$provider_callback_url  = '<span style="color:green">' . $plugin_base_url . '?hauth.done=' . $provider_id . '</span>';
		}
		
		$setupsteps = 0;
	?> 
	<div> 
		<div style="border-radius:3px; border: 1px solid #999999;">
			<div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
				<h2><img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . "16x16/" . strtolower( $provider_id ) . '.png' ?>" /> <?php echo $provider_name ?></h2>
                <br>
				<ul>
                    <?php $entitykey = 'ha_settings_' . $provider_id . '_enabled'; ?>
					<li><b><?php echo elgg_echo('social_connect:settings:enable_provider', array($provider_name)); ?></b>
						<select name="params[<?php echo $entitykey ?>]" style="height:22px;margin: 3px;" onchange="toggleProviderOptions('<?php echo $provider_id ?>');">
							<option value="1" <?php if( $vars['entity']->$entitykey == 1 ) echo "selected"; ?>><?php echo elgg_echo('social_connect:settings:yes') ?></option>
							<option value="0" <?php if( $vars['entity']->$entitykey == 0 ) echo "selected"; ?>><?php echo elgg_echo('social_connect:settings:no') ?></option>
						</select>
					</li>
                </ul>
            </div>
            <div id="social_connect_div_<?php echo $provider_id ?>" style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;display: <?php echo $vars['entity']->$entitykey == 1 ? 'block' : 'none'; ?>">
                <ul>
                    <?php $entitykey = 'ha_settings_' . $provider_id . '_hook1_default'; ?>
                    <li><b><?php echo elgg_echo('social_connect:settings:hook_default', array($provider_name)); ?></b>
                        <select name="params[<?php echo $entitykey ?>]" style="height:22px;margin: 3px;" >
                            <option value="global" <?php if( $vars['entity']->$entitykey == 'global' ) echo "selected"; ?>>global</option>
                            <option value="true" <?php if( $vars['entity']->$entitykey == 'true' ) echo "selected"; ?>>true</option>
                            <option value="false" <?php if( $vars['entity']->$entitykey == 'false' ) echo "selected"; ?>>false</option>
                            <option value="email" <?php if( $vars['entity']->$entitykey == 'email' ) echo "selected"; ?>>email</option>
                            <option value="emailOnly" <?php if( $vars['entity']->$entitykey == 'emailOnly' ) echo "selected"; ?>>emailOnly</option>
                        </select>
                    </li>

                    <?php foreach ( $item['extras'] as $extra_key => $extra_value ) { ?>
                        <?php $entitykey = "ha_settings_{$provider_id}_{$extra_key}"; ?>
                        <li><b><?php echo elgg_echo("social_connect:settings:{$provider_id}_{$extra_key}"); ?></b>
                            <?php
                            switch ( $extra_value ) {
                                case 'text':
                            ?>
                            <input type="text" style="width: 350px;margin: 3px;"
                                   value="<?php echo $vars['entity']->$entitykey; ?>"
                                   name="params[<?php echo $entitykey ?>]" ></li>
                            <?php break; ?>
                            <?php } ?>
                        </li>
                    <?php } ?>

                    <?php if ( $provider_new_app_link ){ ?>
						<?php if ( $require_client_id ){ // key or id ? ?>
                        <?php $entitykey = 'ha_settings_' . $provider_id . '_app_id'; ?>
					<li><b><?php echo elgg_echo('social_connect:settings:appid') ?></b>
							<input type="text" style="width: 350px;margin: 3px;"
							value="<?php echo $vars['entity']->$entitykey; ?>"
							name="params[<?php echo $entitykey ?>]" ></li>
						    <?php } else { ?>
                            <?php $entitykey = 'ha_settings_' . $provider_id . '_app_key'; ?>
							<li><b><?php echo elgg_echo('social_connect:settings:appkey') ?></b>
							<input type="text" style="width: 350px;margin: 3px;"
								value="<?php echo $vars['entity']->$entitykey; ?>"
								name="params[<?php echo $entitykey ?>]" ></li>
						<?php }; ?>

                        <?php $entitykey = 'ha_settings_' . $provider_id . '_app_secret'; ?>
						<li><b><?php echo elgg_echo('social_connect:settings:appsecret') ?></b>
						<input type="text" style="width: 350px;margin: 3px;"
							value="<?php echo $vars['entity']->$entitykey; ?>"
							name="params[<?php echo $entitykey ?>]" ></li>
					<?php } // if require registration ?>
                </ul>
                <div style="padding: 12px;margin: 5px;background: none repeat scroll 0 0 white;border-radius:3px;">
                    <p><b><?php echo elgg_echo('social_connect:settings:howto_provider', array($provider_name)) ?></b></p>

                    <?php if ( $provider_new_app_link ) : ?>
                    <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto1_provider', array($provider_new_app_link)); ?></p>

                    <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto2_provider'); ?></p>

                    <?php if ( $provider_id == "Google" ) : ?>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto3_provider'); ?></p>
                        <?php endif; ?>

                    <?php if ( $provider_callback_url ) : ?>
                        <p>
                            <?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto4_provider'); ?>
                            <br />
                            <?php echo $provider_callback_url ?>
                        </p>
                        <?php endif; ?>

                    <?php if ( $provider_id == "MySpace" ) : ?>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto5_2_provider', array('External Url', 'External Callback Validation', $_SERVER['SERVER_NAME'])); ?></p>
                        <?php endif; ?>

                    <?php if ( $provider_id == "Live" ) : ?>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto5_1_provider', array('Redirect Domain', $_SERVER['SERVER_NAME'])); ?></p>
                        <?php endif; ?>

                    <?php if ( $provider_id == "Facebook" ) : ?>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto5_1_provider', array('Site Url', $_SERVER['SERVER_NAME'])); ?></p>
                        <?php endif; ?>

                    <?php if ( $provider_id == "LinkedIn" ) : ?>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto5_1_provider', array('Integration Url', $_SERVER['SERVER_NAME'])); ?></p>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto6_provider'); ?></p>
                        <?php endif; ?>

                    <?php if ( $provider_id == "Twitter" ) : ?>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto5_2_provider', array('Application Website', 'Application Callback URL', $_SERVER['SERVER_NAME'])); ?></p>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto7_provider'); ?></p>
                        <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto8_provider'); ?></p>
                        <?php endif; ?>

                    <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo elgg_echo('social_connect:settings:howto9_provider'); ?></p>
                    <?php else: ?>
                    <p><?php echo elgg_echo('social_connect:settings:howto0_provider'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
			</div>
		</div>
	<br />
	<?php 
}

echo '</div>';
