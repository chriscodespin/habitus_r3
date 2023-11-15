<?php
get_header();

//SET LOGIN REDIRECT URL
$redirect_to = '';
if ( ! isset($_GET['redirect-to'])) {
    $redirect_to = site_url() . '/habitus-dashboard';
} else {
    $redirect_to = site_url() . $_GET['redirect-to'];
}

?>

<div class="container mt-md-5 p-md-5 bg-light rounded-3 border border-warning border-3 ">
    <div class="row row-cols-1 row-cols-md-2  mb-md-5 ">
        <div class="col ">
            <img class="img-fluid d-block rounded-3 mb-2 mb-md-0" src="<?php echo get_theme_file_uri( 'images/home-img.png' ); ?>" alt="">      
        </div>
        <div class="col ">
            <?php if (!is_user_logged_in()) : ?>
                <div class="row row-cols-1 " style="height: 100%;">
                    <a href="<?php echo wp_registration_url(); ?>" class="btn w-100 btn btn-lg btn-success text-center align-self-start">JOIN TODAY!</a>
                    <form name="bp-login-form" id="bp-login-widget-form" class="align-self-end" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                        <input type="hidden" name="local-timezone" id="form-timezone">    
                        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to ?>" />
                        <label class="form-label" for="bp-login-widget-user-login"><?php _e( 'Username', 'buddypress' ); ?></label>
                        <input type="text" name="log" id="bp-login-widget-user-login" class="form-control" value="" />

                        <label class="form-label" for="bp-login-widget-user-pass"><?php _e( 'Password', 'buddypress' ); ?></label>
                        <input type="password" name="pwd" id="bp-login-widget-user-pass" class="form-control mb-2" value="" <?php bp_form_field_attributes( 'password' ) ?> />

                        <div class="forgetmenot"><label class="form-check-label mb-2" for="bp-login-widget-rememberme"><input name="rememberme" type="checkbox" id="bp-login-widget-rememberme" value="forever" /> <?php _e( 'Remember Me', 'buddypress' ); ?></label></div>

                        <input class="btn btn-primary w-100 mb-3 mb-md-0 " type="submit" name="wp-submit" id="bp-login-widget-submit" value="<?php esc_attr_e( 'Log In', 'buddypress' ); ?>" />

                        <?php

                        /**
                         * Fires inside the display of the login widget form.
                         *
                         * @since 2.4.0
                         */
                        do_action( 'bp_login_widget_form' ); ?>

                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col ">
            <h3 class="text-dark">Challenge. Grow. Win. Together.</h3>
            <p class="text-secondary">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut culpa deserunt doloremque maxime fuga vel voluptatem. Quaerat, eum doloremque illum inventore voluptas soluta quod labore? Vitae ea eius labore sunt!</p>
        </div>
        <div class="col">
        </div>
    </div>
</div>
        
<?php
get_footer();