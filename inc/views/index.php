<!doctype html>
<html lang="en">
    <head>
        <?php 
        $page_title       = get_option( 'wmm_page_title' );
        $favicon          = get_option( 'wmm_favicon' );
        if( !$favicon ) {
            $favicon = get_site_icon_url();
        }

        $enable_gtracking = get_option( 'wmm_enable_gtracking' );
        $gtracking_id     = get_option( 'wmm_ga_tracking_id' );
        $gready = ( $enable_gtracking == 2 && $gtracking_id != '' && preg_match('/^(UA-\d{4,10}(-\d{1,4})?|G-[A-Z0-9]{10,})$/i', $gtracking_id) !== 0 ) ? 'ready' : '';
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <?php echo $gready == '' ? '<meta name="robots" content="noindex,follow" />' : ''; ?>
       
        <title><?php echo $page_title != '' ? $page_title : __( 'Maintenance Mode Enabled', 'wen-maintenance-mode' ); ?></title>
        <?php if ( $favicon ) { ?>
            <link rel="shortcut icon" href="<?php echo $favicon ?>">
        <?php } ?>
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
            <?php 
                if( $background_option == '3' ) { 
                    $bg_video_url = get_option( 'wmm_background_video' );
                    if( $bg_video_url ){
                        $video_type = pathinfo($bg_video_url, PATHINFO_EXTENSION);
                        ?>
                        <video muted autoplay loop>
                          <source src="<?php echo esc_url( $bg_video_url); ?>" type="video/<?php echo $video_type ?? 'mp3'; ?>">
                          <?php echo __( 'Your browser does not support the video tag.', 'wen-maintenance-mode' ); ?>
                        </video>
                        <?php
                    }
                }
            ?>
            <div class="container">
                <?php
                $logo = get_option( 'wmm_logo' );
                if ( $logo ) {
                    ?>
                    <img src="<?php echo $logo; ?>" class="logo" alt="site-logo">
                    <?php
                }
                ?>
                <div class="inner" style="<?php echo $content_border_color . $content_color; ?>">
                    <?php echo $content_heading . $content; ?>

                    <!-- Maintenance mode countdown timer -->
                    <?php 
                        $disable_maintenance_on = get_option('wmm_disable_on');
                        $show_timer = false;
                        if ( $disable_maintenance_on ) {
                            $current_time = current_datetime()->format('Y-m-d H:i:s');

                            $disable_maintenance_time =  strtotime($disable_maintenance_on);
                            $current_time_str =  strtotime($current_time);

                            $time_diff = $disable_maintenance_time - $current_time_str; // difference between current and maintenance end time
                            if ( $time_diff > 0 ) {
                                $show_timer = true;
                                $maintenance_time_remaining = floor($time_diff / (60 * 60 *24));
                                $time_diff -= $maintenance_time_remaining * (60 * 60 * 24);
                                $hours = floor($time_diff / (60 * 60));
                                $time_diff -= $hours * (60 * 60);

                                $minutes = floor($time_diff / 60);
                                $seconds = $time_diff % 60;

                                //pass value to js...
                                $maintenance_time_js = json_encode([
                                    'days' => $maintenance_time_remaining,
                                    'hours' => $hours,
                                    'minutes' => $minutes,
                                    'seconds' => $seconds
                                ]);
                                ?>
                                <div id="countdown" data-maintenanceoff="<?php echo esc_attr($maintenance_time_js); ?>">
                                    <h4 style="<?php echo $content_color;?>"><?php echo __( 'Website will be accessible after', 'wen-maintenance-mode' ); ?></h4>
                                    <div class="countdown-container">
                                        <div class="circle-timer" id="days">
                                            <svg width="120" height="120">
                                                <circle class="circle-bg" cx="60" cy="60" r="54" fill="none"/>
                                                <circle class="circle-progress" cx="60" cy="60" r="54" fill="none" stroke-dasharray="339.29" stroke-dashoffset="0"/>
                                            </svg>
                                            <div class="timer-value" id="days-value"></div>
                                            <div class="timer-label">Days</div>
                                        </div>

                                        <div class="circle-timer" id="hours">
                                            <svg width="120" height="120">
                                                <circle class="circle-bg" cx="60" cy="60" r="54" fill="none"/>
                                                <circle class="circle-progress" cx="60" cy="60" r="54" fill="none" stroke-dasharray="339.29" stroke-dashoffset="0"/>
                                            </svg>
                                            <div class="timer-value" id="hours-value">00</div>
                                            <div class="timer-label">Hours</div>
                                        </div>

                                        <div class="circle-timer" id="minutes">
                                            <svg width="120" height="120">
                                                <circle class="circle-bg" cx="60" cy="60" r="54" fill="none"/>
                                                <circle class="circle-progress" cx="60" cy="60" r="54" fill="none" stroke-dasharray="339.29" stroke-dashoffset="0"/>
                                            </svg>
                                            <div class="timer-value" id="minutes-value">00</div>
                                            <div class="timer-label">Minutes</div>
                                        </div>

                                        <div class="circle-timer" id="seconds">
                                            <svg width="120" height="120">
                                                <circle class="circle-bg" cx="60" cy="60" r="54" fill="none"/>
                                                <circle class="circle-progress" cx="60" cy="60" r="54" fill="none" stroke-dasharray="339.29" stroke-dashoffset="0"/>
                                            </svg>
                                            <div class="timer-value" id="seconds-value">00</div>
                                            <div class="timer-label">Seconds</div>
                                        </div>
                                    </div>

                                </div>
                                <?php
                            }
                        }
                    ?>

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
                        echo '<li><a href="' . esc_url( $facebook_link ) . '" target="_blank" style="' . $icon_color . '" class="icon-facebook2"></a></li>';
                    }
                    if( !empty( $linkedin_link ) ) {
                        echo '<li><a href="' . esc_url( $linkedin_link ) . '" target="_blank" style="' . $icon_color . '" class="icon-linkedin"></a></li>';
                    }
                    if( !empty( $twitter_link ) ) {
                        echo '<li><a href="' . esc_url( $twitter_link ) . '" target="_blank" style="' . $icon_color . '" class="icon-twitter"></a></li>';
                    }
                    if( !empty( $instagram_link ) ) {
                        echo '<li><a href="' . esc_url( $instagram_link ) . '" target="_blank" style="' . $icon_color . '" class="icon-instagram"></a></li>';
                    }
                    if( !empty( $youtube_link ) ) {
                        echo '<li><a href="' . esc_url( $youtube_link ) . '" target="_blank" style="' . $icon_color . '" class="icon-youtube"></a></li>';
                    }
                    ?>
                </ul>
            <?php } ?>
        </div><!-- .maintenance-mode-wrapper -->
        <?php if( $show_timer ) { ?>
            <script type="text/javascript" src="<?php echo WEN_PLUGIN_DIR_URL . 'assets/js/public.js'; ?>" defer></script>
        <?php }?>
    </body>
</html>