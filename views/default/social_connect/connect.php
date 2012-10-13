<?php
global $CONFIG;
global $HA_SOCIAL_CONNECT_PROVIDERS_CONFIG;

require_once "{$CONFIG->pluginspath}social_connect/settings.php";

// display provider icons
$connect_with = '';
foreach ( $HA_SOCIAL_CONNECT_PROVIDERS_CONFIG AS $item ) {
	$provider_id     = @$item['label'];
	$provider_name   = @$item['provider_name'];

	$link_title = elgg_echo('social_connect:connect:link_title', array($provider_name));

	$assets_base_url = "{$vars['url']}mod/social_connect/graphics/";

	if ( elgg_get_plugin_setting("ha_settings_{$provider_id}_enabled", 'social_connect') ) {
		$connect_with .= "<a href=\"javascript:void(0);\" title=\"$link_title\" class=\"ha_connect_with_provider\" data-provider=\"$provider_id\">";
		$connect_with .= "<img alt=\"$provider_name\" title=\"$provider_name\" src=\"{$assets_base_url}32x32/" . strtolower($provider_id) . ".png\" />";
		$connect_with .= "</a> \n";
	}
}

if ( !empty($connect_with) ) {
	// provide popup url for hybridauth callback
	?>
	<div class="social_connect_site_connect">
		<div><b><?php echo elgg_echo('social_connect:connect:connect_with'); ?></b></div>
	<?php echo $connect_with ?>
	<input id="ha_popup_base_url" type="hidden" value="<?php echo "{$vars['url']}mod/social_connect/"; ?>authenticate.php?" />
	<p>
	<?php
	// link attribution && privacy page 
	if ( elgg_get_plugin_setting('ha_settings_privacy_page', 'social_connect' ) ) {
		?>
			<a href="<?php echo elgg_get_plugin_setting('ha_settings_privacy_page','social_connect') ?>" target="_blank"><?php echo elgg_echo('social_connect:connect:privacy'); ?></a>
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