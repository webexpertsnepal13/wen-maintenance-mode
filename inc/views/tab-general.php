<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php wp_nonce_field( 'wmm-settings-submenu-page-save' ); ?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Enable Maintenance Mode', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e( 'Enable Test Mode', 'wen-maintenance-mode' ); ?></span></legend>
						<label for="enable_maintenance_mode">
							<input name="enable_maintenance_mode" type="checkbox" id="enable_maintenance_mode" <?php if ( get_option( 'wmm_enabled' ) == '1' ) echo 'checked="checked"'; ?> value="1">
							<?php _e( 'Yes', 'wen-maintenance-mode' ); ?>
						</label>
						</fieldset>
				</td>
			</tr>
			
			<!-- Remove maintenance on selected timer -->
			<tr valign="top">
				<th scope="row"><?php _e( 'Disable Maintenance on', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php 
							$disable_maintenance_on = get_option( 'wmm_disable_on' );
							$date_time_val = '';
							if ( ! empty( $disable_maintenance_on ) ) {
								$date = DateTime::createFromFormat( 'Y-m-d\TH:i', $disable_maintenance_on );
								if ( $date ) {
									$date_time_val = $date->format( 'Y-m-d\TH:i' );
								}
							}
						?>
						<legend class="screen-reader-text">
							<span><?php _e( 'Disable Maintenance Mode ON', 'wen-maintenance-mode' ); ?></span>
						</legend>
						<label for="disable_maintenance_mode">
							<input name="disable_maintenance_mode" type="datetime-local" id="disable_maintenance_mode" value="<?php echo esc_attr( $date_time_val ); ?>">
							<p> <?php echo __( 'Your local time is ', 'wen-maintenance-mode' ); ?><code><?php echo  current_time('Y-m-d H:i:s');?></code></p>
						</label>
					</fieldset>

				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'Choose Template', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset class="template-option">
						<?php $template_selected = get_option( 'wmm_template' ); ?>
						<label class="radio"><input type="radio" class="option-show-hide" <?php echo ( $template_selected == 1 || $template_selected == '' ) ? 'checked="checked"' : ''; ?> name="theme-template" value="1" /> <i><?php _e( 'Default', 'wen-maintenance-mode' ); ?><img class="layout-preview template-active" src="<?php echo WEN_PLUGIN_DIR_URL . 'assets/images/t1.jpg'; ?>"></i></label>
						<label class="radio"><input type="radio" class="option-show-hide" <?php echo $template_selected == 2 ? 'checked="checked"' : ''; ?> name="theme-template" value="2" /> <i><?php _e( 'Customized', 'wen-maintenance-mode' ); ?><img class="layout-preview" src="<?php echo WEN_PLUGIN_DIR_URL . 'assets/images/t2.jpg'; ?>"></i></label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top" class="upload-logo logo-option">
				<th scope="row"><?php _e( 'Logo', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php $logo = get_option( 'wmm_logo' ); ?>
						<legend class="screen-reader-text"><span><?php _e( 'Logo', 'wen-maintenance-mode' ); ?></span></legend>
						<label for="upload-media">
							<input type="hidden" name="logo" id="logo" value="<?php echo esc_url_raw( $logo ); ?>"/>
							<button class="button btn-upload"><?php _e( 'Choose Image', 'wen-maintenance-mode' ); ?></button>
							<button type="button" href="javascript:void(0);" class="button clear-input" style="<?php echo $logo ? '' : 'display:none'; ?>"><?php echo __('Clear', 'wen-maintenance-mode'); ?></button>

						</label>
						<div class="img-preview-wrap">
							<?php if( $logo ) { ?>
								<img class="img-preview-logo" src="<?php echo esc_url_raw( $logo ); ?>">
							<?php } else { ?>
								<img class="img-preview-logo" src="">
							<?php } ?>
						</div>
					</fieldset>
				</td>
			</tr>

			<tr valign="top" class="bg-option template-option <?php echo $template_selected == 2 ? 'tr-visible' : 'tr-hide'; ?>">
				<th scope="row"><?php _e( 'Background Options', 'wen-maintenance-mode' ); ?></th>
				<td> 
					<fieldset>
						<?php $background_option = get_option( 'wmm_background_option' ); ?>
						<select name="background_option">
							<option value="1" <?php echo $background_option == 1 ? 'selected="selected"' : ''; ?>><?php _e( 'Image', 'wen-maintenance-mode' ); ?></option>
							<option value="3" <?php echo $background_option == 3 ? 'selected="selected"' : ''; ?>><?php _e( 'Video', 'wen-maintenance-mode' ); ?></option>
							<option value="2" <?php echo $background_option == 2 ? 'selected="selected"' : ''; ?>><?php _e( 'Solid Color', 'wen-maintenance-mode' ); ?></option>
						</select>
					</fieldset>
				</td>
			</tr>
			
			<tr valign="top" class="bg-option template-option <?php echo $template_selected == 2 ? 'tr-visible' : 'tr-hide'; ?>">
				<th scope="row"></th>

				<td class="bg-image <?php echo $background_option != 1 ? 'background-option' : ''; ?>"> 
					<fieldset>
						<?php $background_image = get_option('wmm_background_image'); ?>
						<label for="upload-media">
							<input type="hidden" name="background_image" id="background_image" value="<?php echo esc_url_raw( $background_image ); ?>" />
							<button class="button btn-upload"><?php _e( 'Choose Image', 'wen-maintenance-mode' ); ?></button>
							<button type="button" href="javascript:void(0);" class="button clear-input" style="<?php echo $background_image ? '' : 'display: none'; ?>"><?php echo __( 'Clear', 'wen-maintenance-mode' );?></button><br/>
						</label>
						<div class="img-preview-wrap">
							<?php if( $background_image ) { ?>
								<img class="img-preview-background_image" src="<?php echo esc_url_raw( $background_image ); ?>">
							<?php } else { ?>
								<img class="img-preview-background_image" src="">
							<?php } ?>
						</div>
					</fieldset>
				</td>
				<td class="bg-video <?php echo $background_option != 3 ? 'background-option' : ''; ?>"> 
					<fieldset>
						<?php $background_video = get_option('wmm_background_video'); ?>
						<label for="upload-media">
							<input type="hidden" name="background_video" id="background_video" value="<?php echo esc_url_raw( $background_video ); ?>" />
							<button class="button btn-upload" data-type="video"><?php _e( 'Choose Video', 'wen-maintenance-mode' ); ?></button>
							<button type="button" href="javascript:void(0);" class="button clear-input" style="<?php echo $background_video ? '' : 'display:none' ?>"><?php echo __( 'Clear', 'wen-maintenance-mode' );?></button><br/>
						</label>
						<div class="video-preview-wrap">
							<?php
							if ( $background_image ) { ?>
								<p class="video-preview"><?php echo esc_url_raw( $background_video );?></p>
								<?php } ?>
						</div>
					</fieldset>
				</td>
				<td class="bg-color <?php echo ( $background_option != 2 || $background_option == '' ) ? 'background-option' : ''; ?>">
					<fieldset>
						<?php $bg_color = get_option( 'wmm_background_color' ); ?>
						<input class="background-color" name="background_color" type="text" value="<?php echo $bg_color == '' ? '#fff' : $bg_color; ?>" data-default-color="#fff" />
					</fieldset>
				</td>
			</tr>
			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="update_settings_general" id="update_settings_general" class="button button-primary" value="<?php _e( 'Save Changes', 'wen-maintenance-mode' ); ?>">
	</p>
</form>