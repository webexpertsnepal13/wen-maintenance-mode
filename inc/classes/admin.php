<?php 
class WMM_Admin {

	function init() {
		add_action( 'admin_menu', array( $this, 'wmm_admin_menu' ) );
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wen-maintenance-mode' ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'wmm_admin_scripts' ) );
		}

		add_filter( 'plugin_action_links_wen-maintenance-mode/wen-maintenance-mode.php', array( $this, 'wmm_action_setting_links' ) );
	}

	/*
	 * Setting link
	*/
	public function wmm_action_setting_links( $links ) {
		(array) $links[] = '<a href="' . esc_url( admin_url( 'options-general.php?page=wen-maintenance-mode' ) ) . '">' . __( 'Settings', 'wen-maintenance-mode' ) . '</a>';
		return $links;
	}

	/*
	* create the admin menu
	*/
	public function wmm_admin_menu() {
		add_options_page( __( 'Maintenance Mode', 'wen-maintenance-mode' ), __( 'WEN Maintenance Mode', 'wen-maintenance-mode' ), 'manage_options', 'wen-maintenance-mode', array( $this, 'wmm_settings_page' ) );
	} 

	/*
	 * Enqueue Scripts 
	*/
	public function wmm_admin_scripts() {
		wp_enqueue_style( 'wmm-style', WEN_PLUGIN_DIR_URL . 'assets/css/admin-style.css' );
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'wp-color-picker' );
		wp_register_script( 'wmm-script', WEN_PLUGIN_DIR_URL . 'assets/js/admin.js', array( 'jquery', 'media-upload', 'thickbox' ) );
		wp_enqueue_script( 'wmm-script' );
	}

	/*
	 * Setting Tabs 
	*/
	public function wmm_settings_page() {
		if ( isset( $_POST['update_settings_general'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
			if ( !wp_verify_nonce( $nonce, 'wmm-settings-submenu-page-save' ) ) {
				wp_die( 'Error! Nonce Security Check Failed! please save the settings again.' );
			}
			update_option( 'wmm_enabled', ( isset( $_POST["enable_maintenance_mode"] ) && $_POST["enable_maintenance_mode"] == '1' ) ? '1' : '0' );
			update_option( 'wmm_template', trim( $_POST["theme-template"] ) );
			update_option( 'wmm_display_logo', ( isset( $_POST["display_logo"] ) && $_POST["display_logo"] == '1' ) ? '1' : '0' );
			update_option( 'wmm_logo', esc_url( $_POST["logo"] ) );
			update_option( 'wmm_background_option', trim( $_POST["background_option"] ) );
			update_option( 'wmm_background_image', esc_url( $_POST["background_image"] ) );
			update_option( 'wmm_background_color', sanitize_hex_color( $_POST["background_color"] ) );

			echo $this->wmm_notification();
		}

		if( isset( $_POST['update_settings_content'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
			if ( !wp_verify_nonce( $nonce, 'wmm-settings-submenu-page-save' ) ) {
				wp_die( __( 'Error! Nonce Security Check Failed! please save the settings again.', 'wen-maintenance-mode' ) );
			}
			update_option( 'wmm_content_heading', wp_kses_post( $_POST["content_heading"] ) );
			update_option( 'wmm_content', wp_kses_post( $_POST["wmm_content"] ) );
			update_option( 'wmm_content_border', trim( $_POST["content_border"] ) );
			update_option( 'wmm_border_color', sanitize_hex_color( $_POST["border_color"] ) );
			update_option( 'wmm_content_color', sanitize_hex_color( $_POST["content_color"] ) );

			echo $this->wmm_notification();
		}

		if( isset( $_POST['update_settings_social'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
			if ( !wp_verify_nonce( $nonce, 'wmm-settings-submenu-page-save' ) ) {
				wp_die( __( 'Error! Nonce Security Check Failed! please save the settings again.', 'wen-maintenance-mode' ) );
			}
			update_option( 'wmm_facebook_link', esc_url_raw( $_POST["facebook_link"] ) );
			update_option( 'wmm_twitter_link', esc_url_raw( $_POST["twitter_link"] ) );
			update_option( 'wmm_linkedin_link', esc_url_raw( $_POST["linkedin_link"] ) );
			update_option( 'wmm_instagram_link', esc_url_raw( $_POST["instagram_link"] ) );
			update_option( 'wmm_youtube_link', esc_url_raw( $_POST["youtube_link"] ) );
			update_option( 'wmm_email_link', trim( $_POST["email_link"] ) );
			update_option( 'wmm_phone_number', trim( $_POST["phone_number"] ) );
			update_option( 'wmm_icon_color', sanitize_hex_color( $_POST["icon_color"] ) );

			echo $this->wmm_notification();
		}

		if( isset( $_POST['update_settings_misc'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
			if ( !wp_verify_nonce( $nonce, 'wmm-settings-submenu-page-save' ) ) {
				wp_die( __( 'Error! Nonce Security Check Failed! please save the settings again.', 'wen-maintenance-mode' ) );
			}
			update_option( 'wmm_page_title', sanitize_text_field( $_POST["page_title"] ) );
			update_option( 'wmm_favicon', esc_url_raw( $_POST["favicon"] ) );
			update_option( 'wmm_enable_gtracking', ( isset( $_POST["enable_gtracking"] ) && $_POST["enable_gtracking"] == '2' ) ? '2' : '' );
			update_option( 'wmm_ga_tracking_id', trim( $_POST["ga_tracking_id"] ) );

			echo $this->wmm_notification();
		}
		?>
		<div class="wrap wmm-maintenance">
			<h1><?php _e( 'Settings', 'wen-maintenance-mode' ); ?></h1>

			<h2 class="nav-tab-wrapper">
				<?php do_action('add_settings_tab'); ?>
				<a href="<?php echo add_query_arg( array( 'page' => 'wen-maintenance-mode','tab' => 'general' ), admin_url( 'options-general.php' )	); ?>" 	class="nav-tab <?php if ( !isset( $_GET['tab'] ) || 'general' === $_GET['tab'] ): ?> nav-tab-active<?php endif; ?>">
					<?php _e( 'General', 'wen-maintenance-mode' ); ?>
				</a>
				<a href="<?php echo add_query_arg( array( 'page' => 'wen-maintenance-mode', 'tab' => 'content' ), admin_url( 'options-general.php' ) ); ?>" class="nav-tab <?php if ( isset( $_GET['tab'] ) && 'content' === $_GET['tab'] ): ?> nav-tab-active<?php endif; ?>">
					<?php _e( 'Content', 'wen-maintenance-mode' ); ?>
				</a>
				<a href="<?php echo add_query_arg( array( 'page' => 'wen-maintenance-mode', 'tab' => 'social' ), admin_url( 'options-general.php' ) ); ?>" class="nav-tab <?php if ( isset( $_GET['tab'] ) && 'social' === $_GET['tab'] ): ?> nav-tab-active<?php endif; ?>">
					<?php _e( 'Social', 'wen-maintenance-mode' ); ?>
				</a>
				<a href="<?php echo add_query_arg( array( 'page' => 'wen-maintenance-mode', 'tab' => 'misc' ), admin_url( 'options-general.php' ) ); ?>" class="nav-tab <?php if ( isset( $_GET['tab'] ) && 'misc' === $_GET['tab'] ): ?> nav-tab-active<?php endif; ?>">
					<?php _e( 'Misc', 'wen-maintenance-mode' ); ?>
				</a>
			</h2>

			<?php
			if( !isset( $_GET['tab'] ) || $_GET['tab'] == 'general' ){
				require_once( WEN_PLUGIN_PLUGIN_PATH . '/inc/views/tab-general.php' );
			} elseif( isset( $_GET['tab'] ) && $_GET['tab'] == 'content' ) {
				require_once( WEN_PLUGIN_PLUGIN_PATH . '/inc/views/tab-content.php' );
			} elseif( isset( $_GET['tab'] ) && $_GET['tab'] == 'social' ) {
				require_once( WEN_PLUGIN_PLUGIN_PATH . '/inc/views/tab-social.php' );
			} elseif( isset( $_GET['tab'] ) && $_GET['tab'] == 'misc' ) {
				require_once( WEN_PLUGIN_PLUGIN_PATH . '/inc/views/tab-misc.php' );
			}
			?>  
		</div>
		<?php 
	}

	/*
	 * Setting Tabs 
	*/
	public function wmm_notification(){
		return	'<div id="message" class="updated fade"><p><strong>' . __( 'Settings saved!', 'wen-maintenance-mode' ) . '</strong></p></div>';
	}
}
