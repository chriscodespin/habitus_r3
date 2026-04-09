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
            <h3 class="text-dark">Fuel Your Ambition, Together</h3>
            <p class="text-secondary">Tired of solo streaks that fizzle out? Welcome to <b data-path-to-node="1" data-index-in-node="50">[App Name]</b>, the exclusive membership community where your personal goals meet collective momentum. Whether you’re crushing a fitness milestone, mastering a new language, or building a daily meditation habit, you don't have to do it alone.</p>
            <h3 data-path-to-node="2"><b data-path-to-node="2" data-index-in-node="0">How It Works</b></h3>
            <ul data-path-to-node="3"><li><p data-path-to-node="3,0,0"><b data-path-to-node="3,0,0" data-index-in-node="0">Build Your Tribe:</b> Create private or public groups tailored to your specific passions.</p></li><li><p data-path-to-node="3,1,0"><b data-path-to-node="3,1,0" data-index-in-node="0">Launch Challenges:</b> Set the stakes with custom daily, weekly, or monthly challenges that keep the competitive spirit alive.</p></li><li><p data-path-to-node="3,2,0"><b data-path-to-node="3,2,0" data-index-in-node="0">Live Progress Tracking:</b> Stay inspired with real-time leaderboards and activity feeds. Watch your friends hit their targets and get that extra push to hit yours.</p></li><li><p data-path-to-node="3,3,0"><b data-path-to-node="3,3,0" data-index-in-node="0">Accountability That Works:</b> No more shouting into the void. Our membership-based model ensures a dedicated, high-quality community where everyone is as committed as you are.</p></li></ul>
            <hr data-path-to-node="4">
            <blockquote data-path-to-node="5"><p data-path-to-node="5,0"><b data-path-to-node="5,0" data-index-in-node="0">Turn "I’ll start tomorrow" into "Look what we did today."</b> Join <b data-path-to-node="5,0" data-index-in-node="63">[App Name]</b> and transform your solo grind into a shared victory. Are you ready to level up?</p></blockquote>
        </div>
        <div class="col">
        </div>
    </div>
</div>
        
<?php
get_footer();
