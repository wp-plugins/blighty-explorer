<?php
/**

Copyright (C) 2015 Chris Murfin

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**/

function bex_plugin_prequesites() {
	global $current_user;
	$userid = $current_user->ID;

	// If "Dismiss" link has been clicked, user meta field is added
	if ( isset( $_GET['dismiss_me'] ) && 'yes' == $_GET['dismiss_me'] ) {
		add_user_meta( $userid, 'bex_ignore_warning_notice', 'yes', true );
		return;
	}
	
	global $pagenow;
  
    if ($pagenow != 'plugins.php' && $pagenow != 'admin.php') {
        return;
    }
    
    // Only show this notice if user hasn't already dismissed it...
    if ( get_user_meta( $userid, 'bex_ignore_warning_notice' ) ) {
    	return;
    }
	
	$slug = 'svg-vector-icon-plugin';
	$path = $slug .'/wp-svg-icons.php';
	$plugins = get_plugins();
	
	// This plugin previously used the WP SVG Icons plugin. With version 1.3.0, it is no longer required.
	// Check to see if it exists and prompt to uninstall. The prompt can be dismissed.
	
	if (empty($plugins[$path])) {
		return;
	}

	$dismiss_url = $_SERVER['REQUEST_URI'];
	if (strpos($dismiss_url,'?') > 0) {
		$dismiss_url .= '&';
	} else {
		$dismiss_url .= '?';
	}
	$dismiss_url .= 'dismiss_me=yes';

	echo '<div class="update-nag"><p>The <b>' . PLUGIN_NAME .'</b> plugin used to require the <b>WP SVG Icons</b> plugin. If you\'re not using <b>WP SVG Icons</b> elsewhere, it can be safely removed. <a href="' .$dismiss_url .'">Dismiss</a><br /><br />';
	
	if (is_plugin_active($path)) {
		$deactivate_url = wp_nonce_url(admin_url('plugins.php?action=deactivate&plugin=' .$path), 'deactivate-plugin_' .$path );
		echo '<a href="' .$deactivate_url .'">Deactivate Plugin</a>';
	}

	echo '</p></div>';
}

function bex_init() {
	register_setting( 'bex_option-settings', 'bex_folder', 'bex_folder_validate');
	register_setting( 'bex_option-settings', 'bex_show_moddate');
	register_setting( 'bex_option-settings', 'bex_show_size');
	register_setting( 'bex_option-settings', 'bex_allow_uploads');
	register_setting( 'bex_option-settings', 'bex_email_upload');
	register_setting( 'bex_option-settings-bts', 'bex_dropbox_token' );
	register_setting( 'bex_option-settings-bts', 'bex_dropbox_temp_token' );	
}

function bex_setup_menu(){
	add_menu_page( 'Blighty Explorer', 'Blighty Explorer', 'manage_options', 'blighty-explorer-plugin', 'bex_admin_settings', 'dashicons-index-card' );
}
 
function bex_admin_settings(){
	global $dropbox;
?>
	<div class="wrap">
		<h2><?php echo PLUGIN_NAME; ?> version <?php echo PLUGIN_VERSION; ?></h2>
		<?php
		if (isset($_GET['auth_callback'])) {
			echo '<div class="updated"><p>Dropbox connection successful.</p></div>';
		} else if (isset($_GET['bex_reset'])) {
			echo '<div class="updated"><p>Dropbox connection has been reset.</p></div>';
		}
		settings_errors();
		
		?>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar">
					<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
						<div class="postbox">
							<h3>Dropbox Information</h3>
							<div class="inside">
		<?php
		$rc = bex_handle_dropbox_auth($dropbox);
		if ($rc == 0) {
			$info = $dropbox->GetAccountInfo();
			$quota = $info->quota_info->quota;
			$used = $info->quota_info->normal + $info->quota_info->shared;
			$pc = $used / $quota * 100;
			echo '<b>Account:</b> ' .$info->display_name .'<br />';
			echo '<b>Quota:</b> ' .bex_format_bytes($quota) .'<br />';
			echo '<b>Used:</b> ' .bex_format_bytes($used) .' (' .sprintf("%.1f%%", $pc) .')<br /><br />';
			echo '<a href="?page=blighty-explorer-plugin&bex_reset=1">Reset Dropbox connection.</a><br />';

		} elseif ($rc == 2) {
			$rc = bex_handle_dropbox_auth($dropbox);
		}
		?>						
							</div>
						</div>
						<div class="postbox">
							<h3>Support This Plugin</h3>
							<div class="inside">
								If you find this plugin useful, please consider supporting it and future development. Thank you.<br /><br />
								<div align="center">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
									<input type="hidden" name="cmd" value="_donations">
									<input type="hidden" name="business" value="2D9PDAS9FDDCA">
									<input type="hidden" name="lc" value="US">
									<input type="hidden" name="item_name" value="Blighty Explorer Plugin">
									<input type="hidden" name="item_number" value="BEP001A">
									<input type="hidden" name="button_subtype" value="services">
									<input type="hidden" name="no_note" value="1">
									<input type="hidden" name="no_shipping" value="1">
									<input type="hidden" name="currency_code" value="USD">
									<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donateCC_LG.gif:NonHosted">
									<input type="hidden" name="on0" value="website">
									<input type="hidden" name="os0" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
									<input type="radio" name="amount" value="2">$2&nbsp;
									<input type="radio" name="amount" value="5">$5&nbsp;
									<input type="radio" name="amount" value="10">$10&nbsp;
									<input type="radio" name="amount" value="20">$20&nbsp;
									<input type="radio" name="amount" value="">Other<br /><br />
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
									</form>
								</div>
							</div>
						</div>
						<div class="postbox">
							<h3>Technical Support</h3>
							<div class="inside">
								If you need technical support or would like to see a new featured implemented, please provide your feedback via the <a href="https://wordpress.org/support/plugin/blighty-explorer">WordPress Plugin Forums</a>.
							</div>
						</div>
					</div>
				</div>

				<div id="post-body-content" class="has-sidebar-content">
					<div class="meta-box-sortabless">
						<div class="postbox">
							<h3>Configuration and Usage</h3>
							<div class="inside">
								<ol>
									<li>Connect this plugin to your Dropbox account (see top right).</li>
									<li>This will create a subfolder called <b>Blighty Explorer</b> in your <b>Apps</b> folder within Dropbox.</li>
									<li>Place your folders and files you wish to share with this WordPress installation inside the <b>Apps/Blighty Explorer</b> subfolder.</li>
									<li>Use the shortcode <b>[bex_folder]</b> in your post or page to display a folder structure / file navigator.</li>
									<li>Use the shortcode <b>[bex_upload]</b> in your post or page to display a file upload dialog.</li>
								</ol>
							</div>
						</div>
						<div class="postbox">
							<h3>Options</h3>
							<div class="inside">
								<form method="post" action="options.php">
								<?php
								
								settings_fields('bex_option-settings'); 
								
								if ( get_option('bex_show_moddate') == '1' ) {
									$checkedModDate = 'checked ';
								} else {
									$checkedModDate = '';
								}
								
								if ( get_option('bex_show_size') == '1' ) {
									$checkedSize = 'checked ';
								} else {
									$checkedSize = '';
								}
								
								if ( get_option('bex_email_upload') == '1' ) {
									$checkedEmail = 'checked ';
								} else {
									$checkedEmail = '';
								}

								echo 'By default, folders and files are shared from your <strong>Dropbox Folder/Apps/Blighty Explorer</strong>. ';
								echo 'If you want to share a subfolder under <strong>Apps/Blighty Explorer</strong>, set it here as the root folder. ';
								echo 'This allows you to share different subfolders on different WordPress installations.<br /><br />';
								
								echo '<b>Root Folder:</b>&nbsp;<input type="text" name="bex_folder" value="' .esc_attr( get_option('bex_folder') ) .'" /><br /><br />';
								echo '<b>Show Modification Date:</b>&nbsp;<input type="checkbox" name="bex_show_moddate" value="1"' .$checkedModDate .' /><br /><br />';
								echo '<b>Show Size:</b>&nbsp;<input type="checkbox" name="bex_show_size" value="1"' .$checkedSize .' /><br /><br />';

								echo 'File uploads via this plugin will be stored in the folder <strong>' .UPLOADS_FOLDER .'</strong> under the <strong>Root Folder</strong> above.<br /><br />';
								//echo 'If you want to allow uploads into the folder that the user has navigated to, then check the <strong>Allow Uploads in Active Folder</strong> option below.<br /><br />';

								//echo '<b>Allow Uploads in Active Folder:</b>&nbsp;<input type="checkbox" name="bex_allow_uploads" value="1"' .$checkedAllowUploads .' /><br /><br />';
								echo '<b>Email Admin on Upload:</b>&nbsp;<input type="checkbox" name="bex_email_upload" value="1"' .$checkedEmail .' />';
								echo '&nbsp;Check this box to receive an email every time a user uploads a file.<br />';
								submit_button();
								
								?>		
								</form>					
							</div>
						</div>
					</div>
				</div>
				<?php echo PLUGIN_NAME; ?> version <?php echo PLUGIN_VERSION; ?> by <a href="http://blighty.net" target="_blank">Blighty</a>
			</div>

	</div>
<?php
}

function bex_folder_validate($input){

	if (preg_match('#^(\/)?((\w)+(\.| |\&|\-|\(|\))*(\w)*(\/)*)*(\/)?$#',$input)) {
		$output = $input;
		// Valid path, but add / if not at front...
		if (substr($input,0,1) != '/') {
			$output = '/' .$output;
		}
	} else {
		add_settings_error( 'mbex_option-settings', 'invalid-folder', 'You have entered an invalid root folder.', "error" );
		$output = "";
	}
	
	return $output;

}

?>