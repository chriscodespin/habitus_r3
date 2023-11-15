<?php
get_header();
?>

<div class="container mt-5 p-5 bg-light rounded-3 border border-warning border-3 ">
    <div class="row row-cols-2  mb-5 ">
        <div class="col ">
            <img class="img-fluid rounded-3" src="<?php echo get_theme_file_uri( 'images/home-img.png' ); ?>" alt="">      
        </div>
        <div class="col  ">
            <?php if (!is_user_logged_in()) : ?>
                <div class="row row-cols-1 " style="height: 100%;">
                    <a href="<?php echo wp_registration_url(); ?>" class="btn w-100  btn btn-lg btn-success text-center align-self-start">JOIN TODAY!</a>
                    <form class="align-self-end" name="loginform" id="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post" _lpchecked="1">
                        <div class="form-group">
                            <label class="form-label" for="user_login">Username or Email Address</label>
                            <input type="text" name="log" id="user_login" class="form-control" value="" size="20" autocapitalize="none"  autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="user_pass">Password</label>
                            <input type="password" name="pwd" id="user_pass" class="form-control" value="" size="20"  autocomplete="off">
                        </div>

                        <div class="form-group form-check mb-3">
                            <input class="form-check-input" name="rememberme" type="checkbox" id="rememberme" value="forever"> 
                            <label class="form-check-label" for="rememberme">Remember Me</label>
                        </div>
                        
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary w-25" value="Log In">
                        <input type="hidden" name="redirect_to" value="<?php echo site_url('/habitus-dashboard/'); ?>">
                        <input type="hidden" name="testcookie" value="1">
                    </form>    
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row row-cols-2">
        <div class="col ">
            <h3 class="text-dark">Grow, Challenge, Thrive!</h3>
            <p class="text-secondary">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut culpa deserunt doloremque maxime fuga vel voluptatem. Quaerat, eum doloremque illum inventore voluptas soluta quod labore? Vitae ea eius labore sunt!</p>
        </div>
        <div class="col">
        </div>
    </div>
</div>

<form name="bp-login-form" id="bp-login-widget-form" class="align-self-end" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
    <label class="form-label" for="bp-login-widget-user-login"><?php _e( 'Username', 'buddypress' ); ?></label>
    <input type="text" name="log" id="bp-login-widget-user-login" class="form-control" value="" />

    <label class="form-label" for="bp-login-widget-user-pass"><?php _e( 'Password', 'buddypress' ); ?></label>
    <input type="password" name="pwd" id="bp-login-widget-user-pass" class="form-control" value="" <?php bp_form_field_attributes( 'password' ) ?> />

    <div class="forgetmenot"><label class="form-check-label" for="bp-login-widget-rememberme"><input name="rememberme" type="checkbox" id="bp-login-widget-rememberme" value="forever" /> <?php _e( 'Remember Me', 'buddypress' ); ?></label></div>

    <input class="btn btn-primary w-25" type="submit" name="wp-submit" id="bp-login-widget-submit" value="<?php esc_attr_e( 'Log In', 'buddypress' ); ?>" />

    <?php

    /**
     * Fires inside the display of the login widget form.
     *
     * @since 2.4.0
     */
    do_action( 'bp_login_widget_form' ); ?>

</form>
        
<?php
get_footer();