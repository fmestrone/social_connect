<?php
global $CONFIG;
global $HA_SOCIAL_LOGIN_PROVIDERS_CONFIG;

require_once "{$CONFIG->pluginspath}elgg_social_login/settings.php";

// display provider icons
$connect_with = '';
foreach ( $HA_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ) {
	$provider_id     = @$item['label'];
	$provider_name   = @$item['provider_name'];

	$link_title = elgg_echo('jasl:connect:link_title', array($provider_name));

	$assets_base_url = "{$vars['url']}mod/elgg_social_login/graphics/";

	if ( get_plugin_setting("ha_settings_{$provider_id}_enabled", 'elgg_social_login') ) {
		$connect_with .= "<a href=\"javascript:void(0);\" title=\"$link_title\" class=\"ha_connect_with_provider\" data-provider=\"$provider_id\">";
		$connect_with .= "<img alt=\"$provider_name\" title=\"$provider_name\" src=\"{$assets_base_url}32x32/" . strtolower($provider_id) . ".png\" />";
		$connect_with .= "</a> \n";
	}
}

if ( !empty($connect_with) ) {
	// provide popup url for hybridauth callback
	?>
	<div class="elgg_social_login_site_connect">
		<div><b><?php echo elgg_echo('jasl:connect:connect_with'); ?></b></div>
	<?php echo $connect_with ?>
	<input id="ha_popup_base_url" type="hidden" value="<?php echo "{$vars['url']}mod/elgg_social_login/"; ?>authenticate.php?" />
	<p>
	<?php
	// link attribution && privacy page 
	if ( get_plugin_setting('ha_settings_privacy_page', 'elgg_social_login' ) ) {
		?>
			<a href="<?php echo get_plugin_setting( 'ha_settings_privacy_page', 'elgg_social_login' ) ?>" target="_blank"><?php echo elgg_echo('jasl:connect:privacy'); ?></a>
		<?
	}
	?>
	</p>
	</div>
	<script>
		$(function(){
			$(".ha_connect_with_provider").click(function(){
				popupurl = $("#ha_popup_base_url").val();
				provider = $(this).data("provider");

				window.open(
					popupurl+"provider="+provider,
					"hybridauth_social_sing_on", 
					"location=1,status=0,scrollbars=0,width=800,height=570"
				); 
			});
		});
	</script> 
	<?
}
?>