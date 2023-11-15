<?php

$tracking = null;
$t_meta = null;
$post_id = 0;
$post = null;
$user_id = get_current_user_id();

if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $post = get_post( $post_id );

    $permissions = new Habitus_Theme_Permissions();
    $permissions->login_redirect();
    if (!$permissions->can_user_view_post($post)) {
        wp_redirect(home_url() . '/?error=unauthorized' );
        exit;
    }


if (isset($_POST['meta_id']) && !empty($_POST['meta_id'])) {
    
    $args = array(
        'meta_id' => $_POST['meta_id'],
        'share_updates_and_comments' => isset($_POST['share_updates_and_comments']) ? 1 : 0,
        'cc_email_tracking_comments' => isset($_POST['cc_email_tracking_comments']) ? 1 : 0,
        'id' => $_POST['meta_id']
    );

    $tracking = new Habitus_Theme_Tracking();
    $tracking->update_tracking_meta('update-settings', $args);

}

    $tracking = new Habitus_Theme_Tracking();
    $t_meta = $tracking->get_tracking_meta($post_id);
    $share_checked = '';
    $cc_checked = '';
    $opt_out_checked = '';

    if (isset($t_meta->share_updates_and_comments) && $t_meta->share_updates_and_comments  ) {
        $share_checked = 'checked';
    }

    if (isset($t_meta->cc_email_tracking_comments) && $t_meta->cc_email_tracking_comments) {
        $cc_checked = 'checked';
    }
}

get_header();

?>

<div class="container mt-md-5 p-md-5 mb-3 bg-light rounded-3 border border-warning border-3">
    <h1><?php echo $post->post_title ?></h1>
    <h3>Settings</h3>
    <form class="" id="tracker" method="POST" action="">
        <input type="hidden" name="meta_id" value="<?php echo $t_meta->id ?>">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="share_updates_and_comments" <?php echo $share_checked ?>>
            <label class="form-check-label" for="share_updates_and_comments">Do you want the group to see your challenge updates/comments?</label>
        </div>
        <div class="form-group form-check mb-3">
            <input type="checkbox" class="form-check-input" name="cc_email_tracking_comments" <?php echo $cc_checked ?>>
            <label class="form-check-label" for="cc_email_tracking_comments">Do you want to be CC'd on the emails that are sent to the group of your tracking updates/comments?</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>

    </form>

</div>