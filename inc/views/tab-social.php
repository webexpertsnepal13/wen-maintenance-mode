<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php wp_nonce_field( 'wmm-settings-submenu-page-save' ); ?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<label for="facebook_link"><?php _e( 'Facebook', 'wen-maintenance-mode' ); ?></label>
				</th>
				<td>
					<input name="facebook_link" type="text" id="facebook_link" value="<?php echo esc_url_raw( get_option( 'wmm_facebook_link' ) ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_link"><?php _e( 'Twitter', 'wen-maintenance-mode' ); ?></label>
				</th>
				<td>
					<input name="twitter_link" type="text" id="twitter_link" value="<?php echo esc_url_raw( get_option( 'wmm_twitter_link' ) ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="linkedin_link"><?php _e( 'LinkedIn', 'wen-maintenance-mode' ); ?></label>
				</th>
				<td>
					<input name="linkedin_link" type="text" id="linkedin_link" value="<?php echo esc_url_raw( get_option( 'wmm_linkedin_link' ) ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="instagram_link"><?php _e( 'Instagram', 'wen-maintenance-mode' ); ?></label>
				</th>
				<td>
					<input name="instagram_link" type="text" id="instagram_link" value="<?php echo esc_url_raw( get_option( 'wmm_instagram_link' ) ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="youtube_link"><?php _e( 'YouTube', 'wen-maintenance-mode' ); ?></label>
				</th>
				<td>
					<input name="youtube_link" type="text" id="youtube_link" value="<?php echo esc_url_raw( get_option( 'wmm_youtube_link' ) ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
					<th scope="row">
						<label for="email_link"><?php _e( 'Email', 'wen-maintenance-mode' );?></label>
					</th>
					<td>
						<input name="email_link" type="text" id="email_link" value="<?php echo get_option( 'wmm_email_link' ); ?>" class="regular-text">
					</td>
			</tr>
			<tr valign="top">
					<th scope="row">
						<label for="phone_number"><?php _e( 'Phone Number', 'wen-maintenance-mode' ); ?></label>
					</th>
					<td>
						<input name="phone_number" type="text" id="phone_number" value="<?php echo get_option( 'wmm_phone_number' ); ?>" class="regular-text">
					</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Social Icon Color', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php $icon_color = get_option( 'wmm_icon_color' ); ?>
						<input class="icon_color" name="icon_color" type="text" value="<?php echo $icon_color == '' ? '#000' : $icon_color; ?>" data-default-color="#000" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="update_settings_social" id="update_settings_social" class="button button-primary" value="<?php _e( 'Save Changes', 'wen-maintenance-mode' ); ?>">
	</p>
</form>