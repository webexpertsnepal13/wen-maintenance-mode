<!doctype html>
<html lang="en">
    <head>
        <?php 
        $page_title       = get_option( 'wmm_page_title' );
        $favicon          = get_option( 'wmm_favicon' );
        $enable_gtracking = get_option( 'wmm_enable_gtracking' );
        $gtracking_id     = get_option( 'wmm_ga_tracking_id' );
        $gready           = ( $enable_gtracking == 2 && $gtracking_id != '' && preg_match( '/^ua-\d{4,10}(-\d{1,4})?$/im', $gtracking_id ) != 0 ) ? 'ready' : '' ;
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <?php echo $gready == '' ? '<meta name="robots" content="noindex,follow" />' : ''; ?>
       
        <title><?php echo $page_title != '' ? $page_title : __( 'Maintenance Mode Enabled', 'wen-maintenance-mode' ); ?></title>
        <link rel="shortcut icon" href="<?php echo $favicon != '' ? $favicon : WEN_PLUGIN_DIR_URL . 'assets/images/favicon.ico'; ?>">
        <link rel="stylesheet" href="<?php echo WEN_PLUGIN_DIR_URL . 'assets/css/style.css'; ?>">
        <?php if( $gready == 'ready' ) : ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gtracking_id; ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '<?php echo $gtracking_id ?>');
            </script>
        <?php endif; ?>
    </head>
    <body>
        <?php
        $display_logo          = get_option( 'wmm_display_logo' );
        $templage_choosen      = get_option( 'wmm_template' );  // 1: Default, 2: Customized
        $wrap_class            = $templage_choosen == 1 ? 'plain' : '';

        $background_option     = get_option( 'wmm_background_option' ); // 1: Image, 2: Color
        $background_image      = $background_color = $background_attr = '';
        if ( $templage_choosen != 1 ) {
            if( $background_option == 1 ) {
                $background_image      = get_option( 'wmm_background_image' );
                $background_image      = $background_image != '' ? 'style="background: url(' . $background_image . ') no-repeat center; background-size: cover;"' : '';
                $default_bg_image      = 'style="background: url(' . WEN_PLUGIN_DIR_URL . 'assets/images/default-bg.jpg) no-repeat center; background-size: cover;"';
                $background_attr      = $background_image != '' ? $background_image : $default_bg_image;
            } else {
                $background_color      = get_option( 'wmm_background_color' );
                $background_color      = $background_color != ''  ? $background_color : '#ffffff';
                $background_attr      = 'style="background-color: ' . $background_color . ';"';
            }
        }

        $content_heading      = get_option( 'wmm_content_heading' );
        if( !empty( $content_heading) )
            $content_heading  = '<h2 class="wmm-title"><strong>' . $content_heading . '</strong></h2>';
        $content              = get_option( 'wmm_content' );
        if ( !empty( $content ) )
            $content          = '<div class="wmm-content">' . wpautop( $content ) . '</div>';

        $content_color        = get_option( 'wmm_content_color' );
        $content_color        = ' color: ' . $content_color . ';';


        $content_border       = get_option( 'wmm_content_border' );
        $content_border_color = get_option( 'wmm_border_color' );
        $content_border_color = $content_border == 2 ? 'border-width: 2px; border-style: solid; border-color: ' . $content_border_color . ';' : '';

        $phone                = get_option( 'wmm_phone_number' );
        $email                = get_option( 'wmm_email_link' );
        $facebook_link        = get_option( 'wmm_facebook_link' );
        $twitter_link         = get_option( 'wmm_twitter_link' );
        $linkedin_link        = get_option( 'wmm_linkedin_link' );
        $youtube_link         = get_option( 'wmm_youtube_link' );
        $instagram_link       = get_option( 'wmm_instagram_link' );


        $icon_color           = get_option( 'wmm_icon_color' );
        $icon_color           = 'color: ' . $icon_color . ';';
        ?>
        <div class="maintenance-mode-wrapper <?php echo $wrap_class; ?>" <?php echo $background_attr; ?>>
            <div class="container">
                <?php
                if ( $display_logo ) {
                    $logo = get_option( 'wmm_logo' );
                    ?>
                	<img src="<?php echo $logo != '' ? $logo :  WEN_PLUGIN_DIR_URL . 'assets/images/default-logo.png'; ?>" class="logo" alt="site-logo">
                    <?php
                }
                ?>
                <div class="inner" style="<?php echo $content_border_color . $content_color; ?>">
                    <?php echo $content_heading . $content; ?>
                    <?php if( '' != ( $phone || $email ) ) : ?>
                        <div class="cta-links">
                        <?php
                            if( $phone != '' ) echo '<a href="tel:' . $phone . '" style="' . $content_color . '"><i class="icon-phone"></i>' . $phone . '</a>';
                            if( $email != '' ) echo '<a href="mailto:' . $email . '" style="' . $content_color . '"><i class="icon-mail"></i>' . $email . '</a>';
                        ?>
                        </div>
                    <?php endif; ?>
                </div><!-- .inner -->
            </div><!-- .container -->
            <?php if ( !empty( $facebook_link || $linkedin_link || $twitter_link || $instagram_link || $youtube_link ) ) { ?>
                <ul class="social">
                    <?php
                    if( !empty( $facebook_link ) ) {
                        echo '<li><a href="' . $facebook_link . '" target="_blank" style="' . $icon_color . '" class="icon-facebook2"></a></li>';
                    }
                    if( !empty( $linkedin_link ) ) {
                        echo '<li><a href="' . $linkedin_link . '" target="_blank" style="' . $icon_color . '" class="icon-linkedin"></a></li>';
                    }
                    if( !empty( $twitter_link ) ) {
                        echo '<li><a href="' . $twitter_link . '" target="_blank" style="' . $icon_color . '" class="icon-twitter"></a></li>';
                    }
                    if( !empty( $instagram_link ) ) {
                        echo '<li><a href="' . $instagram_link . '" target="_blank" style="' . $icon_color . '" class="icon-instagram"></a></li>';
                    }
                    if( !empty( $youtube_link ) ) {
                        echo '<li><a href="' . $youtube_link . '" target="_blank" style="' . $icon_color . '" class="icon-youtube"></a></li>';
                    }
                    ?>
                </ul>
            <?php } ?>
        </div><!-- .maintenance-mode-wrapper -->
    </body>
</html>