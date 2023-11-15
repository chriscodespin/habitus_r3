<!DOCTYPE html>
<html lang="en">
<?php
    login_permission_check ();
?>
<head>
    <?php wp_head(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name') ?></title>
</head>
<body>
    <?php if (!is_user_logged_in()) : ?>
        <nav class="navbar bg-dark navbar-dark navbar-expand">
            <div class="container">
                <div class="navbar-brand">
                    <a class="" href="<?php echo site_url('/'); ?>">
                    <img src="<?php echo get_theme_file_uri( 'images/THF_43Logo.gif' ); ?>" style="width: 40px;" alt="Wisdom Pet Logo">
                    </a>
                </div>
                <div class="navbar-nav">
                    <a href="<?php echo site_url('/'); ?>" class="nav-item nav-link">Login</a>
                    <a href="<?php echo wp_registration_url(); ?>" class="nav-item nav-link">Sign Up</a>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <?php if (is_user_logged_in()) : ?>
        <nav class="navbar bg-dark navbar-dark navbar-expand-md">
            <div class="container">
                <div class="navbar-brand">
                    <a class="" href="<?php echo site_url('/'); ?>">
                    <img src="<?php echo get_theme_file_uri( 'images/THF_43Logo.gif' ); ?>" style="width: 40px;" alt="Habitus Logo">
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-toggler-nav" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main-toggler-nav">
                    <div class="navbar-nav ms-auto">
                    <?php
                        if (!pmpro_hasMembershipLevel('Pro')) { ?>
                                <a href="<?php echo site_url('/membership-account/membership-checkout/'); ?>" class="btn btn btn-sm btn-primary text-center align-self-end">Go Pro</a>.
                            <?php
                            } ?>
                        <a class="nav-item nav-link" href="<?php echo site_url('/habitus-dashboard') ?>">Dashboard</a>
                        <a class="nav-item nav-link " href="<?php echo site_url('/new-challenge') ?>">New Habit Challenge</a>
                        <a class="nav-item nav-link " href="<?php echo site_url('/new-issue') ?>">Report an Issue</a>
                       <!-- <a class="nav-item nav-link" href="#">Active Habit Challenges</a> -->
                        <a href="<?php echo wp_logout_url();  ?>" class="nav-item nav-link"">Log Out</a>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>