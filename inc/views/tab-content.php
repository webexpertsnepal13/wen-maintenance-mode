<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php wp_nonce_field( 'wmm-settings-submenu-page-save' ); ?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Heading', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e( 'Heading', 'wen-maintenance-mode' ); ?></span></legend>
						<label for="heading">
							<?php $heading = get_option( 'wmm_content_heading' ); ?>
							<!-- <input type="text" name="content_heading" id="content_heading" class="regular-text" value="<?php echo $heading !='' ? $heading : __( 'Temporarily Down For Maintenance', 'wen-maintenance-mode' ); ?>"/> -->
							<input type="text" name="content_heading" id="content_heading" class="regular-text" value="<?php echo $heading; ?>"/>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Content', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e( 'Content', 'wen-maintenance-mode' ); ?></span></legend>
						<label for="content">
							<?php  
								$content = get_option( 'wmm_content' );
								// $content = $content !='' ? $content : __( 'We are performing scheduled maintenance. Will be back online shortly.', 'wen-maintenance-mode' );
								$settings = array( 'media_buttons' => false, 'textarea_rows' => 15, 'editor_height' => 250 );
								 	wp_editor( $content, 'wmm_content', $settings ); ?>
							<?php // _e( 'Add content here', 'wen-maintenance-mode' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Content Border', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset class="content-border-option">
						<?php $content_border = get_option( 'wmm_content_border' ); ?>
						<input type="radio" class="option-show-hide" <?php echo ( $content_border == 1 || $content_border == '' ) ? 'checked="checked"' : ''; ?> name="content_border" value="1" /> <i><?php _e( 'No', 'wen-maintenance-mode' ); ?></i>
						<input type="radio" class="option-show-hide" <?php echo $content_border == 2 ? 'checked="checked"' : ''; ?> name="content_border" value="2" /> <i><?php _e( 'Yes', 'wen-maintenance-mode' ); ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top" class="content-border-option <?php echo $content_border == 2 ? 'tr-visible' : 'tr-hide'; ?>">
				<th scope="row"><?php _e( 'Choose Border Color', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php $border_color = get_option( 'wmm_border_color' ); ?>
						<input class="border_color" name="border_color" type="text" value="<?php echo $border_color == '' ? '#fff' : $border_color; ?>" data-default-color="#fff" />
					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'Content Text Color', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php $content_color = get_option( 'wmm_content_color' ); ?>
						<input class="content_color" name="content_color" type="text" value="<?php echo $content_color == '' ? '#000' : $content_color; ?>" data-default-color="#000" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="update_settings_content" id="update_settings_content" class="button button-primary" value="<?php _e( 'Save Changes', 'wen-maintenance-mode' ); ?>">
	</p>
</form>