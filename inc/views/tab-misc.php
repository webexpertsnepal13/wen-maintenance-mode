<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php wp_nonce_field( 'wmm-settings-submenu-page-save' ); ?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<label for="page-title"><?php _e( 'Page Title', 'wen-maintenance-mode' ); ?></label>
				</th>
				<td>
					<input name="page_title" type="text" id="page-title" value="<?php echo get_option( 'wmm_page_title' ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Favicon', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php $favicon = get_option( 'wmm_favicon' ); ?>
						<legend class="screen-reader-text"><span><?php _e( 'Favicon', 'wen-maintenance-mode' ); ?></span></legend>
						<label for="upload-media">
							<input type="hidden" name="favicon" id="favicon" value="<?php echo esc_url_raw( $favicon ); ?>"/>
							<button class="button btn-upload"><?php _e( 'Choose Icon', 'wen-maintenance-mode' ); ?></button><br/>
						</label>
						<div class="img-preview-wrap">
							<?php if( $favicon ) { ?>
								<img class="img-preview-favicon" src="<?php echo esc_url_raw( $favicon ); ?>">
							<?php } else { ?>
								<img class="img-preview-favicon" src="<?php echo WEN_PLUGIN_DIR_URL . 'assets/images/favicon.ico'; ?>">
							<?php } ?>
						</div>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Google Analytics Tracking', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<?php $gtracking = get_option( 'wmm_enable_gtracking' ); ?>
					<fieldset class="google-analytics-option">
						<label for="enable_gtracking">
							<input name="enable_gtracking" type="checkbox" id="enable_gtracking" class="option-show-hide" <?php if ( $gtracking == '2' ) echo 'checked="checked"'; ?> value="2">
							<?php _e( 'Check this option if you want to enable Google Tracking', 'wen-maintenance-mode' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top" class="google-analytics-option <?php echo $gtracking == 2 ? 'tr-visible' : 'tr-hide'; ?>">
				<th scope="row"><?php _e('Google Tracking ID', 'wen-maintenance-mode') ?></th>
				<td> 
					<fieldset>
						<input class="ga_tracking_id" name="ga_tracking_id" type="text" value="<?php echo get_option( 'wmm_ga_tracking_id' ); ?>" placeholder="UA-xxxxxx-xx"/>
					</fieldset>
				</td>
			</tr>
			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="update_settings_misc" id="update_settings_misc" class="button button-primary" value="<?php _e( 'Save Changes', 'wen-maintenance-mode' ); ?>">
	</p>
</form>