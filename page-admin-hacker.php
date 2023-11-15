<?php

require_once( ABSPATH . 'wp-admin/includes/post.php' );

if ($_GET['action'] == 'new_tracking_entry_email') {
    $habitus_admin_class = habitus_admin_class();

    $habitus_admin_class->new_tracking_entry_email();

}
